<?php
	session_start();
	$root=$_SERVER['DOCUMENT_ROOT'];
	include_once("$root/bhel/db/login.php");
	$leave_id=$_GET['leave_id'];
	$accept=$_GET['accept'];
	switch($accept){
		case 1:
				accept($leave_id,$conn);
				break;
		case 2:
				reject($leave_id);
	}
	header("Location:timeoffice.php");
function reject($leave_id,$conn){
	$query="SELECT * FROM `leaves` WHERE `leave_id`='$leave_id'";
	$res=mysqli_query($conn,$query);
	if($res && mysqli_num_rows($res)==1)
	{
		$assoc=mysqli_fetch_assoc($res);
		$leaveID=$assoc['leave_id'];
		$query="UPDATE `leaves` SET `timeoffice_status` = 3 WHERE `leave_id` = $leaveID;";
		mysqli_query($conn,$query);
	}
}
function accept($leave_id,$conn){
	$query="SELECT * FROM `leaves` WHERE `leave_id`='$leave_id'";
	$res=mysqli_query($conn,$query);
	if($res && mysqli_num_rows($res)==1)
	{
		$assoc=mysqli_fetch_assoc($res);
		$type=$assoc['type'];
		$fromDate=$assoc['from_date'];
		$toDate=$assoc['to_date'];
		$num_of_days=getDaysBetween($fromDate,$toDate);
		$leaveObj=new LeaveApplyFactory();
		mysqli_query($conn,"BEGIN;");
		$leaveDebitStatus=false;
		switch($type)
		{
			case "0001":
						$leaveDebitStatus=$leaveObj->debitCL($assoc,$num_of_days,$conn);
						break;
			case "0002":
						$leaveDebitStatus=$leaveObj->debitEL($assoc,$num_of_days,$conn);
						break;
			case "0003":
						$leaveDebitStatus=$leaveObj->debitHPL($assoc,$num_of_days,$conn);
						break;
			case "0004":
						$leaveDebitStatus=$leaveObj->debitSL($assoc,$num_of_days,$conn);
						break;
			case "0009":
						$leaveDebitStatus=$leaveObj->debitEOL($assoc,$num_of_days,$conn);
						break;
			case "0016":
						$leaveDebitStatus=$leaveObj->debitUAB($assoc,$num_of_days,$conn);
						break;
			case "0020":
						$leaveDebitStatus=$leaveObj->debitOH($assoc,$num_of_days,$conn);
						break;
			case "0023":
			case "0024":
						$leaveDebitStatus=$leaveObj->debitCL($assoc,$num_of_days/2,$conn);
						break;
			case "0033":
			case "0034":
						$leaveDebitStatus=$leaveObj->debitUAB($assoc,$num_of_days/2,$conn);
						break;
		}
		$timeofficeStatus=$leaveObj->timeOfficerAccept($assoc,$conn);
		if($timeofficeStatus && $leaveDebitStatus)
			mysqli_query($conn,"COMMIT;");
		else
			mysqli_query($conn,"ROLLBACK;");
	}
}
	class LeaveApplyFactory{
		function debitCL($arr,$num_of_days,$conn){
			$id=$arr['E_ID'];
			$query="UPDATE  `leave_count` l SET l.CL = l.CL - $num_of_days WHERE (l.CL - $num_of_days) >=0 and `E_ID`='$id' ;";
			$res=mysqli_query($conn,$query);
			if($res)
			{
				if(mysqli_affected_rows($conn)==0)
				{
					$query="UPDATE  `leave_count` l SET l.CL = 0 WHERE `E_ID`='$id' ";
					$res=$mysqli_query($conn,$query);
					if($res)
						return true;
					return false;
				}
				return true;
			}
		}
		function debitEL($arr,$num_of_days,$conn){
			$id=$arr['E_ID'];
			$query="UPDATE `leave_count` l SET l.EL = l.EL - $num_of_days WHERE (l.EL - $num_of_days) >=0 and `E_ID`='$id' ;";
			$res=mysqli_query($conn,$query);
			if($res)
			{
				if(mysqli_affected_rows($conn)==0)
				{
					$query="UPDATE `leave_count` l SET l.EL = 0 WHERE `E_ID`='$id' ";
					$res=mysqli_query($conn,$query);
					if($res){
						return true;
					}
					else
					{
						echo "<br>failed here.. $query";
						return false;
					}
					
				}
				return true;
			}
			else
			{
				echo "<br>failed .. $query<br>".mysqli::$error;
			}
		}
		function debitHPL($arr,$num_of_days,$conn){
			$id=$arr['E_ID'];
			$query="UPDATE  `leave_count` l SET l.HPL = l.HPL - $num_of_days WHERE (l.HPL - $num_of_days) >=0 and `E_ID`='$id' ;";
			$res=mysqli_query($conn,$query);
			if($res)
			{
				if(mysqli_affected_rows($conn)==0)
				{
					$query="UPDATE  `leave_count` l SET l.HPL = 0 WHERE `E_ID`='$id' ";
					$res=mysqli_query($conn,$query);
					if($res)
						return true;
					return false;
				}
				return true;
			}
		}
		function debitSL($arr,$num_of_days,$conn){
			$id=$arr['E_ID'];
			$query="UPDATE  `leave_count` l SET l.SL = l.SL - $num_of_days WHERE (l.SL - $num_of_days) >=0 and `E_ID`='$id' ;";
			$res=mysqli_query($conn,$query);
			if($res)
			{
				if(mysqli_affected_rows($conn)==0)
				{
					$query="UPDATE  `leave_count` l SET l.SL = 0 WHERE `E_ID`='$id' ";
					$res=mysqli_query($conn,$query);
					if($res)
						return true;
					return false;
				}
				return true;
			}
		}
		function debitEOL($arr,$num_of_days,$conn){
			$id=$arr['E_ID'];
			$query="UPDATE  `leave_count` l SET l.EOL = l.EOL - $num_of_days WHERE (l.EOL - $num_of_days) >=0 and `E_ID`='$id' ;";
			$res=mysqli_query($conn,$query);
			if($res)
			{
				if(mysqli_affected_rows($conn)==0)
				{
					$query="UPDATE  `leave_count` l SET l.EOL = 0 WHERE `E_ID`='$id' ";
					$res=mysqli_query($conn,$query);
					if($res)
						return true;
					return false;
				}
				return true;
			}
		}
		function debitUAB($arr,$num_of_days,$conn){
			$id=$arr['E_ID'];
			$query="UPDATE  `leave_count` l SET l.UAB = l.UAB - $num_of_days WHERE (l.UAB - $num_of_days) >=0 and `E_ID`='$id';";
			$res=mysqli_query($conn,$query);
			if($res)
			{
				if(mysqli_affected_rows($conn)==0)
				{
					$query="UPDATE  `leave_count` l SET l.UAB = 0 WHERE `E_ID`='$id' ";
					$res=mysqli_query($conn,$query);
					if($res)
						return true;
					return false;
				}
				return true;
			}
		}
		function debitOH($arr,$num_of_days,$conn){
			$id=$arr['E_ID'];
			$query="UPDATE  `leave_count` l SET l.OH = l.OH - $num_of_days WHERE (l.OH - $num_of_days) >=0 and `E_ID`='$id' ;";
			$res=mysqli_query($conn,$query);
			if($res)
			{
				if(mysqli_affected_rows($conn)==0)
				{
					$query="UPDATE  `leave_count` l SET l.OH = 0 WHERE `E_ID`='$id' ";
					$res=mysqli_query($conn,$query);
					if($res)
						return true;
					return false;
				}
				return true;
			}
		}
		function timeOfficerAccept($arr,$conn)
		{
			$leaveID=$arr['leave_id'];
			$query="UPDATE `leaves` SET `timeoffice_status` = 2 WHERE `leave_id` = $leaveID;";
			return mysqli_query($conn,$query);
		}
	}
	function getDaysBetween($from,$to)
		{
			$startTime=strtotime($from);
			$endTime=strtotime($to);
			echo "$startTime<br>$endTime<br>";
			return (($endTime-$startTime)/(60*60*24))+1;
		}

?>