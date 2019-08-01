<?php
session_start();

$timeout = 1800; // Number of seconds until it times out.
 
// Check if the timeout field exists.
if(isset($_SESSION['timeout'])) {
    // See if the number of seconds since the last
    // visit is larger than the timeout period.
    $duration = time() - (int)$_SESSION['timeout'];
    if($duration > $timeout) {
        // Destroy the session and restart it.
        session_destroy();
        session_start();
    }
}
 
// Update the timout field with the current time.
$_SESSION['timeout'] = time();


if($_SESSION['authenticated_user'] != true) {
	echo "<p>No session</p>";
	//header('Location: index.php');
	//die();
} else {
	echo "You are logged in!";
}

?>
<html>
<head>
</head>
<body>
<p>P&aacute;gina de prueba</p>

<?php
echo "E-mail: ".$_SESSION['email']." <br>";
echo "Name: ".$_SESSION['name']." <br>";
echo "EmpID: ".$_SESSION['empID']." <br>";
echo "Username: ".$_SESSION['username']." <br>";
echo "Store: ".$_SESSION['store']." <br>";
echo "Admin: ".$_SESSION['role']." <br>";
echo "Sales person: ".$_SESSION['sales']." <br>";
print_r($_SESSION);
?>

<form action="" method="get">

<input type="submit" name="sb" value="One">
<input type="submit" name="sb" value="Two">
<input type="submit" name="sb" value="Three">

</form>


<p><a href="includes/logout.php">Salir</a></p>


</body>
</html>