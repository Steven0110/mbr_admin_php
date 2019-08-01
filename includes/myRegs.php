<?php
$username = "idited_cronos";
$password = "Cr0n0$";
$hostname = "localhost"; 

//connection to the database
$dbhandle = mysql_connect($hostname, $username, $password) 
 or die("Unable to connect to MySQL");

//select a database to work with
$selected = mysql_select_db("idited_cronos",$dbhandle) 
  or die("Could not select DB");

//close the connection
//mysql_close($dbhandle);
?>