<?php
session_start();
include "mysqlconn.php";

$store = $_POST["storeID"];
$employee = $_SESSION["empID"];
$fromDate = $_POST["fromDate"];
$toDate = $_POST["toDate"];

// Discover next ID
$numQuery = "SELECT ID FROM PAYMENT ORDER BY ID DESC LIMIT 1";
$resultID = mysql_query($numQuery);
if (!$resultID)
{
	die('Could not read data ID: ' . mysql_error());
} else {
	$rowID = mysql_fetch_assoc($resultID);	
	$ID = $rowID["ID"] + 1;
}

// Discover next code
$codeQuery = "SELECT code FROM PAYMENT WHERE storeID = $store ORDER BY code DESC LIMIT 1";
$resultCode = mysql_query($codeQuery);
if (!$resultCode)
{
	die('Could not read data Code: ' . mysql_error());
} else {
	$rowCode = mysql_fetch_assoc($resultCode);
	if ($rowCode["code"] == "" || $rowCode["code"] == NULL) {
		$queryStore = mysql_query("SELECT code FROM STORES WHERE ID = $store");
		$rowStore = mysql_fetch_assoc($queryStore);
		$code = $rowStore["code"]."-PGO-100001";
	} else {
		$curCode = str_split($rowCode["code"], 8);
		$code = $curCode[0].($curCode[1] + 1);
	}
}

// Enter Payment to DB
$sql = "INSERT INTO PAYMENT (ID, code, created_at, storeID, empID, active, fromDate, toDate) VALUES ('$ID', '$code', CURRENT_TIMESTAMP, $store, $employee, 'Y', '$fromDate', '$toDate')";
$retval = mysql_query($sql);
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}

// Modify Transfer Lines
foreach ($_POST["tranCode"] as $key => $value) {
	$sql = "UPDATE TRANSFERS SET account = 'Y', pmntCode = '$code' WHERE code = '$value'";
	$retval = mysql_query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
}

header('Location: ../payments.php');
?>