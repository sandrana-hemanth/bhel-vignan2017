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
		$updateQuery="UPDATE `rooster` SET ";
		$j=1;
		for($j=1;$j<=31;$j++)
		{
			$val=$r[$j];
			if($j-1)
				$updateQuery=$updateQuery.",";
			$updateQuery=$updateQuery."`$j`='$val' ";
		}
		$mon=$r["mon"];
		$yr=$r["yr"];
		$eid=$r["eid"];
		$updateQuery=$updateQuery."WHERE `mon`='$mon' AND `yr`='$yr' AND `eid`='$eid'";
		$res=mysqli_query($conn,$updateQuery);
		if($res)
		{
			echo 'success';
		}
		else{
			echo 'failed at query';
		}
		
	}
}
else
{
	echo 'FAILED';
}

?>