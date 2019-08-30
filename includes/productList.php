<?php
session_start();
include "mysqlconn.php";

// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;
$term = utf8_decode($requestData['search']['value']);
try{
	
	if ($_SESSION["role"] != "Y") {
	$wareHouse = $_SESSION["store"];
	}else {
	$wareHouse = $_REQUEST["wh"];
	}
}catch(Exception $ex)
{

}
$by = $_REQUEST["by"];

 

if ($_SESSION["role"] == "Y") {
	$columns = array( 
	// datatable column index  => database column name
		0 => 'code', 
		1 => 'product',
		2 => 'cat',
		3 => 'vendor',
		4 => 'qty',
		5 => 'OnOrder',
		6 => 'ID'
	);
} else {
	$columns = array( 
	// datatable column index  => database column name
		0 => 'code', 
		1 => 'product',
		2 => 'cat',
		3 => 'qty',
		4 => 'OnOrder'
	);
}

// getting total number records without any search
$resultTotal = $db->query("SELECT COUNT(*) quant FROM product");
$rowTotal = $resultTotal->fetch();
$totalData = $rowTotal["quant"];
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

$query = "SELECT T1.ID, T1.code, T1.name product, T2.catName cat, T3.name vendor, ";

if (!isset($wareHouse)) {
	$query.= "(SELECT SUM(qty) FROM prdl T4 WHERE T4.prodCode = T1.code) qty, (SELECT SUM(OnOrder) FROM prdl T4 WHERE T4.prodCode = T1.code) OnOrder ";
} else {
	$query.= "(SELECT qty FROM prdl T4 WHERE T4.prodCode = T1.code AND T4.storeID = '".$wareHouse."') qty, (SELECT OnOrder FROM prdl T4 WHERE T4.prodCode = T1.code AND T4.storeID = '".$wareHouse."') OnOrder ";
}

$query.= "FROM product T1 JOIN cat T2 ON T1.catID = T2.ID JOIN vendor T3 ON T1.vendorID = T3.ID";

if (!empty($term)) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	switch ($by) {
		case "CODIGO":
			$query.= " AND code LIKE '%".$term."%'";
			break;
		case "NOMBRE":
			$query.= " AND T1.name LIKE '%".$term."%'";
			break;
		case "CATEGORIA":
			$query.= " AND T2.catName LIKE '%".$term."%'";
			break;
		case "PROVEEDOR":
			$query.= " AND T3.name LIKE '%".$term."%'";
			break;
	}
		
	$result = $db->query($query);
	$totalFiltered = $result->fetch(); // when there is a search parameter then we have to modify total number filtered rows as per search result.
}

//$query.= " ORDER BY ID ASC LIMIT 0, 10";
$query.= " ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']." ";
//$query.= " LIMIT 100";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */

$result = $db->query($query);

$data = array();
while($row = $result->fetch()) {
	$nestedData = array();

	$nestedData[] = utf8_encode($row["code"]);
	$nestedData[] = utf8_encode($row["product"]);
	$nestedData[] = utf8_encode($row["cat"]);
	if ($_SESSION["role"] == "Y") {
		$nestedData[] = utf8_encode($row["vendor"]);
	}
	$nestedData[] = $row["qty"];
	$nestedData[] = $row["OnOrder"];
	if ($_SESSION["role"] == "Y") {
		$nestedData[] = "<a href='prodDetail.php?prodID=".$row["ID"]."'><i class='fa fa-eye' aria-hidden='true'></i></a>";
	}	
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
