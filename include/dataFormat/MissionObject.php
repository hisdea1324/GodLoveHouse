<?php 
# ************************************************************
#  Object : MissionObject
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************

class MissionObject {
	protected $record = array();
	protected $family = array();
	protected $isNew = true;

	public function __set($name, $value) { 
		$name = strtolower($name);
		$this->record[$name] = $value; 
	}

	public function __get($name) { 
		$name = strtolower($name);
		switch ($name) {
			case 'image':
			case 'fileimage':
				return "/upload/mission_pic/".$this->record['fileimage'];
			case 'memo':
			case 'praylist':
				return $this->record[$name];
			case 'familycount':
				return count($this->family);
			case 'family':
				return $this->family;

			case "attachfilelink":
				global $mysqli;
				$query = "SELECT name FROM attachFile WHERE id = {$this->record['attachfile']}";
				$result = $mysqli->query($query);
				if ($result) {
					$row = $result->fetch_assoc();
					return "<a href=\"/upload/mission/{$row['name']}\" target=\"_blank\">{$row['name']}</a>";
				} else {
					return "";
				}
			case "attachfilename":
				global $mysqli;
				$query = "SELECT name FROM attachFile WHERE id = {$this->record['attachfile']}";
				$result = $mysqli->query($query);
				if ($result) {
					$row = $result->fetch_assoc();
					return $row['name'];
				} else {
					return "";
				}
			default: 
				return $this->record[$name];
		}
	}

	public function __isset($name) {
		$name = strtolower($name);
		return isset($this->record[$name]); 
    }

    function __construct($value = -1) {
		$this->initialize();
		$this->isNew = true;
    	if ($value > -1 && $value != "") {
    		$this->Open($value);
    	}
	}

    private function initialize() {
		$this->userid = "";
		$this->BirthYear = "";
		$this->SentYear = "";
		$this->missionName = "";
		$this->church = "";
		$this->churchContact = "--";
		$this->nationCode = "";
		$this->nation = "";
		$this->accountNo = "";
		$this->bank = "";
		$this->accountName = "";
		$this->managerContact = "--";
		$this->memo = "";
		$this->prayList = "";
		$this->fileImage = "noimg.gif";
		$this->approval = 0;
		$this->flagFamily = "";
		$this->attachFile = "";
	}

	function Open($userid) {
		global $mysqli;

		$query = "SELECT A.*, B.name AS nation FROM missionary A, code B ";
		$query.= "WHERE A.userid = '".$mysqli->real_escape_string($userid)."' AND A.nationcode = B.code";

		$result = $mysqli->query($query);
		if (!$result) return;

		$this->userid = $userid;
		while ($row = $result->fetch_assoc()) {
			$this->BirthYear = $row['birthyear'];
			$this->SentYear = $row['sentyear'];
			$this->missionName = $row['missionname'];
			$this->church = $row['church'];
			$this->churchContact = $row['churchcontact'];
			$this->nationCode = $row['nationcode'];
			$this->nation = $row['nation'];
			$this->accountNo = $row['accountno'];
			$this->bank = $row['bank'];
			$this->accountName = $row['accountname'];
			$this->managerContact = $row['managercontact'];
			$this->memo = $row['memo'];
			$this->prayList = $row['praylist'];
			$this->approval = $row['approval'];
			$this->flagFamily = $row['flagfamily'];
			$this->attachFile = $row['attachFile'];
			$this->isNew = false;
		}
	    $result->close();

		if (isset($this->attachFile) && $this->attachFile > 0) {
			$query = "SELECT `name` FROM attachFile WHERE `id` = '" . $mysqli->real_escape_string($this->attachFile) . "'";
			if ($result = $mysqli->query($query)) {
			    /* Get field information for all columns */
			    while ($row = $result->fetch_assoc()) {
			    	$this->fileimage = $row['name'];
			    }
			    $result->close();
			}
		}
		
		//나중에 확인 해볼 것 
		if (isset($this->familycount) && $this->familycount > 0) {
			$query = "SELECT `id` FROM missionary_family WHERE `userid` = '".$mysqli->real_escape_string($this->userid)."'";
			if ($result = $mysqli->query($query)) {
			    while ($row = $result->fetch_assoc()) {
					$this->family[] = new MissionaryFamily($row['id']);;
				}
			    $result->close();
			}
		}		
	}

