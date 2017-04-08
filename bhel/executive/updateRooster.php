<?php
session_start();
$root=$_SERVER['DOCUMENT_ROOT'];
if(isset($_POST['emp']))
{
	$updatedData=json_decode($_POST['emp'],true);
	include_once("$root/bhel/db/login.php");
	for($i=0;$i<count($updatedData);$i++)
	{
		$r=$updatedData[$i];
		$updateQuery="UPDATE supervisor_rooster s INNER JOIN rooster r ON (s.sid=r.sid ) SET ";
		$j=1;
		for($j=1;$j<=31;$j++)
		{
			$val=$r[$j];
			if($j-1)
				$updateQuery=$updateQuery.",";
			$updateQuery=$updateQuery."s.`$j`='$val' , r.`$j`='$val' ";
		}
		$mon=$r["mon"];
		$yr=$r["yr"];
		$sid=$r["sid"];
		$updateQuery=$updateQuery."WHERE s.`mon`='$mon' AND s.`yr`='$yr' AND s.`sid`='$sid'";
		$res=mysqli_query($conn,$updateQuery);
		if($res)
		{
			echo 'success';
		}
		else{
			echo 'failed at query  '.$updateQuery;
		}
		$pattern=$r['pattern'];
		$updatePattern="UPDATE supervisor s SET s.pattern = '$pattern' where s.id='$sid'";
		mysqli_query($conn,$updatePattern);
		echo $updatePattern;
	}
}
else
{
	echo 'FAILED';
}

?>