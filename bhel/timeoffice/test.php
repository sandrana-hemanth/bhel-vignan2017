<html>
<head>
<script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
<script type="text/javascript" src="downloadRooster.js?a=741245"></script>
<script>
	$(document).ready(function(){
		var a=new DownloadRooster();
		a.initialize();
	});
	
</script>
</head>
<body >
	<input type="date" id="startDate">
	<input type="date" id="endDate">
	<button id="getRoosterButton">Get Rooster</button>
	<div id="roosterHolder">
	</div>
</body>
</html>