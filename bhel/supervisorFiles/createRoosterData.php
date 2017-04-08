<?php
session_start();
$root=$_SERVER['DOCUMENT_ROOT'];
	if(!isset($_SESSION['login_type']))
	{
		echo 'INVALID ACCESS';
		exit();
	}
	$month=($_GET['month'])+1;
	$year=$_GET['year'];
	$sid=$_SESSION['id'];
	include_once("$root/bhel/db/login.php");
	$empListQuery="SELECT `id` from emp WHERE `sid`='$sid' and id NOT IN (SELECT eid from rooster where mon='$month' and yr='$year')";
	
	$resultListQuery=mysqli_query($conn,$empListQuery);
	if($resultListQuery)
	{
		$insertQuery="INSERT INTO `rooster`(`eid`,`sid`,`mon`,`yr`) VALUES";
		$flag=0;
		while($r=mysqli_fetch_array($resultListQuery,MYSQLI_ASSOC))
		{
			$eid=$r['id'];
			if($flag)
				$insertQuery=$insertQuery.",";
			$insertQuery=$insertQuery." ('$eid','$sid','$month','$year')";
			$flag=1;
		}
		echo $insertQuery;
		$insertResult=mysqli_query($conn,$insertQuery);
		if($insertResult)
		{
			echo 'SUCCESS '.$insertQuery;
		}
		else{
			echo 'FAILED';
		}
	}
	else{
		echo 'something went wrong.';
	}

?>
