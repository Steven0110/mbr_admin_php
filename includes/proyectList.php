<?php
session_start();
include "mysqlconn.php";

// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;
$term = utf8_decode($requestData['search']['value']);
$wareHouse = $_REQUEST["wh"];
$by = $_REQUEST["by"];
$columns = array( 
// datatable column index  => database column name
	0 => 'ID',
	1 => 'proyectname',
	2 => 'client' 
);

// getting total number records without any search
$queryTotal = "SELECT COUNT(*) quant FROM proyects";
/*if ($wareHouse == "" || $wareHouse == NULL || $wareHouse == 0) {
	$queryTotal.= "";
} else {
	$queryTotal.= " WHERE storeID = '$wareHouse'";
}*/
$resultTotal = $db->query($queryTotal);
$rowTotal = $resultTotal->fetch();
$totalData = $rowTotal["quant"];
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$query = "SELECT t1.ID, t1.proyectname, t1.client FROM proyects t1";
/*
if ($wareHouse == "" || $wareHouse == NULL || $wareHouse == 0) {
	$query.= "";
} else {
	$query.= " AND T2.ID = '$wareHouse'";
}*/

if (!empty($term)) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	switch ($by) {
		case "FOLIO":
			$query.= " WHERE t1.ID LIKE '%".$term."%'";
			break;
		case "PROYECTO":
			$query.= " WHERE t1.proyectname LIKE '%".$term."%'";
			break;
		case "CLIENTE":
			$query.= " WHERE t1.client LIKE '%".$term."%'";
			break;
	}

	$result = $db->prepare($query);
    $result->execute();
	$totalFiltered = $result->fetchColumn(); // when there is a search parameter then we have to modify total number filtered rows as per search result.
}

//$query.= " ORDER BY ID ASC LIMIT 0, 10";
$query.= " ORDER BY ".$columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start'].", ".$requestData['length']."";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
$result = $db->query($query);
$data = array();
while($row = $result->fetch()) {
	$nestedData = array();

    $nestedData[] = utf8_encode($row["ID"]);
	$nestedData[] = utf8_encode($row["proyectname"]);
    $nestedData[] = utf8_encode($row["client"]);
	
	//$nestedData[] = utf8_encode($row["emp"]);
	$nestedData[] = "<a href='proyectDetail.php?outID=".$row["ID"]."'><i class='fa fa-eye' aria-hidden='true'></i></a>";
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
