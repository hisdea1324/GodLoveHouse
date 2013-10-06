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

		$column = array();
		/* create a prepared statement */
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

		if ($this->record['regId'] == -1) {
			$query = "INSERT INTO requestInfo (`title`, `explain`, `supportType`, `imageId`) VALUES ";
			$query = $query."(?, ?, ?, ?)";

			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("sssi", 
				$this->record['title'], 
				$this->record['explain'],
				$this->record['supportType'], 
				$this->record['imageId']);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();

			$this->record['regId'] = $mysqli->insert_id;
		} else {

			$query = "UPDATE requestInfo SET ";
			$updateData = "`title` = ?, ";
			$updateData = "`explain` = ?, ";
			$updateData.= "`supportType` = ?, ";
			$updateData.= "`imageId` = ? ";
			$query .= $updateData." WHERE `regId` = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			$stmt->bind_param("sssii", 
				$this->record['title'], 
				$this->record['explain'], 
				$this->record['supportType'], 
				$this->record['imageId'],
				$this->record['regId']);
				
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
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
