<?php
$dbhost = 'localhost';
$dbuser = 'idited_cronos';
$dbpass = 'Cr0n0$';

$conn = mysql_connect($dbhost, $dbuser, $dbpass);

if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}

$record = $_POST['record'];
$remarks = $_POST['remarks'];
$endDate = "";
$currentDate = "";
$warnDate = "";
$deadDate = "";
$status = "";

// Check end Date
$sql = "SELECT close FROM SERVICE WHERE ID = $record";

mysql_select_db('idited_cronos');
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not read data: ' . mysql_error());
} else {
	// Define status
	$row = mysql_fetch_array($retval);
	$endDate = new DateTime($row["close"]);
	$currentDate = new DateTime('now');
	$deadDate = clone $endDate;
	$deadDate->add(new DateInterval("P1M"));
	$warnDate = clone $endDate;
	$warnDate->sub(new DateInterval("P1M"));
	if ($currentDate < $warnDate) {
		$status = 1;
	} else if ($currentDate >= $warnDate && $currentDate < $endDate) {
		$status = 2;
	} else if ($currentDate >= $endDate && $currentDate < $deadDate) {
		$status = 3;
	} else {
		$status = 4;
	}
	//echo ("End Date: ".$endDate->format('Y-m-d')."<br>");
	//echo ("Today is: ".$currentDate->format('Y-m-d')."<br>");
	//echo ("Warn Date: ".$warnDate->format('Y-m-d')."<br>");
	//echo ("Dead Date: ".$deadDate->format('Y-m-d')."<br>");
	//echo ("Status: $status");
	//exit;
}

// Write to DB

$sql = "UPDATE SERVICE SET status = '$status', remarks = '$remarks' WHERE ID = $record";

mysql_select_db('idited_cronos');
$retval = mysql_query( $sql, $conn );
if(! $retval )
{
  die('Could not modify data: ' . mysql_error());
}

header('Location: ../records.php');
mysql_close($conn);
?>