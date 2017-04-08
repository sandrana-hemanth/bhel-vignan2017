<?php
	session_start();
	//echo "HERE";
	if(isset($_SESSION['login_type']))
	{
		if(!strcmp($_SESSION['login_type'],'executive'))
		{
			//echo "HERE..";
			$root=$_SERVER['DOCUMENT_ROOT'];
			include_once("$root/bhel/db/login.php");
			$empID=$_GET['empID'];
			$exeid=$_SESSION['id'];
			$searchQuery="SELECT * from leave_count l,executive exe,supervisor s,emp e where l.E_ID='$empID' and e.id='$empID' and e.suspendedStatus=0 and e.sid=s.id and s.exeid='$exeid' and exe.id='$exeid';";
			$searchResult=mysqli_query($conn,$searchQuery);
			if($searchResult)
			{
				if(mysqli_num_rows($searchResult)==1)
				{
					$_SESSION['selected_eid']=$empID;
					$row=mysqli_fetch_array($searchResult,MYSQLI_ASSOC);
					print json_encode($row);
				}
				else
				{
					echo "\n not 1 \n n= ".$searchQuery;
					$_SESSION['selected_eid']='NA';
				}
			}
			else{
				echo "\nquery failed $searchQuery";
				print 'null';
				$_SESSION['selected_eid']='NA';
			}
		}	
		else
		{
			echo "\n invalid USER \n ".$_SESSION['login_type']."\n";
			print 'null';
			$_SESSION['selected_eid']='NA';
		}
	}
	else
	{
		echo "LOGIN REQUIRED";
		$_SESSION['selected_eid']='NA';
		print 'null';
		exit();
	}
		
?>