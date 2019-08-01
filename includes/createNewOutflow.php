<?php
session_start();
include "mysqlconn.php";

$store = $_POST["store"];
$employee = $_SESSION["empID"];
$remarks = $_POST['remarks'];

// Discover next ID
$numQuery = "SELECT ID FROM OUTFLOWS ORDER BY ID DESC LIMIT 1";
$resultID = mysql_query($numQuery);
if (!$resultID)
{
	die('Could not read data: ' . mysql_error());
} else {
	$rowID = mysql_fetch_array($resultID);	
	$ID = $rowID["ID"] + 1;
}

// Discover next code
$codeQuery = "SELECT code FROM OUTFLOWS WHERE storeID = $store ORDER BY code DESC LIMIT 1";
$resultCode = mysql_query($codeQuery);
if (!$resultCode)
{
	die('Could not read data Code: ' . mysql_error());
} else {
	$rowCode = mysql_fetch_assoc($resultCode);
	if ($rowCode["code"] == "" || $rowCode["code"] == NULL) {
		$queryStore = mysql_query("SELECT code FROM STORES WHERE ID = $store");
		$rowStore = mysql_fetch_assoc($queryStore);
		$code = $rowStore["code"]."-SAL-100001";
	} else {
		$curCode = str_split($rowCode["code"], 8);
		$code = $curCode[0].($curCode[1] + 1);
	}
}


// Enter Outflow to DB
$sql = "INSERT INTO OUTFLOWS (ID, code, storeID, empID, created_at, remarks) VALUES ('$ID', '$code', '$store', '$employee', CURRENT_TIMESTAMP, '$remarks')";
$retval = mysql_query($sql);
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}

// Enter Outflow Lines
foreach ($_POST['prodCode'] as $key => $value) {
	$quant = $_POST['quant'][$key];
	$sql = "INSERT INTO OUTLN (ID, outID, prodCode, qty) VALUES (NULL, '$ID', '$value', '$quant')";
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
	
	$newQty = $curQty - $quant;
	
	$sql = "UPDATE PRDL SET qty = '$newQty' WHERE prodCode = '$value' AND storeID = '$store'";
	$retval = mysql_query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
	
	
}

header('Location: ../outflows.php');
?>