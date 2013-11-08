<?php 
# ************************************************************
#  Object : HospitalObject
# 
#  editor : Sookbun Lee 
#  last update date : 2012.02.10
# ************************************************************


class HospitalObject {
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
			case "contact1":
			case "contact2":
			case "zipcode":
				return explode("-", $this->record[$name]);
			case "explain":
				return str_replace(chr(13), "<br>", $this->record[$name]);
			case "roomCount": case "RoomCount": 
				return $this->record['roomCount'];
			case "document_link": 
				if (strlen($this->record['document']) > 0) {
					return "<a href='/upload/room/".$this->record[$name]."'>".$this->record[$name]."</a>";
				}
				return "없음"; 
			case "homepage":
				if (strlen($this->record[$name]) == 0) {
					return "없음";
				} else if (substr($this->record[$name], 0, "4") != "http") {
					return "<a href='http://".$this->record[$name]."' target='_blank'>http://".$this->record[$name]."</a>";
				} else {
					return "<a href='".$this->record[$name]."' target='_blank'>".$this->record[$name]."</a>";
				} 
			case "region":
				$c_Helper = new CodeHelper();
				return $c_Helper->getCodeName($this->record['regioncode']);
			case "status": case "StatusCode":
				$c_Helper = new CodeHelper();
				return $c_Helper->getCodeName($this->record['status']);
			case "roomlist":
				$rooms = array();
				foreach ($this->mRoom as $room) {
					$rooms[] = new RoomObject($room);
				} 
				return $rooms;
			case "image1": case "Image1";
				if (strlen($this->image[0]) > 0) {
					return "/upload/room/".$this->image[0];
				}
				return "/upload/room/ex_01.gif";
			case "image2": case "Image2":
				if (strlen($this->image[1]) > 0) {
					return "/upload/room/".$this->image[1];
				}
				return "/upload/room/ex_01.gif";
			case "image3": case "Image3":
				if (strlen($this->image[2]) > 0) {
					return "/upload/room/".$this->image[2];
				}
				return "/upload/room/ex_01.gif";
			case "image4": case "Image4":
				if (strlen($this->image[3]) > 0) {
					return "/upload/room/".$this->image[3];
				}
				return "/upload/room/ex_01.gif";
			default:
				if (isset($this->record[$name])) {
					return $this->record[$name];
				} else {
					return "";
				}
		}
	}
	
	public function __isset($name) {
		$name = strtolower($name);
		return isset($this->record[$name]);
    }

	function __construct($hospitalId = -1) {
		$this->initialize();
		if ($hospitalId > -1) {
			$this->Open($hospitalId);
		}
	}
	
	private function initialize() {

		$this->hospitalId = -1;
		$this->hospitalName = null;
		$this->assocname = null;
		$this->address1 = null;
		$this->address2 = null;
		$this->zipcode = "-";
		$this->regioncode = "S0000";
		$this->explain = null;
		$this->userid = null;
		$this->manager1 = null;
		$this->contact1 = "--";
		$this->manager2 = null;
		$this->contact2 = "--";
		$this->price = 0;
		$this->personlimit = 0;
		$this->roomlimit = 0;
		$this->housename = null;
		$this->homepage = null;
		$this->documentid = -1;
		$this->document = null;
		$this->status = "S2001";
		$this->regdate = "";
		$this->imageId1 = -1;
		$this->imageId2 = -1;
		$this->imageId3 = -1;
		$this->imageId4 = -1;

		$this->image = array("ex_01.gif","ex_01.gif","ex_01.gif","ex_01.gif");
	}

	function Open($value) {
		global $mysqli;

		$query = "SELECT * from hospital WHERE hospitalId = '".$mysqli->real_escape_string($value)."'";
		$result = $mysqli->query($query);
		if (!$result) return;

		while ($row = $result->fetch_assoc()) {
			$this->hospitalId = $row['hospitalId'];
			$this->hospitalName = $row['hospitalName'];
			$this->assocname = $row['assocName'];
			$this->address1 = $row['address1'];
			$this->address2 = $row['address2'];
			$this->zipcode = $row['zipcode'];
			$this->regioncode = $row['regionCode'];
			$this->explain = $row['explain'];
			$this->userid = $row['userid'];
			$this->manager1 = $row['manager1'];
			$this->contact1 = $row['contact1'];
			$this->manager2 = $row['manager2'];
			$this->contact2 = $row['contact2'];
			$this->price = $row['price'];
			$this->personlimit = $row['personLimit'];
			$this->homepage = $row['homepage'];
			$this->documentid = $row['documentId'];
			$this->document = $row['document'];
			$this->status = $row['status'];
			$this->regdate = $row['regDate'];
			$this->imageId1 = $row['imageId1'];
			$this->imageId2 = $row['imageId2'];
			$this->imageId3 = $row['imageId3'];
			$this->imageId4 = $row['imageId4'];

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
				$cnt = 0;
				if ($result = $mysqli->query($query)) {
				    /* Get field information for all columns */
				    while ($finfo = $result->fetch_array()) {
				    	$this->image[$cnt++] = $finfo["name"];				    	
				    }
				}
			}
		}
		$result->close();
	}

	function Update() {
		global $mysqli;


		if ($this->hospitalId == -1) {
			$query = "INSERT INTO hospital (`hospitalName`, `assocName`, 'address1', 'address2', ";
			$query.= "'zipcode', 'regionCode', 'explain', 'userid', 'manager1', 'manager2', 'contact1', 'contact2', ";
			$query.= "'price', 'personLimit', 'homepage',  ";
			$query.= "'documentId', 'document', 'imageId1', 'imageId2', 'imageId3', 'imageId4') VALUES ";
			$query = $query."(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("ssssssssssssiisisssss", 
				$this->hospitalName,
				$this->assocName,
				$this->address1,
				$this->address2,
				$this->zipcode,
				$this->regionCode,
				$this->explain,
				$this->userid,
				$this->manager1,
				$this->manager2,
				$this->contact1,
				$this->contact2,
				$this->price,
				$this->personLimit,
				$this->homepage,
				$this->documentId,
				$this->document,
				$this->imageId1,
				$this->imageId2,
				$this->imageId3,
				$this->imageId4);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();

			$stmt = $mysqli->prepare("SELECT MAX(hospitalId) as new_id FROM hospital WHERE hospitalName = ?");
			$stmt->bind_param("s", $this->hospitalName);
			$stmt->execute();
			$stmt->bind_result($this->hospitalId);
			$stmt->close();


			
		} else {

			$query = "UPDATE hospital SET ";
			$updateData = "`hospitalName` = ?, ";
			$updateData.= "`assocName` = ?, ";
			$updateData.= "`address1` = ?, ";
			$updateData.= "`address2` = ?, ";
			$updateData.= "`regionCode` = ?, ";
			$updateData.= "`zipcode` = ?, ";
			$updateData.= "`explain` = ?, ";
			$updateData.= "`userid` = ?, ";
			$updateData.= "`manager1` = ?, ";
			$updateData.= "`manager2` = ?, ";
			$updateData.= "`contact1` = ?, ";
			$updateData.= "`contact2` = ?, ";
			$updateData.= "`price` = ?, ";
			$updateData.= "`personLimit` = ?, ";
			$updateData.= "`homepage` = ?, ";
			$updateData.= "`status` = ?, ";
			$updateData.= "`document` = ?, ";
			$updateData.= "`documentId` = ?, ";
			$updateData.= "`imageId1` = ?, ";
			$updateData.= "`imageId2` = ?, ";
			$updateData.= "`imageId3` = ?, ";
			$updateData.= "`imageId4` = ? ";
			$query .= $updateData." WHERE `hospitalId` = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			$stmt->bind_param("ssssssssssssiisisssss", 
				$this->hospitalName,
				$this->assocName,
				$this->address1,
				$this->address2,
				$this->zipcode,
				$this->regionCode,
				$this->explain,
				$this->userid,
				$this->manager1,
				$this->manager2,
				$this->contact1,
				$this->contact2,
				$this->price,
				$this->personLimit,
				$this->homepage,
				$this->documentId,
				$this->document,
				$this->imageId1,
				$this->imageId2,
				$this->imageId3,
				$this->imageId4,
				$this->hospitalId);

				
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
		}
	} 

	function Delete() {
		global $mysqli;

		if ($this->hospitalId > -1) {
			$stmt = $mysqli->prepare("DELETE FROM hospital WHERE hospitalId = ?");
			$stmt->bind_param("s", $this->hospitalId);
			$stmt->execute();
			$stmt->close();
		}
	}

	function showContactInfo() {
		$retString = "";

		if (strlen($this->record['contact1']) > 10) {
			$retString = $this->manager1." ".$this->record['contact1'];
		} 

		if ($this->record['contact2'] > 10) {
			$retString = $retString." / ".$this->manager2." ".$this->record['contact2'];
		} 

		return $retString;
	} 

	function showFee() {
		if ($this->price > 0) {
			$retString = priceFormat($this->hospitalId, 1)." / 일";
		} else {
			$retString = "무료";
		} 
		return $retString;
	}
}	

?>