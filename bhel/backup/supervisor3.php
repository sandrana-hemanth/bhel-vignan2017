<?php	
	session_start();
	if(!isset($_SESSION['login_type']))
	{
		header("Location:index.php");
	}
	$_SESSION['id']='default';
?>
<html>
	<head>
	
	
  <script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>

  

  
  

  
    
      <link rel="stylesheet" type="text/css" href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/css/bootstrap-combined.min.css">
    
  
    
      <link rel="stylesheet" type="text/css" href="http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/css/datepicker.css">
    
  
    
      <script type="text/javascript" src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/js/bootstrap.min.js"></script>
    
  
    
      <script type="text/javascript" src="http://cdn.jsdelivr.net/bootstrap.datepicker/0.1/js/bootstrap-datepicker.js"></script>
    
		<script type="text/javascript" src="supervisorFiles/rooster.js?b=22"></script>

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
					alert(response);
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
			$(document).ready(function(){
				$("#refresh").on("click",function(){
					var val=$("#dateinput").val();
					var arr=val.split("-");
					currentDate=new Date(arr[1]+"-"+arr[0]+"-1");
					//alert(currentDate);
					rooster=new Rooster(currentDate);
					rooster.sendRoosterRequest();
					
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
	</head>
	<body onload="main();">
	<div class="container">
		<button value="logout" class="btn btn-primary active" style="float:right;" id="logout" onclick="logout();">LOGOUT</button>
		
	</div>
	
		<select name="shifts" class="shifts" style="display:none;">
		  <option value="A">A</option>
		  <option value="B">B</option>
		  <option value="C">C</option>
		  <option value="GS">GS</option>
		  <option value="WH">WH</option>
		</select>
		<div class="container">
		
			<div class="input-append date" id="datepicker" data-date="12-2016" 
				 data-date-format="mm-yyyy">

			 <input id="dateinput" style="height:auto;" type="text" readonly="readonly" name="date" >	  
			 <span class="add-on"><i class="icon-th"></i></span>
			 <button id="refresh" style="display:inline;" style="margin-left:20px;" class="btn btn-primary">Refresh</button>
		</div>
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
		<div class="container">
		<button id="commit" class="btn btn-primary active" style="margin-left:80%; font-size:20px; width:20%;">COMMIT</button>
		</div>
		
		
		
	</body>

</html>