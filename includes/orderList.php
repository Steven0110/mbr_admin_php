<?php
session_start();
include "mysqlconn.php";

// storing  request (ie, get/post) global array to a variable  
$requestData = $_REQUEST;
$term = utf8_decode($requestData["search"]["value"]);

$wareHouse = $_REQUEST["wh"];

$by = $_REQUEST["by"];

$columns = array( 
// datatable column index  => database column name
	0 => 'code', 
	1 => 'name',
	2 => 'vendor',
	3 => 'price',
	4 => 'minq',
	5 => 'maxq',
	6 => 'qty',
	7 => 'orderq',
	8 => 'OnOrder',
	9 => 'totCost'
);

// getting total number records without any search
$resultTotal = mysql_query("SELECT COUNT(*) quant FROM PRODUCT T1 JOIN PRDL T2 ON T1.code = T2.prodCode WHERE T1.active = 'Y' AND (T2.maxq - T2.qty) > 0 AND T2.storeID = '$wareHouse'");
$rowTotal = mysql_fetch_assoc($resultTotal);
$totalData = $rowTotal["quant"];
$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

// getting total cost
$totalCostQuery = mysql_query("SELECT SUM(T1.price * (T2.maxq - T2.qty)) totalCost FROM PRODUCT T1 JOIN PRDL T2 ON T1.code = T2.prodCode WHERE T1.active = 'Y' AND (T2.maxq - T2.qty) > 0 AND T2.storeID = '$wareHouse'");
$rowTotalCost = mysql_fetch_assoc($totalCostQuery);
$totalCost = $rowTotalCost["totalCost"];

$query = "SELECT T1.code, T1.name, T3.name vendor, T1.price, T2.minq, T2.maxq, T2.qty, (T2.maxq - T2.qty) orderq, T2.OnOrder, (T1.price * (T2.maxq - T2.qty)) totCost FROM PRODUCT T1 JOIN PRDL T2 ON T1.code = T2.prodCode JOIN VENDOR T3 ON T1.vendorID = T3.ID WHERE T1.active = 'Y' AND (T2.maxq - T2.qty) > 0 AND T2.storeID = '$wareHouse'";

if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
	switch ($by) {
		case "CODIGO":
			$query.= " AND T1.code LIKE '%".$term."%'";
			break;
		case "NOMBRE":
			$query.= " AND T1.name LIKE '%".$term."%'";
			break;
		case "PROVEEDOR":
			$query.= " AND T3.name LIKE '%".$term."%'";
			break;
	}
		
	$result = mysql_query($query);
	$totalFiltered = mysql_num_rows($result); // when there is a search parameter then we have to modify total number filtered rows as per search result.
}

//$query.= " ORDER BY ID ASC LIMIT 0, 10";
$query.= " ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']." LIMIT ".$requestData['start']." ,".$requestData['length']." ";
/* $requestData['order'][0]['column'] contains colmun index, $requestData['order'][0]['dir'] contains order such as asc/desc  */

$result = mysql_query($query);

$data = array();
while($row = mysql_fetch_assoc($result)) {
	$nestedData = array();

	$nestedData[] = utf8_encode($row["code"]);
	$nestedData[] = utf8_encode($row["name"]);
	$nestedData[] = utf8_encode($row["vendor"]);
	$nestedData[] = "<span style='float:left'>$ </span>".$row["price"];
	$nestedData[] = $row["minq"];
	$nestedData[] = $row["maxq"];
	$nestedData[] = $row["qty"];
	$nestedData[] = "<strong>".$row["orderq"]."</strong>";
	$nestedData[] = $row["OnOrder"];
	$nestedData[] = $row["totCost"];
	$data[] = $nestedData;	
}

$json_data = array(
	"draw" => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
	"recordsTotal" => intval( $totalData ),  // total number of records
	"recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
	"totalCost" => $totalCost,
	"data" => $data   // total data array
);

echo json_encode($json_data);  // send data as json format
?>
