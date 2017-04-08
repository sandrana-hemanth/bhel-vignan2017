<?php
	session_start();
	if(isset($_SESSION['login_type']))
	{
		if(!strcmp("supervisor",$_SESSION['login_type']))
		{
			$root=$_SERVER['DOCUMENT_ROOT'];
			include_once("$root/bhel/db/login.php");
			$empID=$_GET['empID'];
			$sid=$_SESSION['id'];
			$searchQuery="SELECT * from leave_count l, emp e where l.E_ID='$empID' and e.sid='$sid' and e.id='$empID'";
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
			echo "\n invalid USER";
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