<?php
session_start();
include "mysqlconn.php";

$infID = $_REQUEST["infID"];
$store = $_REQUEST["storeID"];
$prodCode = array();
/*$prodCode = array();

$selectquery = $db->query("SELECT ID_PRESTAMO FROM prestamos GROUP BY ID_PRESTAMO ORDER BY ID_PRESTAMO DESC");
$oldID = $selectquery->fetch();
$nuevoID = (int)$oldID + 1;
*/
$sql = $db->query("SELECT t1.ID_HERRAMIENTA ID FROM prestamos t1 WHERE t1.ID_PRESTAMO = '$infID' "); /*LIMIT 1*/
	// Enter tool rtn lines

while($row = $sql->fetch()){
	$prodCode[] = $row["ID"];
}

$codesquery = $db->query("SELECT COUNT(t1.ID_HERRAMIENTA) ID FROM prestamos t1 WHERE t1.ID_PRESTAMO = '$infID'");
$codesresult = $codesquery->fetch();

for($i = 0; $i<$codesresult["ID"]; $i++){
		$sql1 = $db->query("SELECT QTY FROM prestamos WHERE ID_PRESTAMO = '$infID' AND ID_HERRAMIENTA = '$prodCode[$i]'");
		$quant = $sql1->fetch();
		$currQuant = $quant["QTY"];
		// Modify inventory
		// Current QTY
		$myQuery = $db->query("SELECT qty FROM prdl WHERE prodCode = '$prodCode[$i]' AND storeID = '$store'");
		$row = $myQuery->fetch();
		$curQty = $row['qty'];
		
		$newQty = $curQty + $currQuant;
		
		
		$sql = $db->query("UPDATE prdl SET qty = '$newQty' WHERE prodCode = '$prodCode[$i]' AND storeID = '$store'");
		if(! $sql )
		{
		  die('Could not enter data: ' . mysqli_error());
		}
	}

	$sql = $db->query("UPDATE prestamos t1 SET t1.STATUS = 'C', t1.CLOSED_AT = now() WHERE t1.ID_PRESTAMO = '$infID' ");
		if(! $sql )
		{
		  die('Could not enter data: ' . mysqli_error());
		}
	/*foreach ( as $key => $value) {
		
	}*/

header('Location: ../prestamos.php');
?>