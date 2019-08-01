<?php
session_start();
include "mysqlconn.php";

$by = $_GET["by"];
$searchTerm = utf8_decode($_GET["term"]);

$queryProd = "SELECT ".$by." Product FROM PRODUCT WHERE active = 'Y' AND ".$by." LIKE '%".$searchTerm."%' ORDER BY ".$by." ASC LIMIT 20";
$resultProd = mysql_query($queryProd);
while ($rowProd = mysql_fetch_assoc($resultProd)) {
	$data[] = utf8_encode($rowProd['Product']);
}

echo json_encode($data);
?>