<?php
$root=$_SERVER['DOCUMENT_ROOT'];

include_once("$root/bhel/db/login.php");
$option=$_POST['option'];
if(!strcmp($option,"supervisor"))
{
	$supervisorID=$_POST['supervisorID'];
	$exeID=$_POST['executiveID'];
	$query="UPDATE `supervisor` SET exeid='$exeID' WHERE id='$supervisorID'";
	$res=mysqli_query($conn,$query);
	if($res)
	{
		if(mysqli_affected_rows($conn)==1)
			echo "success";
		else
			echo "No Effected Rows";
	}
	else
		echo "FAILED  $query";
	exit();
}
if(!strcmp($option,"employee"))
{
	$employeeID=$_POST['employeeID'];
	$sID=$_POST['supervisorID'];
	$query="UPDATE `emp` SET sid='$sID' WHERE id='$employeeID'";
	$res=mysqli_query($conn,$query);
	if($res)
	{
		if(mysqli_affected_rows($conn)==1)
			echo "success";
		else
			echo "No Effected Rows";
	}
	else
		echo "FAILED  $query";
	exit();
}

?>