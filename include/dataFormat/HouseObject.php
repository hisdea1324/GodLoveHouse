<?php 
# ************************************************************
#  Object : HouseObject
# 
#  editor : Sookbun Lee 
#  last update date : 2013. 07. 22
# ************************************************************
class HouseObject {
	protected $record = array();
	public $mRoom = array();
	public $mDocument = "";

	public function __set($name, $value) { 
		$name = strtolower($name);
		switch ($name) {
			default:
				$this->record[$name] = $value; 
				break;
		}
	}
	
	public function __get($name) { 
		$name = strtolower($name);
		switch ($name) {
			case "contact1":
			case "contact2":
			case "zipcode":
				return explode("-", $this->record[$name]);
			case "explain":
				return str_replace(chr(13), "<br>", $this->record[$name]);
			case "roomCount": case "RoomCount": 
				return $this->record['roomCount'];
			case "document_link": 
				if (strlen($this->record['document']) > 0) {
					return "<a href='/upload/room/".$this->record[$name]."'>".$this->record[$name]."</a>";
				}
				return "없음"; 
			case "homepage":
				if (strlen($this->record[$name]) == 0) {
					return "없음";
				} else if (substr($this->record[$name], 0, "4") != "http") {
					return "<a href='http://".$this->record[$name]."' target='_blank'>http://".$this->record[$name]."</a>";
				} else {
					return "<a href='".$this->record[$name]."' target='_blank'>".$this->record[$name]."</a>";
				} 
			case "buildingTypeValue":
				switch ($this->record['buildingtype']) {
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
				return $c_Helper->getCodeName($this->record['regioncode']);
			case "status": case "StatusCode":
				$c_Helper = new CodeHelper();
				return $c_Helper->getCodeName($this->record['status']);
			case "roomlist":
				$rooms = array();
				foreach ($this->mRoom as $room) {
					$rooms[] = new RoomObject($room);
				} 
				return $rooms;
			default:
				if (isset($this->record[$name])) {
					return $this->record[$name];
				} else {
					return "";
				}
		}
	}
	
	public function __isset($name) {
		$name = strtolower($name);
		return isset($this->record[$name]);
    }

	#  class initialize
	# ***********************************************
	function __construct($houseId = -1) {
		$this->initialize();
		if ($houseId > -1) {
			$this->Open($houseId);
		}
	}
	
	private function initialize() {
		$this->houseid = -1;
		$this->assocname = null;
		$this->address1 = null;
		$this->address2 = null;
		$this->zipcode = "-";
		$this->regioncode = "S0000";
		$this->explain = null;
		$this->userid = null;
		$this->manager1 = null;
		$this->contact1 = "--";
		$this->manager2 = null;
		$this->contact2 = "--";
		$this->price = "무료";
		$this->personlimit = 0;
		$this->roomlimit = 0;
		$this->housename = null;
		$this->homepage = null;
		$this->roomcount = 0;
		$this->documentid = -1;
		$this->document = null;
		$this->buildingtype = 1;
		$this->status = "S2001";
		$this->regdate = "";
	}
	
	#  class method
	# ***********************************************
	public function Open($houseId) {
		global $mysqli;

		$query = "SELECT * from house WHERE houseId = '".$mysqli->real_escape_string($houseId)."'";
		$result = $mysqli->query($query);
		if (!$result) return;

		while ($row = $result->fetch_assoc()) {
			$this->houseid = $row['houseId'];
			$this->assocname = $row['assocName'];
			$this->address1 = $row['address1'];
			$this->address2 = $row['address2'];
			$this->zipcode = $row['zipcode'];
			$this->regioncode = $row['regionCode'];
			$this->explain = $row['explain'];
			$this->userid = $row['userid'];
			$this->manager1 = $row['manager1'];
			$this->contact1 = $row['contact1'];
			$this->manager2 = $row['manager2'];
			$this->contact2 = $row['contact2'];
			$this->price = $row['price'];
			$this->personlimit = $row['personLimit'];
			$this->roomlimit = $row['roomLimit'];
			$this->housename = $row['houseName'];
			$this->homepage = $row['homepage'];
			$this->roomcount = $row['roomCount'];
			$this->documentid = $row['documentId'];
			$this->document = $row['document'];
			$this->buildingtype = $row['buildingType'];
			$this->status = $row['status'];
			$this->regdate = $row['regDate'];
		}
		$result->close();

		if (isset($this->documentId) && $this->documentId > 0) {
			$query = "SELECT name FROM attachFile WHERE id = '".$mysqli->real_escape_string($this->documentId)."'";
			if ($result = $mysqli->query($query)) {
				while ($row = $result->fetch_assoc()) {
					$this->document = $row["name"];
				}
				$result->close();
			}
		}
		
		if (isset($this->houseId) && $this->houseId > -1) {
			$query = "SELECT `roomId` FROM room WHERE `houseId` = '".$mysqli->real_escape_string($this->houseId)."'";
			if ($result = $mysqli->query($query)) {
				while ($row = $result->fetch_assoc()) {
					$this->mRoom[] = $row["roomId"];
				}
				$result->close();
			}
		}
	}
	
	function Update() {
		global $mysqli;

		if ($this->houseId == -1) {
			$query = "INSERT INTO house (`assocName`, `address1`, `address2`, `zipcode`, `regionCode`, `explain`, `userid`, ";
			$query = $query."`manager1`, `contact1`, `manager2`, `contact2`, `price`, `personLimit`, `roomLimit`, `houseName`, ";
			$query = $query."`homepage`, `roomCount`, `documentId`, `document`, `buildingType`) VALUES ";
			$query = $query."(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("ssssssssssssiissiisi", 
				$this->assocName,
				$this->address1,
				$this->address2,
				$this->zipcode,
				$this->regionCode,
				$this->explain,
				$this->userid,
				$this->manager1,
				$this->contact1, 
				$this->manager2, 
				$this->contact2, 
				$this->price, 
				$this->personLimit, 
				$this->roomLimit, 
				$this->houseName, 
				$this->homepage, 
				$this->roomCount, 
				$this->documentId, 
				$this->document, 
				$this->buildingType);
		
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
			
			$this->houseId = $mysqli->insert_id;
			
		} else {
			$query = "UPDATE house SET ";
			$updateData = "`assocName` = ?, ";
			$updateData = $updateData."`address1` = ?, ";
			$updateData = $updateData."`address2` = ?, ";
			$updateData = $updateData."`regionCode` = ?, ";
			$updateData = $updateData."`zipcode` = ?, ";
			$updateData = $updateData."`explain` = ?, ";
			$updateData = $updateData."`userid` = ?, ";
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
				$this->assocName, 
				$this->address1, 
				$this->address2, 
				$this->zipcode, 
				$this->regionCode, 
				$this->explain, 
				$this->userid, 
				$this->manager1, 
				$this->contact1, 
				$this->manager2, 
				$this->contact2, 
				$this->price, 
				$this->personLimit, 
				$this->roomLimit, 
				$this->houseName, 
				$this->homepage, 
				$this->roomCount, 
				$this->documentId, 
				$this->document, 
				$this->buildingType, 
				$this->houseId);
				
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
		}
	} 
	
	function Delete() {
		global $mysqli;

		if ($this->houseId > -1) {
			$stmt = $mysqli->prepare("DELETE FROM house WHERE `houseId` = ?");
			$stmt->bind_param("d", $this->houseId);
			$stmt->execute();
			$stmt->close();
		}
	}
	
	function SetRoom($value) {
		$this->mRoom = $value;
	}
	
	function showContactInfo() {
		$retString = "";
		
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