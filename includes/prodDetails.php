<?php
session_start();

include "mysqlconn.php";

$by = $_REQUEST["by"];
$param =  utf8_decode($_GET["param"]);
$storeID = $_REQUEST["storeID"];

$queryProd = "SELECT code, name, price FROM PRODUCT WHERE $by = '$param'";
$resultProd = mysql_query($queryProd);
$rowProd = mysql_fetch_assoc($resultProd);
$code = $rowProd["code"];


$queryQuant = "SELECT qty FROM PRDL WHERE prodCode = '$code' AND storeID = $storeID";
$resultQuant = mysql_query($queryQuant);
$rowQuant = mysql_fetch_assoc($resultQuant);


if(count($rowProd) != 0) {
	$rowProd["code"] = utf8_encode($rowProd["code"]);
	$rowProd["name"] = utf8_encode($rowProd["name"]);
	$rowProd["price"] = $rowProd["price"];
	$rowProd["quant"] = $rowQuant["qty"];
	
	echo json_encode($rowProd);
}
?>