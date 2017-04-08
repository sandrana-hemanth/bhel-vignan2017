<?php
session_start();



$to=$_POST['to'];
$root=$_SERVER['DOCUMENT_ROOT'];

include_once("$root/bhel/db/login.php");
if(!strcmp($to,"supervisor"))
{
	$id=$_POST['id'];
	$firstname=$_POST['firstname'];
	$lastname=$_POST['lastname'];
	$pattern=$_POST['pattern'];
	$exeid=$_POST['exeID'];
	$password=$_POST['password'];
	//echo "$password";
	$query="INSERT INTO `supervisor`(`id`, `exeid`, `pattern`, `firstname`, `lastname`, `password`) VALUES('$id','$exeid','$pattern','$firstname','$lastname','$password')";
	//echo "    $query";
	if(mysqli_query($conn,$query)){
		echo 'success';
	}
	else{
		echo 'failed';
	}
}
else if(!strcmp($to,"employee"))
{
	$id=$_POST['id'];
	$sid=$_POST['sID'];
	$firstname=$_POST['firstname'];
	$lastname=$_POST['lastname'];
	$gender=$_POST['gender'];
	$dob=$_POST['dob'];
	$joiningDate=$_POST['joiningDate'];
	$query="INSERT INTO `emp`(`id`, `sid`, `firstname`, `lastname`, `gender`, `dob`, `joining_date`) VALUES('$id','$sid','$firstname','$lastname','$gender','$dob','$joiningDate');";
	if(mysqli_query($conn,$query)){
		echo 'success';
	}
	else{
		echo 'failed  '.$query;
	}
	$leave_query="INSERT INTO `leave_count`(`E_ID`) values('$id');";
	mysqli_query($conn,$leave_query);
}
else if(!strcmp($to,"executive"))
{
	$id=$_POST['id'];
	$firstname=$_POST['firstname'];
	$lastname=$_POST['lastname'];
	$password=$_POST['password'];
	$query="INSERT INTO `executive` (id,firstname,lastname,password) values('$id','$firstname','$lastname','$password');";
	if(mysqli_query($conn,$query)){
		echo 'success';
	}
	else{
		echo 'failed  '.$query;
	}
}
else{
	echo 'failed nothing matched '.$to;
}

?>
