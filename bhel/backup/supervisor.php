<?php	
	session_start();
	if(!isset($_SESSION['login_type']))
	{
		header("Location: /bhel/index.php");
	}
	$_SESSION['id']='default';
?>
<html>
	<head>
	
	
  <script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>

  <script type="text/javascript" src="jquery/jquery.table2excel.js"></script>

  
  

  
    
      <link rel="stylesheet" type="text/css" href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/css/bootstrap-combined.min.css">
    
  
    
      <link rel="stylesheet" type="text/css" href="http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css">
    
  
    
      <script type="text/javascript" src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/js/bootstrap.min.js"></script>
    
  
    
      <script type="text/javascript" src="http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js"></script>
    
		<script type="text/javascript" src="supervisorFiles/rooster.js?b=232"></script>

  <style type="text/css">
    
  </style>

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
				  url: "/bhel/createRoosterData.php",
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
			function main()
			{
				rooster=new Rooster(new Date());
				rooster.sendRoosterRequest();
			}
			function logout()
			{
				window.location.href="logout.php";
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
				
			});
			
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
		</style>
	</head>
	<body onload="main();">
	
	<div style="background-color:#008CBA; color:white; padding:28px;">  
            <l>supervisor ,<?php echo $_SESSION['id'];?></l>
        <ul id="menu">
            <li><a id="l_show" onclick="logout();">Logout</a></li>
            
            <li><a id="p_show">Change Password</a></li>
            <li><a id="e_show">Employee Details</a></li>
            
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
		<div class="container" style="margin-top:20px;">
		
			<div class="input-append date" id="datepicker" data-date="12-2016" 
				 data-date-format="mm-yyyy">

			 <input id="dateinput" style="height:auto;" type="text" readonly="readonly" name="date" >	  
			 <span class="add-on"><i class="icon-th"></i></span>
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
		<div class="container" id="dummy">
			
		</div>
		
		
		
	</body>

</html>