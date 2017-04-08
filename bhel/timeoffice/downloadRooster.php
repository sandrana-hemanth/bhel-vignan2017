<?php
session_start();

$root=$_SERVER['DOCUMENT_ROOT'];
include_once("$root/bhel/db/login.php");
$startMonth=$_POST['startMonth']+1;
$startYear=$_POST['startYear'];

$query="SELECT * FROM rooster where mon='$startMonth' and yr='$startYear';";
$res=mysqli_query($conn,$query);
if($res)
{
	$row=[];
	while($r=mysqli_fetch_assoc($res))
	{
		$row[]=$r;
	}
	print json_encode($row);
}

?>