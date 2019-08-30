<?php
include_once "mysqlconn.php";

$ID = $_POST["storeID"];
$code =  $_POST["code"];
$name = utf8_decode($_POST["name"]);
$phone =  $_POST["phone"];
$address = utf8_decode($_POST["address"]);
if(isset($_POST["active"])) {
	$active = 'Y';
} else {
	$active = 'N';
}

if($ID == "") {
	// Discover next ID
	$queryID = "SELECT ID FROM stores ORDER BY ID DESC LIMIT 1";
	$resultID = $db->query($queryID);
	$rowID = $resultID->fetch();
	$lastID = $rowID["ID"];
	$nextID = $lastID + 1;
		
	$query = "INSERT INTO stores (ID, code, name, phone, address, active) VALUES ($nextID, '$code', '$name', '$phone', '$address', '$active')";
	
	$queryProducts = "SELECT code FROM product";
	$resultProducts = $db->query($queryProducts);
	while($rowProducts = $resultProducts->fetch()) {
		$queryLines = "INSERT INTO prdl (ID, prodCode, storeID, qty, minq, maxq) VALUES (NULL, '".$rowProducts["code"]."', $nextID, 0, '1', '1')";
		$resultLines = $db->query($queryLines);
	};
	
} else {
	$query = "UPDATE stores SET code = '$code', name = '$name', phone = '$phone', address = '$address', active = '$active' WHERE ID = '$ID'";
}
$result = $db->query($query);

if(!$result) {
	die('Could not enter data STORE: ' . mysql_error());
}
?>