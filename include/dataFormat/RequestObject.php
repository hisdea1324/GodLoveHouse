<?php 
# ************************************************************
#  Object : RequestObject
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************

class RequestObject {
	protected $record = array();

	public function __set($name, $value) { 
		$name = strtolower($name);
		switch($name) {
			default :
				$this->record[$name] = $value;
				break;	
		}
	}


	public function __get($name) { 
		$name = strtolower($name);
		switch($name) {
			case "supporttype" : 
				switch ($this->record[$name]) {
					case "03001": default:
						return "특별후원";
					case "03002":
						return "센터후원";
					case "03003":
						return "자원봉사";
				} 
			case "fileimage" : case "image":
				if (strlen($this->record["fileimage"]) == 0) {
					$this->record["fileimage"] = "noimg.gif";
				} 
				return "/upload/support/".$this->record["fileimage"];

			default : 
				return $this->record[$name];
		}
	}


	public function __isset($name) {
		$name = strtolower($name);
		switch($name) {
			default : 
				return isset($this->record[$name]); 
		}
  }
  
  
  function __construct($idx = -1) {
		$this->initialize();
    	if ($idx > -1) {
    		$this->Open($idx);
    	}
	}

 	function initialize() {
	    $c_Helper = new CodeHelper();

		$this->requestId = -1;
		$this->title = "";
		$this->explain = "";
		$this->supportType = $c_Helper->getSupportCode(2);
		$this->regDate = -1;
		$this->imageId = -1;
		$this->fileImage = "noimg.gif";
	}


	function Open($value) {
		global $mysqli;

		$query = "SELECT `reqId`, `title`, `explain`, `supportType`, `regDate`, `imageId` FROM requestInfo WHERE `reqId` = '".$mysqli->real_escape_string($value)."'";
		$result = $mysqli->query($query);
		if (!$result) return;

		while ($row = $result->fetch_assoc()) {
			$this->requestId = $row['reqId'];
			$this->title = $row['title'];
			$this->explain = $row['explain'];
			$this->supportType = $row['supportType'];
			$this->regDate = $row['regDate'];
			$this->imageId = $row['imageId'];
		}
		$result->close();

		if ($this->imageId > 0) {
			$query = "SELECT name FROM attachFile WHERE id = '".$mysqli->real_escape_string($this->imageId)."'";
			if ($result = $mysqli->query($query)) {
				while ($row = $result->fetch_assoc()) {
					$this->fileImage = $row['name'];
				}
				$result->close();
			}
		} 
	}


	function Update() {
		global $mysqli;


		if ($this->requestId == -1) {
			# New Data
			$query = "INSERT INTO requestInfo (`title`, `explain`, `supportType`, `imageId`) VALUES ";
			$insertData="'".$this->title."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->explain)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->supportType)."', ";
			$insertData = $insertData.$mysqli->real_escape_string($this->imageId);
			$query = $query."(".$insertData.")";

			$result = $mysqli->query($query);
			// new id
			$this->requestId = $mysqli->insert_id;
		} else {
			$query = "UPDATE requestInfo SET ";
			$updateData="`title` = '".$mysqli->real_escape_string($this->title)."', ";
			$updateData = $updateData."`explain` = ".$mysqli->real_escape_string($this->explain).", ";
			$updateData = $updateData."supportType = ".$mysqli->real_escape_string($this->supportType).", ";
			$updateData = $updateData."imageId = ".$mysqli->real_escape_string($this->imageId)." ";
			$query = $query.$updateData." WHERE regId = ".$mysqli->real_escape_string($this->requestId);

			$result = $mysqli->query($query);
		} 
	}

	function Delete() {
		global $mysqli;

		if ($this->requestId > -1) {
			$query = "DELETE FROM requestInfo WHERE `reqId` = '".$mysqli->real_escape_string($this->requestId)."'";
			if ($result = $mysqli->query($query)) {
				$result->close();
				return true;
			}
		}

		return false;
	}  
}	
?>
