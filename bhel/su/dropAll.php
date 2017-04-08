<?php
include_once('../db/login.php');
mysqli_query($conn,"SET FOREIGN_KEY_CHECKS=0;");
$drop=array('drop table emp;','drop table biometric;','drop table leave_count;','drop table supervisor;','drop table shifts;','drop table leaves;','drop table rooster;','drop table executive;','drop table supervisor_rooster;','drop table admin;','drop table timeoffice;');
$len=count($drop);
for($i=0;$i<$len;$i++){
	if(mysqli_query($conn,$drop[$i])){
		echo '<br>drop SUCCESS';
	}
	else
		echo '<br>drop FAILED';
}	
mysqli_query($conn,"SET FOREIGN_KEY_CHECKS=1;");
?>