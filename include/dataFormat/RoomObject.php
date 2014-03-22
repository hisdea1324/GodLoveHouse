<?php 
# ************************************************************
#  Object : HouseObject
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************
class RoomObject {
	protected $record = array();
	protected $image = array();

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
			case "image1":
				if (strlen($this->image[0]) > 0) {
					return "/upload/room/".$this->image[0];
				}
				return "/upload/room/ex_01.gif";
			case "image2":
				if (strlen($this->image[1]) > 0) {
					return "/upload/room/".$this->image[1];
				}
				return "/upload/room/ex_01.gif";
			case "image3":
				if (strlen($this->image[2]) > 0) {
					return "/upload/room/".$this->image[2];
				}
				return "/upload/room/ex_01.gif";
			case "image4":
				if (strlen($this->image[3]) > 0) {
					return "/upload/room/".$this->image[3];
				}
				return "/upload/room/ex_01.gif";
			default:
				return $this->record[$name];
		}
	}
	
	public function __isset($name) {
		$name = strtolower($name);
		switch ($name) {
			default:
				return isset($this->record[$name]); 
				break;
		}
    }

	#  class initialize
	# ***********************************************
	function __construct($roomId = -1) {
		$this->initialize();
		if ($roomId > -1) {
			$this->Open($roomId);
		}
	}
	
	private function initialize() {
		$this->roomid = -1;
		$this->houseid = -1;
		$this->roomName = null;
		$this->limit = 0;
		$this->explain = null;
		$this->network = null;
		$this->kitchen = null;
		$this->laundary = null;
		$this->bed = null;
		$this->fee = 0;
		$this->imageId1 = -1;
		$this->imageId2 = -1;
		$this->imageId3 = -1;
		$this->imageId4 = -1;
		$this->hide = 0;

		$this->image = array("ex_01.gif","ex_01.gif","ex_01.gif","ex_01.gif");
	} 

	function Open($roomId) {
		global $mysqli;

		$query = "SELECT * from room WHERE roomId = ".$mysqli->real_escape_string($roomId);
		$result = $mysqli->query($query);
		if (!$result) return; 

		while ($row = $result->fetch_assoc()) {
			$this->roomid = $row['roomId'];
			$this->houseid = $row['houseId'];
			$this->roomName = $row['roomName'];
			$this->limit = $row['limit'];
			$this->explain = $row['explain'];
			$this->network = $row['network'];
			$this->kitchen = $row['kitchen'];
			$this->laundary = $row['laundary'];
			$this->bed = $row['bed'];
			$this->fee = $row['fee'];
			$this->hide = $row['hide'];
			$this->imageId1 = $row['imageId1'];
			$this->imageId2 = $row['imageId2'];
			$this->imageId3 = $row['imageId3'];
			$this->imageId4 = $row['imageId4'];
		}

		$result->close();

		$imgArray = array();
		if ($this->imageId1 > 0) {
			$imgArray[] = $this->imageId1;
		}
		if ($this->imageId2 > 0) {
			$imgArray[] = $this->imageId2;
		}
		if ($this->imageId3 > 0) {
			$imgArray[] = $this->imageId3;
		}
		if ($this->imageId4 > 0) {
			$imgArray[] = $this->imageId4;
		}
		
		if (count($imgArray) > 0) {
			$query = "SELECT name FROM attachFile WHERE id IN (".join($imgArray, ",").")";
			if ($result = $mysqli->query($query)) {
			    /* Get field information for all columns */
			    $cnt = 0;
			    while ($row = $result->fetch_assoc()) {
			    	$this->image[$cnt] = $row["name"];				    	
			    	$cnt++;
			    }
			}
		}
		
	} 

	function Update() {
		global $mysqli;

		if ($this->roomId == -1) {
			$values = $mysqli->real_escape_string($this->houseId);
			$values .= ", '".$mysqli->real_escape_string($this->roomName)."'";
			$values .= ", ".$mysqli->real_escape_string($this->limit);
			$values .= ", '".$mysqli->real_escape_string($this->explain)."'";
			$values .= ", '".$mysqli->real_escape_string($this->network)."'";
			$values .= ", '".$mysqli->real_escape_string($this->kitchen)."'";
			$values .= ", '".$mysqli->real_escape_string($this->laundary)."'";
			$values .= ", '".$mysqli->real_escape_string($this->bed)."'";
			$values .= ", ".$mysqli->real_escape_string($this->fee);
			$values .= ", ".$mysqli->real_escape_string($this->imageId1);
			$values .= ", ".$mysqli->real_escape_string($this->imageId2);
			$values .= ", ".$mysqli->real_escape_string($this->imageId3);
			$values .= ", ".$mysqli->real_escape_string($this->imageId4);
			$values .= ", ".$mysqli->real_escape_string($this->hide);

			$query = "INSERT INTO room (`houseId`, `roomName`, `limit`, `explain`, ";
			$query = $query."`network`, `kitchen`, `laundary`, `bed`, `fee`, ";
			$query = $query."`imageId1`, `imageId2`, `imageId3`, `imageId4`, `hide`) VALUES ";
			$query = $query."($values)";

			$result = $mysqli->query($query);
			if (!$result) {
				return false;
			}

			$this->roomId = $mysqli->insert_id;
			
			$query = "UPDATE house SET roomCount = (SELECT COUNT(roomId) FROM room WHERE houseId = '{$this->houseId}') WHERE houseId = '{$this->houseId}'";
			$mysqli->query($query);
			
		} else {
			$query = "UPDATE room SET ";
			$updateData = "`houseId` = ".$mysqli->real_escape_string($this->houseId).", ";
			$updateData = $updateData."`roomName` = '".$mysqli->real_escape_string($this->roomName)."', ";
			$updateData = $updateData."`limit` = ".$mysqli->real_escape_string($this->limit).", ";
			$updateData = $updateData."`explain` = '".$mysqli->real_escape_string($this->explain)."', ";
			$updateData = $updateData."`network` = '".$mysqli->real_escape_string($this->network)."', ";
			$updateData = $updateData."`kitchen` = '".$mysqli->real_escape_string($this->kitchen)."', ";
			$updateData = $updateData."`laundary` = '".$mysqli->real_escape_string($this->laundary)."', ";
			$updateData = $updateData."`bed` = '".$mysqli->real_escape_string($this->bed)."', ";
			$updateData = $updateData."`fee` = ".$mysqli->real_escape_string($this->fee).", ";
			$updateData = $updateData."`hide` = ".$mysqli->real_escape_string($this->hide).", ";
			$updateData = $updateData."`imageId1` = ".$mysqli->real_escape_string($this->imageId1).", ";
			$updateData = $updateData."`imageId2` = ".$mysqli->real_escape_string($this->imageId2).", ";
			$updateData = $updateData."`imageId3` = ".$mysqli->real_escape_string($this->imageId3).", ";
			$updateData = $updateData."`imageId4` = ".$mysqli->real_escape_string($this->imageId4)." ";
			$query = $query.$updateData." WHERE `roomId` = ".$mysqli->real_escape_string($this->roomId);

			$result = $mysqli->query($query);
			if (!$result) {
				return false;
			}
		}

		$query = "UPDATE house SET roomCount = (SELECT COUNT(roomId) FROM room WHERE houseId = '{$this->houseId}') WHERE houseId = '{$this->houseId}'";
		$mysqli->query($query);

		return true;
	} 

	function Delete() {
		global $mysqli;

		if ($this->roomId > -1) {
			$query = "DELETE FROM room WHERE roomId = ".$mysqli->real_escape_string($this->roomId);
			$result = $mysqli->query($query);
			
			$query = "UPDATE house SET roomCount = roomCount - 1 WHERE houseId = '".$mysqli->real_escape_string($this->houseId)."'";
			$result = $mysqli->query($query);
		}
	} 
	
	function showFee() {
		if ($this->fee > 0) {
			return number_format($this->Fee)."원 / 일";
		} else {
			return "무료";
		} 
	}
} 
?>
