<?php
	
			$root=$_SERVER['DOCUMENT_ROOT'];
			include_once("$root/bhel/db/login.php");
	$startDate=implode(".",explode("-",$_POST['startDate']));
	//$startDate=replace("/",".",$startDate);
	$endDate=$_POST['endDate'];
	$endDate=implode(".",explode("-",$_POST['endDate']));
	$query="SELECT * from `leaves` where `from_date` between '$startDate' and '$endDate'";
	$res=mysqli_query($conn,$query);
	if($res)
	{
		echo "<table><tbody>";
		echo "<tr><td></td></tr>";
		while($r=mysqli_fetch_array($res,MYSQLI_ASSOC))
		{
			echo "<tr><td style='mso-number-format:\@;'>".$r['E_ID']."</td><td style='mso-number-format:dd\\.mm\\.yyyy;'>".$r['from_date']."</td><td style='mso-number-format:dd\\.mm\\.yyyy;'>".$r['to_date']."</td><td style='mso-number-format:\@;'>".$r['type']."</td><td>".$r['reason']."</td></tr>";
		}
		echo "</tbody></table>";
	}
	else
	{
		echo "<table><tbody>";
		echo "<tr><td>ERROR</td></tr>";
		echo "</tbody></table>";
	}
	function replace($source,$dest,$str)
	{
		$tokens=explode($source,$str);
		$res="";
		if(count($tokens)){
			$res=$tokens[0];
		}
		for($i=1;$i<count($tokens);$i++)
		{
			$res.=$dest.$tokens[$i];
		}
		return $res;
	}
?>