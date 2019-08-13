<?php
session_start();
include "mysqlconn.php";

$proyectname = $_POST["proyectname"];
$fchinicio = $_POST["fchinicio"];
$fchterm = $_POST['fchterm'];
$proyectcst = $_POST['proyectcst'];
$ganancia = $_POST['ganancia'];
$client = $_POST['client'];
$cntct_prsn = $_POST['cntct_prsn'];
$client_num = $_POST['client_num'];
$correo = $_POST['email'];
$coments = $_POST['remarks'];

// Enter Inflow Lines
try 
{
    $quant = $_POST['quant'][$key];
    $sql = "INSERT INTO proyects (proyectname,fchinicio,fchterm,proyectcst,ganancia,client,cntct_prsn,client_num,correo,coments) VALUES ('$proyectname','$fchinicio','$fchterm',$proyectcst,$ganancia,'$client','$cntct_prsn','$client_num','$correo','$coments')";
    $db->query($sql);
}	
catch(PDOException $e)
{
    echo $e;
}

header('Location: ../proyects.php');
?>