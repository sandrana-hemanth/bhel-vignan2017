<?php
	session_start();
	if(isset($_SESSION['login_type']))
	{
		if(strcmp('admin',$_SESSION['login_type']))
		{
			header("Location: /bhel/logout.php");
		}
	}
	else{
		header("Location: /bhel/logout.php");
	}
?>

<html>
	<head>
		<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
	  <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">

	 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="js/Hierarchy.js?a=65781"></script>
	<link href="css/Hierarchy.css" rel="stylesheet">
	<script>
		function logout()
			{
				window.location.href="/bhel/logout.php";
			}
	</script>
	
	<style>
	
			body{
				background-image:url("../images/bg2.jpg");
			}
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
			
			
			.tab-pane{
			  height:100%;
			  overflow-y:scroll;
			  width:100%;
			}
			::-webkit-scrollbar {
				width: 5px;
				
			}
			 
			::-webkit-scrollbar-track {
				-webkit-box-shadow: inset 0 0 1px rgba(200,10,10,1); 
				border-radius: 2px;
			}
			 
			::-webkit-scrollbar-thumb {
				border-radius: 2px;
				-webkit-box-shadow: inset 5px 5px 5px rgba(0,0,255,1); 
			}
			.Texecutive{
				color:red;
			}
			.Tsupervisor{
				color:green;
			}
			.Temployee{
				color:blue;
			}
			.input-group {
				-webkit-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.21)!important;
				-moz-box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.21)!important;
				box-shadow: 0px 2px 5px 0px rgba(0,0,0,0.21)!important;
			}
			#ExecutivePanel,#SupervisorPanel,#EmployeePanel,#hierarchyPanel{
				background-color: #d3f0ea;
				text-align: center;
				margin-top: 10px;
				box-shadow: 0px 0px 18px 9px;
				padding-top:10px;
				padding-bottom:20px;
			}
			#EmployeePanel span,#ExecutivePanel span,#SupervisorPanel span{
				font-size:20px;
			}
	</style>
	
	<script>
		function main()
		{
			var hierarchy=new Hierarchy();
			window.hierarchy=hierarchy; //making it available globally
			$("#tree").append(hierarchy.tree);
		}
		function showConfirmBox(message){
			$("#confirmMessage").text(message);
			$("#confirmBox").show();
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
	</script>
	</head>

<body onload="main();" class="container-fluid">
	<div style="background-color:#008CBA; color:white; padding:28px;" class="container-fluid">  
            <l>Admin ,<?php echo $_SESSION['id'];?></l>
        <ul id="menu">
            <li><a id="l_show" onclick="logout();">Logout</a></li>
            
            <li><a id="changePasswordButton">Change Password</a></li>
            
        </ul>
		<div class="row" style="margin-top:20px;">
			<div class="col-sm-8">
			</div>
			<div class="col-sm-2">
				<input id="searchBox" class="form-control" style="" type="text" placeholder="search"> 
			</div>
			<div class="col-sm-2">
				<button  class="form-control col-sm-3" style="width:200px;" onclick="searchFor('#searchBox');" >Search</button>
			</div>
			
		</div>
    </div>
	<script>
		function searchFor(id)
		{
			var searchVal=$(id).val();
			$.ajax({
					 url: "/bhel/admin/helper/search.php",
					type: "post", 
					 data:{id:searchVal},
					 success: function(response) {
						console.log(response);
						if(response=="failed")
						{
							alert("No such ID found");
						}
						else{
							var data=JSON.parse(response);
							if(data.who=="employee")
							{
								new Employee(data).panelEmployee();
								return;
							}
							if(data.who=="supervisor")
							{
								new Supervisor(data).panelSupervisor();
							}
							if(data.who=="executive")
							{
								new Executive(data).panelExecutive();
							}
						}
					 },
					error: function(xhr) {
					}
				});
		}
	</script>
	<div class="container-fluid" id="wrapper">
		<div class="row" style="width:100%;">
			<div class="col-sm-4" style="min-height:800px; max-width:350px; border-style:solid; border-color:black; border-width:0px;">
				<button id="addNewExecutiveButton" style="margin-top:10px; margin-left:20%;" class="btn btn-primary">Add New Executive</button>
					<div class="panel-body" style="width:100%;">
						<div class="tab-content">
							<div class="tab-pane active" id="test">
								<div id="tree">
								</div>
							</div>
						</div>
					</div>
					
					
			
			</div>
			<div class="col-sm-8"style="margin-top:20px; max-height:40px;">
				<div class="panel-body" style="width:100%;">
						<div class="tab-content">
							<div id="ExecutivePanel" style="display:none;" class="hid">
							<h3>Executive</h3>
								<div class="row">
									<div class="col-sm-4">
										<span>ID: </span><span id="EPanelID"></span>
									</div>
									<div class="col-sm-4">
										<span>First Name: </span><span id="EPanelFirstName"></span>
									</div>
									<div class="col-sm-4">
										<span>Last Name: </span><span id="EPanelLastName"></span>
									</div>
								</div>
								<div class="row"style="margin-top:20px;">
									<div class="col-sm-4">
										<button class="btn btn-primary" id="EPanelDeleteExecutive">Delete Executive</button>
									</div>
									<div class="col-sm-4">
										<button class="btn btn-primary" id="EPanelChangePassword">Change Password</button>
									</div>
									<div class="col-sm-4">
										<button class="btn btn-primary" id="EPanelAddNewSuperVisor">Add New Supervisor</button>
									</div>
								</div>
								<div class="row hid innerHid" id="EPanelPasswordPanel" style="margin-top:20px;" >
									<div class="col-sm-12">
										<span>Enter New Password:</span><span><input id="EPanelPasswordInp" type="password" placeholder="password"> </span><span><button class="btn btn-primary" id="EPanelPasswordSubmit">Change</button></span>
									</div>
								</div>
								<div id="ExecutiveDeletePanel" class="row hid innerHid" style="margin-top:20px;">
									<div class="col-sm-4" style="width:20%;">
										
									</div>
									<div class="col-sm-4" style="width:60%;">
										<span>Reason For Removing Executive</span><br><span><textarea id="executiveDeleteReason" type="textarea" rows="5" cols="30" ></textarea> </span><br><span><button class="btn btn-primary" id="ExecutiveDeleteButton">Delete</button></span>
									</div>
									<div class="col-sm-4" style="width:20%;">
									</div>
								</div>
								<div class="container-fluid hid innerHid" id="EPanelAddNewSupervisorDiv" style="margin-top:20px;">
								
									<div class="container" style="max-width:400px;">
										<form  method="post" onsubmit="false" id="EPanelAddNewSupervisorForm" style="text-align:initial;">
											<input type="text" value="supervisor" name="to" style="display:none;">
											<input type="text" name="exeID" id="EPanelExeID" style="display:none;">
												<div class="form-group">
													<label for="name" class="cols-sm-2 control-label">ID</label>
													<div class="cols-sm-10">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
															<input type="text" class="form-control" name="id" id="name"  placeholder="Enter ID"/>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label for="email" class="cols-sm-2 control-label">First Name</label>
													<div class="cols-sm-10">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
															<input type="text" class="form-control" name="firstname" id="email"  placeholder="First Name"/>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label for="username" class="cols-sm-2 control-label">Last Name</label>
													<div class="cols-sm-10">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
															<input type="text" class="form-control" name="lastname" id="username"  placeholder="Last Name"/>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label for="password" class="cols-sm-2 control-label">PATTERN</label>
													<div class="cols-sm-10">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
															<input type="text" class="form-control" name="pattern"  placeholder="A,B,C"/>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label for="confirm" class="cols-sm-2 control-label"> Password</label>
													<div class="cols-sm-10">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
															<input type="password" class="form-control" name="password" id="confirm"  placeholder="Password"/>
														</div>
													</div>
												</div>

												<div class="form-group">
													<a class="btn btn-primary" id="EPanelAddButton">Add</a>
												</div>
												
											
									</form>
									</div>
								</div>
							</div>
							<div id="SupervisorPanel" style="display:none;" class="hid">
							<h3>Supervisor</h3>
								<div class="row" style="margin-top:20px;">
									<div class="col-sm-6">
										<span>ID:</span><span id="SPanelID"></span>
									</div>
									<div class="col-sm-6">
										<span>Executive ID:</span><span id="SPanelExeID"></span>
									</div>
								</div>
								<div class="row" style="margin-top:20px;">
									<div class="col-sm-6">
										<span>First Name:</span><span id="SPanelFirstName"></span>
									</div>
									<div class="col-sm-6">
										<span>Last Name: </span><span id="SPanelLastName"></span>
									</div>
								</div>
								<div class="row" style="margin-top:20px;">
									<div class="col-sm-6">
										<button class="btn btn-primary" id="SPanelDeleteSupervisorButton">Delete Supervisor</button>
									</div>
									<div class="col-sm-6">
										<button class="btn btn-primary" id="SPanelChangePasswordButton">Change Password</button>
									</div>
								</div>
								<div class="row" style="margin-top:20px;">
									<div class="col-sm-6">
										<button class="btn btn-primary" id="SPanelChangeExecutiveButton">Change Executive</button>
									</div>
									<div class="col-sm-6">
										<button class="btn btn-primary" id="SPanelAddNewEmployeeButton">Add New Employee</button>
									</div>
								</div>
								<div id="SPanelPasswordPanel" class="row hid innerHid" style="margin-top:20px;">
									<div class="col-sm-4" style="width:20%;">
										
									</div>
									<div class="col-sm-4" style="width:60%;">
										<span>Enter New Password:</span><span><input id="SPanelPasswordInp" type="password" placeholder="password"> </span><span><button class="btn btn-primary" id="SPanelPasswordSubmit">Change</button></span>
									</div>
									<div class="col-sm-4" style="width:20%;">
									</div>
								</div>
								<div id="SupervisorDeletePanel" class="row hid innerHid" style="margin-top:20px;">
									<div class="col-sm-4" style="width:20%;">
										
									</div>
									<div class="col-sm-4" style="width:60%;">
										<span>Reason For Removing Supervisor</span><br><span><textarea id="supervisorDeleteReason" type="textarea" rows="5" cols="30" ></textarea> </span><br><span><button class="btn btn-primary" id="SupervisorDeleteButton">Delete</button></span>
									</div>
									<div class="col-sm-4" style="width:20%;">
									</div>
								</div>
								<div id="SPanelChangeExecutivePanel" class="row hid innerHid" style="margin-top:20px;">
									<div class="col-sm-4" style="width:20%;">
										
									</div>
									<div class="col-sm-4" style="width:60%;">
										<span>Enter Executive ID:</span><span><input id="SPanelExecutiveInp" type="text" placeholder="executive ID"> </span><span><button class="btn btn-primary" id="SPanelExecutiveSubmit">Change</button></span>
									</div>
									<div class="col-sm-4" style="width:20%;">
									</div>
								</div>
								<div id="SPanelAddNewEmployeePanel" class="hid innerHid container-fluid" style="margin-top:20px;">
						<!--			<form  onsubmit="false" id="SPanelAddNewEmployeeForm">
										<input type="text" value="employee" name="to" style="display:none;">
										<input type="text" name="sID" id="SPanelsID" style="display:none;">
										<div class="row">
											<div class="col-sm-4">
												<span>ID: </span><input style="float:right;" type="text" placeholder="ID" name="id">
											</div>
											<div class="col-sm-4">
												<span>First Name: </span><input style="float:right;" type="text" placeholder="First Name" name="firstname">
											</div>
											<div class="col-sm-4">
												<span>Last Name: </span><input style="float:right;" type="text" placeholder="First Name" name="lastname">
											</div>
										</div>
										<div class="row" style="margin-top:20px;">
											<div class="col-sm-4">
												<span>GENDER: </span><select style="float:right;" name="gender"><option value="M">Male</option><option value="F">Female</option></select>
											</div>
											<div class="col-sm-4">
												<span>D.O.B: </span><input style="float:right;" type="date" name="dob">
											</div>
											<div class="col-sm-4">
												<span>Joining Date</span><input style="float:right;" type="date" name="joiningDate">
											</div>
										</div>
										<div class="row" style="margin-top:20px;">
											<div class="col-sm-12" style="text-align:center;">
												<span class="btn btn-primary" id="SPanelAddButton">Add</span>
											</div>
										
										</div>
									</form>
								
								!-->
										<div class="container" style="max-width:400px;">
										<form onsubmit="false" id="SPanelAddNewEmployeeForm" style="text-align:initial;">
											<input type="text" value="employee" name="to" style="display:none;">
										<input type="text" name="sID" id="SPanelsID" style="display:none;">
												<div class="form-group">
													<label for="name" class="cols-sm-2 control-label">ID</label>
													<div class="cols-sm-10">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
															<input type="text" class="form-control" name="id"   placeholder="Enter ID"/>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label for="email" class="cols-sm-2 control-label">First Name</label>
													<div class="cols-sm-10">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
															<input type="text" class="form-control" name="firstname"   placeholder="First Name"/>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label for="username" class="cols-sm-2 control-label">Last Name</label>
													<div class="cols-sm-10">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
															<input type="text" class="form-control" name="lastname" id="username"  placeholder="Last Name"/>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label for="password" class="cols-sm-2 control-label">GENDER</label>
													<div class="cols-sm-10">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
															<select  class="form-control" name="gender"><option value="M">Male</option><option value="F">Female</option></select>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label for="confirm" class="cols-sm-2 control-label"> D.O.B</label>
													<div class="cols-sm-10">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
															<input type="date" class="form-control" name="dob" id="confirm" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label for="confirm" class="cols-sm-2 control-label"> Joining Date</label>
													<div class="cols-sm-10">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
															<input type="date" class="form-control" name="joiningDate" id="confirm" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<a class="btn btn-primary" id="SPanelAddButton">Add</a>
												</div>
												
											
									</form>
									</div>
								</div>
							</div>
							<div id="EmployeePanel" style="display:none;" class="hid">
								<h3>Employee</h3>
								<div class="row" style="margin-top:20px;">
									<div class="col-sm-6">
										<span>ID:</span><span id="EmpPanelID"></span>
									</div>
									<div class="col-sm-6">
										<span>Supervisor ID:</span><span id="EmpPanelSupID"></span>
									</div>
								</div>
								<div class="row" style="margin-top:20px;">
									<div class="col-sm-6">
										<span>First Name:</span><span id="EmpPanelFirstName"></span>
									</div>
									<div class="col-sm-6">
										<span>Last Name: </span><span id="EmpPanelLastName"></span>
									</div>
								</div>
								<div class="row" style="margin-top:20px;">
									<div class="col-sm-6">
										<button class="btn btn-primary" id="EmpPanelDeleteEmployeeButton">Delete Employee</button>
									</div>
									<div class="col-sm-6">
										<button class="btn btn-primary" id="EmpPanelChangeSupervisorButton">Change Supervisor</button>
									</div>
								</div>
								<div class="row" style="margin-top:20px;">
									<div class="col-sm-6">
										
									</div>
									<div class="col-sm-6">
										
									</div>
								</div>
								<div id="EmpPanelChangeSupervisorPanel" class="row hid innerHid" style="margin-top:20px;">
									<div class="col-sm-4" style="width:20%;">
										
									</div>
									<div class="col-sm-4" style="width:60%;">
										<span>Enter Supervisor ID:</span><span><input id="EmpPanelSupervisorInp" type="text" placeholder="Supervisor ID"> </span><span><button class="btn btn-primary" id="EmpPanelSupervisorSubmit">Change</button></span>
									</div>
									<div class="col-sm-4" style="width:20%;">
									</div>
								</div>
								<div id="EmpDeletePanel" class="row hid innerHid" style="margin-top:20px;">
									<div class="col-sm-4" style="width:20%;">
										
									</div>
									<div class="col-sm-4" style="width:60%;">
										<span>Reason For Removing Employee</span><br><span><textarea id="employeeDeleteReason" type="textarea" rows="5" cols="30" ></textarea> </span><br><span><button class="btn btn-primary" id="EmpDeleteButton">Delete</button></span>
									</div>
									<div class="col-sm-4" style="width:20%;">
									</div>
								</div>
							</div>
							<div id="hierarchyPanel" style="display:none;" class="hid">
								<div id="HPanelAddNewExecutivePanel" class="container" style="margin-top:20px; max-width:400px;">
									<form  onsubmit="false" id="HPanelAddNewExecutiveForm">
									<input type="text" name="to" value="executive" style="display:none;">
												<div class="form-group">
													<label for="name" class="cols-sm-2 control-label">Executive ID</label>
													<div class="cols-sm-10">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
															<input type="text" class="form-control" name="id" id="name"  placeholder="Enter Executive ID"/>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label for="email" class="cols-sm-2 control-label">First Name</label>
													<div class="cols-sm-10">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-envelope fa" aria-hidden="true"></i></span>
															<input type="text" class="form-control" name="firstname" id="email"  placeholder="First Name"/>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label for="username" class="cols-sm-2 control-label">Last Name</label>
													<div class="cols-sm-10">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
															<input type="text" class="form-control" name="lastname" id="username"  placeholder="Last Name"/>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label for="password" class="cols-sm-2 control-label">Default Password</label>
													<div class="cols-sm-10">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
															<input type="password" class="form-control" name="password" id="password"  placeholder="Enter Default Password"/>
														</div>
													</div>
												</div>

												<div class="form-group">
													<label for="confirm" class="cols-sm-2 control-label">Confirm Password</label>
													<div class="cols-sm-10">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
															<input type="password" class="form-control" name="confirm" id="confirm"  placeholder="Confirm Password"/>
														</div>
													</div>
												</div>

												<div class="form-group">
													<a id="HPanelAddButton" class="btn btn-primary">Add Executive</a>
												</div>
												
											
									</form>
								</div>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="confirmBox" class="alert-success container" style="display:none !important; width: 30%;position: absolute;top: 40%;left: 40%;border: 2px solid black;/* width: 300px; */height: 200px;display: block;">
		<h4 style="margin-top:20%; text-align:center;" id="confirmMessage">This is Message</h4>
		<button id="confirmBoxOK" class="btn btn-success" onclick="$('#confirmBox').hide(); document.getElementById('wrapper').style.opacity='1';" style="margin-top: 40px;float:right;/* width:50px; */margin-right:10px;">OKAY</button>
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
</html>