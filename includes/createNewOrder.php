<?php
session_start();
include "mysqlconn.php";

$store = $_POST["storeID"];
$employee = $_SESSION["empID"];
$remarks = $_POST['remarks'];

// Discover next ID
$numQuery = "SELECT ID FROM ORDERS ORDER BY ID DESC LIMIT 1";
$resultID = $db->query($numQuery);
if (!$resultID)
{
	die('Could not read data ID: ' . mysql_error());
} else {
	$rowID = $resultID->fetch();	
	$ID = $rowID["ID"] + 1;
}

// Discover next code
$codeQuery = "SELECT code FROM ORDERS WHERE storeID = $store ORDER BY code DESC LIMIT 1";
$resultCode = $db->query($codeQuery);
if (!$resultCode)
{
	die('Could not read data Code: ' . mysql_error());
} else {
	$rowCode = $resultCode->fetch();
	if ($rowCode["code"] == "" || $rowCode["code"] == NULL) {
		$queryStore = $db->query("SELECT code FROM STORES WHERE ID = $store");
		$rowStore = $queryStore->fetch();
		$code = $rowStore["code"]."-PED-100001";
	} else {
		$curCode = str_split($rowCode["code"], 8);
		$code = $curCode[0].($curCode[1] + 1);
	}
}

// Enter Inflow to DB
$sql = "INSERT INTO ORDERS (ID, code, storeID, empID, created_at, remarks) VALUES ('$ID', '$code', $store, '$employee', CURRENT_TIMESTAMP, '$remarks')";
$retval = $db->query($sql);
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}

// Enter Inflow Lines
foreach ($_POST['prodCode'] as $key => $value) {
	$quant = $_POST['quant'][$key];
	$sql = "INSERT INTO ORDL (ID, ordID, prodCode, qty) VALUES (NULL, '$ID', '$value', '$quant')";
	$retval = $db->query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
	
	// Modify inventory
	// Current QTY onOrder
	$myQuery = $db->query("SELECT OnOrder FROM PRDL WHERE prodCode = '$value' AND storeID = '$store'");
	$row = $myQuery->fetch();
	$curQty = $row["OnOrder"];
	
	$newQty = $curQty + $quant;
	
	$sql = "UPDATE PRDL SET OnOrder = '$newQty' WHERE prodCode = '$value' AND storeID = '$store'";
	$retval = $db->query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
}

header('Location: ../orders.php');
?>