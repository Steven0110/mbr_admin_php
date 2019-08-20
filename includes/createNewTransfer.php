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
	$resultCloseOrder = $db->query($queryCloseOrder);
	if (!$resultCloseOrder)
	{
		die('Could not write data CloseOrder: ' . mysql_error());
	}
	
	// Modify inventory
	foreach ($_POST['prodCode'] as $key => $value) {
		$quant = $_POST['quant'][$key];
		// Current QTY onOrder
		$myQuery = $db->query("SELECT OnOrder FROM PRDL WHERE prodCode = '$value' AND storeID = '$dsStore'");
		$row = $myQuery->fetch();
		$curQty = $row["OnOrder"];
		
		$newQty = $curQty - $quant;
		
		$sql = "UPDATE PRDL SET OnOrder = '$newQty' WHERE prodCode = '$value' AND storeID = '$dsStore'";
		$retval = $db->query($sql);
		if(! $retval )
		{
		  die('Could not enter data: ' . mysql_error());
		}
	}
}

// Discover next ID
$numQuery = "SELECT ID FROM TRANSFERS ORDER BY ID DESC LIMIT 1";
$resultID = $db->query($numQuery);
if (!$resultID)
{
	die('Could not read data: ' . mysql_error());
} else {
	$rowID = $resultID->fetch();	
	$ID = $rowID["ID"] + 1;
}

// Discover next code
$codeQuery = "SELECT code FROM TRANSFERS WHERE dsStore = $dsStore ORDER BY code DESC LIMIT 1";
$resultCode = $db->query($codeQuery);
if (!$resultCode)
{
	die('Could not read data Code: ' . mysql_error());
} else {
	$rowCode = $resultCode->fetch();
	if ($rowCode["code"] == "" || $rowCode["code"] == NULL) {
		$queryStore = $db->query("SELECT code FROM STORES WHERE ID = $dsStore");
		$rowStore = $queryStore->fetch();
		$code = $rowStore["code"]."-TRN-100001";
	} else {
		$curCode = str_split($rowCode["code"], 8);
		$code = $curCode[0].($curCode[1] + 1);
	}
}



// Enter Transfer to DB
$sql = "INSERT INTO TRANSFERS (ID, code, baseOrd, orStore, dsStore, empID, created_at, account, remarks) VALUES ('$ID', '$code', '$baseOrd', '$orStore', '$dsStore', '$employee', CURRENT_TIMESTAMP, 'N', '$remarks')";
$retval = $db->query($sql);
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}

// Enter Inflow Lines
foreach ($_POST['prodCode'] as $key => $value) {
	$quant = $_POST['quant'][$key];
	$sql = "INSERT INTO TRLN (ID, tranID, prodCode, qty) VALUES (NULL, '$ID', '$value', '$quant')";
	$retval = $db->query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
	
	// Modify inventory
	// Current QTY
	$myQuery = $db->query("SELECT qty FROM PRDL WHERE prodCode = '$value' AND storeID = '$orStore'");
	$row = $myQuery->fetch();
	$curQty = $row['qty'];
	
	$newQty = $curQty - $quant;
	
	$sql = "UPDATE PRDL SET qty = '$newQty' WHERE prodCode = '$value' AND storeID = '$orStore'";
	$retval = $db->query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
	
	$myQuery = $db->query("SELECT qty FROM PRDL WHERE prodCode = '$value' AND storeID = '$dsStore'");
	$row = $myQuery->fetch();
	$curQty = $row['qty'];
	
	$newQty = $curQty + $quant;
	
	$sql = "UPDATE PRDL SET qty = '$newQty' WHERE prodCode = '$value' AND storeID = '$dsStore'";
	$retval = $db->query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
	
	
}

header('Location: ../transfers.php');
?>