<?php

session_start();
include_once "mysqlconn.php";
$data = json_decode(file_get_contents('php://input'), true);
/**
 *
 * 	{
		"store": "100",	
		"products": [
			"BLINK",
			"ACET10",
		]
 	}
 *
**/
$store = $data["store"];
$products = $data["products"];
$q = $data["quantities"];
$responseCode = 1;
$prodID = "";
$stock = 0;
$i = 0;
foreach($products as $product){
	$sql = "SELECT qty FROM prdl WHERE prodCode = '".$product."' AND storeID = '".$store."'";
	$resultSet = $db->query($sql);
	if(!$resultSet)
		die("{\"code\":-1, \"msg\":\"" . mysql_error() . "\"}");
	else{
		$row = $resultSet->fetch();
		if(intval($row["qty"]) < intval( $q[ $i ] )){
		    $stock = intval( $row["qty"] );
			$responseCode = 0;
			$prodID = $product;
			break;
		}
	}
	$i++;
}
if($responseCode == 0)
	$msg = "{\"code\":\"".$responseCode."\", \"prodID\": \"".$prodID."\", \"stock\":\"".$stock."\"}";
else
	$msg = "{\"code\":\"".$responseCode."\"}";

echo $msg;