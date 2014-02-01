<?php 
//************************************************************
// Object : MissionaryFamily
//
// editor : Sookbun Lee 
// last update date : 2010.03.04
//************************************************************

class MissionaryFamily {
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

    function __construct($value = -1) {
		$this->initialize();
    	if ($value > -1 && $value != "") {
    		$this->Open($value);
    	}
	}
	
	function initialize() {
    	$this->id = -1;
		$this->userid = -1;
		$this->name = "";
		$this->age = 0;
		$this->sex = "";
		$this->relation = "";
	}

	function Open($id) {
		global $mysqli;

		$query = "SELECT * FROM missionary_family WHERE `id` = ".$mysqli->real_escape_string($id);

		$result = $mysqli->query($query);
		if (!$result) return;
		
		while ($row = $result->fetch_assoc()) {
	    	$this->id = $row['id'];
			$this->userid = $row['userid'];
			$this->name = $row['name'];
			$this->age = $row['age'];
			$this->sex = $row['sex'];
			$this->relation = $row['relation'];
		}
		$result->close();
	}

	function Update() {
		global $mysqli;

		if ($this->id == -1) {
			
			$values = "'".$mysqli->real_escape_string($this->userid)."'";
			$values .= ", '".$mysqli->real_escape_string($this->name)."'";
			$values .= ", '".$mysqli->real_escape_string($this->age)."'";
			$values .= ", '".$mysqli->real_escape_string($this->sex)."'";
			$values .= ", '".$mysqli->real_escape_string($this->relation)."'";

			$query = "INSERT INTO missionary_family (`userid`, `name`, `age`, `sex`, `relation` ) VALUES ";
			$query = $query."($values)";

			$result = $mysqli->query($query);
			
			if (!$result) {
				return false;
			}

			// new id
			$this->id = $mysqli->insert_id;
			
		} else {

			$query = "UPDATE missionary_family SET ";
			$updateData = "`userid` = '".$mysqli->real_escape_string($this->userid)."', ";
			$updateData.= "`name` = ".$mysqli->real_escape_string($this->name).", ";
			$updateData.= "`age` = '".$mysqli->real_escape_string($this->age)."', ";
			$updateData.= "`sex` = '".$mysqli->real_escape_string($this->sex)."', ";
			$updateData.= "`relation` = '".$mysqli->real_escape_string($this->relation)."' ";
			$query .= $updateData." WHERE `id` = '".$mysqli->real_escape_string($this->id)."'";

			$result = $mysqli->query($query);
			if (!$result) {
				return false;
			}
		}
	} 

	function Delete() {
		global $mysqli;

		if ($this->id > -1) {
			$query = "DELETE FROM missionary_family WHERE `id` = '".$mysqli->real_escape_string($this->id)."'";
			$result = $mysqli->query($query);
		}
	}
}	
?>
