<?php
session_start();
include_once "mysqlconn.php";

$store = $_POST["storeID"];
$employee = $_SESSION["empID"];
$fromDate = $_POST["fromDate"];
$toDate = $_POST["toDate"];

// Discover next ID
$numQuery = "SELECT ID FROM payment ORDER BY ID DESC LIMIT 1";
$resultID = $db->query($numQuery);
if (!$resultID)
{
	die('Could not read data ID: ' . mysql_error());
} else {
	$rowID = $resultID->fetch();	
	$ID = $rowID["ID"] + 1;
}

// Discover next code
$codeQuery = "SELECT code FROM payment WHERE storeID = $store ORDER BY code DESC LIMIT 1";
$resultCode = $db->query($codeQuery);
if (!$resultCode)
{
	die('Could not read data Code: ' . mysql_error());
} else {
	$rowCode = $resultCode->fetch();
	if ($rowCode["code"] == "" || $rowCode["code"] == NULL) {
		$queryStore = $db->query("SELECT code FROM stores WHERE ID = $store");
		$rowStore = $queryStore->fetch();
		$code = $rowStore["code"]."-PGO-100001";
	} else {
		$curCode = str_split($rowCode["code"], 8);
		$code = $curCode[0].($curCode[1] + 1);
	}
}

// Enter Payment to DB
$sql = "INSERT INTO payment (ID, code, created_at, storeID, empID, active, fromDate, toDate) VALUES ('$ID', '$code', CURRENT_TIMESTAMP, $store, $employee, 'Y', '$fromDate', '$toDate')";
$retval = $db->query($sql);
if(! $retval )
{
  die('Could not enter data: ' . mysql_error());
}

// Modify Transfer Lines
foreach ($_POST["tranCode"] as $key => $value) {
	$sql = "UPDATE transfers SET account = 'Y', pmntCode = '$code' WHERE code = '$value'";
	$retval = $db->query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysql_error());
	}
}

header('Location: ../payments.php');
?>