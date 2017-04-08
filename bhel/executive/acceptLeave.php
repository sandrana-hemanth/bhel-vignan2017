<?php
	session_start();
	$root=$_SERVER['DOCUMENT_ROOT'];
	include_once("$root/bhel/db/login.php");
	$leave_id=$_GET['leave_id'];
	$accept=$_GET['accept'];
	$query="";
	if($accept==1)
	{
		$query.="UPDATE `leaves` SET `exe_status`=2 , `timeoffice_status`=1 WHERE `leave_id`=$leave_id";
	}
	else
	{
		
		$query.="UPDATE `leaves` SET `exe_status`=3, `timeoffice_status`=0 WHERE `leave_id`=$leave_id";
	}
	mysqli_query($conn,$query);
	header("Location: executive.php");
	

?>