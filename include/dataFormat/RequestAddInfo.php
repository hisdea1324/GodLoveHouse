<?php 
# ************************************************************
#  Object : RequestAddInfo
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************

class RequestAddInfo {
	protected $record = array();
	protected $items = array();

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
		$this->reqId = -1;
		$this->dueDate = "";
		$this->nick = "";
		$this->nationCode = "";
		$this->currentCost = 0;
		$this->totalCost = 0;
		$this->status = "05001";
		$this->email = "";
		$this->userid = "";
	}


	function Open($value) {
		global $mysqli;

		$query = "SELECT A.reqId, A.userid, B.nick, A.status, A.nationCode, C.name, A.dueDate, B.email, ";
		$query.= " (SELECT SUM(cost) FROM requestItem WHERE reqId = A.reqId) AS totalCost, ";
		$query.= " (SELECT SUM(cost) FROM requestItem WHERE reqId = A.reqId AND userid > '') AS currentCost ";
		$query.= " FROM requestAddInfo A, users B, code C ";
		$query.= " WHERE A.reqId = ".$mysqli->cubrid_real_escape_string($value)." AND A.userid = B.userid AND A.nationCode = C.code";
		$result = $mysqli->query($query);
		if (!$result) return;

		while ($row = $result->fetch_assoc()) {
			$this->reqId = $row['reqId'];
			$this->dueDate = $row['dueDate'];
			$this->nick = $row['nick'];
			$this->nationCode = $row['nationCode'];
			$this->currentCost = $row['currentCost'];
			$this->totalCost = $row['totalCost'];
			$this->status = $row['status'];
			$this->email = $row['email'];
			$this->userid = $row['userid'];
		}
		$result->close();


		$query = "SELECT reqItemId FROM requestItem WHERE reqId = ".$mysqli->cubrid_real_escape_string($value);
		$result = $mysqli->query($query);
		if (!$result) return;

		$regItemColumn = array();
		while ($row = $result->fetch_assoc()) {
			$this->items[] = new RequestItemObject($row['reqItemId']);
		}
	}

	function Update() {
		global $mysqli;

		if ($this->reqId == -1) {
			# New Data
			$query = "INSERT INTO requestAddInfo (`userid`, `status', `dueDate`, `nationCode`) VALUES ";
			$insertData = "'".$mysqli->real_escape_string($this->userid)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->status)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->dueDate)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->nationCode)."'";
			$query = $query."(".$insertData.")";

			$result = $mysqli->query($query);
			// new id
			$this->reqId = $mysqli->insert_id;

			foreach ($this->items as $item) {
				$item->insert($this->userid, $this->supType);
			}

		} else {
			$query = "UPDATE requestAddInfo SET ";
			$updateData="`userid` = '".$mysqli->real_escape_string($this->userid)."', ";
			$updateData = $updateData."`status` = '".$mysqli->real_escape_string($this->status)."', ";
			$updateData = $updateData."`dueDate` = '".$mysqli->real_escape_string($this->dueDate)."', ";
			$updateData = $updateData."`nationCode` = ".$mysqli->real_escape_string($this->nationCode)." ";
			$query = $query.$updateData." WHERE reqId = ".$mysqli->real_escape_string($this->reqId);

			$result = $mysqli->query($query);

			foreach ($this->items as $item) {
				$item->update($this->reqId);
			}
		}
	}

	function Delete() {
		global $mysqli;

		if ($this->reqId > -1) {
			$query = "DELETE FROM requestAddInfo WHERE reqId = ".$mysqli->real_escape_string($this->reqId);
			$result = $mysqli->query($query);
		}
	} 
}
?>
