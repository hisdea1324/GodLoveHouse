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
				return str_replace("\n", "<br>", $this->record[$name]);
			case 'familycount':
				return count($this->family);
			case 'family':
				return $this->family;
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
    	if ($value > -1 && $value != "") {
    		$this->Open($value);
    	}
	}

    private function initialize() {
		$this->userid = "";
		$this->missionName = "";
		$this->church = "";
		$this->churchContact = "--";
		$this->ngo = "";
		$this->ngoContact = "--";
		$this->nationCode = "";
		$this->nation = "";
		$this->accountNo = "";
		$this->bank = "";
		$this->accountName = "";
		$this->homepage = "";
		$this->manager = "";
		$this->managerContact = "--";
		$this->managerEmail = "";
		$this->memo = "";
		$this->prayList = "";
		$this->fileImage = "noimg.gif";
		$this->imageId = 0;
		$this->approval = 0;
		$this->flagFamily = "";
	}

	function Open($userid) {
		global $mysqli;

		$query = "SELECT A.*, B.name AS nation FROM missionary A, code B ";
		$query.= "WHERE A.userid = '".$mysqli->real_escape_string($userid)."' AND A.nationcode = B.code";

		$result = $mysqli->query($query);
		if (!$result) return;

		while ($row = $result->fetch_assoc()) {
			$this->userid = $row['userid'];
			$this->missionName = $row['missionname'];
			$this->church = $row['church'];
			$this->churchContact = $row['churchcontact'];
			$this->ngo = $row['ngo'];
			$this->ngoContact = $row['ngocontact'];
			$this->nationCode = $row['nationcode'];
			$this->nation = $row['nation'];
			$this->accountNo = $row['accountno'];
			$this->bank = $row['bank'];
			$this->accountName = $row['accountname'];
			$this->homepage = $row['homepage'];
			$this->manager = $row['manager'];
			$this->managerContact = $row['managercontact'];
			$this->managerEmail = $row['manageremail'];
			$this->memo = $row['memo'];
			$this->prayList = $row['praylist'];
			$this->imageId = $row['imageid'];
			$this->approval = $row['approval'];
			$this->flagFamily = $row['flagfamily'];
		}
	    $result->close();

		if (isset($this->imageid) && $this->imageid > 0) {
			$query = "SELECT `name` FROM attachFile WHERE `id` = '" . $mysqli->real_escape_string($this->imageid) . "'";
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

		if ($this->userid == "") {
			$values = "'".$mysqli->real_escape_string($this->userid)."'";
			$values .= ", '".$mysqli->real_escape_string($this->missionname)."'";
			$values .= ", '".$mysqli->real_escape_string($this->church)."'";
			$values .= ", '".$mysqli->real_escape_string($this->churchcontact)."'";
			$values .= ", '".$mysqli->real_escape_string($this->ngo)."'";
			$values .= ", '".$mysqli->real_escape_string($this->ngocontact)."'";
			$values .= ", '".$mysqli->real_escape_string($this->nationcode)."'";
			$values .= ", '".$mysqli->real_escape_string($this->accountno)."'";
			$values .= ", '".$mysqli->real_escape_string($this->bank)."'";
			$values .= ", '".$mysqli->real_escape_string($this->accountname)."'";
			$values .= ", '".$mysqli->real_escape_string($this->homepage)."'";
			$values .= ", '".$mysqli->real_escape_string($this->manager)."'";
			$values .= ", '".$mysqli->real_escape_string($this->managercontact)."'";
			$values .= ", '".$mysqli->real_escape_string($this->manageremail)."'";
			$values .= ", '".$mysqli->real_escape_string($this->memo)."'";
			$values .= ", '".$mysqli->real_escape_string($this->praylist)."'";
			$values .= ", '".$mysqli->real_escape_string($this->approval)."'";
			$values .= ", '".$mysqli->real_escape_string($this->imageid)."'";
			$values .= ", '".$mysqli->real_escape_string($this->flagfamily)."'";

			$query = "INSERT INTO missionary (`userid`, `missionname`, `church`, `churchcontact`, ";
			$query = $query."`ngo`, `ngocontact`, `nationcode`, `accountno`, ";
			$query = $query."`bank`, `accountname`, `homepage`, `manager`, ";	
			$query = $query."`managercontact`, `manageremail`, `memo`, `praylist`, ";
			$query = $query."`approval`, `imageid`, `flagfamily`) VALUES ";
			$query = $query."($values)";

			$result = $mysqli->query($query);
			if (!$result) {
				return false;
			}

		} else {

			$query = "UPDATE missionary SET ";
			$updateData = "`missionname` = '".$mysqli->real_escape_string($this->missionname)."', ";
			$updateData.= "`church` = ".$mysqli->real_escape_string($this->church).", ";
			$updateData.= "`churchcontact` = '".$mysqli->real_escape_string($this->churchcontact)."', ";
			$updateData.= "`ngo` = '".$mysqli->real_escape_string($this->ngo)."', ";
			$updateData.= "`ngocontact` = '".$mysqli->real_escape_string($this->ngocontact)."', ";
			$updateData.= "`nationcode` = '".$mysqli->real_escape_string($this->nationcode)."', ";
			$updateData.= "`accountno` = '".$mysqli->real_escape_string($this->accountno)."', ";
			$updateData.= "`bank` = '".$mysqli->real_escape_string($this->bank)."', ";
			$updateData.= "`accountname` = '".$mysqli->real_escape_string($this->accountname)."', ";
			$updateData.= "`homepage` = '".$mysqli->real_escape_string($this->homepage)."', ";
			$updateData.= "`manager` = '".$mysqli->real_escape_string($this->manager)."', ";
			$updateData.= "`managercontact` = '".$mysqli->real_escape_string($this->managercontact)."', ";
			$updateData.= "`manageremail` = '".$mysqli->real_escape_string($this->manageremail)."', ";
			$updateData.= "`memo` = '".$mysqli->real_escape_string($this->memo)."', ";
			$updateData.= "`praylist` = '".$mysqli->real_escape_string($this->praylist)."', ";
			$updateData.= "`approval` = '".$mysqli->real_escape_string($this->approval)."', ";
			$updateData.= "`imageid` = '".$mysqli->real_escape_string($this->imageid)."', ";
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
