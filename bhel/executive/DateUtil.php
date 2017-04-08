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
	}
?>