<?php

	session_start();
	$root=$_SERVER['DOCUMENT_ROOT'];
	include_once("$root/bhel/db/login.php");
	$id=$_POST['id'];
	$query="SELECT * FROM emp where id='$id'";
	$res=mysqli_query($conn,$query);
	if($res)
	{
		if(mysqli_num_rows($res)==1)
		{
			$arr=mysqli_fetch_assoc($res);
			$arr['who']='employee';
			print json_encode($arr);
		}
		else
		{
			$query="SELECT * FROM supervisor where id='$id'";
			$res=mysqli_query($conn,$query);
			if($res)
			{
				if(mysqli_num_rows($res)==1)
				{
					$arr=mysqli_fetch_assoc($res);
					$arr['who']='supervisor';
					print json_encode($arr);
				}
				else
				{
					$query="SELECT * FROM executive where id='$id'";
					$res=mysqli_query($conn,$query);
					if($res)
					{
						if(mysqli_num_rows($res)==1)
						{
							$arr=mysqli_fetch_assoc($res);
							$arr['who']='executive';
							print json_encode($arr);
						}
						else
							print "failed";
					}
				}
			}
		}
	}
	
?>