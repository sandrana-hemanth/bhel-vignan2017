<?php
	session_start();
$root=$_SERVER['DOCUMENT_ROOT'];
	if(isset($_SESSION['id']))
	{
		$exeid=$_SESSION['id'];
		$mon=$_GET['month']+1; //as in javascript month is 0 based
		$yr=$_GET['year'];
		include_once("$root/bhel/db/login.php");
		$checkQuery="SELECT id from supervisor where exeid='$exeid' and suspendedStatus=0 and id not in (select sid from supervisor_rooster where mon='$mon' and yr='$yr');";
		$checkResult=mysqli_query($conn,$checkQuery);
		if($checkResult)
		{
			if(mysqli_num_rows($checkResult)>0){
				$rows=array();
				print json_encode($rows);
				exit();
			}
		}
		else{
			echo "check failed ".$checkQuery;
			exit();
		}
		$roosterQuery="SELECT * FROM supervisor_rooster,supervisor where supervisor.id=supervisor_rooster.sid  and supervisor_rooster.exeid='$exeid' and mon='$mon' and yr='$yr' ORDER BY sid ASC;";
		$result=mysqli_query($conn,$roosterQuery);
		//echo "kukuku  $roosterQuery";
		if($result)
		{
			$rows=array();
			while($r=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$rows[]=$r;
			}
			//echo "iam here. kkd";
			print json_encode($rows);
		}
	}
	else{
		
		exit();
	}

?>