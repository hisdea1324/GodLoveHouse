<?php 
class CalendarBuilder {
	public $year;
	public $month;
	public $firstday;
	public $lastday;

	function __construct() {
		$this->year = strftime("%Y", time());
		$this->month = strftime("%m", time());
		$this->firstday = mktime(0, 0, 0, $this->month, 1, $this->year);
		$this->lastday = mktime(0, 0, 0, $this->month + 1, 1, $this->year) - 24 * 60 * 60;
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
				# 0:일요일 1:월요일 ....6:토요일
				return date("w", $this->firstday); 
			case "LastDate":
				# 이번달 마지막날 찾기
				# --------------------------------	
				# (다음달 1일) - (이번달 1일 ) = 이번달 마지막날짜		
				return date("d", $this->lastday);
			case "LastDay":
				# 이번달 마지막날의 요일 찾기
				# --------------------------------	
				# 0:일요일 1:월요일 ....6:토요일
				return date("w", $this->lastday);
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
		$timestamp = $this->firstday + (($value - 1) * 24 * 60 * 60);
		$weekNumber = date('w', $timestamp);
		switch ($weekNumber) {
			case 1:
				$weekName = "<font color='black'>월 </font>";
				break;
			case 2:
				$weekName = "<font color='black'>화 </font>";
				break;
			case 3:
				$weekName = "<font color='black'>수 </font>";
				break;
			case 4:
				$weekName = "<font color='black'>목 </font>";
				break;
			case 5:
				$weekName = "<font color='black'>금 </font>";
				break;
			case 6:
				$weekName = "<font color='blue'>토 </font>";
				break;
			default:
				$weekName = "<font color='red'>일 </font>";
				break;
		} 

		return $weekName;
	} 

	function IsWeekStart($value) {
		$timestamp = $this->firstday + (($value - 1) * 24 * 60 * 60);
		$weekNumber = date('w', $timestamp);
		return ($weekNumber == 0);
	} 

	function IsWeekEnd($value) {
		$timestamp = $this->firstday + (($value - 1) * 24 * 60 * 60);
		$weekNumber = date('w', $timestamp);
		return ($weekNumber == 6);
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
