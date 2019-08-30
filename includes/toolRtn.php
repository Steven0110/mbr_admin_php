<?php
session_start();
include "mysqlconn.php";

$infID = $_REQUEST["infID"];
$store = $_REQUEST["storeID"];
/*
$selectquery = $db->query("SELECT ID_PRESTAMO FROM prestamos GROUP BY ID_PRESTAMO ORDER BY ID_PRESTAMO DESC");
$oldID = $selectquery->fetch();
$nuevoID = (int)$oldID + 1;
*/
$sql = $db->query("SELECT t1.ID_HERRAMIENTA ID FROM prestamos t1 WHERE t1.ID_PRESTAMO = '$infID' LIMIT 1");
$reg = $sql->fetch();
	// Enter tool rtn lines
	foreach ($reg as $key => $value) {
		$sql1 = $db->query("SELECT QTY FROM prestamos WHERE ID_HERRAMIENTA = '$value' AND ID_PRESTAMO = '$infID'");
		$quant = $sql1->fetch();
		$currQuant = $quant["QTY"];
		// Modify inventory
		// Current QTY
		$myQuery = $db->query("SELECT qty FROM prdl WHERE prodCode = '$value' AND storeID = '$store'");
		$row = $myQuery->fetch();
		$curQty = $row['qty'];
		
		$newQty = $curQty + $currQuant;
		
		$sql = $db->query("UPDATE prestamos t1 SET t1.STATUS = 'C' WHERE t1.ID_PRESTAMO = '$infID' ");
		if(! $sql )
		{
		  die('Could not enter data: ' . mysqli_error());
		}
		$sql = $db->query("UPDATE prdl SET qty = '$newQty' WHERE prodCode = '$value' AND storeID = '$store'");
		if(! $sql )
		{
		  die('Could not enter data: ' . mysqli_error());
		}
	}

header('Location: ../prestamos.php');
?>