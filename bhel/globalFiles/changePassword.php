<?php
session_start();
if(!isset($_SESSION['login_type']))
{
	echo "Login FAield";
	exit();
}
	
$url;

if(isset($_POST['password']))
{
	changeCurrentPassword();
	exit();
}
echo "huhuhu";
function changeCurrentPassword()
{
	$tableName;
	switch($_SESSION['login_type'])
	{
		case 'executive':
			$tableName='executive';
			break;
		case 'supervisor':
			$tableName='supervisor';
			break;
		case 'timeoffice':
			$tableName='timeoffice';
			break;
		case 'admin':
			$tableName='admin';
			break;
			
		
	}
	$root=$_SERVER['DOCUMENT_ROOT'];
	include("$root/bhel/db/login.php");
	$id=$_SESSION['id'];
	$currentPassword=$_POST['currentpassword'];
	$password=$_POST['password'];
	$query="UPDATE $tableName SET password ='$password' WHERE password = '$currentPassword' and id= '$id'";
	$res=mysqli_query($conn,$query);
	if($res)
	{
		echo "success";
	}
	else
		echo "failed";
}
?>
