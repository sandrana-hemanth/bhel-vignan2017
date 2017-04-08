<?php
	session_start();
	$id="";
	if(isset($_SESSION['login_type']))
	{
		if(!strcmp($_SESSION['login_type'],'executive'))
		{
			$root=$_SERVER['DOCUMENT_ROOT'];
			include_once("$root/bhel/db/login.php");
			$id=$_SESSION['id'];
		}
		else
		{
			session_destroy();
			header("Location: /bhel/index.php");
		}
	}
	else
	{
		session_destroy();
		header("Location: /bhel/index.php");
	}


?>

<html>
	<head>
	
  <script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>

  <script type="text/javascript" src="jquery/jquery.table2excel.js"></script>

  
  

  
    
      
		<script type="text/javascript" src="rooster.js?b=274"></script>
		<script type="text/javascript" src="EmployeeRooster.js?b=4477"></script>
		<script type="text/javascript" src="Employee.js?b=75447"></script>
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="bootstrap-datepicker.css">  

 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="bootstrap-datepicker.js"></script>
 
 <style type="text/css">
    
  </style>

  <title></title>

  
    




<script type='text/javascript'>//<![CDATA[
$(function(){
$(".datepicker").datepicker( {
	    format: "mm-yyyy",
	    viewMode: "months", 
	    minViewMode: "months"
	});
});//]]> 
/*$("#employeeDatepicker").datepicker( {
	    format: "mm-yyyy",
	    viewMode: "months", 
	    minViewMode: "months"
	});
});*/
</script>
	
		<script>
			var data;
			var currentDate;
			var selectTag;
			
			function createData()
			{
				var mon=currentDate.getMonth();
				var yr=currentDate.getFullYear();
				startLoadingEffect();
				$.ajax({
				  url: "/bhel/executive/createRoosterData.php",
				  type: "get", 
				  data:{month:mon,year:yr },
				  success: function(response) {
					 //alert(response);
						releaseLoadingEffect();
					    console.log(response);
						refresh();
				  },
				  error: function(xhr) {
					releaseLoadingEffect();
					alert("got Error "+xhr);
				  }
				});
			}
			
			var rooster,employeeRooster;
			function main()
			{
				//var val=$("#dateinput").val();
			//	var arr=val.split("-");
				var currentDate=new Date();
				rooster=new Rooster(currentDate);
				rooster.sendRoosterRequest();
				var dateHelper=new DateHelper(new Date());
				$("#currentDateHolder").text(""+dateHelper.getMonthName(currentDate.getMonth())+", "+currentDate.getFullYear());
				employeeRooster=new EmployeeRooster(currentDate);
				employeeRooster.sendRoosterRequest();
				var dateHelper=new DateHelper(new Date());
				$("#employeeCurrentDateHolder").text(""+dateHelper.getMonthName(currentDate.getMonth())+", "+currentDate.getFullYear());
				
			}
			function logout()
			{
				window.location.href="logout.php";
			}
			
			function refresh()
			{
					var val=$("#dateinput").val();
					var arr=val.split("-");
					console.log(arr[0]+"   "+arr[1]);
					currentDate=new Date(arr[1]+"-"+arr[0]+"-1");
					//alert(currentDate);
					var dateHelper=new DateHelper(new Date());
					$("#currentDateHolder").text(""+dateHelper.getMonthName(currentDate.getMonth())+", "+currentDate.getFullYear());
					rooster=new Rooster(currentDate);
					rooster.sendRoosterRequest();
			}
			function employeeRefresh(){
					var val=$("#employeeDateinput").val();
					var arr=val.split("-");
					console.log(arr[0]+"   "+arr[1]);
					currentDate=new Date(arr[1]+"-"+arr[0]+"-1");
					//alert(currentDate);
					var dateHelper=new DateHelper(new Date());
					$("#employeeCurrentDateHolder").text(""+dateHelper.getMonthName(currentDate.getMonth())+", "+currentDate.getFullYear());
					employeeRooster=new EmployeeRooster(currentDate);
					employeeRooster.sendRoosterRequest();
			}
			$(document).ready(function(){
				$("#refresh").on("click",function(){
					refresh();
				});
				$("#employeeRefresh").on("click",function(){
					employeeRefresh();
				});
				$("#table2excel").on("click",function(){
					alert("clicked");
					rooster.downloadExcel();
				});
				$("#employeeTable2excel").on("click",function(){
					alert("clicked");
					employeeRooster.downloadExcel();
				});
				$("#roosterButton").on("click",function(){
					$(".volatile").hide();
					$("#roosterContainer").show();
					
				});
				$("#employeeRoosterButton").on("click",function(){
					$(".volatile").hide();
					$("#employeeRoosterContainer").show();
					
				});
				$("#employeeDetailsButton").on("click",function(){
					$(".volatile").hide();
					$("#employeeDetailsContainer").show();
					
				});
				$(".volatile").hide();
				$("#employeeDetailsContainer").show();
				
			});
		/*	$(document).ready(function(){
				
				$("#employeeSearchButton").on("click",function(){
					var empid=$("#employeeSearchBar").val();
					alert(empid);
					$.ajax({
					  url: "/bhel/executive/searchEmployee.php",
					  type: "get", 
					  data:{empID:empid },
					  success: function(response) {
						console.log(response);
						new Employee(response).display();
					  },
					  error: function(xhr) {
						console.log("got Error "+xhr);
					  }
					});
				});
			});*/
			$(document).ready(function(){
				
				$("#employeeSearchButton").on("click",function(){
					var empid=$("#employeeSearchBar").val();
					//alert(empid);
					startLoadingEffect();
					$.ajax({
					  url: "/bhel/executive/searchEmployee.php",
					  type: "get", 
					  data:{empID:empid },
					  success: function(response) {
						console.log(response);
						new Employee(response).display();
						releaseLoadingEffect();
					  },
					  error: function(xhr) {
						alert("got Error "+xhr);
						releaseLoadingEffect();
					  }
					});
				});
				
			});
			function changePassword(){
				var passwordBox=$("#passwordBox");
				passwordBox.show();
				$("#passwordConfirm").unbind('click');
				$("#passwordConfirm").on('click',function(){
					var currentPassword=$("#currentPassword").val();
					var newPassword=$("#newPassword").val();
					var retypePassword=$("#retypePassword").val();
					if(newPassword!=retypePassword)
					{
						//alert("not equal");
						$("#errorPassword").text("Password Mismatch!");
						return;
					}
					else{
							$("#errorPassword").text("");
							$.ajax({
							  url: "/bhel/globalFiles/changePassword.php",
							  type: "post", 
							  data:{currentpassword:currentPassword,password:newPassword,retypepassword:retypePassword},
							  success: function(response) {
								  console.log(response);
								  if(response=="success")
								  {
									  $("#passwordBox").hide();
									  $("#confirmMessage").text("Password Successfully Changed");
									  $("#confirmBox").show();
								  }
								  else{
									  $("#passwordBox").hide();
									  $("#confirmMessage").text("Oops! Failed to Change Password");
									  $("#confirmBox").show();
								  }
							  },
							  error: function(xhr) {
								$("#passwordBox").hide();
								$("#confirmMessage").text("Oops! Failed to Change Password");
								$("#confirmBox").show();
							  }
							});
					}
					
				});
				
				
			}
			$(document).ready(function(){
					console.log("registering");
					$("#changePasswordButton").on('click',changePassword);
				});
			function startLoadingEffect()
			{
				document.getElementById("wrapper").style.opacity="0.3";
				var loadBox=$($(".windows8")[0]);
				loadBox.show();
				console.log("loading");
			}
			function releaseLoadingEffect()
			{
				var loadBox=$($(".windows8")[0]);
				loadBox.hide();
				console.log("released");
				document.getElementById("wrapper").style.opacity="1";
				
			}
		</script>
		
		<style>
		
		.shifts{
			box-shadow: 0px 0px 12px 2px rgba(93, 214, 56, 0.37);
				border-style: groove;
				background-color: #f5f5f5;
				color: #061d1c;
				font-size: 12px;
				font-weight: 700;
		}
		#roosterTable,#employeeRoosterTable{
			table-layout:auto !important;
			text-align:center !important;
		}
		#roosterTable td,#roosterTable th,#employeeRoosterTable td,#employeeRoosterTable th{
			padding:3px;
		}
		#roosterTable th,#employeeRoosterTable th{
			    color: cornsilk;
			background-color: #008cba;
			font-family: sans-serif;
			font-weight: 300;
			font-size: 14px;
			text-align: center !important;
		}
			td{
				border-top: 2px solid black;
				padding:5px;
			}
			.tab-pane{
			  height:80%;
			  overflow-y:scroll;
			  width:100%;
			}
			::-webkit-scrollbar {
				width: 5px;
				height:5px;
				
			}
			 
			::-webkit-scrollbar-track,::-webkit-scrollbar-track:horizontal {
				    width: 5px;
					height: 5px;
					background-color: rgb(87, 133, 232);
					border-radius: 2px;
			}
			 
			::-webkit-scrollbar-thumb ,::-webkit-scrollbar-thumb:horizontal {
				border-radius: 2px;
				width: 5px;
				height: 8px;
				background-color: #002f8e;

			}
			.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
				border-right:1px solid #ddd;
				border-bottom:1px solid #ddd;
				white-space:nowrap;
			}
			select{
				
				width:auto !important;
			}
			.tEmpID{
				background-color: #4087af;
				color: cornsilk;
			}
			.week1{
				background-color:#8a5ca2;
				
			}
			.week2{
				background-color:rgb(136, 161, 202);
			}
			.highlighted select{
				background-color: #51514f;
				color: white;
			}
		</style>
		<style>
			<style>
					ul#menu {
				padding: 0;
					 
			}

			ul#menu li {
				display: inline-block;
				float: right;
			}
			ul#menu li a {
				background-color:008CBA ;
				color: white;
				padding: 10px 20px;
				text-decoration: none;
				border-radius: 4px 4px 0 0;
			}

			ul#menu li a:hover {
				cursor:pointer;
				background-color: darkblue;
			}
			l{
			   color: white; 
			   font-style:italic;
			   font-size:20px;
			}
			body{
				background-image:url("../images/bg2.jpg");
			}
	
		#notificationPanel{
				width:100%;
				min-height: 500px;
				border:3px solid #008CBA;
			}
		#leaveNotificationTable{
				font-size:15px;
				width:100% !important;
			}
		.btn-danger, .btn-success{
			padding:4px;
		}
		.btn-danger{
			margin-left:3px;
		}
		</style>
		
	</head>
	<body onload="main();">
	
	<div class="container-fluid" id="wrapper">
		<div style="background-color:#008CBA; color:white; padding:15px;">  
				<l>Executive ,<?php echo $_SESSION['id'];?></l>
			<ul id="menu" style="display:inline;">
				<li><a id="l_show" onclick="logout();">Logout</a></li>
				
				<li><a id="changePasswordButton">Change Password</a></li>
				<li><a id="employeeDetailsButton">Employee Details</a></li>
				<li><a id="employeeRoosterButton">Employee Rooster</a></li>
				<li><a id="roosterButton">Supervisor Rooster</a></li>
			</ul>
		</div>
			
		
			<select name="shifts" class="shifts" style="display:none;">
			  <option value="A">A</option>
			  <option value="B">B</option>
			  <option value="C">C</option>
			  <option value="H">H</option>
			  <option value="M">M</option>
			  <option value="F">F</option>
			  <option value="T">T</option>
			  <option value="G">G</option>
			  <option value="WO">WO</option>
			</select>
			<div id="roosterContainer" class="volatile">
			
					<div class="container" style="margin-top:20px;">
						<div class="row">
							<div class="col-sm-4">
								<div style="display:inline;" class="input-append date datepicker" id="datepicker" data-date="12-2016" 
									 data-date-format="mm-yyyy">

									 <input id="dateinput" style="height:auto;" type="text" readonly="readonly" name="date" >	  
									 <span class="add-on"><i class="glyphicon glyphicon-th"></i></span>
								</div>
								<button id="refresh" style="display:inline;" style="margin-left:20px;" class="btn btn-primary">Refresh</button>
							</div>
							<div class="col-sm-4" style="text-align:center;">
								<h3 style="display:inline;" id="currentDateHolder"></h3>
							</div>
							<div class="col-sm-4">
								<button id="commit" class="btn btn-primary " style="float:right; font-size:15px;">SAVE</button>
								<button id="table2excel" class="btn btn-primary " style="float:right; font-size:15px; margin-right:5px;">Download as Excel</button>
							</div>
						</div>
					</div>
					<div class="container">
					<div class="panel-body">
						<div class="tab-content" style="box-shadow: 0px 0px 10px 7px rgb(110, 111, 115);">
							<div class="tab-pane active" id="test">
								<table id="roosterTable" class="table">
									<tbody id="roosterTableBody"></tbody>
								</table>
							</div>
						</div>
					</div>
					</div>
			</div>
			<div id="employeeRoosterContainer" class="volatile">
				<div class="container" style="margin-top:20px;">
						<div class="row">
							<div class="col-sm-4">
								<div style="display:inline;" class="input-append date datepicker" id="employeeDatepicker" data-date="12-2016" 
									 data-date-format="mm-yyyy">

									 <input id="employeeDateinput" style="height:auto;" type="text" readonly="readonly" name="date" >	  
									 <span class="add-on"><i class="glyphicon glyphicon-th"></i></span>
								</div>
								<button id="employeeRefresh" style="display:inline;" style="margin-left:20px;" class="btn btn-primary">Refresh</button>
							</div>
							<div class="col-sm-4" style="text-align:center;">
								<h3 style="display:inline;" id="employeeCurrentDateHolder"></h3>
							</div>
							<div class="col-sm-4">
								<button id="employeeCommit" class="btn btn-primary " style="float:right; font-size:15px;">SAVE</button>
								<button id="employeeTable2excel" class="btn btn-primary " style="float:right; font-size:15px; margin-right:5px;">Download as Excel</button>
							</div>
						</div>
					</div>
					<div class="container">
						<div class="panel-body">
							<div class="tab-content" style="box-shadow: 0px 0px 10px 7px rgb(110, 111, 115);">
								<div class="tab-pane active" id="test">
									<table id="employeeRoosterTable" class="table">
										<tbody id="employeeRoosterTableBody"></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
			</div>
			
			<div id="employeeDetailsContainer" class="volatile">
				<div class="row">
					<div class="col-sm-6">
						<div  id="notificationPanel" style="margin-top:10px; width:100%;">
							<table class="table" id="leaveNotificationTable">
								<tr>
									<th>EID</th><th>From</th><th>TO</th><th>Type</th><th>Applied On</th><th>Executive Status</th><th>TimeOffice Status</th>
								</tr>
								<?php
									$exeid=$_SESSION['id'];
									$leavesQuery="SELECT * FROM `leaves` WHERE exeid='$exeid'";
									$leavesResult=mysqli_query($conn,$leavesQuery);
									$status=["NA","PENDING","<span style='color:green; font-weight:600;'>ACCEPTED </span><span class='glyphicon glyphicon-ok-circle' style='color:green;'></span>","<span style='color:red; font-weight:600;'>REJECTED</span> <span class='glyphicon glyphicon-remove-circle' style='color:red;' ></span>","OTHER"];
									if($leavesResult)
									{
										while($row=mysqli_fetch_array($leavesResult,MYSQLI_ASSOC))
										{
											$eid=$row['E_ID'];
											$leave_id=$row['leave_id'];
											$from=$row['from_date'];
											$to=$row['to_date'];
											$applied=$row['applied_date'];
											if($row['exe_status']==1)
											{
												$exeStas="<a class='btn btn-success' href='acceptLeave.php?leave_id=$leave_id&accept=1'>Accept</a><a class='btn btn-danger' href='acceptLeave.php?leave_id=$leave_id'&accept=0>Reject</a>";
											}
											else
												$exeStas=$status[$row['exe_status']];
											$timeofficeStas=$status[$row['timeoffice_status']];
											$type=$row['type'];
											$tr="<tr><td>$eid</td><td>$from</td><td>$to</td><td>$type</td><td>$applied</td><td>$exeStas</td><td>$timeofficeStas</td></tr>";
											echo $tr;
										}
										
									}
								?>
							</table>
						</div>
					</div>
					<div class="col-sm-6">
						<div  id="employeeDetailsPanel">
							<div  class="container" style="padding:10px 100px 10px 10px;">
								<div  align="center">
								<input id="employeeSearchBar" style="border-style:solid; border-width:2px;"> <button type="button" id="employeeSearchButton" class="btn btn-default">
																											Search
																										</button> 
																										
									<div class="container volatile" id="employeeSearchData">
										<span>EMP ID:</span><span id="Searchempid"></span>
										<br>
										<table id="empSearchTable" class="table" style="max-width:600px;">
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
		</div>	
	</div>
	<div class="windows8" style="display:none;">
	<link href="windows8Loading.css" rel="stylesheet">
		<div class="wBall" id="wBall_1">
			<div class="wInnerBall"></div>
		</div>
		<div class="wBall" id="wBall_2">
			<div class="wInnerBall"></div>
		</div>
		<div class="wBall" id="wBall_3">
			<div class="wInnerBall"></div>
		</div>
		<div class="wBall" id="wBall_4">
			<div class="wInnerBall"></div>
		</div>
		<div class="wBall" id="wBall_5">
			<div class="wInnerBall"></div>
		</div>
	</div>
	<div id="alertBox" class="alert-success" style="display:none; position:absolute; top:40%; left:40%; border:2px solid black; width:300px; height:200px;">
		<h4 style="margin-top:20%; text-align:center;" id="alertMessage">This is Message</h4>
		<button id="alertBoxYes" class="btn btn-success" onclick="$('#alertBox').hide(); document.getElementById('wrapper').style.opacity='1';" style="margin-top:60px;float:right; width:50px; margin-right:10px;">YES</button>
		<button id="alertBoxNo" class="btn btn-danger" onclick="$('#alertBox').hide(); document.getElementById('wrapper').style.opacity='1';" style="margin-top:60px; margin-left:10px;width:50px;">NO</button>
	</div>
	<div id="confirmBox" class="alert-success" style="display:none; position:absolute; top:40%; left:40%; border:2px solid black; width:300px; height:200px;">
		<h4 style="margin-top:20%; text-align:center;" id="confirmMessage">This is Message</h4>
		<button id="confirmBoxOK" class="btn btn-success" onclick="$('#confirmBox').hide(); document.getElementById('wrapper').style.opacity='1';" style="margin-top:60px;float:right; width:50px; margin-right:10px;">OKAY</button>
	</div>
	<div id="passwordBox" class="alert-success container" style="display:none; position:absolute; top:40%; left:32%; border:2px solid black; width:auto; height:200px;">
		
		<div style="margin:10px;">
			<div class="row" style="margin:10px;">
				<div class="col-sm-6">
					<span>Enter Current Password:</span>
				</div>
				<div class="col-sm-6">
					<input name="currentPassword" type="password" id="currentPassword">
				</div>
			</div>
			<div class="row" style="margin:10px;">
				<div class="col-sm-6">
					<span>Enter New Password:</span>
				</div>
				<div class="col-sm-6">
					<input name="password" type="password" id="newPassword">
				</div>
			</div>
			<div class="row" style="margin:10px;">
				<div class="col-sm-6">
					<span>Re-type Password:</span>
				</div>
				<div class="col-sm-6">
					<input name="retypePassword" type="password" id="retypePassword">
				</div>
				<hr>
				<span class="alert-danger" id="errorPassword">&nbsp;</span>
			</div>
			
			<button  class="btn btn-success" id="passwordConfirm"  style="margin-top:0px;float:right;  margin-right:10px;">CHANGE</button>
			<button  class="btn btn-danger" id="passwordCancel" onclick="$('#passwordBox').hide(); document.getElementById('wrapper').style.opacity='1';" style="margin-top:0px; margin-left:10px;">CANCEL</button>
		</div>
</div>
	</body>

</html>














