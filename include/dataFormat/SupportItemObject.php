<?php 
# ************************************************************
#  Object : SupportItemObject
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************
class SupportItemObject {

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
			default : 
				return $this->record[$name];
		}
	}

	public function __isset($name) {
		$name = strtolower($name);
		return isset($this->record[$name]); 
    }

    function __construct($supItemId = -1) {
		$this->initialize();
		if ($supItemId != -1) {
			$this->Open($supItemId);
		}
	}

	private function initialize() {
		$this->supportItemId = -1;
		$this->supportId = -1;
		$this->requestId = -1;
		$this->cost = 0;
	} 

	#  class method
	# ***********************************************
	function OpenByQuery($query) {
		global $mysqli;
		$result = $mysqli->query($query);

		while ($row = $result->fetch_assoc()) {
			$this->supportItemId = $row['supItemId'];
			$this->supportId = $row['supId'];
			$this->requestId = $row['reqId'];
			$this->cost = $row['cost'];
		}
	}

	function Open($supItemId) {
		global $mysqli;
		$query = "SELECT supItemId, supId, reqId, cost FROM supportItem WHERE supItemId = '".$mysqli->real_escape_string($supItemId)."'";
		$this->OpenByQuery($query);
	} 

	function OpenWithIndex($supId, $reqId) {
		global $mysqli;

		$query = "SELECT supItemId, supId, reqId, cost FROM supportItem ";
		$query = $query."WHERE supId = '".$mysqli->real_escape_string($supId)."' AND reqId = '".$mysqli->real_escape_string($reqId)."'";
		$this->supportId = $supId;
		$this->requestId = $reqId;

		$this->OpenByQuery($query);
	}

	function Update() {
		global $mysqli;

		if ($this->supportItemId == -1) {
			# New Data
			$query = "INSERT INTO supportItem (supId, reqId, cost) VALUES ";
			$insertData="'".$this->supportId."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->requestId)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->cost)."'";
			$query = $query."(".$insertData.")";

			$result = $mysqli->query($query);
			// new id
			$this->supportItemId = $mysqli->insert_id;
		} else {
			$query = "UPDATE supportItem SET ";
			$updateData="supId = '".$mysqli->real_escape_string($this->supportId)."', ";
			$updateData = $updateData."reqId = ".$mysqli->real_escape_string($this->requestId).", ";
			$updateData = $updateData."cost = ".$mysqli->real_escape_string($this->cost)." ";
			$query = $query.$updateData." WHERE supItemId = ".$mysqli->real_escape_string($this->supportItemId);

			$result = $mysqli->query($query);
		} 
	} 

	function Delete($supId, $reqId) {
		global $mysqli;

		$query = "DELETE FROM supportItem WHERE supId = '".$mysqli->real_escape_string($supId)."' AND reqId = '".$mysqli->real_escape_string($reqId)."'";
		$result = $mysqli->query($query);
	} 

	function showPrice() {
		return priceFormat($this->cost, 1)." / 월";
	} 
} 
?>
