<?php
	session_start();
	if(isset($_SESSION['login_type']))
	{
		if(strcmp($_SESSION['login_type'],"timeoffice")==0)
		{
			echo dirname(__FILE__);
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
        <title>TimeOffice</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js">
      
</script>
<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">  
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"> </script> 
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
   <script src="http://multidatespickr.sourceforge.net/jquery-ui.multidatespicker.js"> 
   </script>
   <link href="../Content/assets/css/jquery-ui.structure.css" rel="stylesheet" />
    <script src="../Scripts/jquery-ui.multidatespicker.js"></script>
    <script src="../Scripts/prettify.js"></script>
  <link rel="stylesheet" type="text/css" href="styles.css">
<script type="text/javascript">
$(document).ready(function(){
	 $("#g_show").click(function(){
		$("#emp_id").slideToggle("slow");
		
	});
        $("#v_show").click(function(){
		$("#date").slideToggle("slow");
                $("#g_show").hide();
                $("#emp_id").hide();
		
	});
   });
    $(function ()
    {
        $('#datePick').multiDatesPicker();
    });
</script>

        <style>
            select{
				   background: white;
				   border: none;
				   font-size: 14px;
				   height: 38px;
				   padding: 5px; 
				   width: 268px;
            }
        </style>
    </head>
    <body>

        <div style="background-color:#008CBA;  padding:28px;">  
          <l>Time Officer,101</l>
        <ul id="menu">
            
            <li><a href="index.html">Logout</a></li>
        </ul>
         </div>
        <div>
            <br><br><br>
            <center>
            <input type="button" value="Grant Leave" name="g" id="g_show" class="button">
            </center>
        </div>
 <div id="emp_id" style="display:none">
     <center>
        <form id="emp" action="">
            <br><br>
            <input type="text" placeholder="Enter Employee id" id="emps_id" class="text">
            <br>
            <select>
  <option value="Casual Leave">Casual Leave</option>
  <option value="Medical Leave">Medical Leave</option>
  <option value="Weekly Leave">Weekly Leave</option>
  
</select>
        <br><br>
        <input type="button" value="Verify" class="button" id="v_show" name="v">
       </form>
     </center>
 </div>
        <div id="date" style="display:none">
            <center>
            <form id="d1">
              <input type="text" placeholder="choose dates" id="datePick" class="text"> 
              <input type="submit" value="Submit" class="button">
              
              </form>
            </center>
        </div>
        
        
        
        
    </body>
</html>
