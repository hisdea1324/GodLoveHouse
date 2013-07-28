<?php 
class CalendarBuilder {
	public $year;
	public $month;

	function __construct() {
		$this->year = strftime("%Y",time());
		$this->month = strftime("%m",time());
	} 

	function __destruct() {
		
	} 

	# Get property
	# ***********************************************	
	function CYear() {
		$CYear = $this->year;
	} 

	function CMonth() {
		return DataFormat($this->month);
	} 

	function CurrentMonth() {
		return $this->year.(DataFormat($this->month));
	} 

	function NextMonth() {
		if ($this->month == 12) {
			$retValue = ($this->year + 1)."01";
		} else {
			$retValue = $this->year.(DataFormat($this->month + 1));
		} 

		return $retValue;
	} 

	# 이번달 1일의 요일계산
	# ---------------------------------	
	function FirstDay() {
		# 1:일요일 2:월요일 ....7:토요일
		return strftime("%w", $this->year."-".$this->month."-1") + 1; 
	} 

	# 이번달 마지막날 찾기
	# --------------------------------	
	function LastDate() {
		# (다음달 1일) - (이번달 1일 ) = 이번달 마지막날짜		
		$v_thisMonth = $this->year."-".$this->month."-1";
		$v_nextMonth = DateAdd("m", 1, $v_thisMonth);
		return DateDiff("y", $v_thisMonth, $v_nextMonth);
	} 

	# 이번달 마지막날의 요일 찾기
	# --------------------------------	
	function LastDay() {
		$LastDay = strftime("%w", $this->year."-".$this->month."-".$this->LastDate()) + 1; 
		# 1:일요일 2:월요일 ....7:토요일		 
		return $LastDay;
	} 

	#  Set property 
	# ***********************************************
	function CYear($value) {
		if (strlen($value) > 0) {
			$this->year = $value;
		} 
	} 

	function CMonth($value) {
		if (strlen($value) > 0) {
			$this->month = $value;
		} 
	} 

	#  Method
	# ***********************************************
	function getWeekName($value) {
		$weekEndDate = (8 - $this->FirstDay()) % 7;
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
		$weekEndDate = (8 - $this->FirstDay()) % 7;

		if (($weekEndDate == (($value - 1) % 7))) {
			$retValue = true;
		} else {
			$retValue = false;
		} 

		return $retValue;
	} 

	function IsWeekEnd($value) {
		$weekEndDate = (8 - $this->FirstDay()) % 7;

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