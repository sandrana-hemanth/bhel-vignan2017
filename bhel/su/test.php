
<?php
include_once('../db/login.php');
$supervisor_rooster='create table supervisor_rooster(
			exeid varchar(255),
			sid varchar(255),
			mon varchar(16),
			yr varchar(6)';
	$i=1;
	for($i=1;$i<=31;$i++)
	{
		$supervisor_rooster=$supervisor_rooster.',`'.$i."` varchar(5) DEFAULT 'A'";
	}
	$supervisor_rooster=$supervisor_rooster.', CONSTRAINT uniquekey UNIQUE (sid,mon,yr));';
	if(mysqli_query($conn,$supervisor_rooster)){
		echo '<br>supervisor_rooster table created';
	}
	else
		echo '<br>supervisor_rooster tab FAILED';	
	echo '<br>'.$supervisor_rooster;
?>