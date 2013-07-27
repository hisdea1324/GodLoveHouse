<?php 
class CalendarBuilder {
	var $m_year;
	var $m_month;

	function __construct() {
		$m_year=strftime("%Y",time());
		$m_month=strftime("%m",time());
	} 

	function __destruct() {
		

	} 

	# Get property
	# ***********************************************	
	function CYear() {
		$CYear = $m_year;
	} 

	function CMonth() {
		$CMonth=DataFormat($m_month);
	} 

	function CurrentMonth() {
		$CurrentMonth = $m_year.DataFormat($m_month);
	} 

	function NextMonth() {
		if (($m_month==12)) {
			$retValue=($m_year+1)."01";
		} else {
			$retValue = $m_year.DataFormat($m_month+1);
		} 

		$NextMonth = $retValue;
	} 

	# 이번달 1일의 요일계산
	# ---------------------------------	
	function FirstDay() {
		$FirstDay=strftime("%w",$m_year."-".$m_month."-1")+1; # 1:일요일 2:월요일 ....7:토요일		 return $function_ret;
	} 

	# 이번달 마지막날 찾기
	# --------------------------------	
	function LastDate() {
		# (다음달 1일) - (이번달 1일 ) = 이번달 마지막날짜		
		$v_thisMonth = $m_year."-".$m_month."-1";
		$v_nextMonth = $DateAdd["m"][1][$v_thisMonth];
		$LastDate = $DateDiff["y"][$v_thisMonth][$v_nextMonth];
	} 

	# 이번달 마지막날의 요일 찾기
	# --------------------------------	
	function LastDay() {
		$LastDay = strftime("%w",$m_year."-".$m_month."-".$LastDate) + 1; 
		# 1:일요일 2:월요일 ....7:토요일		 
		return $LastDay;
	} 

	#  Set property 
	# ***********************************************
	function CYear($value) {
		if ((strlen($value) > 0)) {
			$m_year = $value;
		} 
	} 

	function CMonth($value) {
		if ((strlen($value) > 0)) {
			$m_month = $value;
		} 
	} 

	#  Method
	# ***********************************************
	function getWeekName($value) {
		$weekEndDate = (8 - $FirstDay) % 7;
		$weekNumber = ($weekEndDate + 7 - (($value) % 7)) % 7;
		switch (($weekNumber)) {
			case 5:
				$weekName="<font color='black'>월 </font>";
				break;
			case 4:
				$weekName="<font color='black'>화 </font>";
				break;
			case 3:
				$weekName="<font color='black'>수 </font>";
				break;
			case 2:
				$weekName="<font color='black'>목 </font>";
				break;
			case 1:
				$weekName="<font color='black'>금 </font>";
				break;
			case 0:
				$weekName="<font color='blue'>토 </font>";
				break;
			default:
				$weekName="<font color='red'>일 </font>";
				break;
		} 

		return $weekName;
	} 

	function IsWeekStart($value) {
		$weekEndDate = (8 - $FirstDay) % 7;

		if (($weekEndDate == (($value - 1) % 7))) {
			$retValue = true;
		} else {
			$retValue = false;
		} 

		return $retValue;
	} 

	function IsWeekEnd($value) {
		$weekEndDate = (8 - $FirstDay) % 7;

		if (($weekEndDate == ($value % 7))) {
			$retValue = true;
		} else {
			$retValue = false;
		} 

		return $retValue;
	} 

	function DataFormat($value) {
		return substr("00".$value,strlen("00".$value) - (2));
	} 

	# 참고할 사항
	# convert(char(10),getdate(),126) -> 2006-06-02
	# convert(char(10),getdate(),102) -> 2006.06.02
	# convert(char(10),getdate(),111) -> 2006/06/02
	# convert(char(8),getdate(),112)-> 20060602
}
?>