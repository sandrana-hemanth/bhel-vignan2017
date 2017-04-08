<?php
	$servername = "localhost";
	$username = "root";
	$password = "MyPassword";
	$dbname = "bhel";
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	if(!$conn)
	{
		echo 'database connection failed..contact Admin';
		exit();
	}
?>