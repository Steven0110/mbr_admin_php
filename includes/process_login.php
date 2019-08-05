<?php
include_once 'mysqlconn.php';

session_start();
 
if (isset($_POST["username"], $_POST["password"]) && $_POST["username"] != "" && $_POST["password"] != "") {
    $username = $_POST["username"];
    $password = $_POST["password"];
	
	$query = "SELECT ID, storeID, role, first, last, username, email, password, active, salesPrson FROM CREW WHERE username = '$username'";
	$result = $db->query($query);
	
	if ($row = $result->fetch()) {
		$pass = $row["password"];
		$active = $row["active"];
		
		if ($active != "Y") {
			header('Location: ../index.php?error=noactive');
		} else {
			if ($password == $pass) {
				// Login success
				$_SESSION["authenticated_user"] = true;
				$_SESSION["timeout"] = time();
				$_SESSION["empID"] = $row["ID"];
				$_SESSION["email"] = $row["email"];
				$_SESSION["name"] = $row["first"]." ".$row["last"];
				$_SESSION["username"] = $row["username"];
				$_SESSION["store"] = $row["storeID"];
				$_SESSION["role"] = $row["role"];
				$_SESSION["sales"] = $row["salesPrson"];
				
				header('Location: ../main.php');
			} else {
				// Login failed 
				header('Location: ../index.php?error=badlogin');
			}
		}
	} else {
		header('Location: ../index.php?error=nosuchuser');
	}	

} else {
    // The correct POST variables were not sent to this page. 
    echo 'Invalid Request';
}