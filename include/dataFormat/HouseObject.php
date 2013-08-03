<?php 
# ************************************************************
#  Object : HouseObject
# 
#  editor : Sookbun Lee 
#  last update date : 2013. 07. 22
# ************************************************************

//$house = new HouseObject();
//$house->Open(1);

//$house->houseId;
//isset($house->houseId);

class HouseObject {
	protected $record = array();
	public $mRoom = array();
	public $mDocument = "";

	public function __set($name, $value) { 
		switch ($name) {
			case "contact1":
			case "contact2":
			case "zipcode":
				$this->record[$name] = join("-", $value); 
				break;
			default:
				$this->record[$name] = $value; 
				break;
		}
	}
	
	public function __get($name) { 
		switch ($name) {
			case "contact1":
			case "contact2":
			case "zipcode":
				return explode("-", $this->record[$name]);
			case "explain":
				return str_replace(chr(13), "<br>", $this->record[$name]);
			case "roomCount": 
				return count($this->mRoom);
			case "document": 
				if (strlen($this->record[$name]) > 0) {
					return "<a href='/upload/room/".$this->record[$name]."'>".$this->record[$name]."</a>";
				}
				return "없음"; 
			case "homepage":
				if (strlen($this->record[$name]) == 0) {
					return "없음";
				} else if ((substr($this->record[$name],0,"4")!="http")) {
					return "<a href='http://".$this->record[$name]."' target='_blank'>http://".$this->record[$name]."</a>";
				} else {
					return "<a href='".$this->record[$name]."' target='_blank'>".$this->record[$name]."</a>";
				} 
			case "buildingType":
				switch ($this->record[$name]) {
					case 1:
						return "아파트";
					case 2:
						return "빌라";
					case 3:
						return "원룸";
					case 4: default:
						return "기타";
				}
			case "region":
				$c_Helper = new CodeHelper();
				return $c_Helper->getCodeName($this->record['regionCode']);
			case "status":
				$c_Helper = new CodeHelper();
				return $c_Helper->getCodeName($this->record['status']);
			default:
				return $this->record[$name];
		}
	}
	
	public function __isset($name) {
		return isset($this->record[$name]);
    }

	#  class initialize
	# ***********************************************
	function __construct($houseId = -1) {
		echo "HouseId : ".$houseId; 

		if ($houseId == -1) {
			$this->initialize();
		} else {
			$this->Open($houseId);
		}
	}
	
	private function initialize() {
		$this->record['houseId'] = -1;
		$this->record['assocName'] = null;
		$this->record['address1'] = null;
		$this->record['address2'] = null;
		$this->record['zipcode'] = "000-000";
		$this->record['regionCode'] = "S0000";
		$this->record['explain'] = null;
		$this->record['userId'] = null;
		$this->record['manager1'] = null;
		$this->record['contact1'] = null;
		$this->record['manager2'] = null;
		$this->record['contact2'] = null;
		$this->record['price'] = "무료";
		$this->record['personLimit'] = 0;
		$this->record['roomLimit'] = 0;
		$this->record['houseName'] = null;
		$this->record['homepage'] = null;
		$this->record['roomCount'] = 0;
		$this->record['documentId'] = -1;
		$this->record['document'] = null;
		$this->record['buildingType'] = 1;
		$this->record['status'] = "S2001";
		$this->record['regDate'] = "";
	}
	
	#  class method
	# ***********************************************
	public function Open($houseId) {
		global $mysqli;

		$column = array();
		/* create a prepared statement */
		if ($stmt = $mysqli->prepare("SELECT * from house WHERE houseId = ?")) {

			/* bind parameters for markers */
			$stmt->bind_param("i", $houseId);

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
			// $statment: $stmt->bind_result($colomn['a'], $column['b']...)
			eval($statment);
			
			while ($stmt->fetch()) {
				//Now the data is contained in the assoc array $column. Useful if you need to do a foreach, or 
				//if your lazy and didn't want to write out each param to bind.
				$this->record = $column;
			}
			
			/* close statement */
			$stmt->close();

			if (($this->record['documentId'] > 0)) {
				$stmt = $mysqli->prepare("SELECT name FROM attachFile WHERE id = ?");
				$stmt->bind_param("i", $this->record['documentId']);
				$stmt->execute();
				$stmt->bind_result($this->document);
				$stmt->close();
			} else {
				$this->mDocument = $this->record["document"];
			} 
			
			
			$roomId = -1;
			if (($this->record['houseId'] > -1)) {
				$stmt = $mysqli->prepare("SELECT `roomId` FROM room WHERE `houseId` = ?");
				$stmt->bind_param("i", $this->record['houseId']);
				$stmt->execute();
				$stmt->bind_result($roomId);
				while ($stmt->fetch()) {
					$room = new RoomObject();
					$room->Open($roomId);
					$this->mRoom[] = $room;
				}
   				$stmt->close();
			}
		}
	} 
	
