<?php
session_start();
include_once "mysqlconn.php";

$store = $_POST["store"];
$proyect = $_POST["proyect"];
$employee = $_SESSION["empID"];
$remarks = $_POST["remarks"];
$empEnc = $_POST["emp"];

$selectquery = $db->query("SELECT ID_PRESTAMO FROM prestamos GROUP BY ID_PRESTAMO ORDER BY ID_PRESTAMO DESC LIMIT 1");
$oldID = $selectquery->fetch();
$nuevoID = $oldID["ID_PRESTAMO"] + 1;
// Enter Inflow Lines
foreach ($_POST['prodCode'] as $key => $value) {
	$quant = $_POST['quant'][$key];
	$sql = "INSERT INTO prestamos (ID_PRESTAMO,ID_PROYECTO, ID_EMPLEADO, ID_STORE, ID_HERRAMIENTA,CREATED_AT,REMARKS,STATUS,qty) VALUES ('$nuevoID','$proyect', '$empEnc','$store', '$value', CURRENT_TIMESTAMP,'$remarks','A','$quant')";
	$retval = $db->query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysqli_error());
	}
	
	// Modify inventory
	// Current QTY
	$myQuery = $db->query("SELECT qty FROM prdl WHERE prodCode = '$value' AND storeID = '$store'");
	$row = $myQuery->fetch();
	$curQty = $row['qty'];
	
	$newQty = $curQty - $quant;
	
	$sql = "UPDATE prdl SET qty = '$newQty' WHERE prodCode = '$value' AND storeID = '$store'";
	$retval = $db->query($sql);
	if(! $retval )
	{
	  die('Could not enter data: ' . mysqli_error());
	}
}

header('Location: ../prestamos.php');
?>