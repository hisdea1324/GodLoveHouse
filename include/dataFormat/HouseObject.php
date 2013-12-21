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
			case 'price':
			case 'price1':
			case 'personlimit':
			case 'personlimit1':
			case 'roomlimit':
				$this->record[$name] = is_numeric($value) ? $value : 0;
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
				if (substr_count($this->record[$name], '-') != 2) {
					$this->record[$name] .= "--";
				}
				return explode("-", $this->record[$name]);
			case "zipcode":
				if (substr_count($this->record[$name], '-') != 1) {
					$this->record[$name] .= "-";
				}
				return explode("-", $this->record[$name]);
			case "document_link": 
				if (strlen($this->record['document']) > 0) {
					return "<a href='/upload/room/".$this->record['document']."'>".$this->record['document']."</a>";
				}
				return "없음"; 
			case "homepage":
				if (strlen($this->record['homepage']) == 0 || $this->record['homepage'] == '0' || strpos($this->record['homepage'], '없음')) {
					return "없음";
				} else if (substr($this->record['homepage'], 0, "4") != "http") {
					return "http://".$this->record['homepage'];
				} else {
					return $this->record['homepage'];
				} 
			case "building":
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
			case "status":
				$c_Helper = new CodeHelper();
				return $c_Helper->getCodeName($this->record['status']);
			case "statuscode":
				return str_pad($this->record['status'], 5, '0', STR_PAD_LEFT);
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
		if ($houseId != -1) {
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
		$this->price = 0;
		$this->price1 = 0;
		$this->personlimit = 0;
		$this->personlimit1 = 0;
		$this->roomlimit = 0;
		$this->housename = null;
		$this->homepage = null;
		$this->roomcount = 0;
		$this->documentid = -1;
		$this->document = null;
		$this->documentid2 = -1;
		$this->document2 = null;
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
			$this->price = $row['price1'];
			$this->personlimit = $row['personLimit'];
			$this->personlimit1 = $row['personLimit1'];
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
			# New Data
			$query = "INSERT INTO house (`assocName`, `address1`, `address2`, `zipcode`, `regionCode`, `explain`, `userid`, ";
			$query = $query."`manager1`, `contact1`, `manager2`, `contact2`, `price`, `price1`, `personLimit`, `personLimit1`, `roomLimit`, `houseName`, ";
			$query = $query."`homepage`, `roomCount`, `documentId`, `document`, `buildingType`) VALUES ";
			$insertData = "'".$mysqli->real_escape_string($this->assocName)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->address1)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->address2)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->record['zipcode'])."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->regionCode)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->explain)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->userid)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->manager1)."',";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->record['contact1'])."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->manager2)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->record['contact2'])."', ";
			$insertData = $insertData.$mysqli->real_escape_string($this->price).", ";
			$insertData = $insertData.$mysqli->real_escape_string($this->price1).", ";
			$insertData = $insertData.$mysqli->real_escape_string($this->personLimit).", ";
			$insertData = $insertData.$mysqli->real_escape_string($this->personLimit1).", ";
			$insertData = $insertData.$mysqli->real_escape_string($this->roomLimit).", ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->houseName)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->homepage)."', ";
			$insertData = $insertData.$mysqli->real_escape_string($this->roomCount).", ";
			$insertData = $insertData.$mysqli->real_escape_string($this->documentId).", ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->document)."', ";
			$insertData = $insertData.$mysqli->real_escape_string($this->buildingType);
			$query = $query."(".$insertData.")";

			$result = $mysqli->query($query);

			// new id
			$this->supportid = $mysqli->insert_id;

		} else {
			$query = "UPDATE house SET ";
			$updateData="assocName = '".$mysqli->real_escape_string($this->assocName)."', ";
			$updateData = $updateData."address1 = '".$mysqli->real_escape_string($this->address1)."', ";
			$updateData = $updateData."address2 = '".$mysqli->real_escape_string($this->address2)."', ";
			$updateData = $updateData."regionCode = '".$mysqli->real_escape_string($this->regionCode)."', ";
			$updateData = $updateData."`zipcode` = '".$mysqli->real_escape_string($this->record['zipcode'])."', ";
			$updateData = $updateData."`explain` = '".$mysqli->real_escape_string($this->explain)."', ";
			$updateData = $updateData."`userid` = '".$mysqli->real_escape_string($this->userid)."', ";
			$updateData = $updateData."manager1 = '".$mysqli->real_escape_string($this->manager1)."', ";
			$updateData = $updateData."contact1 = '".$mysqli->real_escape_string($this->record['contact1'])."', ";
			$updateData = $updateData."manager2 = '".$mysqli->real_escape_string($this->manager2)."', ";
			$updateData = $updateData."contact2 = '".$mysqli->real_escape_string($this->record['contact2'])."', ";
			$updateData = $updateData."`price` = ".$mysqli->real_escape_string($this->price).", ";
			$updateData = $updateData."price1 = ".$mysqli->real_escape_string($this->price1).", ";
			$updateData = $updateData."personLimit = ".$mysqli->real_escape_string($this->personLimit).", ";
			$updateData = $updateData."personLimit1 = ".$mysqli->real_escape_string($this->personLimit1).", ";
			$updateData = $updateData."roomLimit = ".$mysqli->real_escape_string($this->roomLimit).", ";
			$updateData = $updateData."houseName = '".$mysqli->real_escape_string($this->houseName)."', ";
			$updateData = $updateData."`status` = '".$mysqli->real_escape_string($this->statuscode)."', ";
			$updateData = $updateData."`homepage` = '".$mysqli->real_escape_string($this->homepage)."', ";
			$updateData = $updateData."roomCount = ".$mysqli->real_escape_string($this->roomCount).", ";
			$updateData = $updateData."`document` = '".$mysqli->real_escape_string($this->document)."', ";
			$updateData = $updateData."documentId = ".$mysqli->real_escape_string($this->documentId).", ";
			$updateData = $updateData."buildingType = ".$mysqli->real_escape_string($this->buildingType)." ";
			$query = $query.$updateData." WHERE houseId = ".$mysqli->real_escape_string($this->houseId);

			$result = $mysqli->query($query);
		} 
	} 
	
	function Delete() {
		global $mysqli;

		$query = "DELETE FROM house WHERE houseId = ".$mysqli->real_escape_string($this->houseId);
		$result = $mysqli->query($query);
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