<?php 
# ************************************************************
#  Object : AttachFile
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************

class AttachFile {
	protected $record = array();

	public function __set($name,$value) { 
		$name = strtolower($name);
		$this->record[$name] = $value;
	}

	public function __get($name) { 
		$name = strtolower($name);
		switch ($name) {
			case 'imageid':
				return $this->record['id'];
			default:
				return $this->record[$name];
		}
	}

	public function __isset($name) {
		$name = strtolower($name);
		return isset($this->record[$name]); 
    }

    function __construct($id = -1) {
    	$this->initializse();
    	if ($id != -1) {
    		$this->Open($id);
    	}
    }

    function initializse() {
		$this->id = -1;
		$this->userid = "";
		$this->name = "";
	}

	function Open($value) {
		global $mysqli;

		$query = "SELECT * from attachFile WHERE `id` = ".$mysqli->real_escape_string($value);

		$result = $mysqli->query($query);
		if (!$result) return;
		
		while ($row = $result->fetch_assoc()) {
			$this->id = $row['id'];
			$this->userid = $row['userid'];
			$this->name = $row['name'];
		}
		$result->close();
	}

	function Update() {
		global $mysqli;

		if ($this->id == -1) {
			$query = "INSERT INTO attachFile (`userid`, `name`) VALUES ";
			$query = $query."('".$mysqli->real_escape_string($this->userid)."', '".$mysqli->real_escape_string($this->name)."')";

			$result = $mysqli->query($query);
			if (!$result) {
				return false;
			}
			$this->id = $mysqli->insert_id;
		} else {
			$query = "UPDATE attachFile SET ";
			$updateData = "`userid` = '".$mysqli->real_escape_string($this->userid)."', ";
			$updateData.= "`name` = '".$mysqli->real_escape_string($this->name)."' ";
			$query .= $updateData." WHERE `id` = ".$this->id;

			$result = $mysqli->query($query);
			if (!$result) {
				return false;
			}
		}

		return true;
	} 

	function Delete() {
		global $mysqli;

		if ($this->id > -1) {
			$query = "DELETE FROM attachFile WHERE `id` = ".$this->id;
			$result = $mysqli->query($query);
		}
	}
} 
?>
