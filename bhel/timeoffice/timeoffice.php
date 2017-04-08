<?php
	session_start();
	if(isset($_SESSION['login_type']))
	{
		if(!strcmp($_SESSION['login_type'],"timeoffice"))
		{
			$root=$_SERVER['DOCUMENT_ROOT'];
			include_once("$root/bhel/db/login.php");
		}
		else
		{
			include_once("logout.php");
			exit();
		}
	}

?>



<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script src="Leave.js?a=273"></script>
		<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
		 <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
         <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
		<script type="text/javascript" src="jquery/jquery.table2excel.js"></script>
</script>

<script type="text/javascript" src="downloadRooster.js?a=741245"></script>
   
  <link rel="stylesheet" type="text/css" href="styles.css">


        <style>
            select{
				   background: white;
				   border: none;
				   font-size: 14px;
				   height: 38px;
				   padding: 5px; 
				   width: 268px;
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
		.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th
		{
			border-right: 1px solid #ddd;
			border-bottom: 1px solid #ddd;
		}
        </style>
		
		<script>
		$(document).ready(function(){
			$("#DLeave").on('click',function(){
				var sDate=$("#DStartDate").val();
				var eDate=$("#DEndDate").val();
				alert(sDate);
				$.ajax({
					  url: "/bhel/timeoffice/downloadLeaveExcel.php",
					  type: "post", 
					  data:{startDate:sDate,endDate:eDate },
					  success: function(response) {
						 console.log(response);
						download(response);
						
					  },
					  error: function(xhr) {
						alert("got Error "+xhr);
					  }
					});
				
			});
		});
		function download(response){
				$(response).table2excel({
				exclude: ".noExl",
				name: "LeaveFile",
				filename: "leaves",
				fileext: ".xls",
				exclude_img: true,
				exclude_links: true,
				exclude_inputs: true
			});
		}
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
				$(document).ready(function(){
					
					$("#downloadRooster").on('click',function(){
						$("#downloadRoosterBox").show();
					});
				});
			$(document).ready(function(){
				///initializing rooster. 
				new DownloadRooster().initialize();
			});
		</script>
    </head>
    <body>

        <div style="background-color:#008CBA;  padding:28px;">  
          <l>Time Officer,101</l>
        <ul id="menu">
            
            <li><a href="/bhel/logout.php">Logout</a></li>
			<li><a id="changePasswordButton">Change Password</a></li>
			<li><a id="downloadRooster">Download Rooster</a></li>
        </ul>
         </div>
		 <div class="row">
		 <div class="col-sm-6" style="margin-top:10px;">
			<input type="date" id="DStartDate">
			<input type="date" id="DEndDate">
			<button class="btn btn-primary" id="DLeave">Download</button>
		 </div>
			<div class="col-sm-6">
				<div class="container" style="text-align:center; margin-top:10px;" id="search_container" >
					
					<div class="input-append date">

					 <input id="empid" style="height:auto;" type="text" placeholder="Emp ID" >	  
					 <button id="search" style="display:inline;" style="margin-left:20px;" class="btn btn-primary">SEARCH</button>
					</div>
					
				</div>	
			</div>
		</div>
        <div class="datePicker input-append date" style="display:none; ">           
        </div>
		
              
			  <input type="text" class="dateinp" placeholder="choose dates" style="margin:0px; height:auto; display:none;" > 
              <button class="datebtn btn btn-primary" style="display:none" >SUBMIT</button>
	   
	   <div class="row" style="margin-top:20px;">
			<div class="col-sm-6">
				<div  id="notificationPanel" style="margin-top:10px; width:100%;">
						<table class="table" id="leaveNotificationTable">
							<tr>
								<th>EID</th><th>From</th><th>TO</th><th>Type</th><th>Applied On</th><th>Executive Status</th><th>TimeOffice Status</th>
							</tr>
							<?php
								$sid=$_SESSION['id'];
								$leavesQuery="SELECT * FROM `leaves`";
								$leavesResult=mysqli_query($conn,$leavesQuery);
								$status=["NA","PENDING","<span style='color:green; font-weight:600;'>ACCEPTED </span><span class='glyphicon glyphicon-ok-circle' style='color:green;'></span>","<span style='color:red; font-weight:600;'>REJECTED</span> <span class='glyphicon glyphicon-remove-circle' style='color:red;' ></span>","OTHER"];
								if($leavesResult)
								{
									while($row=mysqli_fetch_array($leavesResult,MYSQLI_ASSOC))
									{
										$leave_id=$row['leave_id'];
										$eid=$row['E_ID'];
										$from=$row['from_date'];
										$to=$row['to_date'];
										$applied=$row['applied_date'];
										$exeStas=$status[$row['exe_status']];
										$timeofficeStas=$status[$row['timeoffice_status']];
										if($row['timeoffice_status']==1)
										{
											$timeofficeStas="<a class='btn btn-success' href='acceptLeave.php?leave_id=$leave_id&accept=1'>Accept</a><a class='btn btn-danger' href='acceptLeave.php?leave_id=$leave_id'&accept=0>Reject</a>";
										}
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
				<div id="leaveDiv" class="container">
				</div>
			</div>
	   </div>
	   
	   
        
        <div id="confirmBox" class="alert-success container" style="display:none !important; width: 30%;position: absolute;top: 40%;left: 40%;border: 2px solid black;/* width: 300px; */height: 200px;display: block;">
		<h4 style="margin-top:20%; text-align:center;" id="confirmMessage">This is Message</h4>
		<button id="confirmBoxOK" class="btn btn-success" onclick="$('#confirmBox').hide(); document.getElementById('wrapper').style.opacity='1';" style="margin-top: 40px;float:right;/* width:50px; */margin-right:10px;">OKAY</button>
	</div>
	<div id="downloadRoosterBox" class="alert-success container" style="display:none !important; width: 30%;position: absolute;top: 40%;left: 40%;border: 2px solid black;/* width: 300px; */height: 200px;display: block;">
			<div style="margin-top:5px;">
				<label>From: </label>
			<input id="roosterFrom" name="from" type="date" /><br>
			<label>To:</label>
			<input id="roosterTo" name="to" type="date" />
			</div>
			<a class="btn btn-primary" id="getRoosterButton" >Download Rooster</a>
			<button onclick="$('#downloadRoosterBox').hide()">Cancel</button>
	</div>
	<div id="passwordBox" class="alert-success container" style="display:none; position:absolute; top:40%; left:32%; border:2px solid black; width:auto; height:200px;">
		<div style="margin:10px;">
			<div class="row" style="margin:10px;">
				<div class="col-sm-6">
					<span>Enter Current Password:</span>
				</div>
				<div class="col-sm-6">
					<input type="password" id="currentPassword">
				</div>
			</div>
			<div class="row" style="margin:10px;">
				<div class="col-sm-6">
					<span>Enter New Password:</span>
				</div>
				<div class="col-sm-6">
					<input type="password" id="newPassword">
				</div>
			</div>
			<div class="row" style="margin:10px;">
				<div class="col-sm-6">
					<span>Re-type Password:</span>
				</div>
				<div class="col-sm-6">
					<input type="password" id="retypePassword">
				</div>
				<hr>
				<span class="alert-danger" id="errorPassword">&nbsp;</span>
			</div>
			
			<button  class="btn btn-success" id="passwordConfirm"  style="margin-top:0px;float:right;  margin-right:10px;">CHANGE</button>
			<button  class="btn btn-danger" id="passwordCancel" onclick="$('#passwordBox').hide(); document.getElementById('wrapper').style.opacity='1';" style="margin-top:0px; margin-left:10px;">CANCEL</button>
		</div>
	</div>

    </body>
	<script>
		$(function(){
			$("#grant_leave").on('click',function(){
				$("#search_container").css('display','block');
			});
			
		});
		$("#search").on('click',function(){
			var id=$("#empid").val();
			$.ajax({
				  url: "/bhel/timeoffice/searchEmployee.php",
				  type: "get", 
				  data:{empid:id },
				  success: function(response) {
					parseEmployee(response);
				  },
				  error: function(xhr) {
					alert("got Error "+xhr);
				  }
				});
			
			
		});
		function parseEmployee(response)
		{
			
			console.log(response);
			var emp=JSON.parse(response);
			if(emp.status==1)
			{
				var leave=new Leave(emp);
				leave.attach("#leaveDiv");
			}
			else{
				alert("Invalid emp id");
			}
		}
	</script>
</html>

