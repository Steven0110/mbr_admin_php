<?php
session_start();
include "mysqlconn.php";

$baseOrd = "";
$store = $_POST["store"];
$employee = $_SESSION["empID"];
$remarks = $_POST['remarks'];

if (isset($_POST["ordCode"])) {
	$baseOrd = $_POST["ordCode"];
	
	$queryCloseOrder = "UPDATE ORDERS SET active = 'C', closed_at = CURRENT_TIMESTAMP, remarks = '$remarks' WHERE code = '$baseOrd'";
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

header('Location: ../orders.php');
?>