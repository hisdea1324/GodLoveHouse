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
		$this->record[$name] = $value; 
	}
	
	public function __get($name) { 
		switch ($name) {
			case 'Status': 
				switch (($this->record['reservStatus'])) {
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
		return isset($this->record[$name]); 
    }
	
	
	#  class initialize
	# ***********************************************
	function __construct() {
		$this->record['reservationNo'] = -1;
		$this->record['userId'] = null;
		$this->record['roomId'] = -1;
		$this->record['hospitalId'] = -1;
		$this->record['reservStatus'] = "S0001";
		$this->record['startDate'] = null;
		$this->record['endDate'] = null;
		$this->record['regDate'] = null;
	} 

	function __destruct() {
	} 

	function Open($number) {
		global $mysqli;

		$column = array();
		/* create a prepared statement */
		$query = "SELECT A.*, B.houseName, C.roomName from reservation A, house B, room C ";
		$query = $query."WHERE A.roomId = C.roomId AND C.houseId = B.houseId AND A.reservationNo = ?";
		if ($stmt = $mysqli->prepare($query)) {

			/* bind parameters for markers */
			$stmt->bind_param("i", $number);

			/* execute query */
			$stmt->execute();
			
			$metaResults = $stmt->result_metadata();
			$fields = $metaResults->fetch_fields();
			$statementParams='';
			//build the bind_results statement dynamically so I can get the results in an array
			foreach ($fields as $field) {
				if (empty($statementParams)) {
					$statementParams.="\$column['".$field->name."']";
				} else {
					$statementParams.=", \$column['".$field->name."']";
				}
			}

			$statment = "\$stmt->bind_result($statementParams);";
			eval($statment);
			
			while ($stmt->fetch()) {
				//Now the data is contained in the assoc array $column. Useful if you need to do a foreach, or 
				//if your lazy and didn't want to write out each param to bind.
				$this->record = $column;
			}
			
			/* close statement */
			$stmt->close();
		}
	} 

	function OpenHospitalReserv($number) {
		global $mysqli;

		$column = array();
		/* create a prepared statement */
		$query = "SELECT A.*, B.hospitalName from reservation A, hospital B ";
		$query = $query."WHERE A.hospitalId = B.hospitalId AND A.reservationNo = ?";
		if ($stmt = $mysqli->prepare($query)) {

			/* bind parameters for markers */
			$stmt->bind_param("i", $number);

			/* execute query */
			$stmt->execute();
			
			$metaResults = $stmt->result_metadata();
			$fields = $metaResults->fetch_fields();
			$statementParams='';
			//build the bind_results statement dynamically so I can get the results in an array
			foreach ($fields as $field) {
				if (empty($statementParams)) {
					$statementParams.="\$column['".$field->name."']";
				} else {
					$statementParams.=", \$column['".$field->name."']";
				}
			}

			$statment = "\$stmt->bind_result($statementParams);";
			eval($statment);
			
			while ($stmt->fetch()) {
				//Now the data is contained in the assoc array $column. Useful if you need to do a foreach, or 
				//if your lazy and didn't want to write out each param to bind.
				$this->record = $column;
			}
			
			/* close statement */
			$stmt->close();
		}
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
				$this->record['userId'], 
				$this->record['roomId'], 
				$this->record['hospitalId'], 
				$this->record['reservStatus'], 
				$this->record['startDate'], 
				$this->record['endDate'], 
				$this->record['regDate']);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
			
			$this->record['reservationNo'] = $mysqli->insert_id;
			
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
				$this->record['userId'], 
				$this->record['roomId'], 
				$this->record['hospitalId'], 
				$this->record['reservStatus'], 
				$this->record['startDate'], 
				$this->record['endDate'], 
				$this->record['reservationNo']);
				
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
		}
	} 

	function Delete() {
		global $mysqli;

		if ($this->record['reservationNo'] > -1) {
			$stmt = $mysqli->prepare("delete from reservation where reservationNo = ?");
			$stmt->bind_param("i", $this->record['reservationNo']);
			$stmt->execute();
			$stmt->close();
		}
	} 

	function checkId() {
		return (strlen($this->record['userId']) != 0 && strlen($this->record['roomId']) != 0);
	} 

	function checkDate() {
		return (strlen($this->record['startDate']) != 0 && strlen($this->record['endDate']) != 0);
	} 
	
} 
?>

