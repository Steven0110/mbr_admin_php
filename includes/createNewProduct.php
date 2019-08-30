<?php
session_start();
include_once "mysqlconn.php";

$product = $_POST['product'];
$code = $_POST['code'];
$detail = $_POST['detail'];
$cat = $_POST['cat'];
$vendor = $_POST['vendor'];
$cost = $_POST['cost'];
$price = $_POST['price'];
$remarks = $_POST['remarks'];

// Enter New Product to DB
$sql = "INSERT INTO product (ID, code, name, detail, vendorID, catID, cost, price, inventory, remarks) VALUES (NULL, '$code', '$product', '$detail', '$vendor', '$cat', '$cost', '$price', 0, '$remarks')";
$retval = $db->query($sql);
if(!$retval)
{
  die('Could not enter data: ' . mysql_error());
}

// Enter Product Lines
foreach ($_POST['storeID'] as $key => $value) {
	$min = $_POST['min'][$key];
	$max = $_POST['max'][$key];
	$sql = "INSERT INTO prdl (ID, prodCode, storeID, qty, minq, maxq) VALUES (NULL, '$code', '$value', 0, '$min', '$max')";
	$retval = $db->query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
}

header('Location: ../products.php');
?>