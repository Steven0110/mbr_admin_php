<?php

session_start();
include "mysqlconn.php";

$prodID = $_POST['prodID'];
$name = $_POST['name'];
$code = $_POST['code'];
$detail = $_POST['detail'];
$cat = $_POST['cat'];
$vendor = $_POST['vendor'];
$cost = $_POST['cost'];
$price = $_POST['price'];
$remarks = $_POST['remarks'];
if(isset($_POST["active"])) {
	$active = 'Y';
} else {
	$active = 'N';
}


// Write to DB

$sql = "UPDATE PRODUCT SET name = '$name', code = '$code', detail = '$detail', catID = $cat, vendorID = $vendor, cost = $cost, price = $price, active = '$active', remarks = '$remarks' WHERE ID = $prodID";

$retval = mysql_query($sql);
if(! $retval )
{
  die('Could not modify data: ' . mysql_error());
}

// Edit Product Lines
foreach ($_POST['storeID'] as $key => $value) {
	$min = $_POST['min'][$key];
	$max = $_POST['max'][$key];
	$sql = "UPDATE PRDL SET minq = '$min', maxq = '$max' WHERE storeID = $value AND prodCode = '$code'";
	
	$retval = mysql_query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
}


header("Location: ../prodDetail.php?prodID=".$prodID);
?>