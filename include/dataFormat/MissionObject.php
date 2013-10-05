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
		return $this->record[$name];
	}

	public function __isset($name) {
		$name = strtolower($name);
		return isset($this->record[$name]); 
    }

    function __construct($value = -1) {
    	if ($value == -1) {
    		$this->initialize();
    	} else {
    		$this->Open($value);
    	}
	}

    private function initialize() {
		$this->userid = "";
		$this->missionname = "";
		$this->church = "";
		$this->churchcontact = "";
		$this->ngo = "";
		$this->ngocontact = "";
		$this->nationcode = "";
		$this->nation = "";
		$this->accountno = "";
		$this->bank = "";
		$this->accountname = "";
		$this->homepage = "";
		$this->manager = "";
		$this->managercontact = "";
		$this->manageremail = "";
		$this->memo = "";
		$this->praylist = "";
		$this->familycount = "";
		$this->fileimage = "noimg.gif";
		$this->imageid = 0;
		$this->approval = 0;
		$this->flagfamily = "";
	}

	function Open($userid) {
		global $mysqli;

		$query = "SELECT A.*, B.name AS nation FROM missionary A, code B ";
		$query.= "WHERE A.userid = '".$mysqli->real_escape_string($userid)."' AND A.nationcode = B.code";
		echo $query;

		$result = $mysqli->query($query);
		if (!$result) return;

		while ($row = $result->fetch_assoc()) {
			$this->userid = $row['userid'];
			$this->missionname = $row['missionname'];
			$this->church = $row['church'];
			$this->churchcontact = $row['churchcontact'];
			$this->ngo = $row['ngo'];
			$this->ngocontact = $row['ngocontact'];
			$this->nationcode = $row['nationcode'];
			$this->nation = $row['nation'];
			$this->accountno = $row['accountno'];
			$this->bank = $row['bank'];
			$this->accountname = $row['accountname'];
			$this->homepage = $row['homepage'];
			$this->manager = $row['manager'];
			$this->managercontact = $row['managercontact'];
			$this->manageremail = $row['manageremail'];
			$this->memo = $row['memo'];
			$this->praylist = $row['praylist'];
			$this->familycount = $row['familycount'];
			$this->imageid = $row['imageid'];
			$this->approval = $row['approval'];
			$this->flagfamily = $row['flagfamily'];
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

		if ($this->userId == "") {
			$query = "INSERT INTO missionary (`userid`, `missionname`, `church`, `churchcontact`, ";
			$query = $query."`ngo`, `ngocontact`, `nationcode`, `accountno`, ";
			$query = $query."`bank`, `accountname`, `homepage`, `manager`, ";	
			$query = $query."`managercontact`, `manageremail`, `memo`, `praylist`, ";
			$query = $query."`approval`, `imageid`, `flagfamily`) VALUES ";
			$query = $query."(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("ssssssssssssssssiss", 
				$this->userid, 
				$this->missionname, 
				$this->church, 
				$this->churchcontact, 
				$this->ngo, 
				$this->ngocontact, 
				$this->nationcode, 
				$this->accountno, 
				$this->bank, 
				$this->accountname, 
				$this->homepage, 
				$this->manager,
				$this->managercontact,
				$this->manageremail,
				$this->memo,
				$this->praylist,
				$this->approval,
				$this->imageid,
				$this->flagfamily);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
			
		} else {

			$query = "UPDATE missionary SET ";
			$updateData = "`missionname` = ?, ";
			$updateData.= "`church` = ?, ";
			$updateData.= "`churchcontact` = ?, ";
			$updateData.= "`ngo` = ?, ";
			$updateData.= "`ngocontact` = ?, ";
			$updateData.= "`nationcode` = ?, ";
			$updateData.= "`accountno` = ?, ";
			$updateData.= "`bank` = ?, ";
			$updateData.= "`accountname` = ?, ";
			$updateData.= "`homepage` = ?, ";
			$updateData.= "`manager` = ?, ";
			$updateData.= "`managercontact` = ?, ";
			$updateData.= "`manageremail` = ?, ";
			$updateData.= "`memo` = ?, ";
			$updateData.= "`praylist` = ?, ";
			$updateData.= "`approval` = ?, ";
			$updateData.= "`imageid` = ?, ";
			$updateData.= "`flagfamily` = ? ";
			$query .= $updateData." WHERE `userid` = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			$stmt->bind_param("ssssssssssssssssiss", 
				$this->missionname, 
				$this->church, 
				$this->churchcontact, 
				$this->ngo, 
				$this->ngocontact, 
				$this->nationcode, 
				$this->accountno, 
				$this->bank, 
				$this->accountname, 
				$this->homepage, 
				$this->manager,
				$this->managercontact,
				$this->manageremail,
				$this->memo,
				$this->praylist,
				$this->approval,
				$this->imageid,
				$this->flagfamily);
				$this->userid;
				
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
		}
	} 

	function Delete() {
		global $mysqli;

		if ($this->userid > -1) {
			$query = "DELETE FROM missionary WHERE `userid` = '".$mysqli->real_escape_string($this->userid)."'";
			$result = $mysqli->query($query);
		}
	} 

	function GetFamilyPrayCount($userId) {
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