	function Update() {
		global $mysqli;

		if (($this->record['houseId'] == -1)) {
			$query = "INSERT INTO house (`assocName`, `address1`, `address2`, `zipcode`, `regionCode`, `explain`, `userId`, ";
			$query = $query."`manager1`, `contact1`, `manager2`, `contact2`, `price`, `personLimit`, `roomLimit`, `houseName`, ";
			$query = $query."`homepage`, `roomCount`, `documentId`, `document`, `buildingType`) VALUES ";
			$query = $query."(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("ssssssssssssiissiisi", 
				$this->record['assocName'], 
				$this->record['address1'], 
				$this->record['address2'], 
				$this->record['zipcode'], 
				$this->record['regionCode'], 
				$this->record['explain'], 
				$this->record['userId'], 
				$this->record['manager1'], 
				$this->record['contact1'], 
				$this->record['manager2'], 
				$this->record['contact2'], 
				$this->record['price'], 
				$this->record['personLimit'], 
				$this->record['roomLimit'], 
				$this->record['houseName'], 
				$this->record['homepage'], 
				$this->record['roomCount'], 
				$this->record['documentId'], 
				$this->record['document'], 
				$this->record['buildingType']);
		
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
			
			$this->record['houseId'] = $mysqli->insert_id;
			
		} else {
			$query = "UPDATE house SET ";
			$updateData = "`assocName` = ?, ";
			$updateData = $updateData."`address1` = ?, ";
			$updateData = $updateData."`address2` = ?, ";
			$updateData = $updateData."`regionCode` = ?, ";
			$updateData = $updateData."`zipcode` = ?, ";
			$updateData = $updateData."`explain` = ?, ";
			$updateData = $updateData."`userId` = ?, ";
			$updateData = $updateData."`manager1` = ?, ";
			$updateData = $updateData."`contact1` = ?, ";
			$updateData = $updateData."`manager2` = ?, ";
			$updateData = $updateData."`contact2` = ?, ";
			$updateData = $updateData."`price` = ?, ";
			$updateData = $updateData."`personLimit` = ?, ";
			$updateData = $updateData."`roomLimit` = ?, ";
			$updateData = $updateData."`houseName` = ?, ";
			$updateData = $updateData."`homepage` = ?, ";
			$updateData = $updateData."`roomCount` = ?, ";
			$updateData = $updateData."`document` = ?, ";
			$updateData = $updateData."`documentId` = ?, ";
			$updateData = $updateData."`buildingType` = ? ";
			$query = $query.$updateData." WHERE `houseId` = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			$stmt->bind_param("sssssssssssiiissiisii", 
				$this->record['assocName'], 
				$this->record['address1'], 
				$this->record['address2'], 
				$this->record['zipcode'], 
				$this->record['regionCode'], 
				$this->record['explain'], 
				$this->record['userId'], 
				$this->record['manager1'], 
				$this->record['contact1'], 
				$this->record['manager2'], 
				$this->record['contact2'], 
				$this->record['price'], 
				$this->record['personLimit'], 
				$this->record['roomLimit'], 
				$this->record['houseName'], 
				$this->record['homepage'], 
				$this->record['roomCount'], 
				$this->record['documentId'], 
				$this->record['document'], 
				$this->record['buildingType'], 
				$this->record['houseId']);
				
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
		}
	} 
	
	function Delete() {
		global $mysqli;

		if ($this->record['houseId'] > -1) {
			$stmt = $mysqli->prepare("DELETE FROM house WHERE `houseId` = ?");
			$stmt->bind_param("d", $this->record['houseId']);
			$stmt->execute();
			$stmt->close();
		}
	}
	
	function SetRoom($value) {
		$this->mRoom = $value;
	}
	
	function showContactInfo() {
		if (strlen($this->record["contact1"]) > 10) {
			$retString = $this->record["manager1"]." ".$this->record["contact1"];
		} 

		if (strlen($this->record["contact2"]) > 10) {
			$retString = $retString." / ".$this->record["manager2"]." ".$this->record["contact2"];
		} 

		return $retString;
	}
}
?>