	function Update() {
		global $mysqli;

		if ($this->isNew) {
			$values = "'".$mysqli->real_escape_string($this->userid)."'";
			$values .= ", '".$mysqli->real_escape_string($this->sentyear)."'";
			$values .= ", '".$mysqli->real_escape_string($this->birthyear)."'";
			$values .= ", '".$mysqli->real_escape_string($this->missionname)."'";
			$values .= ", '".$mysqli->real_escape_string($this->church)."'";
			$values .= ", '".$mysqli->real_escape_string($this->churchcontact)."'";
			$values .= ", '".$mysqli->real_escape_string($this->nationcode)."'";
			$values .= ", '".$mysqli->real_escape_string($this->accountno)."'";
			$values .= ", '".$mysqli->real_escape_string($this->bank)."'";
			$values .= ", '".$mysqli->real_escape_string($this->accountname)."'";
			$values .= ", '".$mysqli->real_escape_string($this->managercontact)."'";
			$values .= ", '".$mysqli->real_escape_string($this->memo)."'";
			$values .= ", '".$mysqli->real_escape_string($this->praylist)."'";
			$values .= ", '".$mysqli->real_escape_string($this->approval)."'";
			$values .= ", '".$mysqli->real_escape_string($this->attachFile)."'";
			$values .= ", '".$mysqli->real_escape_string($this->flagfamily)."'";

			$query = "INSERT INTO missionary (`userid`, `sentyear`, `birthyear`, `missionname`, `church`, `churchcontact`, ";
			$query = $query."`nationcode`, `accountno`, ";
			$query = $query."`bank`, `accountname`, ";	
			$query = $query."`managercontact`, `memo`, `praylist`, ";
			$query = $query."`approval`, `attachFile`, `flagfamily`) VALUES ";
			$query = $query."($values)";

			$result = $mysqli->query($query);
			if (!$result) {
				return false;
			}

		} else {

			$query = "UPDATE missionary SET ";
			$updateData = "`sentyear` = '".$mysqli->real_escape_string($this->sentyear)."', ";
			$updateData.= "`birthyear` = '".$mysqli->real_escape_string($this->birthyear)."', ";
			$updateData.= "`missionname` = '".$mysqli->real_escape_string($this->missionname)."', ";
			$updateData.= "`church` = '".$mysqli->real_escape_string($this->church)."', ";
			$updateData.= "`churchcontact` = '".$mysqli->real_escape_string($this->churchcontact)."', ";
			$updateData.= "`nationcode` = '".$mysqli->real_escape_string($this->nationcode)."', ";
			$updateData.= "`accountno` = '".$mysqli->real_escape_string($this->accountno)."', ";
			$updateData.= "`bank` = '".$mysqli->real_escape_string($this->bank)."', ";
			$updateData.= "`accountname` = '".$mysqli->real_escape_string($this->accountname)."', ";
			$updateData.= "`managercontact` = '".$mysqli->real_escape_string($this->managercontact)."', ";
			$updateData.= "`memo` = '".$mysqli->real_escape_string($this->memo)."', ";
			$updateData.= "`praylist` = '".$mysqli->real_escape_string($this->praylist)."', ";
			$updateData.= "`approval` = '".$mysqli->real_escape_string($this->approval)."', ";
			$updateData.= "`attachFile` = '".$mysqli->real_escape_string($this->attachFile)."', ";
			$updateData.= "`flagfamily` = '".$mysqli->real_escape_string($this->flagfamily)."' ";
			$query .= $updateData." WHERE `userid` = '".$mysqli->real_escape_string($this->userid)."'";

			$result = $mysqli->query($query);
			if (!$result) {
				return false;
			}
		}
	} 

	function Delete() {
		global $mysqli;

		if ($this->userid > -1) {
			$query = "DELETE FROM missionary WHERE `userid` = '".$mysqli->real_escape_string($this->userid)."'";
			$result = $mysqli->query($query);
		}
	} 

	function GetFamilyPrayCount($userid) {
		global $mysqli;
		$coust_array = array(0, 0);

		$query = "SELECT ";
		$query.= " SUM(CASE WHEN `familytype` = 'F0001' THEN 1 ELSE 0 END) AS familypraycnt, ";
		$query.= " SUM(CASE WHEN `familytype` = 'F0002' THEN 1 ELSE 0 END) AS familyfarecnt ";
		$query.= " FROM family WHERE `userid` = '".$mysqli->real_escape_string($this->userid)."'";
		$result = $mysqli->query($query);

		while ($row = $result->fetch_array()) {
			$coust_array[0] = $row['familypraycnt'];
			$coust_array[1] = $row['familyfarecnt'];
		}

		return $resultCount;
	}
}

?>
