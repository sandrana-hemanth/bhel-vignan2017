<?php
	class DateUtil{
		var $dateString;
		var $timestamp;
		function __construct($day,$mon,$year)
		{
			$this->dateString=$year."/".$mon."/".$day;
			$this->timestamp=strtotime($this->dateString);
		}
		function getDay(){
			
			return date('N',$this->timestamp);
		}
		static function getPreviousMonth($month,$year)
		{
			if($month==1){
				$month=12;
				$year-=1;
			}
			else
				$month-=1;
			return array($month,$year);
		}
		function getDaysBetween($from,$to)
		{
			$startTime=strtotime($from);
			$endTime=strtotime($to);
			echo "$startTime<br>$endTime<br>";
			return (($endTime-$startTime)/(60*60*24))+1;
		}
		
	}
	$obj=new DateUtil('1','1','1');
	echo $obj->getDaysBetween('2016/12/31','2017/1/2');
	
?>