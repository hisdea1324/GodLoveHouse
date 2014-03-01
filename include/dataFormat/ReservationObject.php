<?php 
# ************************************************************
#  Object : ReservationObject
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************
class ReservationObject {
	protected $record = array();
	protected $image = array();

	public function __set($name, $value) { 
		$name = strtolower($name);
		switch ($name) {
			default : 
				$this->record[$name] = $value;
				break;
			case 'status': 
				$this->record['reservstatus'] = $value;
				break;
			case 'startdate':
			case 'enddate':
			case 'regdate':
				if (strpos($value, '-') !== false) {
					$time = explode('-', $value);
					$value = mktime(0, 0, 0, $time[1], $time[2], $time[0]);
				}
				$this->record[$name] = $value;
				break;
		}
	}
	
	public function __get($name) { 
		$name = strtolower($name);
		switch ($name) {
			case 'bookno':
				return $this->record['reservationno'];
			case 'status': 
				switch ($this->record['reservstatus']) {
					case "S0001":
						return "신규예약";
					case "S0002":
						return "승인";
					case "S0003":
						return "완료";
					case "S0004":
						return "거절";
				} 
			case 'regdate':
				if (!$this->record[$name]) {
					$this->record[$name] = time();
				}
				return $this->record[$name];
			case 'duration':
				$duration = ($this->enddate - $this->startdate) / (24 * 60 * 60);
				return $duration;
			default:
				return $this->record[$name];
		}
	}
	
	public function __isset($name) {
		$name = strtolower($name);
		return isset($this->record[$name]); 
    }
	
	
	#  class initialize
	# ***********************************************
    function __construct($reservationNo = -1) {
		$this->initialize();
		if ($reservationNo > -1) {
			$this->Open($reservationNo);
		}
	}

	function __destruct() {
	} 

	private function initialize() {
		$this->reservationNo = -1;
		$this->userid = null;
		$this->roomId = -1;
		$this->hospitalId = -1;
		$this->reservStatus = "S0001";
		$this->startDate = null;
		$this->endDate = null;
		$this->regDate = null;
		$this->resv_name = null;
		$this->resv_phone = null;
		$this->arrival_time = "";
		$this->people_number = "";
		$this->purpose = "";
		$this->memo = "";
	} 

	function Open($number) {
		global $mysqli;

		$query = "SELECT A.*, B.houseName, C.roomName from reservation A, house B, room C ";
		$query = $query."WHERE A.roomId = C.roomId AND C.houseId = B.houseId AND A.reservationNo = '".$mysqli->real_escape_string($number)."'";

		$result = $mysqli->query($query);
		if (!$result) return;

		while ($row = $result->fetch_assoc()) {
			$this->reservationNo = $row['reservationNo'];
			$this->userid = $row['userid'];
			$this->roomId = $row['roomId'];
			$this->hospitalId = $row['hospitalId'];
			$this->reservStatus = $row['reservStatus'];
			$this->startDate = $row['startDate'];
			$this->endDate = $row['endDate'];
			$this->regDate = $row['regDate'];
			$this->houseName = $row['houseName'];
			$this->roomName = $row['roomName'];
			$this->resv_name = $row['resv_name'];
			$this->resv_phone = $row['resv_phone'];
			$this->arrival_time = $row['arrival_time'];
			$this->people_number = $row['people_number'];
			$this->purpose = $row['purpose'];
			$this->memo = $row['memo'];
		}

		$result->close();
	} 

	function OpenHospitalReserv($number) {
		global $mysqli;

		$column = array();
		/* create a prepared statement */
		$query = "SELECT A.*, B.hospitalName from reservation A, hospital B ";
		$query = $query."WHERE A.hospitalId = B.hospitalId AND A.reservationNo = '".$mysqli->real_escape_string($number)."'";

		$result = $mysqli->query($query);
		if (!$result) return;

		while ($row = $result->fetch_assoc()) {
			$this->reservationNo = $row['reservationNo'];
			$this->userid = $row['userid'];
			$this->roomId = $row['roomId'];
			$this->hospitalId = $row['hospitalId'];
			$this->reservStatus = $row['reservStatus'];
			$this->startDate = $row['startDate'];
			$this->endDate = $row['endDate'];
			$this->regDate = $row['regDate'];
			$this->houseName = $row['houseName'];
			$this->resv_name = $row['resv_name'];
			$this->resv_phone = $row['resv_phone'];
			$this->arrival_time = $row['arrival_time'];
			$this->people_number = $row['people_number'];
			$this->purpose = $row['purpose'];
			$this->memo = $row['memo'];
		}

		$result->close();
	} 

