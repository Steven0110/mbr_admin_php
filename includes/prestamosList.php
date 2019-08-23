<?php
session_start();
require "mysqlconn.php";
// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;
$term = utf8_decode($requestData['search']['value']);
$wareHouse = $_REQUEST["wh"];
$by = $_REQUEST["by"];
$columns = array( 
// datatable column index  => database column name
	0 => 'NOMBRE',
	1 => 'CREATED_AT',
	2 => 'ID_PROYECTO',
	3 => 'ID_PRESTAMO'
);

// getting total number records without any search
$queryTotal = "SELECT COUNT(*) quant FROM prestamos GROUP BY ID_PRESTAMO";
/*if ($wareHouse == "" || $wareHouse == NULL || $wareHouse == 0) {
	$queryTotal.= "";
} else {
	$queryTotal.= " WHERE storeID = '$wareHouse'";
}*/
$resultTotal = $db->query($queryTotal);
$rowTotal = $resultTotal->fetch();
$totalData = $rowTotal["quant"];
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$query = "SELECT t1.ID_PRESTAMO ID, t1.ID_PROYECTO ID_PROYECTO,DATE_FORMAT(t1.CREATED_AT, '%Y-%m-%d %H:%i') created,t2.NOMBRE name FROM prestamos t1 INNER JOIN empleados t2 ON t1.ID_EMPLEADO = t2.ID_EMPLEADO GROUP BY t1.ID_PRESTAMO";
/*
if ($wareHouse == "" || $wareHouse == NULL || $wareHouse == 0) {
	$query.= "";
} else {
	$query.= " AND T2.ID = '$wareHouse'";
}*/

if (!empty($term)) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	switch ($by) {
		case "ID":
			$query.= " WHERE t1.ID_PROYECTO LIKE '%".$term."%'";
			break;
		case "PRESTAMO": /*AND DATE_FORMAT(T1.created_at, '%Y-%m-%d %H:%i') LIKE '%".$term."%'*/
			$query.= " WHERE t1.ID_PRESTAMO LIKE '%".$term."%'";
			break;
		case "EMPLEADO":
			$query.= " WHERE t2.ID_EMPLEADO LIKE '%".$term."%'";
			break;
	}

	$result = $db->query($query);
	$totalFiltered = $result->fetch(); // when there is a search parameter then we have to modify total number filtered rows as per search result.
}

//$query.= " ORDER BY ID ASC LIMIT 0, 10";
$query.= " ORDER BY ".$columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start'].", ".$requestData['length']."";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
$result = $db->query($query);
$data = array();
while($row = $result->fetch()) {
	$nestedData = array();


	$nestedData[] = utf8_encode($row["ID_PROYECTO"]);
	$nestedData[] = utf8_encode($row["ID"]);
	$nestedData[] = utf8_encode($row["created"]);
	$nestedData[] = utf8_encode($row["name"]);
	$nestedData[] = "<a href='inflowDetail.php?infID=".$row["ID"]."'><i class='fa fa-eye' aria-hidden='true'></i></a>";
	$data[] = $nestedData;	
}

$json_data = array(
	"draw" => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
	"recordsTotal" => intval( $totalData ),  // total number of records
	"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
	"data" => $data   // total data array
);

echo json_encode($json_data);  // send data as json format
?>
