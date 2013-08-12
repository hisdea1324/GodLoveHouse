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

	public function __set($name,$value) { 
		$this->record[$name] = $value; 
	}
	
	public function __get($name) {
		switch ($name) {
			case "explain":
				return str_replace(chr(13), "<br>", $this->record['explain']);
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
		return isset($this->record[$name]); 
    }

	#  class initialize
	# ***********************************************
	function __construct() {
		$this->record['roomId'] = -1;
		$this->record['houseId'] = -1;
		$this->record['roomName'] = null;
		$this->record['limit'] = 0;
		$this->record['explain'] = null;
		$this->record['network'] = null;
		$this->record['kitchen'] = null;
		$this->record['laundary'] = null;
		$this->record['fee'] = 0;
		$this->record['imageId1'] = -1;
		$this->record['imageId2'] = -1;
		$this->record['imageId3'] = -1;
		$this->record['imageId4'] = -1;

		$this->image = array("ex_01.gif","ex_01.gif","ex_01.gif","ex_01.gif");
	} 

	function Open($roomId) {
		global $mysqli;

		$column = array();
		/* create a prepared statement */
		if ($stmt = $mysqli->prepare("SELECT * from room WHERE roomId = ?")) {

			/* bind parameters for markers */
			$stmt->bind_param("i", $roomId);

			/* execute query */
			$stmt->execute();
			
			$metaResults = $stmt->result_metadata();
			$fields = $metaResults->fetch_fields();
			$statementParams='';
			
			//build the bind_results statement dynamically so I can get the results in an array
			foreach ($fields as $field) {
				if (empty($statementParams)) {
					$statementParams.="\$column['".$field->name."']";
				} else {
					$statementParams.=", \$column['".$field->name."']";
				}
			}

			$statment = "\$stmt->bind_result($statementParams);";
			eval($statment);
			
			while($stmt->fetch()){
				//Now the data is contained in the assoc array $column. Useful if you need to do a foreach, or 
				//if your lazy and didn't want to write out each param to bind.
				$this->record = $column;
			}
			
			/* close statement */
			$stmt->close();


			if ($this->record['imageId1'] > 0) {
				$query = "SELECT name FROM attachFile WHERE id = '".$this->record['imageId1']."'";
				if ($result = $mysqli->query($query)) {
				    /* Get field information for all columns */
				    while ($finfo = $result->fetch_field()) {
				    	$this->image[0] = $finfo->name;
				    }
				    $result->close();
				}
			} 
			if ($this->record['imageId2'] > 0) {
				$query = "SELECT name FROM attachFile WHERE id = '".$this->record['imageId2']."'";
				if ($result = $mysqli->query($query)) {
				    /* Get field information for all columns */
				    while ($finfo = $result->fetch_field()) {
				    	$this->image[1] = $finfo->name;
				    }
				    $result->close();
				}
			} 
			if ($this->record['imageId3'] > 0) {
				$query = "SELECT name FROM attachFile WHERE id = '".$this->record['imageId3']."'";
				if ($result = $mysqli->query($query)) {
				    /* Get field information for all columns */
				    while ($finfo = $result->fetch_field()) {
				    	$this->image[2] = $finfo->name;
				    }
				    $result->close();
				}
			} 
			if ($this->record['imageId4'] > 0) {
				$query = "SELECT name FROM attachFile WHERE id = '".$this->record['imageId4']."'";
				if ($result = $mysqli->query($query)) {
				    /* Get field information for all columns */
				    while ($finfo = $result->fetch_field()) {
				    	$this->image[3] = $finfo->name;
				    }
				    $result->close();
				}
			} 
		}
		
	} 

	function Update() {
		global $mysqli;

		if (($this->record['roomId'] == -1)) {
			$query = "INSERT INTO room (`houseId`, `roomName`, `limit`, `explain`, ";
			$query = $query."`network`, `kitchen`, `laundary`, `fee`, ";
			$query = $query."`imageId1`, `imageId2`, `imageId3`, `imageId4`) VALUES ";
			$query = $query."(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("isissssiiiii", 
				$this->record['houseId'], 
				$this->record['roomName'], 
				$this->record['limit'], 
				$this->record['explain'], 
				$this->record['network'], 
				$this->record['kitchen'], 
				$this->record['laundary'], 
				$this->record['fee'], 
				$this->record['imageId1'], 
				$this->record['imageId2'], 
				$this->record['imageId3'], 
				$this->record['imageId4']);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
			
			$this->record['roomId'] = $mysqli->insert_id;
			
			$query = "UPDATE house SET roomCount = roomCount + 1 WHERE houseId = '".$this->record['houseId']."'";
			$mysqli->real_query($query);
			
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
				$this->record['houseId'], 
				$this->record['roomName'], 
				$this->record['limit'], 
				$this->record['explain'], 
				$this->record['network'], 
				$this->record['kitchen'], 
				$this->record['laundary'], 
				$this->record['fee'], 
				$this->record['imageId1'], 
				$this->record['imageId2'], 
				$this->record['imageId3'], 
				$this->record['imageId4'], 
				$this->record['roomId']);
				
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
		}
	} 

	function Delete() {
		global $mysqli;

		if ($this->record['roomId'] > -1) {
			$stmt = $mysqli->prepare("DELETE FROM room WHERE roomId = ?");
			$stmt->bind_param("i", $this->record['roomId']);
			$stmt->execute();
			$stmt->close();
			
			$query = "UPDATE house SET roomCount = roomCount - 1 WHERE houseId = '".$this->record['houseId']."'";
			$mysqli->real_query($query);
		}
	} 
	
	function showFee() {
		if ($this->record['fee'] > 0) {
			return priceFormat($this->record['fee'], 1)." / 일";
		} else {
			return "무료";
		} 
	}
} 
?>

