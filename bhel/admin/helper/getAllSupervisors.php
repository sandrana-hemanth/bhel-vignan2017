<?php
	session_start();
	$root=$_SERVER['DOCUMENT_ROOT'];
	include_once("$root/bhel/db/login.php");
	$exeid=$_POST['id'];
	$query="SELECT * FROM `supervisor` WHERE `exeid`='$exeid' and suspendedStatus=0";
	$result=mysqli_query($conn,$query);
	if($result)
	{
		$rows=array();
		while($r=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$rows[]=$r;
		}
		print json_encode($rows);
	}

?>