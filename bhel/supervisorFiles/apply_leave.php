<?php
	if(isset($_POST['apply_leave']))
	{
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$eid=$_SESSION['selected_eid'];
		$sid=$_SESSION['id'];
		$typeReason=explode(":",$_POST['type']);
		$type=$typeReason[0];
		$reason=$typeReason[1];
		$applied_date=date("y-m-d");
		$exeid=$_SESSION['exeid'];
		$query="INSERT INTO `leaves`(`E_ID`,`sid`,`exeid`,`from_date`,`to_date`,`applied_date`,`type`,`reason`,`exe_status`,`timeoffice_status`) values('$eid','$sid','$exeid','$from_date','$to_date','$applied_date','$type','$reason',1,0);";
		//echo $query;
		$result=mysqli_query($conn,$query);
	//	echo $query;
		
	}
	else
		echo "FAILED";

?>