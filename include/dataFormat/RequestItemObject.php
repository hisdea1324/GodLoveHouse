<?php 
# ************************************************************
#  Object : RequestItemObject
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************


class RequestItemObject {
	protected $record = array();

	public function __set($name,$value) { 
		$name = strtolower($name);
		$this->record[$name] = $value;
	}

	public function __get($name) { 
		$name = strtolower($name);
		return $this->record[$name];
	}

	public function __isset($name) {
		$name = strtolower($name);
		return isset($this->record[$name]); 
    }

    function __construct($value = -1) {
    	$this->initialize();
    	if ($value != -1) {
    		$this->Open($value);
    	}
    }

    function initialize() {
		$this->reqItemId = -1;
		$this->reqId = "";
		$this->item = "";
		$this->descript = "";
		$this->cost = 0;
		$this->userid = "";
		$this->sendStatus = "s1001";
	}

	function Open($value) {
		global $mysqli;

		$query = "SELECT reqItemId, reqId, item, descript, cost, userid, sendStatus FROM requestItem WHERE reqItemId = ".$mysqli->real_escape_string($value);
		$result = $mysqli->query($query);
		if (!$result) return;

		while ($row = $result->fetch_assoc()) {
			$this->reqItemId = $row['reqItemId'];
			$this->reqId = $row['reqId'];
			$this->item = $row['item'];
			$this->descript = $row['descript'];
			$this->cost = $row['cost'];
			$this->userid = $row['userid'];
			$this->sendStatus = $row['sendStatus'];
		}
		$result->close();
	}

	function Update() {
		global $mysqli;

		if ($this->reqItemId == -1) {
			# New Data
			$query = "INSERT INTO requestItem ('reqId', 'item', 'descript', 'cost', 'userid', 'sendStatus') VALUES ";
			$insertData = $this->reqId.", ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->item)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->descript)."', ";
			$insertData = $insertData.$mysqli->real_escape_string($this->cost).", ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->userid)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->sendStatus)."'";
			$query = $query."(".$insertData.")";

			$result = $mysqli->query($query);
			// new id
			$this->reqItemId = $mysqli->insert_id;
		} else {
			$query = "UPDATE requestItem SET ";
			$updateData="reqId = ".$mysqli->real_escape_string($this->reqId).", ";
			$updateData = $updateData."`item` = '".$mysqli->real_escape_string($this->item)."', ";
			$updateData = $updateData."`descript` = '".$mysqli->real_escape_string($this->descript)."', ";
			$updateData = $updateData."`cost` = ".$mysqli->real_escape_string($this->cost).", ";
			$updateData = $updateData."`secret` = '".$mysqli->real_escape_string($this->secret)."', ";
			$updateData = $updateData."`userid` = '".$mysqli->real_escape_string($this->userid)."', ";
			$updateData = $updateData."sendStatus = '".$mysqli->real_escape_string($this->sendStatus)."'' ";
			$query = $query.$updateData." WHERE reqItemId = ".$mysqli->real_escape_string($this->reqItemId);

			$result = $mysqli->query($query);
		} 
	}

	function Insert($userid, $supType) {
		global $mysqli;

		$query = "SELECT supId FROM requestInfo WHERE userid = '".$mysqli->real_escape_string($userid)."'' AND supType = '".$mysqli->real_escape_string($supType)."''";
		$result = $mysqli->query($query);
		if (!$result) {
			return false;
		}

		while ($row = $result->fetch_assoc()) {
			$supId = $row['supId'];
		}

		if ($supId > 0) {
			$query = "INSERT INTO requestItem (supId, reqItemId, cost) VALUES ";
			$query = $query."(".$mysqli->real_escape_string($supId).", ".$mysqli->real_escape_string($this->reqItemId).", ".$mysqli->real_escape_string($this->cost).")";
			$result = $mysqli->query($query);
		}

		return true;
	} 

	function Delete() {
		global $mysqli;

		if ($this->reqItemId > -1) {
			$query = "DELETE FROM requestItem WHERE reqItemId = ".$mysqli->real_escape_string($this->reqItemId);
			$result = $mysqli->query($query);
		}
	}


	function showPrice() {
		return priceFormat($this->cost, 1);
	} 
}
?>
