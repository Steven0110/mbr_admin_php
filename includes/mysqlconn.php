<?php
$username = "root";
$password = "";
$hostname = "localhost";

$db = new PDO('mysql:host=localhost;dbname=unassalo_intranet;charset=utf8', $username, $password);

/*connection to the database
$dbhandle = mysqli_connect($hostname, $username, $password) 
 or die("Unable to connect to MySQL");

//select a database to work with
$selected = mysqli_select_db($dbhandle,'unassalo_intranet') 
  or die("Could not select DB");

//close the connection
//mysql_close($dbhandle);*/
	return $db;
?>