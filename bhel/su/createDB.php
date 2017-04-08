<?php
include_once('../db/login.php');
mysqli_query($conn,"SET FOREIGN_KEY_CHECKS=0;");
$admin='create table admin(
			id varchar(255),
			password varchar(255)
		);';
if(mysqli_query($conn,$admin)){
		echo '<br>admin table created';
	}
	else
		echo '<br>admin tab FAILED<br>'.$admin.'<br>';

$emp_tab='create table emp(
		id varchar(255) PRIMARY KEY,
		sid varchar(255),
		firstname varchar(255),
		lastname varchar(255),
		gender varchar(6),
		dob date,
		joining_date date,
		INDEX (sid),
		FOREIGN KEY (sid) REFERENCES supervisor(id)
	)';
	if(mysqli_query($conn,$emp_tab)){
		echo '<br>emp table created';
	}
	else
		echo '<br>emp tab FAILED<br>'.$emp_tab.'<br>';
	
$supervsr_tab='create table supervisor(
		id varchar(255) PRIMARY KEY,
		exeid varchar(255),
		pattern varchar(255),
		firstname varchar(255),
		lastname varchar(255),
		password varchar(255),
		INDEX (exeid),
		FOREIGN KEY (exeid) REFERENCES executive(id)
	)';
	if(mysqli_query($conn,$supervsr_tab)){
		echo '<br>superVisor table created';
	}
	else
		echo '<br>superVisor tab FAILED<br>'.$supervsr_tab.'<br>';
	
	
$biometric='create table biometric(
		E_ID varchar(255) ,
		in_time DATETIME,
		out_time DATETIME,
		INDEX (E_ID),
		FOREIGN KEY (E_ID) REFERENCES emp(id)
	)';
	if(mysqli_query($conn,$biometric)){
		echo '<br>biometric table created';
	}
	else
		echo '<br>biometric tab FAILED';
	
	
$shifts='create table shifts(
		shift varchar(100),
		s_from TIME,
		s_to TIME
	)';
	if(mysqli_query($conn,$shifts)){
		echo '<br>shifts table created';
	}
	else
		echo '<br>shifts tab FAILED';
	
$leaves='create table leaves(
		leave_id int PRIMARY KEY,
		E_ID varchar(255) ,
		sid varchar(255) ,
		exeid varchar(255) ,
		type varchar(100),
		reason varchar(255),
		applied_date DATE,
		from_date DATE,
		to_date DATE,
		exe_status int,
		timeoffice_status int,
		INDEX (E_ID),
		FOREIGN KEY (E_ID) REFERENCES emp(id),
		INDEX (sid),
		FOREIGN KEY (sid) REFERENCES supervisor(id),
		INDEX (exeid),
		FOREIGN KEY (exeid) REFERENCES executive(id)
	)';
	if(mysqli_query($conn,$leaves)){
		echo '<br>leaves table created';
	}
	else
		echo '<br>leaves tab FAILED<br>'.$leaves.'<br>';
$emp_leave_count='create table leave_count(
		E_ID varchar(255) ,
		CL float,
		EL float,
		HPL float,
		SL float,
		EOL float,
		UAB float,
		OH float,
		INDEX (E_ID),
		FOREIGN KEY (E_ID) REFERENCES emp(id)
	)';
	if(mysqli_query($conn,$emp_leave_count)){
		echo '<br>leave_count table created';
	}
	else
		echo '<br>leave_count tab FAILED<br>'.$emp_leave_count.'<br>';
	
$rooster='create table rooster(
			eid varchar(255) ,
			sid varchar(255) ,
			mon varchar(16),
			yr varchar(6),
			INDEX (eid),
			FOREIGN KEY (eid) REFERENCES emp(id),
			INDEX (sid),
			FOREIGN KEY (sid) REFERENCES supervisor(id)';
	$i=1;
	for($i=1;$i<=31;$i++)
	{
		$rooster=$rooster.',`'.$i."` varchar(5) DEFAULT 'A'";
	}
	$rooster=$rooster.', CONSTRAINT uniquekey UNIQUE (eid,mon,yr));';
	if(mysqli_query($conn,$rooster)){
		echo '<br>rooster table created';
	}
	else
		echo '<br>rooster tab FAILED<br>'.$rooster.'<br>';
	
	
	
$supervisor_rooster='create table supervisor_rooster(
			exeid varchar(255) ,
			sid varchar(255) ,
			mon varchar(16),
			yr varchar(6),
			INDEX (exeid),
			FOREIGN KEY (exeid) REFERENCES executive(id),
			INDEX (sid),
			FOREIGN KEY (sid) REFERENCES supervisor(id)';
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
		echo '<br>supervisor_rooster tab FAILED<br>'.$supervisor_rooster.'<br>';	
	
	
	
	
$executive='create table executive(
				id VARCHAR(255) PRIMARY KEY,
				firstname VARCHAR(255),
				lastname VARCHAR(255),
				password VARCHAR(255)
			)';
	if(mysqli_query($conn,$executive))
	{
		echo '<br>Executive table created';
		
	}
	else
		echo '<br>Executive tab FAILED<br>'.$executive.'<br>';

$timeoffice='create table timeoffice(
		id varchar(255),
		firstname varchar(255),
		lastname varchar(255),
		password varchar(255)
	)';
	if(mysqli_query($conn,$timeoffice))
	{
		echo '<br>timeoffice table created';
		
	}
	else
		echo '<br>timeoffice tab FAILED<br>'.$timeoffice.'<br>';
mysqli_query($conn,"SET FOREIGN_KEY_CHECKS=1;");
?>