<?php 
# ************************************************************
#  Object : BoardGroup
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************

class BoardGroup {
	protected $record = array();


	public function __set($name, $value) { 
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

    function __construct($groupId = -1) {
		$this->initialize();
		if ($groupId != -1) {
			$this->Open($groupId);
		}
    }

    function initialize() {
		$this->groupId = -1;
		$this->managerId = "";
		$this->authReadLv = 0;
		$this->authWriteLv = 0;
		$this->authCommentLv = 0;
		$this->countList = -1;
		$this->name = "";
	}


	function Open($value) {
		global $mysqli;

		$query = "SELECT * from boardGroup WHERE `groupId` = ".$mysqli->real_escape_string($value);

		$result = $mysqli->query($query);
		if (!$result) return;
		
		while ($row = $result->fetch_assoc()) {
			$this->groupId = $row['groupId'];
			$this->managerId = $row['managerId'];
			$this->authReadLv = $row['authReadLv'];
			$this->authWriteLv = $row['authWriteLv'];
			$this->authCommentLv = $row['authCommentLv'];
			$this->countList = $row['countList'];
			$this->name = $row['name'];
		}
		$result->close();
	}


	function Update() {
		global $mysqli;

		$resultCount = 0;
		$stmt = $mysqli->prepare("SELECT CNT(*) FROM boardGroup WHERE groupId = ?");
		$stmt->bind_param("s", $this->record['groupId']);
		$stmt->execute();
		$stmt->bind_result($resultCount);
		$stmt->close();

		if ($resultCount == 0) {
			$query = "INSERT INTO boardGroup (`groupId`, `managerId`, `authReadLv`, `authWriteLv`, ";
			$query.= "`authCommentLv`, `countList`,`name`) VALUES ";
			$query = $query."(?, ?, ?, ?, ?, ?, ?)";

			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("ssiiiis", 
				$this->record['groupId'],
				$this->record['managerId'],
				$this->record['authReadLv'],
				$this->record['authWriteLv'],
				$this->record['authCommentLv'],
				$this->record['countList'], 
				$this->record['name']);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();

			
		} else {

			$query = "UPDATE boardGroup SET ";
			$updateData = "`managerId` = ?, ";
			$updateData = "`authReadLv` = ?, ";
			$updateData = "`authWriteLv` = ?, ";
			$updateData = "`authCommentLv` = ?, ";
			$updateData = "`countList` = ?, ";
			$updateData = "`name` = ?, ";
			$query .= $updateData." WHERE `groupId` = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			$stmt->bind_param("ssiiiis", 
				$this->record['groupId'],
				$this->record['managerId'],
				$this->record['authReadLv'],
				$this->record['authWriteLv'],
				$this->record['authCommentLv'],
				$this->record['countList'], 
				$this->record['name'],
				$this->record['groupId']);
				
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
		}
	} 

	function Delete() {
		global $mysqli;

		if ($this->groupId > -1) {
			$query = "DELETE FROM boardGroup WHERE groupId = ".$mysqli->real_escape_string($this->groupId);
			$result = $mysqli->query($query);
		}
	} 

	function AddList() {
		$this->record['countList'] = $this->record['countList'] + 1;
	}

	function WritePermission() {
		if (!isset($_SESSION["userLv"]) || $_SESSION["userLv"] == "") return false;

		if ($this->authWriteLv <= $_SESSION["userLv"]) {
			return true;
		} else {
			return false;
		} 
	} 

	function ReadPermission() {
		if (!isset($_SESSION["userLv"]) || $_SESSION["userLv"] == "") return false;

		if ($this->authReadLv <= $_SESSION["userLv"]) {
			return true;
		} else {
			return false;
		} 
	} 

	function CommentPermission() {
		if (!isset($_SESSION["userLv"]) || $_SESSION["userLv"] == "") return false;

		if ($this->authCommentLv <= $_SESSION["userLv"]) {
			return true;
		} else {
			return false;
		} 
	} 
}
?>
