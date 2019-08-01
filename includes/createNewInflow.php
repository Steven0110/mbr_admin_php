<?php
session_start();
include "mysqlconn.php";

$baseOrd = "";
$store = $_POST["store"];
$employee = $_SESSION["empID"];
$remarks = $_POST['remarks'];

if (isset($_POST["ordCode"])) {
	$baseOrd = $_POST["ordCode"];
	
	$queryCloseOrder = "UPDATE ORDERS SET active = 'N', closed_at = CURRENT_TIMESTAMP WHERE code = '$baseOrd'";
	$resultCloseOrder = mysql_query($queryCloseOrder);
	if (!$resultCloseOrder)
	{
		die('Could not write data CloseOrder: ' . mysql_error());
	}
	
	// Modify inventory
	foreach ($_POST['prodCode'] as $key => $value) {
		$quant = $_POST['quant'][$key];
		// Current QTY onOrder
		$myQuery = mysql_query("SELECT OnOrder FROM PRDL WHERE prodCode = '$value' AND storeID = '$store'");
		$row = mysql_fetch_assoc($myQuery);
		$curQty = $row["OnOrder"];
		
		$newQty = $curQty - $quant;
		
		$sql = "UPDATE PRDL SET OnOrder = '$newQty' WHERE prodCode = '$value' AND storeID = '$store'";
		$retval = mysql_query($sql);
		if(! $retval )
		{
		  die('Could not enter data: ' . mysql_error());
		}
	}
}


// Discover next ID
$numQuery = "SELECT ID FROM INFLOWS ORDER BY ID DESC LIMIT 1";
$resultID = mysql_query($numQuery);
if (!$resultID)
{
	die('Could not read data ID: ' . mysql_error());
} else {
	$rowID = mysql_fetch_assoc($resultID);	
	$ID = $rowID["ID"] + 1;
}

// Discover next code
$codeQuery = "SELECT code FROM INFLOWS WHERE storeID = $store ORDER BY code DESC LIMIT 1";
$resultCode = mysql_query($codeQuery);
if (!$resultCode)
{
	die('Could not read data Code: ' . mysql_error());
} else {
	$rowCode = mysql_fetch_assoc($resultCode);
	if ($rowCode["code"] == "" || $rowCode["code"] == NULL) {
		$queryStore = mysql_query("SELECT code FROM STORES WHERE ID = $store");
		$rowStore = mysql_fetch_assoc($queryStore);
		$code = $rowStore["code"]."-ENT-100001";
	} else {
		$curCode = str_split($rowCode["code"], 8);
		$code = $curCode[0].($curCode[1] + 1);
	}
}

// Enter Inflow to DB
$sql = "INSERT INTO INFLOWS (ID, code, baseOrd, storeID, empID, created_at, remarks) VALUES ('$ID', '$code', '$baseOrd', $store, '$employee', CURRENT_TIMESTAMP, '$remarks')";
$retval = mysql_query($sql);
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}

// Enter Inflow Lines
foreach ($_POST['prodCode'] as $key => $value) {
	$quant = $_POST['quant'][$key];
	$sql = "INSERT INTO INLN (ID, infID, prodCode, qty) VALUES (NULL, '$ID', '$value', '$quant')";
	$retval = mysql_query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
	
	// Modify inventory
	// Current QTY
	$myQuery = mysql_query("SELECT qty FROM PRDL WHERE prodCode = '$value' AND storeID = '$store'");
	$row = mysql_fetch_assoc($myQuery);
	$curQty = $row['qty'];
	
	$newQty = $curQty + $quant;
	
	$sql = "UPDATE PRDL SET qty = '$newQty' WHERE prodCode = '$value' AND storeID = '$store'";
	$retval = mysql_query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
	
	
}

header('Location: ../inflows.php');
?>