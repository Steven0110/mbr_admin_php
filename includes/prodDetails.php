<?php
session_start();

$by = $_REQUEST["by"];
$param =  utf8_decode($_GET["param"]);
$storeID = $_REQUEST["storeID"];

$queryProd = "SELECT code, name, price FROM PRODUCT WHERE $by = '$param'";
$resultProd = $db->query($queryProd);
$rowProd = $resultProd->fetch();
$code = $rowProd["code"];


$queryQuant = "SELECT qty FROM PRDL WHERE prodCode = '$code' AND storeID = $storeID";
$resultQuant = $db->query($queryQuant);
$rowQuant = $resultQuant->fetch();


if(count($rowProd) != 0) {
	$rowProd["code"] = utf8_encode($rowProd["code"]);
	$rowProd["name"] = utf8_encode($rowProd["name"]);
	$rowProd["price"] = $rowProd["price"];
	$rowProd["quant"] = $rowQuant["qty"];
	
	echo json_encode($rowProd);
}
?>