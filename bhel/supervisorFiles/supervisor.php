<?php	
	session_start();
	
	if(!isset($_SESSION['login_type']))
	{
		header("Location:index.php");
	}
	$root=$_SERVER['DOCUMENT_ROOT'];
	include_once("$root/bhel/db/login.php");
	if(isset($_POST['apply_leave']))
	{
		
		
		include_once("apply_leave.php");
	}
?>
<html>
	<head>
	
		
  <script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>

  <script type="text/javascript" src="jquery/jquery.table2excel.js"></script>
 <script type="text/javascript" src="jquery/doubleScroll.js"></script>
		<script type="text/javascript" src="rooster.js?b=46442"></script>
		<script type="text/javascript" src="Employee.js?b=62"></script>
  <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="bootstrap-datepicker.css">  

 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="bootstrap-datepicker.js"></script>
	
	
	<title></title>

  
    




<script type='text/javascript'>//<![CDATA[
$(function(){
$("#datepicker").datepicker( {
	    format: "mm-yyyy",
	    viewMode: "months", 
	    minViewMode: "months"
	});
});//]]> 

</script>
	
		<script>
			var data;
			var currentDate;
			var selectTag;
			
			function createData()
			{
				var mon=currentDate.getMonth();
				var yr=currentDate.getFullYear();
				$.ajax({
				  url: "/bhel/supervisorFiles/createRoosterData.php",
				  type: "get", 
				  data:{month:mon,year:yr },
				  success: function(response) {
					refresh();
				  },
				  error: function(xhr) {
					alert("got Error "+xhr);
				  }
				});
			}
			
			var rooster;
			function rooster()
			{
				rooster=new Rooster(new Date());
				rooster.sendRoosterRequest();
			}
			function logout()
			{
				window.location.href="/bhel/logout.php";
			}
			function refresh()
			{
					var val=$("#dateinput").val();
					var arr=val.split("-");
					currentDate=new Date(arr[1]+"-"+arr[0]+"-1");
					//alert(currentDate);
					rooster=new Rooster(currentDate);
					rooster.sendRoosterRequest();
					var dateHelper=new DateHelper(new Date());
					$("#currentDateHolder").text(""+dateHelper.getMonthName(currentDate.getMonth())+", "+currentDate.getFullYear());
					
			}
			
			$(document).ready(function(){
				$("#refresh").on("click",function(){
					refresh();
				});
				$("#table2excel").on("click",function(){
					alert("clicked");
					rooster.downloadExcel();
				});
				$("#roosterButton").on("click",function(){
					$(".volatile").hide();
					$("#roosterContainer").show();
					
				});
				$("#employeeDetailsButton").on("click",function(){
					$(".volatile").hide();
					$("#employeeDetailsContainer").show();
					
				});
				$(".volatile").hide();
				$("#employeeDetailsContainer").show();
				
			});
			$(document).ready(function(){
				
				$("#employeeSearchButton").on("click",function(){
					var empid=$("#employeeSearchBar").val();
				//	alert(empid);
					$.ajax({
					  url: "/bhel/supervisorFiles/searchEmployee.php",
					  type: "get", 
					  data:{empID:empid },
					  success: function(response) {
						console.log(response);
						if(response=="error")
						{
							
						}
						new Employee(response).display();
					  },
					  error: function(xhr) {
						alert("got Error "+xhr);
					  }
					});
				});
			});
			function main()
			{
				var currentDate=new Date();
				rooster=new Rooster(currentDate);
				rooster.sendRoosterRequest();
				var dateHelper=new DateHelper(new Date());
				$("#currentDateHolder").text(""+dateHelper.getMonthName(currentDate.getMonth())+", "+currentDate.getFullYear());
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
		#roosterTable{
			table-layout:auto !important;
			text-align:center !important;
		}
		#roosterTable td,#roosterTable th{
			padding:3px;
		}
		#roosterTable th{
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
				width:5px; 
				height:5px;
				background-color:rgba(200,10,10,1);
				border-radius: 2px;
			}
			 
			::-webkit-scrollbar-thumb ,::-webkit-scrollbar-thumb:horizontal {
				border-radius: 2px;
				width:5px; 
				height:5px;
				background-color:rgba(0,0,255,1);
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
				color: #76e8d4;
			}
			.week1{
				background-color:#8a5ca2;
				
			}
			.week2{
				background-color:rgb(136, 161, 202);
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
				background-image:url("images/bg2.jpg");
			}
			#notificationPanel{
				width:100%;
				min-height: 500px;
				border:3px solid #008CBA;
			}
			#employeeDetailsPanel{
				
				
			}
			#leaveNotificationTable{
				font-size:15px;
				
			}
		</style>
	
	<script>
		function validate_leave()
		{
			console.log("callingggg");
			var form=document.getElementById("leaveApplyForm");
			var from_date=new Date(form['from_date'].value);
			var to_date=new Date(form['to_date'].value);
			var type=form['type'].value;
			if(from_date.getTime()<=to_date.getTime()){
				console.log(from_date.getTime()+"  --  "+to_date.getTime()+ " --  "+type);
				if(type=="-1")
					return false;
				form.submit();
				
			}
			else
				alert("Fasle Time00");
				return false;
		}
	</script>
	
	</head>
	<body onload="main();">
	
	<div style="background-color:#008CBA; color:white; padding:28px;">  
            <l>supervisor ,<?php echo $_SESSION['id'];?></l>
        <ul id="menu">
            <li><a id="l_show" onclick="logout();">Logout</a></li>
            
            <li><a id="changePasswordButton">Change Password</a></li>
            <li><a id="employeeDetailsButton">Employee Details</a></li>
			<li><a id="roosterButton">Rooster</a></li>
            
        </ul>
    </div>
        <div id="ch_pwd" style="display:none" >
			 <center>
				<form id="pwd" action="">
					<br><br><br>
					<input type="text" placeholder="Enter current password" id="cu_pwd" class="text"><br><br>
					<input type="text" placeholder="Enter new password" id="n_pwd" class="text">
					<br><br><br>
				<input type="submit" value="Submit" class="button">
			   </form>
			 </center>
		</div>
	
		<select name="shifts" class="shifts" style="display:none; editable:false;">
		  <option disabled value="A">A</option>
			  <option disabled value="B">B</option>
			  <option disabled value="C">C</option>
			  <option disabled value="H">H</option>
			  <option disabled value="M">M</option>
			  <option disabled value="F">F</option>
			  <option disabled value="T">T</option>
			  <option disabled value="G">G</option>
			  <option disabled value="WO">WO</option>
		</select>
		
		<div id="roosterContainer" class="volatile">
			<div class="container" style="margin-top:20px;">
			
				<div class="row">
						<div class="col-sm-4">
							<div style="display:inline;" class="input-append date" id="datepicker" data-date="12-2016" 
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
							<!--<button id="commit" class="btn btn-primary " style="float:right; font-size:15px;">SAVE</button> !-->
							<button id="table2excel" class="btn btn-primary " style="float:right; font-size:15px; margin-right:5px;">Download as Excel</button>
						</div>
					</div>
				</div>
			<div class="container">
			<div class="panel-body">
				<div class="tab-content" style="box-shadow: 0px 1px 20px 10px rgba(52, 69, 103, 0.63);">
					<div class="tab-pane active" id="test">
						<table id="roosterTable" class="table">
							<tbody id="roosterTableBody">
								<tr><td>Choose A month to load Roaster Data</td></tr>
							</tbody>
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
								$sid=$_SESSION['id'];
								$leavesQuery="SELECT * FROM `leaves` WHERE sid='$sid'";
								$leavesResult=mysqli_query($conn,$leavesQuery);
								$status=["NA","PENDING","ACCEPTED","REJECTED","OTHER"];
								if($leavesResult)
								{
									while($row=mysqli_fetch_array($leavesResult,MYSQLI_ASSOC))
									{
										$eid=$row['E_ID'];
										$from=$row['from_date'];
										$to=$row['to_date'];
										$applied=$row['applied_date'];
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
									<div>
										<form id="leaveApplyForm" method="POST" action="supervisor.php">
											<input type="text" name="apply_leave" style="display:none;">
											<input type="text" name="eid" id="leave_form_eid" style="display:none;">
											<div class="container-fluid">
											<div style="margin-top:20px;" class="row">
													<div class="col-xs-6">
														<span>From:</span><input name="from_date" style="margin:0px !important; height:auto;" type="date" >
													</div>
													<div class="col-xs-6">
														<span>To</span><input name="to_date" style="margin:0px !important; height:auto;" type="date">
											
													</div>
											</div>
											</div>
											<div class="container-fluid">
												<div style="margin-top:20px;" class="row">
													<div class="col-xs-4">
														<span>Type</span>
														<select name="type" style="margin:0px; height:auto;" class="btn ">
															<option value="-1" selected disabled>Type Of Leave</option>
															<option value="0001:GEN003">CL</option>
															<option value="0002:GEN003">EL</option>
															<option value="0003:GEN003">HPL</option>
															<option value="0004:GEN001">SL</option>
															<option value="0009:XXX001">EOL</option>
															<option value="0016:XXX001">UAB</option>
															<option value="0020:GEN003">OH</option>
															<option value="0023:GEN003">1/2 CL - F/N</option>
															<option value="0024:GEN003">1/2 CL - A/N</option>
															<option value="0033:XXX001">UAB 1/2 F/N</option>
															<option value="0034:XXX001">UAB 1/2 A/N</option>
														</select>
													</div>
												<!--	<div class="col-xs-4">
														<span>Reason</span>
														<select name="reason" style="margin:0px;  height:auto;" class="btn ">
															<option value="-1" selected disabled>Reason</option>
															<option value="GEN01">GEN01</option>
															<option value="GEN02">GEN02</option>
															<option value="GEN03">GEN03</option>
															<option value="GEN04">GEN04</option>
														</select>
														</div> !-->
													<div class="col-xs-4">
													<span onclick="validate_leave();" class="btn btn-primary">APPLY</span>
													</div>
												</div>
											</div>
											
											</form>
									</div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
		</div>
		
		
		
	</body>

</html>