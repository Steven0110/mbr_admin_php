<?php
session_start();
<<<<<<< HEAD
include_once "mysqlconn.php";
=======
include "mysqlconn.php";
>>>>>>> ea9b9e88c8d78990808217bcfda7ebd9e524f1a7

$baseOrd = "";
$store = $_POST["store"];
$employee = $_SESSION["empID"];
$remarks = $_POST['remarks'];

if (isset($_POST["ordCode"])) {
	$baseOrd = $_POST["ordCode"];
	
<<<<<<< HEAD
	$queryCloseOrder = "UPDATE orders SET active = 'N', closed_at = CURRENT_TIMESTAMP WHERE code = '$baseOrd'";
=======
	$queryCloseOrder = "UPDATE ORDERS SET active = 'N', closed_at = CURRENT_TIMESTAMP WHERE code = '$baseOrd'";
>>>>>>> ea9b9e88c8d78990808217bcfda7ebd9e524f1a7
	$resultCloseOrder = $db->query($queryCloseOrder);
	if (!$resultCloseOrder)
	{
		die('Could not write data CloseOrder: ' . mysql_error());
	}
	
	// Modify inventory
	foreach ($_POST['prodCode'] as $key => $value) {
		$quant = $_POST['quant'][$key];
		// Current QTY onOrder
<<<<<<< HEAD
		$myQuery = $db->query("SELECT OnOrder FROM prdl WHERE prodCode = '$value' AND storeID = '$store'");
=======
		$myQuery = $db->query("SELECT OnOrder FROM PRDL WHERE prodCode = '$value' AND storeID = '$store'");
>>>>>>> ea9b9e88c8d78990808217bcfda7ebd9e524f1a7
		$row = $myQuery->fetch();
		$curQty = $row["OnOrder"];
		
		$newQty = $curQty - $quant;
		
<<<<<<< HEAD
		$sql = "UPDATE prdl SET OnOrder = '$newQty' WHERE prodCode = '$value' AND storeID = '$store'";
=======
		$sql = "UPDATE PRDL SET OnOrder = '$newQty' WHERE prodCode = '$value' AND storeID = '$store'";
>>>>>>> ea9b9e88c8d78990808217bcfda7ebd9e524f1a7
		$retval = $db->query($sql);
		if(! $retval )
		{
		  die('Could not enter data: ' . mysql_error());
		}
	}
}


// Discover next ID
<<<<<<< HEAD
$numQuery = "SELECT ID FROM inflows ORDER BY ID DESC LIMIT 1";
=======
$numQuery = "SELECT ID FROM INFLOWS ORDER BY ID DESC LIMIT 1";
>>>>>>> ea9b9e88c8d78990808217bcfda7ebd9e524f1a7
$resultID = $db->query($numQuery);
if (!$resultID)
{
	die('Could not read data ID: ' . mysql_error());
} else {
	$rowID = $resultID->fetch();	
	$ID = $rowID["ID"] + 1;
}

// Discover next code
<<<<<<< HEAD
$codeQuery = "SELECT code FROM inflows WHERE storeID = $store ORDER BY code DESC LIMIT 1";
=======
$codeQuery = "SELECT code FROM INFLOWS WHERE storeID = $store ORDER BY code DESC LIMIT 1";
>>>>>>> ea9b9e88c8d78990808217bcfda7ebd9e524f1a7
$resultCode = $db->query($codeQuery);
if (!$resultCode)
{
	die('Could not read data Code: ' . mysql_error());
} else {
	$rowCode = $resultCode->fetch();
	if ($rowCode["code"] == "" || $rowCode["code"] == NULL) {
<<<<<<< HEAD
		$queryStore = $db->query("SELECT code FROM stores WHERE ID = $store");
=======
		$queryStore = $db->query("SELECT code FROM STORES WHERE ID = $store");
>>>>>>> ea9b9e88c8d78990808217bcfda7ebd9e524f1a7
		$rowStore = $queryStore->fetch();
		$code = $rowStore["code"]."-ENT-100001";
	} else {
		$curCode = str_split($rowCode["code"], 8);
		$code = $curCode[0].($curCode[1] + 1);
	}
}

// Enter Inflow to DB
<<<<<<< HEAD
$sql = "INSERT INTO inflows (ID, code, baseOrd, storeID, empID, created_at, remarks) VALUES ('$ID', '$code', '$baseOrd', $store, '$employee', CURRENT_TIMESTAMP, '$remarks')";
=======
$sql = "INSERT INTO INFLOWS (ID, code, baseOrd, storeID, empID, created_at, remarks) VALUES ('$ID', '$code', '$baseOrd', $store, '$employee', CURRENT_TIMESTAMP, '$remarks')";
>>>>>>> ea9b9e88c8d78990808217bcfda7ebd9e524f1a7
$retval = $db->query($sql);
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}

// Enter Inflow Lines
foreach ($_POST['prodCode'] as $key => $value) {
	$quant = $_POST['quant'][$key];
<<<<<<< HEAD
	$sql = "INSERT INTO inln (ID, infID, prodCode, qty) VALUES (NULL, '$ID', '$value', '$quant')";
=======
	$sql = "INSERT INTO INLN (ID, infID, prodCode, qty) VALUES (NULL, '$ID', '$value', '$quant')";
>>>>>>> ea9b9e88c8d78990808217bcfda7ebd9e524f1a7
	$retval = $db->query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
	
	// Modify inventory
	// Current QTY
<<<<<<< HEAD
	$myQuery = $db->query("SELECT qty FROM prdl WHERE prodCode = '$value' AND storeID = '$store'");
	$row = $myQuery->fetch();
=======
	$myQuery = $db->query("SELECT qty FROM PRDL WHERE prodCode = '$value' AND storeID = '$store'");
	$row = m$myQuery->fetch();
>>>>>>> ea9b9e88c8d78990808217bcfda7ebd9e524f1a7
	$curQty = $row['qty'];
	
	$newQty = $curQty + $quant;
	
<<<<<<< HEAD
	$sql = "UPDATE prdl SET qty = '$newQty' WHERE prodCode = '$value' AND storeID = '$store'";
=======
	$sql = "UPDATE PRDL SET qty = '$newQty' WHERE prodCode = '$value' AND storeID = '$store'";
>>>>>>> ea9b9e88c8d78990808217bcfda7ebd9e524f1a7
	$retval = $db->query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
	
	
}

header('Location: ../inflows.php');
?>