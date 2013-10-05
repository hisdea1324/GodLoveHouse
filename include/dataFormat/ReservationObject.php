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

	public function __set($name,$value) { 
		$name = strtolower($name);
		switch ($name) {
			default : 
				$this->record[$name] = $value;
				break;
		}
	}
	
	public function __get($name) { 
		$name = strtolower($name);
		switch ($name) {
			case 'status': 
				switch ($this->record['reservStatus']) {
					case "S0001":
						return "신규예약";
					case "S0002":
						return "승인";
					case "S0003":
						return "완료";
					case "S0004":
						return "거절";
				} 
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
		$this->userId = null;
		$this->roomId = -1;
		$this->hospitalId = -1;
		$this->reservStatus = "S0001";
		$this->startDate = null;
		$this->endDate = null;
		$this->regDate = null;
	} 

	function Open($number) {
		global $mysqli;

		$query = "SELECT A.*, B.houseName, C.roomName from reservation A, house B, room C ";
		$query = $query."WHERE A.roomId = C.roomId AND C.houseId = B.houseId AND A.reservationNo = '".$mysqli->real_escape_string($number)."'";

		$result = $mysqli->query($query);
		if (!$result) return;

		while ($row = $result->fetch_assoc()) {
			$this->reservationNo = $row['reservationNo'];
			$this->userId = $row['userId'];
			$this->roomId = $row['roomId'];
			$this->hospitalId = $row['hospitalId'];
			$this->reservStatus = $row['reservStatus'];
			$this->startDate = $row['startDate'];
			$this->endDate = $row['endDate'];
			$this->regDate = $row['regDate'];
			$this->houseName = $row['houseName'];
			$this->roomName = $row['roomName'];
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
			$this->userId = $row['userId'];
			$this->roomId = $row['roomId'];
			$this->hospitalId = $row['hospitalId'];
			$this->reservStatus = $row['reservStatus'];
			$this->startDate = $row['startDate'];
			$this->endDate = $row['endDate'];
			$this->regDate = $row['regDate'];
			$this->houseName = $row['houseName'];
		}

		$result->close();
	} 

	function Update() {
		global $mysqli;

		if (($this->record['reservationNo'] == -1)) {
			$query = "INSERT INTO reservation (`userId`, `roomId`, `hospitalId`, `reservStatus`, `startDate`, `endDate`, `regDate`) VALUES ";
			$query = "(?, ?, ?, ?, ?, ?, ?)";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("iiisiii", 
				$this->userId, 
				$this->roomId, 
				$this->hospitalId, 
				$this->reservStatus, 
				$this->startDate, 
				$this->endDate, 
				$this->regDate);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
			
			$this->reservationNo = $mysqli->insert_id;
			
		} else {
			$query = "UPDATE reservation SET ";
			$updateData=" userId = ?, ";
			$updateData = $updateData." roomId = ?, ";
			$updateData = $updateData." hospitalId = ?, ";
			$updateData = $updateData." reservStatus = ?, ";
			$updateData = $updateData." startDate = ?, ";
			$updateData = $updateData." endDate = ?";
			$query = $query.$updateData." WHERE reservationNo = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			$stmt->bind_param("iiisiii", 
				$this->userId, 
				$this->roomId, 
				$this->hospitalId, 
				$this->reservStatus, 
				$this->startDate, 
				$this->endDate, 
				$this->reservationNo);
				
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
		}
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
		return (strlen($this->userId) != 0 && strlen($this->roomId) != 0);
	} 

	function checkDate() {
		return (strlen($this->startDate) != 0 && strlen($this->endDate) != 0);
	} 
	
} 
?>

