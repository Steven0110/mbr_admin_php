<?php
session_start();

include_once "mysqlconn.php";
// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;
$term = utf8_decode($requestData['search']['value']);
$wareHouse = $_REQUEST["wh"];
$by = $_REQUEST["by"];
$columns = array( 
// datatable column index  => database column name
	0 => 'ID_PROYECTO',
	1 => 'ID_PRESTAMO',
	2 => 'CREATED_AT',
	3 => 'ID_EMPLEADO',
	4 => 'STATUS'
);

// getting total number records without any search

$queryTotal = "SELECT ID_PRESTAMO quant FROM prestamos GROUP BY ID_PRESTAMO ORDER BY ID_PRESTAMO DESC";

/*if ($wareHouse == "" || $wareHouse == NULL || $wareHouse == 0) {
	$queryTotal.= "";
} else {
	$queryTotal.= " WHERE storeID = '$wareHouse'";
}*/
$resultTotal = $db->query($queryTotal);
$rowTotal = $resultTotal->fetch();
$totalData = $rowTotal["quant"];
$totalFiltered = (int)$totalData - 1;  // when there is no search parameter then total number rows = total number filtered rows.

$query = "SELECT t1.ID_PRESTAMO ID, t3.proyectname ID_PROYECTO,DATE_FORMAT(t1.CREATED_AT, '%Y-%m-%d %H:%i') created,t2.NOMBRE name, if(t1.STATUS = 'C' , 'Cerrado', 'Abierto') stat FROM prestamos t1 INNER JOIN empleados t2 ON t1.ID_EMPLEADO = t2.ID_EMPLEADO INNER JOIN proyects t3 ON t1.ID_PROYECTO = t3.ID";
/*
if ($wareHouse == "" || $wareHouse == NULL || $wareHouse == 0) {
	$query.= "";
} else {
	$query.= " AND T2.ID = '$wareHouse'";
}*/

if (!empty($term)) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	switch ($by) {
		case "PROYECTO":
			$query.= " WHERE t3.proyectname LIKE '%".$term."%'";
			break;
		case "PRESTAMO": /*AND DATE_FORMAT(T1.created_at, '%Y-%m-%d %H:%i') LIKE '%".$term."%'*/
			$query.= " WHERE t1.ID_PRESTAMO LIKE '%".$term."%'";
			break;
		case "EMPLEADO":
			$query.= " WHERE t2.NOMBRE LIKE '%".$term."%'";
			break;
	}

	if ($wareHouse == "" || $wareHouse == NULL || $wareHouse == 0) {
	$query.= "";
	} else {
		$query.= " AND t2.NUMERO = '$wareHouse'";
	}
	$result = $db->prepare($query);
    $result->execute();
	$totalFiltered = $result->rowCount(); // when there is a search parameter then we have to modify total number filtered rows as per search result.
}
else{
	if ($wareHouse == "" || $wareHouse == NULL || $wareHouse == 0) {
	$query.= "";
	} else {
		$query.= " WHERE t2.NUMERO = '$wareHouse'";
	}
}

//$query.= " ORDER BY ID ASC LIMIT 0, 10";
$query.= " GROUP BY t1.ID_PRESTAMO ORDER BY t1.".$columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start'].", ".$requestData['length']." ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc*/ 
$result = $db->query($query);
$data = array();
while($row = $result->fetch()) {
	$nestedData = array();


	$nestedData[] = utf8_encode($row["ID_PROYECTO"]);
	$nestedData[] = utf8_encode($row["ID"]);
	$nestedData[] = utf8_encode($row["created"]);
	$nestedData[] = utf8_encode($row["name"]);
	$nestedData[] = utf8_encode($row["stat"]);
	$nestedData[] = "<a href='prestamoDetail.php?infID=".$row["ID"]."'><i class='fa fa-eye' aria-hidden='true'></i></a>";
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
