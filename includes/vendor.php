<?php
include_once "mysqlconn.php";

$ID = $_POST["vendorID"];
$name = utf8_decode($_POST["name"]);

if($ID == "") {
	
	$query = "INSERT INTO vendor (name) VALUES ('$name')";
	
	
} else {
	$query = "UPDATE vendor SET name = '$name' WHERE ID = '$ID'";
}
$result = $db->query($query);

if(!$result) {
	die('Could not enter data STORE: ' . mysql_error());
}
?>