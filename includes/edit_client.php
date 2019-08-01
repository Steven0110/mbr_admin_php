<?php
$dbhost = 'localhost';
$dbuser = 'idited_cronos';
$dbpass = 'Cr0n0$';

$conn = mysql_connect($dbhost, $dbuser, $dbpass);

if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}

$clientID = $_POST['clientID'];
$customer = $_POST['customer'];
$first = $_POST['first'];
$last = $_POST['last'];
$phone = $_POST['phone'];
$alt_phone = $_POST['alt_phone'];
$email = $_POST['email'];
$alt_email = $_POST['alt_email'];
$notes = $_POST['notes'];


// Write to DB

$sql = "UPDATE CLIENTS SET name = '$customer', cnt_first = '$first', cnt_last = '$last', phone = '$phone', alt_phone = '$alt_phone', email = '$email', alt_email = '$alt_email', notes = '$notes' WHERE ID = $clientID";

mysql_select_db('idited_cronos');
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not modify data: ' . mysql_error());
}

header('Location: ../clients.php');
mysql_close($conn);
?>