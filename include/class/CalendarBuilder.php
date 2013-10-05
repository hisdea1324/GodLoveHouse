<?php 
class CalendarBuilder {
	public $year;
	public $month;

	function __construct() {
		$this->year = strftime("%Y", time());
		$this->month = strftime("%m", time());
	} 

	function __destruct() {
		
	} 

	# Get property
	# ***********************************************	
	function __get($name) {
		switch ($name) {
			case "CYear":
				return $this->year;
			case "CMonth":
				return $this->DataFormat($this->month);
			case "CurrentMonth":
				return $this->year.($this->DataFormat($this->month));
			case "NextMonth":
				if ($this->month == 12) {
					return ($this->year + 1)."01";
				} else {
					return $this->year.($this->DataFormat($this->month + 1));
				} 
			case "FirstDay":
				# 이번달 1일의 요일계산
				# ---------------------------------	
				# 1:일요일 2:월요일 ....7:토요일
				return strftime("%w", $this->year."-".$this->month."-1") + 1; 
			case "LastDate":
				# 이번달 마지막날 찾기
				# --------------------------------	
				# (다음달 1일) - (이번달 1일 ) = 이번달 마지막날짜		
				$v_thisMonth = $this->year."-".$this->month."-1";
				$v_nextMonth = DateAdd("m", 1, $v_thisMonth);
				return DateDiff("y", $v_thisMonth, $v_nextMonth);
			case "LastDay":
				# 이번달 마지막날의 요일 찾기
				# --------------------------------	
				$LastDay = strftime("%w", $this->year."-".$this->month."-".$this->LastDate()) + 1; 
				# 1:일요일 2:월요일 ....7:토요일		 
				return $LastDay;

		}
	}

	#  Set property 
	# ***********************************************
	function __set($name, $value) {
		if (strlen($value) == 0) return;

		switch ($name) {
			case "CYear":
				$this->year = $value;
				break;
			case "CMonth":
				$this->month = $value;
				break;
		}
	}

	#  Method
	# ***********************************************
	function getWeekName($value) {
		$weekEndDate = (8 - $this->FirstDay) % 7;
		$weekNumber = ($weekEndDate + 7 - (($value) % 7)) % 7;
		switch ($weekNumber) {
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
		$weekEndDate = (8 - $this->FirstDay) % 7;
		return ($weekEndDate == (($value - 1) % 7));
	} 

	function IsWeekEnd($value) {
		$weekEndDate = (8 - $this->FirstDay) % 7;
		return ($weekEndDate == ($value % 7));
	} 

	function DataFormat($value) {
		return substr("00".$value, strlen("00".$value) - 2);
	} 

	# 참고할 사항
	# convert(char(10),getdate(),126) -> 2006-06-02
	# convert(char(10),getdate(),102) -> 2006.06.02
	# convert(char(10),getdate(),111) -> 2006/06/02
	# convert(char(8),getdate(),112)-> 20060602
}
?>
