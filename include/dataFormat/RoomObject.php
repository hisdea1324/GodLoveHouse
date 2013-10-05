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
			case "explain":
				return str_replace(chr(13), "<br>", $this->record[$name]);
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
		$this->fee = 0;
		$this->imageId1 = -1;
		$this->imageId2 = -1;
		$this->imageId3 = -1;
		$this->imageId4 = -1;

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
			$this->fee = $row['fee'];
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
			    while ($row = $result->fetch_assoc()) {
			    	$this->image[] = $row["name"];				    	
			    }
			}
		}
		
	} 

	function Update() {
		global $mysqli;

		if ($this->roomId == -1) {
			$query = "INSERT INTO room (`houseId`, `roomName`, `limit`, `explain`, ";
			$query = $query."`network`, `kitchen`, `laundary`, `fee`, ";
			$query = $query."`imageId1`, `imageId2`, `imageId3`, `imageId4`) VALUES ";
			$query = $query."(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("isissssiiiii", 
				$this->houseId, 
				$this->roomName, 
				$this->limit, 
				$this->explain, 
				$this->network, 
				$this->kitchen, 
				$this->laundary, 
				$this->fee, 
				$this->imageId1, 
				$this->imageId2, 
				$this->imageId3, 
				$this->imageId4);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
			
			$this->roomId = $mysqli->insert_id;
			
			$query = "UPDATE house SET roomCount = roomCount + 1 WHERE houseId = '".$this->houseId."'";
			$mysqli->query($query);
			
		} else {
			$query = "UPDATE room SET ";
			$updateData = "`houseId` = ?, ";
			$updateData = $updateData."`roomName` = ?, ";
			$updateData = $updateData."`limit` = ?, ";
			$updateData = $updateData."`explain` = ?, ";
			$updateData = $updateData."`network` = ?, ";
			$updateData = $updateData."`kitchen` = ?, ";
			$updateData = $updateData."`laundary` = ?, ";
			$updateData = $updateData."`fee` = ?, ";
			$updateData = $updateData."`imageId1` = ?, ";
			$updateData = $updateData."`imageId2` = ?, ";
			$updateData = $updateData."`imageId3` = ?, ";
			$updateData = $updateData."`imageId4` = ? ";
			$query = $query.$updateData." WHERE `roomId` = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			$stmt->bind_param("isissssiiiiii", 
				$this->houseId, 
				$this->roomName, 
				$this->limit, 
				$this->explain, 
				$this->network, 
				$this->kitchen, 
				$this->laundary, 
				$this->fee, 
				$this->imageId1, 
				$this->imageId2, 
				$this->imageId3, 
				$this->imageId4, 
				$this->roomId);
				
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
		}
	} 

	function Delete() {
		global $mysqli;

		if ($this->roomId > -1) {
			$stmt = $mysqli->prepare("DELETE FROM room WHERE roomId = ?");
			$stmt->bind_param("i", $this->roomId);
			$stmt->execute();
			$stmt->close();
			
			$query = "UPDATE house SET roomCount = roomCount - 1 WHERE houseId = '".$this->houseId."'";
			$mysqli->real_query($query);
		}
	} 
	
	function showFee() {
		if ($this->fee > 0) {
			return priceFormat($this->fee, 1)." / 일";
		} else {
			return "무료";
		} 
	}
} 
?>
