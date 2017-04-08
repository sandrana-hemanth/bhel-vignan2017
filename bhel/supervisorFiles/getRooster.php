<?php
	session_start();
$root=$_SERVER['DOCUMENT_ROOT'];
	if(isset($_SESSION['id']))
	{
		$id=$_SESSION['id'];
		$mon=$_GET['month']+1; //as in javascript month is 0 based
		$yr=$_GET['year'];
		include_once("$root/bhel/db/login.php");
		$empCountQuery="SELECT `id` from emp where sid='$id'";
		$empCountResult=mysqli_query($conn,$empCountQuery);
		$empCount=mysqli_num_rows($empCountResult);
		$roosterQuery="SELECT * from rooster where mon='$mon' and yr='$yr' and sid='$id'";
		$result=mysqli_query($conn,$roosterQuery);
		if($result)
		{
			$rows=array();
			if(mysqli_num_rows($result)!=$empCount)
			{
				print json_encode($rows);
				exit();
			}
			while($r=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$rows[]=$r;
			}
			print json_encode($rows);
		}
	}
	else{
		exit();
	}

?>