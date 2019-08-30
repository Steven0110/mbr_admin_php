<?php
include "mysqlconn.php";

$ID = $_POST["userID"];
$first =  utf8_decode($_POST["first"]);
$last = utf8_decode($_POST["last"]);
$email = $_POST["email"];
$storeID = $_POST["store"];
$username = $_POST["username"];
$password = $_POST["password"];
if(isset($_POST["admin"])) {
	$role = 'Y';
} else {
	$role = 'N';
}
if(isset($_POST["active"])) {
	$active = 'Y';
} else {
	$active = 'N';
}
if(isset($_POST["sales"])) {
	$sales = 'Y';
} else {
	$sales = 'N';
}

if($ID == "") {
	$query = "INSERT INTO crew (ID, first, last, email, storeID, username, password, role, active, salesPrson) VALUES (NULL, '$first', '$last', '$email', $storeID, '$username', '$password', '$role', '$active', '$sales')";
} else {
	$query = "UPDATE crew SET first = '$first', last = '$last', email = '$email', storeID = $storeID, username = '$username', password = '$password', role = '$role', active = '$active', salesPrson = '$sales' WHERE ID = '$ID'";
}
$result = $db->query($query);

if(!$result) {
	die('Could not enter data USER: ' . mysql_error());
}
?>