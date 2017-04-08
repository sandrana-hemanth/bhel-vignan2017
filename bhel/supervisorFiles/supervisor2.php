<?php	
	session_start();
	if(!isset($_SESSION['login_type']))
	{
		header("Location:index.php");
	}
?>
<html>
	<head>
	
		
  <script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>

  <script type="text/javascript" src="jquery/jquery.table2excel.js"></script>

		<script type="text/javascript" src="rooster.js?b=2542"></script>
		<script type="text/javascript" src="Employee.js?b=2542"></script>
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
					alert(empid);
					$.ajax({
					  url: "/bhel/supervisorFiles/searchEmployee.php",
					  type: "get", 
					  data:{empID:empid },
					  success: function(response) {
						//console.log(response);
						new Employee(response).display();
					  },
					  error: function(xhr) {
						alert("got Error "+xhr);
					  }
					});
				});
			});
			function main(){}
			
		</script>
		
		<style>
		#roosterTable{
			table-layout:auto !important;
			text-align:center !important;
		}
		#roosterTable th{
			text-align:center !important;
		}
			td{
				border-top: 2px solid black;
				padding:5px;
			}
			.tab-pane{
			  height:600px;
			  overflow-y:scroll;
			  width:100%;
			}
			::-webkit-scrollbar {
				width: 10px;
			}
			 
			::-webkit-scrollbar-track {
				-webkit-box-shadow: inset 0 0 10px rgba(0,0,0,0.3); 
				border-radius: 7px;
			}
			 
			::-webkit-scrollbar-thumb {
				border-radius: 7px;
				-webkit-box-shadow: inset 0 0 10px rgba(0,0,0,0.5); 
			}
			.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
				border-right:1px solid #ddd;
				border-bottom:1px solid #ddd;
				white-space:nowrap;
			}
			select{
				width:auto !important;
			}
			.week1{
				background-color:#999;
				
			}
			.week2{
				background-color:#ccc;
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
				width:30%;
				min-width:200px;
				float:left;
				min-height:400px;
				border-style:solid;
			}
			#employeeDetailsPanel{
				width:auto;
				min-width:600px;
				min-height:400px;
				overflow:none;
				
			}
		</style>
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
	
		<select name="shifts" class="shifts" style="display:none;">
		  <option value="A">A</option>
		  <option value="B">B</option>
		  <option value="C">C</option>
		  <option value="GS">GS</option>
		  <option value="WH">WH</option>
		</select>
		
		<div id="roosterContainer" class="volatile">
			<div class="container" style="margin-top:20px;">
			
				<div class="input-append date" id="datepicker" data-date="12-2016" 
					 data-date-format="mm-yyyy">

				 <input id="dateinput" style="height:auto;" type="text" readonly="readonly" name="date" >	  
				 <span class="add-on"><i class="glyphicon glyphicon-th"></i></span>
				 <button id="refresh" style="display:inline;" style="margin-left:20px;" class="btn btn-primary">Refresh</button>
			</div>
			<button id="commit" class="btn btn-primary " style="float:right; font-size:20px;">COMMIT</button>
			<button id="table2excel" class="btn btn-primary " style="float:right; font-size:20px; margin-right:5px;">Download as Excel</button>
				</div>
			<div class="container">
			<div class="panel-body">
				<div class="tab-content">
					<div class="tab-pane active" id="test">
						<table id="roosterTable" class="table">
							<tbody id="roosterTableBody"></tbody>
						</table>
					</div>
				</div>
			</div>
			</div>
		</div>
		<div id="employeeDetailsContainer" class="volatile">
			<div class="row">
				<div class="col-sm-4">
					<div  id="notificationPanel" style="margin-top:10px; width:100%;">
			
					</div>
				</div>
				<div class="col-sm-8">
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
										<form id="leaveApplyForm">
											<span>From:</span><input style="margin:0px !important; height:auto;" type="date" >
											<span>To</span><input style="margin:0px !important; height:auto;" type="date">
											<br>
											<div style="margin-top:10px;">
												<span>Type</span>
												<select style="margin:0px; height:auto;">
													<option selected disabled>Type Of Leave</option>
													<option value="0001">0001</option>
													<option value="0002">0002</option>
													<option value="0003">0003</option>
													<option value="0004">0004</option>
													<option value="0024">0024</option>
												</select>
												<span>Reason</span>
												<select style="margin:0px;  height:auto;">
													<option selected disabled>Reason</option>
													<option value="GEN01">GEN01</option>
													<option value="GEN02">GEN02</option>
													<option value="GEN03">GEN03</option>
													<option value="GEN04">GEN04</option>
												</select>
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