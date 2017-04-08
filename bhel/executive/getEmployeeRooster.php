<?php
	session_start();
	//echo "called";
$root=$_SERVER['DOCUMENT_ROOT'];
	if(isset($_SESSION['id']))
	{
		$id=$_SESSION['id'];
		$mon=$_GET['month']+1; //as in javascript month is 0 based
		$yr=$_GET['year'];
		include_once("$root/bhel/db/login.php");
		$roosterQuery="SELECT * FROM rooster r,supervisor s INNER JOIN executive e ON (s.exeid = e.id) where r.sid = s.id and e.id='$id' and r.mon='$mon' and r.yr='$yr'";
		$result=mysqli_query($conn,$roosterQuery);
		if($result)
		{
			//echo "success";
			$rows=array();
			if(mysqli_num_rows($result)==0)
			{
				//echo "\n $roosterQuery\n";
				print json_encode($rows);
				exit();
			}
			while($r=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$rows[]=$r;
			}
			//echo "more rows\n $roosterQuery\n";
			print json_encode($rows);
			
		}
		else{
			echo "\n$roosterQuery\n";
		}
	}
	else{
		echo "failing here";
		exit();
	}

?>