<?php
	session_start();
	$root=$_SERVER['DOCUMENT_ROOT'];
	if(isset($_SESSION['login_type']))
	{
		$login_type=$_SESSION['login_type'];
		if(strcmp($login_type,"supervisor")==0)
			header("Location:/bhel/supervisorFiles/supervisor.php");
		else if(strcmp($login_type,"timeoffice")==0)
			header("Location:/bhel/timeoffice/timeoffice.php");
		else if(strcmp($login_type,"executive")==0)
			header("Location:/bhel/executive/executive.php");
		else if(strcmp($login_type,"admin")==0)
			header("Location:/bhel/admin/admin.php");
		exit();
	}
	else if(isset($_POST['login']))
	{
		if(!strcmp($_POST['login_type'],"supervisor"))
		{
			include_once("db/login.php");
			$userid=$_POST['username'];
			$pass=$_POST['password'];
			$query="SELECT * from `supervisor` where `id`='$userid' and `password`='$pass' and suspendedStatus=0";
			$result=mysqli_query($conn,$query);
			if($result)
			{
				if(mysqli_num_rows($result)==1)
				{
					$arr=mysqli_fetch_array($result,MYSQLI_ASSOC);
					$_SESSION['login_type']='supervisor';
					$_SESSION['id']=$userid;
					$_SESSION['exeid']=$arr['exeid'];
					header("Location:/bhel/supervisorFiles/supervisor.php");
				}
			}
		}
		else if(!strcmp($_POST['login_type'],"admin")){
			include_once("db/login.php");
			$userid=$_POST['username'];
			$pass=$_POST['password'];
			$query="SELECT * from `admin` where `id`='$userid' and `password`='$pass'";
			$result=mysqli_query($conn,$query);
			if($result)
			{
				if(mysqli_num_rows($result)==1)
				{
					$arr=mysqli_fetch_array($result,MYSQLI_ASSOC);
					$_SESSION['login_type']='admin';
					$_SESSION['id']=$userid;
					header("Location:/bhel/admin/admin.php");
				}
			}
		}
		else if(!strcmp($_POST['login_type'],"timeoffice"))
		{
			include_once("db/login.php");
			$userid=$_POST['username'];
			$pass=$_POST['password'];
			$query="SELECT * from `timeoffice` where `id`='$userid' and `password`='$pass'";
			$result=mysqli_query($conn,$query);
			echo 'huhu';
			if($result)
			{
				if(mysqli_num_rows($result)==1)
				{
					$_SESSION['login_type']='timeoffice';
					$_SESSION['id']=$userid;
					header("Location:/bhel/timeoffice/timeoffice.php");
				}
				
			}
			
		}
		else if(!strcmp($_POST['login_type'],"executive")){
			include_once("db/login.php");
			$userid=$_POST['username'];
			$pass=$_POST['password'];
			$query="SELECT * from `executive` where `id`='$userid' and `password`='$pass' and suspendedStatus=0";
			$result=mysqli_query($conn,$query);
			if($result)
			{
				if(mysqli_num_rows($result)==1)
				{
					$_SESSION['login_type']='executive';
					$_SESSION['id']=$userid;
					header("Location:/bhel/executive/executive.php");
				}
				
			}
			
		}
	}
	
?>
<!DOCTYPE html>
<html>
<head>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js">
</script>
<link rel="stylesheet" type="text/css" href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/css/bootstrap-combined.min.css">
<script type="text/javascript" src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/js/bootstrap.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	 $("#a_show").click(function(){
		$("#admin_login").slideToggle("slow");
		$("#s_show").hide();
		$("#t_show").hide();
		$("#e_show").hide();
	});
	$("#s_show").click(function(){
		$("#supervisor_login").slideToggle("slow");
		$("#a_show").hide();
		$("#t_show").hide();
		$("#e_show").hide();
	});
	$("#t_show").click(function(){
		$("#timeoffice_login").slideToggle("slow");
		$("#a_show").hide();
		$("#s_show").hide();
		$("#e_show").hide();
	});
	$("#e_show").click(function(){
		$("#executive_login").slideToggle("slow");
		$("#a_show").hide();
		$("#s_show").hide();
		$("#t_show").hide();
	});
	$("#a_back").click(function(){
		$("#admin_login").slideToggle("slow");
		$("#a_show").show();
		$("#s_show").show();
		$("#t_show").show();
		$("#e_show").show();
	});
	$("#s_back").click(function(){
		$("#supervisor_login").slideToggle("slow");
		$("#a_show").show();
		$("#s_show").show();
		$("#t_show").show();
		$("#e_show").show();
	});
	$("#t_back").click(function(){
		$("#timeoffice_login").slideToggle("slow");
		$("#a_show").show();
		$("#s_show").show();
		$("#t_show").show();
		$("#e_show").show();
	});
	$("#e_back").click(function(){
		$("#executive_login").slideToggle("slow");
		$("#a_show").show();
		$("#s_show").show();
		$("#t_show").show();
		$("#e_show").show();
	});
	
});

