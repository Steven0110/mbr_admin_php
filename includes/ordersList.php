<?php
session_start();
include "mysqlconn.php";

// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;
$term = utf8_decode($requestData['search']['value']);
$wareHouse = $_REQUEST["wh"];
$status = $_REQUEST["status"];
$by = $_REQUEST["by"];
$columns = array( 
// datatable column index  => database column name
	0 => 'store', 
	1 => 'code',
	2 => 'created',
	3 => 'emp',
	4 => 'active',
	5 => 'ID'
);

// getting total number records without any search
$queryTotal = "SELECT COUNT(*) quant FROM ORDERS WHERE storeID <> 0";
if ($wareHouse == "" || $wareHouse == NULL || $wareHouse == 0) {
	$queryTotal.= "";
} else {
	$queryTotal.= " AND storeID = '$wareHouse'";
}
if ($status == "Y" || $status == "N" || $status == "C") {
	$queryTotal.= " AND active = '$status'";
}

$resultTotal = mysql_query($queryTotal);
$rowTotal = mysql_fetch_assoc($resultTotal);
$totalData = $rowTotal["quant"];
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$query = "SELECT T2.name store, T1.code, DATE_FORMAT(T1.created_at, '%Y-%m-%d %H:%i') created, CONCAT(T3.first, ' ', T3.last) emp, T1.active, T1.ID FROM ORDERS T1 JOIN STORES T2 ON T1.storeID = T2.ID JOIN CREW T3 ON T1.empID = T3.ID WHERE T1.storeID <> 0";

if ($wareHouse == "" || $wareHouse == NULL || $wareHouse == 0) {
	$query.= "";
} else {
	$query.= " AND T2.ID = '$wareHouse'";
}

if ($status == "Y" || $status == "N" || $status == "C") {
	$query.= " AND T1.active = '$status'";
} else {
	$query.= "";
}

if (!empty($term)) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	switch ($by) {
		case "FOLIO":
			$query.= " AND T1.code LIKE '%".$term."%'";
			break;
		case "FECHA":
			$query.= " AND DATE_FORMAT(T1.created_at, '%Y-%m-%d %H:%i') LIKE '%".$term."%'";
			break;
		case "EMPLEADO":
			$query.= " AND CONCAT(T3.first, ' ', T3.last) LIKE '%".$term."%'";
			break;
	}

	$result = mysql_query($query);
	$totalFiltered = mysql_num_rows($result); // when there is a search parameter then we have to modify total number filtered rows as per search result.
}

//$query.= " ORDER BY ID ASC LIMIT 0, 10";
$query.= " ORDER BY ".$columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start'].", ".$requestData['length']."";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */

$result = mysql_query($query);

$data = array();
while($row = mysql_fetch_assoc($result)) {
	$nestedData = array();

	$nestedData[] = utf8_encode($row["store"]);
	$nestedData[] = utf8_encode($row["code"]);
	$nestedData[] = utf8_encode($row["created"]);
	$nestedData[] = utf8_encode($row["emp"]);
	switch ($row["active"]) {
		case "Y":
			$nestedData[] = "<span class='status'>Abierto</span>";
			break;
		case "N":
			$nestedData[] = "<span class='status'>Cerrado</span>";
			break;
		case "C":
			$nestedData[] = "<span class='status'>Cancelado</span>";
			break;
	}
	$nestedData[] = "<a href='viewOrder.php?ordID=".$row["ID"]."'><i class='fa fa-eye' aria-hidden='true'></i></a>";
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
