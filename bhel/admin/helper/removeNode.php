<?php
session_start();
$root=$_SERVER['DOCUMENT_ROOT'];
include_once("$root/bhel/db/login.php");
$query="";
switch($_POST['option'])
{
	case 'employee':
			$id=$_POST['employeeID'];
			$reason=$_POST['reason'];
			$fetchData="SELECT * from `emp` where id='$id'";
			$fetchRes=mysqli_query($conn,$fetchData);
			if($fetchRes && mysqli_num_rows($fetchRes)==1)
			{
				$fetchRow=mysqli_fetch_assoc($fetchRes);
				$joiningDate=$fetchRow['joining_date'];
				$todayDateQuery="select CURDATE() as today";
				$todayDateQueryRes=mysqli_query($conn,$todayDateQuery);
				if($todayDateQueryRes)
				{
					$todayDateQueryRow=mysqli_fetch_assoc($todayDateQueryRes);
					$today=$todayDateQueryRow['today'];
					$insertHistoryQuery="INSERT INTO `employee_suspended_history`(`id`, `joining_date`, `suspended_date`, `reason`) VALUES ('$id','$joiningDate','$today','$reason'); ";
					$insertHistoryRes=mysqli_query($conn,$insertHistoryQuery);
					if($insertHistoryRes)
					{
						$query="UPDATE `emp` SET suspendedStatus=1 , suspendedDate='$today' where id='$id'; ";
						$res=mysqli_query($conn,$query);
						if($res)
						{
							echo "success";
						}
						else
							echo "failed  ".$query;
					}
					else
						echo "failed to insert into history";
					
				}
				else
					echo "failed";
			}
			else 
				echo "failed";
			break;
	case 'supervisor':
			$id=$_POST['supervisorID'];
			$checkQuery="SELECT * FROM `emp` where emp.sid='$id' and emp.suspendedStatus=0";
			$checkRes=mysqli_query($conn,$checkQuery);
			if($checkRes)
			{
				if(mysqli_num_rows($checkRes)==0)
				{
					//$id=$_POST['employeeID'];
					$reason=$_POST['reason'];
					$fetchData="SELECT * from `supervisor` where id='$id'";
					$fetchRes=mysqli_query($conn,$fetchData);
					if($fetchRes && mysqli_num_rows($fetchRes)==1)
					{
						$fetchRow=mysqli_fetch_assoc($fetchRes);
						//$joiningDate=$fetchRow['joining_date'];
						$todayDateQuery="select CURDATE() as today";
						$todayDateQueryRes=mysqli_query($conn,$todayDateQuery);
						if($todayDateQueryRes)
						{
							$todayDateQueryRow=mysqli_fetch_assoc($todayDateQueryRes);
							$today=$todayDateQueryRow['today'];
							$insertHistoryQuery="INSERT INTO `supervisor_suspended_history`(`id`, `joining_date`, `suspended_date`, `reason`) VALUES ('$id','NULL','$today','$reason'); ";
							$insertHistoryRes=mysqli_query($conn,$insertHistoryQuery);
							if($insertHistoryRes)
							{
								$query="UPDATE `supervisor` SET suspendedStatus=1 , suspendedDate='$today' where id='$id'; ";
								$res=mysqli_query($conn,$query);
								if($res)
								{
									echo "success";
								}
								else
									echo "failed  ".$query;
							}
							else
								echo "failed to insert into history";
							
						}
						else
							echo "failed";
					}
				}
				else
				{
					echo "Please remove/move Employees under the Supervisor";
				}
			}
			else
			{
				echo "FAILED ";
			}
			break;
	case 'executive':
			$id=$_POST['executiveID'];
			$checkQuery="SELECT * FROM `supervisor` where exeid='$id' and suspendedStatus=0";
			$checkRes=mysqli_query($conn,$checkQuery);
			if($checkRes)
			{
				if(mysqli_num_rows($checkRes)==0)
				{
					//$id=$_POST['employeeID'];
					$reason=$_POST['reason'];
					$fetchData="SELECT * from `executive` where id='$id'";
					$fetchRes=mysqli_query($conn,$fetchData);
					if($fetchRes && mysqli_num_rows($fetchRes)==1)
					{
						$fetchRow=mysqli_fetch_assoc($fetchRes);
						//$joiningDate=$fetchRow['joining_date'];
						$todayDateQuery="select CURDATE() as today";
						$todayDateQueryRes=mysqli_query($conn,$todayDateQuery);
						if($todayDateQueryRes)
						{
							$todayDateQueryRow=mysqli_fetch_assoc($todayDateQueryRes);
							$today=$todayDateQueryRow['today'];
							$insertHistoryQuery="INSERT INTO `executive_suspended_history`(`id`, `joining_date`, `suspended_date`, `reason`) VALUES ('$id','NULL','$today','$reason'); ";
							$insertHistoryRes=mysqli_query($conn,$insertHistoryQuery);
							if($insertHistoryRes)
							{
								$query="UPDATE `executive` SET suspendedStatus=1 , suspendedDate='$today' where id='$id'; ";
								$res=mysqli_query($conn,$query);
								if($res)
								{
									echo "success";
								}
								else
									echo "failed  ".$query;
							}
							else
								echo "failed to insert into history";
							
						}
						else
							echo "failed";
					}
				}
				else
				{
					echo "Please remove/move Supervisors under the Executive";
				}
			}
			else
			{
				echo "FAILED ";
			}
			break;;
			
}
//echo "success";
?>