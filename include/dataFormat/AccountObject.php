<?php 
#************************************************************
# Object : AccountObject
#
# editor : Sookbun Lee 
# last update date : 2010.03.04
#************************************************************
class AccountObject {
	protected $record = array();

	public function __set($name,$value) { 
		$name = strtolower($name);
		switch ($name) {
			case "method" :
				if($value == "CM5")
					$this->record['method'] = 1;
				else if($value == "DIRECT")
					$this->record['method'] = 2;
				else if($value == "ZIRO")
					$this->record['method'] = 3;
				break;

			case "jumin" : 
				$this->record[$name] = join("-", $value);
			default : 
				$this->record[$name] = $value;
				break;
		}
	}

	public function __get($name) { 
		$name = strtolower($name);
		switch ($name) {
			case "method" :
				if ($this->record[$name] == 1) 
					return "CMS";
				else if ($this->record[$name] == 2) 
					return "DIRECT";
				else if ($this->record[$name] == 3) 
					return "GIRO";
				else
					return "";
			case "jumin" : 
				return explode("-", $this->record[$name]);

			default : 
				return $this->record[$name];
		}
	}

	public function __isset($name) {
		$name = strtolower($name);
		return isset($this->record[$name]); 
    }

    function __construct($userid = "") {
		$this->initialize();
		if ($userid != "") {
			$this->Open($userid);
		}
	}

    function initialize() {
		$this->id = -1;
		$this->userid = "";
		$this->name = "";
		$this->bank = "";
		$this->method = "";
		$this->number = "";
		$this->jumin = "000000-0000000";
		$this->senddate = -1;
		$this->expectdate = -1;
		$this->regdate = "";
	}

	function Open($userid) {
		global $mysqli;

		$query = "SELECT * from account WHERE userid = '".$mysqli->real_escape_string($userid)."'";

		$result = $mysqli->query($query);
		if (!$result) return;
		
		while ($row = $result->fetch_assoc()) {
			$this->id = $row['id'];
			$this->userid = $row['userid'];
			$this->name = $row['name'];
			$this->bank = $row['bank'];
			$this->method = $row['method'];
			$this->number = $row['number'];
			$this->senddate = $row['senddate'];
			$this->expectdate = $row['expectdate'];
			$this->regdate = $row['regdate'];
		}
	}


	function Update() {
		global $mysqli;

		if (($this->record['userid'] == "")) {
			$query = "INSERT INTO account (`userid`, `name`, `bank`, `method`, ";
			$query = $query."`number`, `nid`, `senddate`, `expectdate`) VALUES ";
			$query = $query."(?, ?, ?, ?, ?, ?, ?, ?)";

			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("sssssssss", 
				$this->record['userid'], 
				$this->record['name'], 
				$this->record['bank'], 
				$this->record['method'], 
				$this->record['number'], 
				$this->record['nid'], 
				$this->record['senddate'], 
				$this->record['expectdate']);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
			
		} else {

			$query = "UPDATE account SET ";
			$updateData = "`name` = ?, ";
			$updateData.= "`bank` = ?, ";
			$updateData.= "`method` = ?, ";
			$updateData.= "`number` = ?, ";
			$updateData.= "`nid` = ?, ";
			$updateData.= "`senddate` = ?, ";
			$updateData.= "`expectdate` = ? ";
			$query .= $updateData." WHERE `userid` = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			$stmt->bind_param("sssssssss", 
				$this->record['expectdate'], 
				$this->record['name'], 
				$this->record['bank'], 
				$this->record['method'], 
				$this->record['number'], 
				$this->record['nid'], 
				$this->record['senddate'], 
				$this->record['userid']);

				
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
		}
	} 

	function Delete() {
		global $mysqli;

		if ($this->record['userid'] > -1) {
			$stmt = $mysqli->prepare("DELETE FROM account WHERE userid = ?");
			$stmt->bind_param("s", $this->record['userid']);
			$stmt->execute();
			$stmt->close();
		}
	}
}
?>