</script>
<meta charset="ISO-8859-1">
<title>BHEL</title>
<style>
	h3{
			text-align:center;
		
			font-style:italic;
			font-size:30px;
		}
		h1{
			text-align:center;
			font-size:60px;
			font-style:italic;
		}
	form.f1{
		align:center;
	}
	body{
		background-image:url("images/bg2.jpg");
	}
	

</style>
</head>
<body>
<h1><img src="images/2.jpg" height="100" width="100">
Bharat Heavy Electricals Limited
<br>
</h1>
<center>
<h2>

Heavy Plates and Vessels Plant, Visakhapatnam
</h2>
</center>
<h3><b>WELCOME</b></h3>
<br><br><br>
<center>
<form id="f1" action="" method="POST">
<input type="button" value="Admin login" name="a" id="a_show" class="btn btn-primary">
<input type="button"value="Supervisor Login" name="s" id="s_show" class="btn btn-primary">
<input type="button" value="Time Office Login" name="t" id="t_show" class="btn btn-primary">
<input type="button" value="Executive Login" name="t" id="e_show" class="btn btn-primary">
</form>
<br><br><br>
<div id="admin_login" style="display:none">
<form  action="index.php" method="POST">
	<input type="text" placeholder="Enter User Name" name="username" id="admin_uname" class="text"><br><br>
	<input type="password" placeholder="Enter Password " name="password" id="admin_pwd" class="text"><br>
	<input type="text" name="login_type" value="admin" style="display:none;">
	<br><br><br>
	<input type="submit" value="Login" name="login" id="a_submit" class="btn btn-primary">
	<input type="button" value="Return to Previous" name="Return" id="a_back" class="btn btn-primary">
</form>
</div>
<div id="supervisor_login" style="display:none">
	<form  action="index.php" method="POST">
	<input type="text" placeholder="Enter User Name" name="username" id="supervisor_uname" class="text"><br><br>
	<input type="password" placeholder="Enter Password " name="password" id="supervisor_pwd" class="text"><br>
	<input type="text" name="login_type" value="supervisor" style="display:none;">
	<br><br><br>
	<input type="submit" value="Login" name="login" id="s_submit" class="btn btn-primary">
	<input type="button" value="Return to Previous" name="Return" id="s_back" class="btn btn-primary">
	</form>
</div>
<div id="timeoffice_login" style="display:none">
	<form  action="index.php" method="POST">
	<input type="text" placeholder="Enter User Name" name="username" id="timeoffice_uname" class="text"><br><br>
	<input type="password" placeholder="Enter Password " name="password" id="timeoffice_pwd" class="text"><br>
	<input type="text" name="login_type" value="timeoffice" style="display:none;">
	<br><br><br>
	<input type="submit" value="Login" name="login" id="t_submit" class="btn btn-primary">
	<input type="button" value="Return to Previous" name="Return" id="t_back" class=" btn btn-primary">
</form>
</div>
<div id="executive_login" style="display:none">
	<form  action="index.php" method="POST">
	<input type="text" placeholder="Enter User Name" name="username" id="executive_uname" class="text"><br><br>
	<input type="password" placeholder="Enter Password " name="password" id="executive_pwd" class="text"><br>
	<input type="text" name="login_type" value="executive" style="display:none;">
	<br><br><br>
	<input type="submit" value="Login" name="login" id="t_submit" class="btn btn-primary">
	<input type="button" value="Return to Previous" name="Return" id="e_back" class=" btn btn-primary">
</form>
</div>

</center>
</body>
</html> 