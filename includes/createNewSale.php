<?php
session_start();
include_once "mysqlconn.php";

$store = 100;
$employee = $_SESSION["empID"];
$remarks = $_POST['remarks'];

// Discover next ID
$numQuery = "SELECT ID FROM SALES ORDER BY ID DESC LIMIT 1";
$resultID = $db->query($numQuery);
if (!$resultID)
{
	die('Could not read data: ' . mysql_error());
} else {
	$rowID = $resultID->fetch();	
	$ID = $rowID["ID"] + 1;
}

// Discover next code
$codeQuery = "SELECT code FROM SALES WHERE storeID = $store ORDER BY code DESC LIMIT 1";
$resultCode = $db->query($codeQuery);
if (!$resultCode)
{
	die('Could not read data Code: ' . mysql_error());
} else {
	$rowCode = $resultCode->fetch();
	if ($rowCode["code"] == "" || $rowCode["code"] == NULL) {
		$queryStore = $db->query("SELECT code FROM STORES WHERE ID = $store");
		$rowStore = $queryStore->fetch();
		$code = $rowStore["code"]."-VEN-100001";
	} else {
		$curCode = str_split($rowCode["code"], 8);
		$code = $curCode[0].($curCode[1] + 1);
	}
}


// Enter Outflow to DB
$sql = "INSERT INTO SALES (ID, code, storeID, empID, created_at, remarks) VALUES ('$ID', '$code', '$store', '$employee', CURRENT_TIMESTAMP, '$remarks')";
$retval = $db->query($sql);
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}

// Enter Outflow Lines
foreach ($_POST['prodCode'] as $key => $value) {
	$quant = $_POST['quant'][$key];
	$price = $_POST['price'][$key];
	$sql = "INSERT INTO SALLN (ID, salID, prodCode, qty, price) VALUES (NULL, '$ID', '$value', '$quant', '$price')";
	$retval = $db->query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
	
	// Modify inventory
	// Current QTY
	$myQuery = $db->query("SELECT qty FROM PRDL WHERE prodCode = '$value' AND storeID = '$store'");
	$row = $myQuery->fetch();
	$curQty = $row['qty'];
	
	$newQty = $curQty - $quant;
	
	$sql = "UPDATE PRDL SET qty = '$newQty' WHERE prodCode = '$value' AND storeID = '$store'";
	$retval = $db->query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
	
	
}

header('Location: ../sales.php');
?>