<?php
session_start();
include "mysqlconn.php";

$baseOrd = "";
$orStore = $_POST["orStore"];
$dsStore = $_POST["dsStore"];
$employee = $_SESSION["empID"];
$remarks = $_POST['remarks'];

if (isset($_POST["ordCode"])) {
	$orStore = 100;
	$dsStore = $_POST["store"];
	
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
		$myQuery = mysql_query("SELECT OnOrder FROM PRDL WHERE prodCode = '$value' AND storeID = '$dsStore'");
		$row = mysql_fetch_assoc($myQuery);
		$curQty = $row["OnOrder"];
		
		$newQty = $curQty - $quant;
		
		$sql = "UPDATE PRDL SET OnOrder = '$newQty' WHERE prodCode = '$value' AND storeID = '$dsStore'";
		$retval = mysql_query($sql);
		if(! $retval )
		{
		  die('Could not enter data: ' . mysql_error());
		}
	}
}

// Discover next ID
$numQuery = "SELECT ID FROM TRANSFERS ORDER BY ID DESC LIMIT 1";
$resultID = mysql_query($numQuery);
if (!$resultID)
{
	die('Could not read data: ' . mysql_error());
} else {
	$rowID = mysql_fetch_array($resultID);	
	$ID = $rowID["ID"] + 1;
}

// Discover next code
$codeQuery = "SELECT code FROM TRANSFERS WHERE dsStore = $dsStore ORDER BY code DESC LIMIT 1";
$resultCode = mysql_query($codeQuery);
if (!$resultCode)
{
	die('Could not read data Code: ' . mysql_error());
} else {
	$rowCode = mysql_fetch_assoc($resultCode);
	if ($rowCode["code"] == "" || $rowCode["code"] == NULL) {
		$queryStore = mysql_query("SELECT code FROM STORES WHERE ID = $dsStore");
		$rowStore = mysql_fetch_assoc($queryStore);
		$code = $rowStore["code"]."-TRN-100001";
	} else {
		$curCode = str_split($rowCode["code"], 8);
		$code = $curCode[0].($curCode[1] + 1);
	}
}



// Enter Transfer to DB
$sql = "INSERT INTO TRANSFERS (ID, code, baseOrd, orStore, dsStore, empID, created_at, account, remarks) VALUES ('$ID', '$code', '$baseOrd', '$orStore', '$dsStore', '$employee', CURRENT_TIMESTAMP, 'N', '$remarks')";
$retval = mysql_query($sql);
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}

// Enter Inflow Lines
foreach ($_POST['prodCode'] as $key => $value) {
	$quant = $_POST['quant'][$key];
	$sql = "INSERT INTO TRLN (ID, tranID, prodCode, qty) VALUES (NULL, '$ID', '$value', '$quant')";
	$retval = mysql_query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
	
	// Modify inventory
	// Current QTY
	$myQuery = mysql_query("SELECT qty FROM PRDL WHERE prodCode = '$value' AND storeID = '$orStore'");
	$row = mysql_fetch_assoc($myQuery);
	$curQty = $row['qty'];
	
	$newQty = $curQty - $quant;
	
	$sql = "UPDATE PRDL SET qty = '$newQty' WHERE prodCode = '$value' AND storeID = '$orStore'";
	$retval = mysql_query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
	
	$myQuery = mysql_query("SELECT qty FROM PRDL WHERE prodCode = '$value' AND storeID = '$dsStore'");
	$row = mysql_fetch_assoc($myQuery);
	$curQty = $row['qty'];
	
	$newQty = $curQty + $quant;
	
	$sql = "UPDATE PRDL SET qty = '$newQty' WHERE prodCode = '$value' AND storeID = '$dsStore'";
	$retval = mysql_query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
	
	
}

header('Location: ../transfers.php');
?>