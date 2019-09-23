<?php
session_start();
include "mysqlconn.php";

// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;
$term = utf8_decode($requestData['search']['value']);
$list = $_REQUEST["ls"];
$by = $_REQUEST["by"];
//$by = $REQUEST["by"];
$columns = array( 
// datatable column index  => database column name
	0 => 'ref',
	1 => 'product',
	2 => 'code',
	3 => 'pricebuy',
	4 => 'pricesell'
);
   
// getting total number records without any search
$queryTotal = "SELECT COUNT(*) quant FROM products t1";
if (!empty($term)) {
		$queryTotal.=" WHERE t1.NAME LIKE '%".$term."%'";
	}
/*if ($wareHouse == "" || $wareHouse == NULL || $wareHouse == 0) {
	$queryTotal.= "";
} else {
	$queryTotal.= " WHERE storeID = '$wareHouse'";
}*/
$resultTotal = $db->query($queryTotal);
$rowTotal = $resultTotal->fetch();
$totalData = $rowTotal["quant"];
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$query = "SELECT t1.ID id, t1.REFERENCE ref, t1.CODE code, t1.NAME product, t1.PRICEBUY pricebuy, ";
/*switch ($list) {
		case "0":
			$query.= "t1.PRICESELL price FROM products t1";
			break;
		case "1":
			$query.= "t1.PRICESELL2 price FROM products t1";
			break;
		case "2":
			$query.= "t1.PRICESELL3 price FROM products t1";
			break;
	}*/
// if there is a search parameter, $requestData['search']['value'] contains search parameter

	switch ($list) {
		case "0":
			$query.= "t1.PRICESELL price FROM products t1";
			break;
		case "1":
			$query.= "t1.PRICESELL2 price FROM products t1";
			break;
		case "2":
			$query.= "t1.PRICESELL3 price FROM products t1";
			break;
	}
	
	if (!empty($term)) {
		$query.=" WHERE t1.NAME LIKE '%".$term."%'";
	}

	$result = $db->query($query);
	//$totalFiltered = $result->fetchColumn(); //In this case search parameters are not available, so we wont use this var// when there is a search parameter then we have to modify total number filtered rows as per search result.

//$query.= " ORDER BY ID ASC LIMIT 0, 10";
$query.= " ORDER BY ".$columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir'];
//We save the $query var to use it to create the pdf file exactly as the user sees the table
$_SESSION['priceListQR'] = $query;

$query.=" LIMIT ".$requestData['start'].", ".$requestData['length']."";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */
$result = $db->query($query);
$data = array();
while($row = $result->fetch()) {
	$nestedData = array();
	$nestedData[] = utf8_encode($row["ref"]);
	$nestedData[] = utf8_encode($row["product"]);
	$nestedData[] = utf8_encode($row["code"]);
	$nestedData[] = utf8_encode("$".$row["pricebuy"]);
	$nestedData[] = utf8_encode("$".$row["price"]);
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
