<?php
session_start();
include_once("DateUtil.php");
$root=$_SERVER['DOCUMENT_ROOT'];
	if(!isset($_SESSION['login_type']))
	{
		echo 'INVALID ACCESS';
		exit();
	}
	$month=($_GET['month'])+1;
	$year=$_GET['year'];
	$exeid=$_SESSION['id'];
	include_once("$root/bhel/db/login.php");
	$empListQuery="SELECT `id`,`pattern` from supervisor WHERE `exeid`='$exeid' and suspendedStatus=0 and id NOT IN (SELECT sid from supervisor_rooster where mon='$month' and yr='$year')";
	$supervisorList=array();
	$resultListQuery=mysqli_query($conn,$empListQuery);
	if($resultListQuery)
	{
		$insertQuery="INSERT INTO `supervisor_rooster`(`exeid`,`sid`,`mon`,`yr`) VALUES";
		$empRoosterInsertQuery="INSERT INTO `rooster` (`eid`,`sid`,`mon`,`yr`) VALUES";
		$flag=0;
		$empFlag=0;
		$count=0;
		while($r=mysqli_fetch_array($resultListQuery,MYSQLI_ASSOC))
		{
			$sid=$r['id'];
			$supervisorList[]=$r;
			if($flag)
				$insertQuery=$insertQuery.",";
			$insertQuery=$insertQuery." ('$exeid','$sid','$month','$year')";
			$empUnderSupervisorQuery="SELECT `id` from `emp` WHERE `sid`='$sid' and suspendedStatus=0 and id NOT IN(SELECT eid from rooster where mon='$month' and yr='$year')";
			$empUnderSupervisor=mysqli_query($conn,$empUnderSupervisorQuery);
			while($q=mysqli_fetch_array($empUnderSupervisor,MYSQLI_ASSOC))
			{
				$count++;
				echo "\nincrementing $count\n";
				$eid=$q['id'];
				if($empFlag)
					$empRoosterInsertQuery=$empRoosterInsertQuery.",";
				$empRoosterInsertQuery=$empRoosterInsertQuery."('$eid','$sid','$month','$year')";
				$empFlag=1;
			}
			$flag=1;
			
		}
		$isTranscation=true;
		if($count==0)
			$isTranscation=false;
		echo "\n count $count\n";
		$insertQuery.=";";
		$empRoosterInsertQuery.=";";
		if(startTranscation($insertQuery,$empRoosterInsertQuery,$conn,$isTranscation))
			autoFillTable($exeid,$month,$year,$supervisorList,$conn);
		else
			echo "\n$insertQuery  ::\n$empRoosterInsertQuery   ::\n";
	}
	else{
		echo 'something went wrong.';
	}
	function startTranscation($q1,$q2,$conn,$isTranscation)
	{
		echo 'starting transcation '.$q1;
		if($isTranscation){
			mysqli_query($conn,"BEGIN;");
			$r1=mysqli_query($conn,$q1);
			$r2=mysqli_query($conn,$q2);
			echo "\nQuery::\n$q1 \n $q2";
			if($r1 AND $r2)
				return mysqli_query($conn,"COMMIT;");
			else{
				if($r1)
					echo "\nq1 success\n";
				if($r2)
					echo "\nq2 success\n";
				echo "\nRolling Back\n";
					return mysqli_query($conn,"ROLLBACK;");
			}
				
		}
		else{
			$r1=mysqli_query($conn,$q1);
			return $r1;
		}
		
	}
	function autoFillTable($exeid,$month,$year,$supervisorList,$conn)
	{
			for($l=0;$l<count($supervisorList);$l++)
			{
				$supervisor=$supervisorList[$l];
				echo "<br> NOWWW..iam here.<br>";
				$supervisorID=$supervisor['id'];
				$pattern=explode("," , $supervisor['pattern']);
				$dateUtil=new DateUtil('1','1','1');
				$prevDate=$dateUtil->getPreviousMonth($month,$year);
				$lastDay = cal_days_in_month(CAL_GREGORIAN, $prevDate[0],$prevDate[1]);
				$isSunday=new DateUtil($lastDay,$prevDate[0],$prevDate[1]);
				if($isSunday->getDay()==7)
					$lastDay--;
				$prevMonth=$prevDate[0];
				$prevYear=$prevDate[1];
				$findQuery="SELECT `$lastDay` from supervisor_rooster where sid='$supervisorID' and mon='$prevMonth' and yr='$prevYear' ";
				$find=mysqli_query($conn,$findQuery);
				if($find)
				{
					$lastShift;
					if(mysqli_num_rows($find)==1)
					{
						$row=mysqli_fetch_array($find,MYSQLI_ASSOC);
						$lastShift=$row["$lastDay"];
						echo 'LAST SHIFT::: '.$lastShift.'  '.$findQuery.' <br>';
					}
					else
						$lastShift="NA";
					updateSupervisor($lastShift,$pattern,$month,$year,$supervisorID,$conn);
				}
				else
				{
					echo 'hgsfasgf\sdf';
				}
			}
	}
	function updateSupervisor($lastShift,$pattern,$month,$year,$supervisorID,$conn) //only called in autoFillTable function..
	{
		echo "updating for $supervisorID <br>";
		$updateQuery="UPDATE supervisor_rooster s INNER JOIN rooster r ON (s.sid=r.sid ) SET ";
		$j=1;
		$numberOfDays=cal_days_in_month(CAL_GREGORIAN, $month,$year);
		$presentShift=$lastShift;
		$index=0;
		$patternLength=count($pattern);
		$flag=false;
		for($i=0;$i<$patternLength;$i++)
		{
			if(!strcmp($lastShift,$pattern[$i]))
			{
				$index=$i;
				$flag=true;
				break;
			}
		}
		for($j=1;$j<=$numberOfDays;$j++)
		{
			$currentDate=new DateUtil($j,$month,$year);
			$day=$currentDate->getDay();
			if($day==1)
			{
				if(!$flag)
				{
					//do not increment index as we dont have prev day shif.
					$flag=true;
				}
				else{
					$index++;
					if($index==$patternLength)
						$index=0;
				}
			}
			else if($day!=7)
				$flag=true;
			$val=$pattern[$index];
			if($day==7)
				$val='WO';
			if($j-1)
				$updateQuery=$updateQuery.",";
			$updateQuery=$updateQuery."s.`$j`='$val' , r.`$j`='$val' ";
		}
		$updateQuery=$updateQuery."WHERE s.`mon`='$month' AND s.`yr`='$year' AND s.`sid`='$supervisorID' and r.`mon`='$month' AND r.`yr`='$year'";
		$res=mysqli_query($conn,$updateQuery);
		echo "<br>flag 2 <br>";
		if($res)
		{
			if(mysqli_affected_rows($conn)==0)
			{
				//if there are no employees under supervisor , no rooster is created for supervisor. 
				//such that we now only fill supervisor without JOIN
				$flag=false;
				$index=0;
				for($i=0;$i<$patternLength;$i++)
				{
					if(!strcmp($lastShift,$pattern[$i]))
					{
						$index=$i;
						$flag=true;
						break;
					}
				}
				$updateQuery="UPDATE supervisor_rooster s SET ";
				for($j=1;$j<=$numberOfDays;$j++)
				{
					$currentDate=new DateUtil($j,$month,$year);
					$day=$currentDate->getDay();
					if($day==1)
					{
						if(!$flag)
						{
							//do not increment index as we dont have prev day shif.
							$flag=true;
						}
						else{
							$index++;
							if($index==$patternLength)
								$index=0;
						}
					}
					else if($day!=7)
						$flag=true;
					$val=$pattern[$index];
					if($day==7)
						$val='WO';
					if($j-1)
						$updateQuery=$updateQuery.",";
					$updateQuery=$updateQuery."s.`$j`='$val'";
				}
				$updateQuery=$updateQuery."WHERE s.`mon`='$month' AND s.`yr`='$year' AND s.`sid`='$supervisorID'";
				$res=mysqli_query($conn,$updateQuery);
				if($res)
				{
					echo "success <br>$updateQuery</br>";
				}
				else
				{
					echo "failed <br>$updateQuery</br>";
				}
			}
			else{
				echo "<br>more than 1 row..join worked.<br>$updateQuery<br>";
			}
		}
		else
		{
			echo "\nfailed completely\n$updateQuery";
		}
	}
?>