	function Update() {
		global $mysqli;

		if ($this->reservationNo == -1) {
			$temp = "'".$mysqli->real_escape_string($this->userid)."'";
			$temp = $temp.", ".$mysqli->real_escape_string($this->roomId);
			$temp = $temp.", ".$mysqli->real_escape_string($this->hospitalId);
			$temp = $temp.", '".$mysqli->real_escape_string($this->reservStatus)."'";
			$temp = $temp.", ".$mysqli->real_escape_string($this->startDate);
			$temp = $temp.", ".$mysqli->real_escape_string($this->endDate);
			$temp = $temp.", ".$mysqli->real_escape_string($this->regDate);
			$temp = $temp.", '".$mysqli->real_escape_string($this->resv_name)."'";
			$temp = $temp.", '".$mysqli->real_escape_string($this->resv_phone)."'";
			$temp = $temp.", '".$mysqli->real_escape_string($this->arrival_time)."'";
			$temp = $temp.", '".$mysqli->real_escape_string($this->people_number)."'";
			$temp = $temp.", '".$mysqli->real_escape_string($this->purpose)."'";
			$temp = $temp.", '".$mysqli->real_escape_string($this->memo)."'";

			$query = "INSERT INTO reservation (`userid`, `roomId`, `hospitalId`, `reservStatus`, `startDate`, `endDate`, `regDate`, resv_name, resv_phone, arrival_time, people_number, purpose, memo) VALUES ($temp)";

			$result = $mysqli->query($query);
			if (!$result) {
				return false;
			}
			
			$this->reservationNo = $mysqli->insert_id;
		} else {
			$query = "UPDATE reservation SET ";
			$updateData=" userid = '".$mysqli->real_escape_string($this->userid)."', ";
			$updateData = $updateData." roomId = ".$mysqli->real_escape_string($this->roomId).", ";
			$updateData = $updateData." hospitalId = ".$mysqli->real_escape_string($this->hospitalId).", ";
			$updateData = $updateData." reservStatus = '".$mysqli->real_escape_string($this->reservStatus)."', ";
			$updateData = $updateData." startDate = ".$mysqli->real_escape_string($this->startDate).", ";
			$updateData = $updateData." endDate = ".$mysqli->real_escape_string($this->endDate).", ";
			$updateData = $updateData." resv_name = '".$mysqli->real_escape_string($this->resv_name)."', ";
			$updateData = $updateData." resv_phone = '".$mysqli->real_escape_string($this->resv_phone)."', ";
			$updateData = $updateData." arrival_time = '".$mysqli->real_escape_string($this->arrival_time)."', ";
			$updateData = $updateData." people_number = '".$mysqli->real_escape_string($this->people_number)."', ";
			$updateData = $updateData." purpose = '".$mysqli->real_escape_string($this->purpose)."', ";
			$updateData = $updateData." memo = '".$mysqli->real_escape_string($this->memo)."' ";
			$query = $query.$updateData." WHERE reservationNo = ".$mysqli->real_escape_string($this->reservationNo);

			$result = $mysqli->query($query);

			if (!$result) {
				return false;
			}
		}

		return true;
	} 

	function Delete() {
		global $mysqli;

		if ($this->reservationNo > -1) {
			$query = "DELETE FROM reservation WHERE reservationNo = '".$mysqli->real_escape_string($number)."'";
			if ($result = $mysqli->query($query)) {
				$result->close();
				return true;
			}
		}

		return false;
	} 

	function checkId() {
		return (strlen($this->userid) != 0 && strlen($this->roomId) != 0);
	} 

	function checkDate() {
		return (strlen($this->startDate) != 0 && strlen($this->endDate) != 0);
	} 
	
} 
?>

