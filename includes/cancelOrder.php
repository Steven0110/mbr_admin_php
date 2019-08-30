<?php
session_start();
include "mysqlconn.php";

$baseOrd = "";
$store = $_POST["store"];
$employee = $_SESSION["empID"];
$remarks = $_POST['remarks'];

if (isset($_POST["ordCode"])) {
	$baseOrd = $_POST["ordCode"];
	
	$queryCloseOrder = "UPDATE orders SET active = 'C', closed_at = CURRENT_TIMESTAMP, remarks = '$remarks' WHERE code = '$baseOrd'";
	$resultCloseOrder = $db->query($queryCloseOrder);
	if (!$resultCloseOrder)
	{
		die('Could not write data CloseOrder: ' . mysql_error());
	}
	
	// Modify inventory
	foreach ($_POST['prodCode'] as $key => $value) {
		$quant = $_POST['quant'][$key];
		// Current QTY onOrder
		$myQuery = $db->query("SELECT OnOrder FROM prdl WHERE prodCode = '$value' AND storeID = '$store'");
		$row = $myQuery->fetch();
		$curQty = $row["OnOrder"];
		
		$newQty = $curQty - $quant;
		
		$sql = "UPDATE prdl SET OnOrder = '$newQty' WHERE prodCode = '$value' AND storeID = '$store'";
		$retval = $db->query($sql);
		if(! $retval )
		{
		  die('Could not enter data: ' . mysql_error());
		}
	}
}

header('Location: ../orders.php');
?>