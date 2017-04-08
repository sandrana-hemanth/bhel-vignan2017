<?php
session_start();


$to=$_POST['to'];
$root=$_SERVER['DOCUMENT_ROOT'];
include_once("$root/bhel/db/login.php");
if(!strcmp($to,"executive"))
{
	$password=$_POST['password'];
	$id=$_POST['id'];
	$query="UPDATE `executive` SET `password`='$password' WHERE `id`='$id'";
	if(mysqli_query($conn,$query)){
		echo 'success';
	}
	else{
		echo 'failed';
	}
}
else if(!strcmp($to,"supervisor"))
{
	$password=$_POST['password'];
	$id=$_POST['id'];
	$query="UPDATE `supervisor` SET `password`='$password' WHERE `id`='$id'";
	if(mysqli_query($conn,$query)){
		echo 'success';
	}
	else{
		echo 'failed';
	}
}
else{
	echo 'failed';
}

?>
