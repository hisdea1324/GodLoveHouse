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

	public function __set($name,$value) { 
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

    	print_r($this->record);
	}

    private function initialize() {
		$this->record['userid'] = "";
		$this->record['missionname'] = "";
		$this->record['church'] = "";
		$this->record['churchcontact'] = "";
		$this->record['ngo'] = "";
		$this->record['ngocontact'] = "";
		$this->record['nationcode'] = "";
		$this->record['nation'] = "";
		$this->record['accountno'] = "";
		$this->record['bank'] = "";
		$this->record['accountname'] = "";
		$this->record['homepage'] = "";
		$this->record['manager'] = "";
		$this->record['managercontact'] = "";
		$this->record['manageremail'] = "";
		$this->record['memo'] = "";
		$this->record['praylist'] = "";
		$this->record['familycount'] = "";
		$this->record['fileimage'] = "noimg.gif";
		$this->record['imageid'] = 0;
		$this->record['approval'] = 0;
		$this->record['flagfamily'] = "";
		$this->record['family'] = "";
	}


	function Open($userId) {
		global $mysqli;

		$column = array();
		/* create a prepared statement */
		$query = "SELECT A.*, B.name AS nation";
		$query.= " FROM missionary A, code B ";
		$query.= " WHERE A.userid = ? AND A.nationcode = B.code";

		if ($stmt = $mysqli->prepare($query)) {

			/* bind parameters for markers */
			$stmt->bind_param("s", $userId);

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

			if (isset($this->record['imageid']) && $this->record['imageid'] > 0) {
				$query = "SELECT `name` FROM attachFile WHERE `id` = '" . $this->record['imageid'] . "'";

				if ($result = $mysqli->query($query)) {
				    /* Get field information for all columns */
				    while ($finfo = $result->fetch_field()) {
				    	$this->record['fileimage'] = $finfo->name;
				    }
				    $result->close();
				}
			}
			
			//나중에 확인 해볼 것 
			$familyId = -1;
			if (isset($this->record['familycount']) && $this->record['familycount'] > 0) {
				$stmt = $mysqli->prepare("SELECT `id` FROM missionary_family WHERE `userid` = ?");
				$stmt->bind_param("s", $this->record['userid']);
				$stmt->execute();
				$stmt->bind_result($familyId);
				while ($stmt->fetch()) {
//					$mFamily->Open($familyId);
					$this->family[] = new MissionaryFamily($familyId);;
				}
   				$stmt->close();
			}
		}
	}

	function Update() {
		global $mysqli;


		if (($this->record['userId'] == "")) {
			$query = "INSERT INTO missionary (`userid`, `missionname`, `church`, `churchcontact`, ";
			$query = $query."`ngo`, `ngocontact`, `nationcode`, `accountno`, ";
			$query = $query."`bank`, `accountname`, `homepage`, `manager`, ";	
			$query = $query."`managercontact`, `manageremail`, `memo`, `praylist`, ";
			$query = $query."`approval`, `imageid`, `flagfamily`) VALUES ";
			$query = $query."(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("ssssssssssssssssiss", 
				$this->record['userid'], 
				$this->record['missionname'], 
				$this->record['church'], 
				$this->record['churchcontact'], 
				$this->record['ngo'], 
				$this->record['ngocontact'], 
				$this->record['nationcode'], 
				$this->record['accountno'], 
				$this->record['bank'], 
				$this->record['accountname'], 
				$this->record['homepage'], 
				$this->record['manager'],
				$this->record['managercontact'],
				$this->record['manageremail'],
				$this->record['memo'],
				$this->record['praylist'],
				$this->record['approval'],
				$this->record['imageid'],
				$this->record['flagfamily']);

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
				$this->record['missionname'], 
				$this->record['church'], 
				$this->record['churchcontact'], 
				$this->record['ngo'], 
				$this->record['ngocontact'], 
				$this->record['nationcode'], 
				$this->record['accountno'], 
				$this->record['bank'], 
				$this->record['accountname'], 
				$this->record['homepage'], 
				$this->record['manager'],
				$this->record['managercontact'],
				$this->record['manageremail'],
				$this->record['memo'],
				$this->record['praylist'],
				$this->record['approval'],
				$this->record['imageid'],
				$this->record['flagfamily']);
				$this->record['userid'];
				
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
		}
	} 



	function Delete() {
		global $mysqli;

		if ($this->record['userid'] > -1) {
			$stmt = $mysqli->prepare("DELETE FROM missionary WHERE `userid` = ?");
			$stmt->bind_param("s", $this->record['userid']);
			$stmt->execute();
			$stmt->close();
		}
	} 



	function GetFamilyPrayCount($userId) {
		global $mysqli;
		$coust_array = array(0, 0);

		$query = "SELECT ";
		$query.= " SUM(CASE WHEN `familytype` = 'F0001' THEN 1 ELSE 0 END) AS familypraycnt, ";
		$query.= " SUM(CASE WHEN `familytype` = 'F0002' THEN 1 ELSE 0 END) AS familyfarecnt ";
		$query.= " FROM family WHERE `userid` = '".mysql_escape_string($this->record['userid'])."'";
		$result = $mysqli->query($query);
		while ($row = $result->fetch_array()) {
			$coust_array[0] = $row['familypraycnt'];
			$coust_array[1] = $row['familyfarecnt'];
		}

		return $resultCount;
	}
}







/*

class MissionObject {
	var $familyRS;
	var $attachRS;
	var $missionRS;

	#  class member variable
	# ***********************************************
	var $m_userid;
	var $m_missionName;
	var $m_church;
	var $m_churchContact;
	var $m_ngo;
	var $m_ngoContact;
	var $m_nationCode;
	var $m_nation;
	var $m_accountNo;
	var $m_bank;
	var $m_accountName;
	var $m_homepage;
	var $m_manager;
	var $m_managerContact;
	var $m_managerEmail;
	var $m_memo;
	var $m_prayList;
	var $m_familycount;
	var $m_fileImage;
	var $m_imageId;
	var $m_approval;

	var $m_flagFamily;
	var $m_family;

	#  property getter
	# ***********************************************
	function UserID() {
		$UserID = $m_userid;
	} 

	function MissionName() {
		$MissionName = $m_missionName;
	} 

	function Church() {
		$Church = $m_church;
	} 

	function ChurchContact() {
		if ((strlen($m_churchContact)<=2)) {
			$m_churchContact="--";
		} 

		$ChurchContact = $m_churchContact;
	} 

	function Ngo() {
		$Ngo = $m_ngo;
	} 

	function NgoContact() {
		if ((strlen($m_ngoContact)<=2)) {
			$m_ngoContact="--";
		} 

		$NgoContact = $m_ngoContact;
	} 

	function Nation() {
		$Nation = $m_nation;
	} 

	function NationCode() {
		$NationCode = $m_nationCode;
	} 

	function AccountNo() {
		$AccountNo = $m_accountNo;
	} 

	function Bank() {
		$Bank = $m_bank;
	} 

	function AccountName() {
		$AccountName = $m_accountName;
	} 

	function Homepage() {
		$Homepage = $m_homepage;
	} 

	function Manager() {
		$Manager = $m_manager;
	} 

	function ManagerContact() {
		if ((strlen($m_managerContact)<=2)) {
			$m_managerContact="--";
		} 

		$ManagerContact = $m_managerContact;
	} 

	function ManagerEmail() {
		if ((strlen($m_managerEmail)<=1)) {
			$m_managerEmail="@";
		} 

		$ManagerEmail = $m_managerEmail;
	} 

	function Memo() {
		$Memo = $m_memo;
	} 

	function PrayList() {
		$PrayList = $m_prayList;
	} 

	function FamilyCount() {
		$FamilyCount = $m_familycount;
	} 

	function Family() {
		$Family = $m_family;
	} 

	function Image() {
		if ((strlen($m_fileImage)==0)) {
			$m_fileImage="noimg.gif";
		} 

		$Image="/upload/mission_pic/".$m_fileImage;
	} 

	function ImageID() {
		$ImageID = $m_imageId;
	} 

	function FlagFamily() {
		$FlagFamily = $m_flagFamily;
	} 

	function Approval() {
		if (($m_approval==0)) {
			$Approval=false;
		} else {
			$Approval=true;
		} 
	} 

	#  property setter
	# ***********************************************
	function UserID($value) {
		$m_userid = trim($value);
	} 

	function Church($value) {
		$m_church = trim($value);
	} 

	function MissionName($value) {
		$m_missionName = trim($value);
	} 

	function Ngo($value) {
		$m_ngo = trim($value);
	} 

	function NationCode($value) {
		$m_nationCode = trim($value);
	} 

	function Homepage($value) {
		$m_homepage = trim($value);
	} 

	function Manager($value) {
		$m_manager = trim($value);
	} 

	function AccountNo($value) {
		$m_accountNo = trim($value);
	} 

	function Bank($value) {
		$m_bank = trim($value);
	} 

	function AccountName($value) {
		$m_accountName = trim($value);
	} 

	function Memo($value) {
		$m_memo = trim($value);
	} 

	function PrayList($value) {
		$m_prayList = trim($value);
	} 

	function FlagFamily($value) {
		$m_flagFamily = trim($value);
	} 

	function ManagerEmail($value) {
		$m_managerEmail = trim($value);
	} 

	function ChurchContact($value) {
		$m_churchContact = trim($value);
	} 

	function NgoContact($value) {
		$m_ngoContact = trim($value);
	} 

	function ManagerContact($value) {
		$m_managerContact = trim($value);
	} 

	function Family($value) {
		$count=count($value);

		for ($i=0; $i <= $count; $i = $i+1) {
			$familyObject = $value[$i];
			$m_family[$i] = $familyObject;
		}
	} 

	function ImageID($value) {
		$m_imageId=intval($value);
	} 

	function Approval($value) {
		if ((intval($value)==1)) {
			$m_approval=1;
		} else {
			$m_approval=0;
		} 
	} 

	#  class initialize
	# ***********************************************
	function __construct() {
		$m_userid="";
		$m_missionName="";
		$m_church="";
		$m_churchContact="";
		$m_ngo="";
		$m_ngoContact="";
		$m_nationCode="";
		$m_nation="";
		$m_accountNo="";
		$m_bank="";
		$m_accountName="";
		$m_homepage="";
		$m_manager="";
		$m_managerContact="";
		$m_managerEmail="";
		$m_memo="";
		$m_prayList="";
		$m_familycount="";
		$m_fileImage="noimg.gif";
		$m_imageId=0;
		$m_approval=0;
	} 

	function __destruct() {
		$familyRS = null;
		$attachRS = null;
		$missionRS = null;
	} 

	#  class method
	# ***********************************************
	function Open($userid) {
		$query = "SELECT A.*, B.name AS nation";
		$query = $query." FROM missionary A, code B ";
		$query = $query." WHERE A.userId = '".$mssqlEscapeString[$userid]."' AND A.nationCode = B.code";
		$missionRS = $objDB->execute_query($query);

		if ((!$missionRS->eof && !$missionRS->bof)) {
			$m_userid = $userid;
			$m_missionName = $missionRS["missionName"];
			$m_church = $missionRS["church"];
			$m_churchContact = $missionRS["churchContact"];
			$m_ngo = $missionRS["ngo"];
			$m_ngoContact = $missionRS["ngoContact"];
			$m_nationCode = $missionRS["nationCode"];
			$m_nation = $missionRS["nation"];
			$m_accountNo = $missionRS["accountNo"];
			$m_bank = $missionRS["bank"];
			$m_accountName = $missionRS["accountName"];
			$m_homepage = $missionRS["homepage"];
			$m_manager = $missionRS["manager"];
			$m_managerContact = $missionRS["managerContact"];
			$m_managerEmail = $missionRS["managerEmail"];
			$m_memo = $missionRS["memo"];
			$m_prayList = $missionRS["prayList"];
			$m_familycount=getFamilyCount($m_userid);
			$m_imageId=intval($missionRS["imageId"]);
			$m_flagFamily = $missionRS["flagFamily"];
			$m_approval=intval($missionRS["approval"]);

			$query = "SELECT name FROM attachFile WHERE id = '".$m_imageId."'";
			$attachRS = $objDB->execute_query($query);
			if ((!$attachRS->eof && !$attachRS->bof)) {
				$m_fileImage = $attachRS["name"];
			} 

			$query = "SELECT id FROM missionary_family WHERE userId = '".$mssqlEscapeString[$m_userId]."'";
			$objDB->CursorLocation=3;
			$familyRS = $objDB->execute_query($query);

			$index = $familyRS->RecordCount-1;
			for ($i=0; $i <= $index; $i = $i+1) {
				$m_family = $i;				echo new MissionaryFamily();
				$m_family[$i]->$Open[$familyRS["id"]];
				$familyRS->MoveNext;
			}
		} 
	} 

	function Update() {
		$query = "SELECT * from missionary WHERE userId = '".$m_userId."'";
		$missionRS = $objDB->execute_query($query);

		if (($missionRS->eof || $missionRS->bof)) {
			$query = "INSERT INTO missionary (userId, missionName, church, churchContact, ngo, ngoContact, nationCode, accountNo, bank, accountName, homepage, manager, managerContact, managerEmail, memo, prayList, approval, imageId, flagFamily) VALUES ";
			$insertData="'".$mssqlEscapeString[$m_userid]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_missionName]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_church]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_churchContact]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_ngo]."', ";
			$insertData = $insertData."'".$m_ngoContact."', ";
			$insertData = $insertData."'".$m_nationCode."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_accountNo]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_bank]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_accountName]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_homepage]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_manager]."', ";
			$insertData = $insertData."'".$m_managerContact."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_managerEmail]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_memo]."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_prayList]."',";
			$insertData = $insertData."'".$m_approval."',";
			$insertData = $insertData."'".$m_imageId."',";
			$insertData = $insertData."'".$m_flagFamily."'";
			$query = $query."(".$insertData.") ";
			$objDB->execute_command($query);
		} else {
			$query = "UPDATE missionary SET ";
			$updateData=" missionName = '".$mssqlEscapeString[$m_missionName]."', ";
			$updateData = $updateData." church = '".$mssqlEscapeString[$m_church]."', ";
			$updateData = $updateData." churchContact = '".$m_churchContact."', ";
			$updateData = $updateData." ngo = '".$mssqlEscapeString[$m_ngo]."', ";
			$updateData = $updateData." ngoContact = '".$m_ngoContact."', ";
			$updateData = $updateData." nationCode = '".$m_nationCode."', ";
			$updateData = $updateData." accountNo = '".$mssqlEscapeString[$m_accountNo]."', ";
			$updateData = $updateData." bank = '".$mssqlEscapeString[$m_bank]."', ";
			$updateData = $updateData." accountName = '".$mssqlEscapeString[$m_accountName]."', ";
			$updateData = $updateData." homepage = '".$mssqlEscapeString[$m_homepage]."', ";
			$updateData = $updateData." manager = '".$mssqlEscapeString[$m_manager]."', ";
			$updateData = $updateData." managerContact = '".$m_managerContact."', ";
			$updateData = $updateData." managerEmail = '".$mssqlEscapeString[$m_managerEmail]."', ";
			$updateData = $updateData." memo = '".$mssqlEscapeString[$m_memo]."', ";
			$updateData = $updateData." prayList = '".$mssqlEscapeString[$m_prayList]."', ";
			$updateData = $updateData." approval = '".$m_approval."', ";
			$updateData = $updateData." imageId = '".$m_imageId."', ";
			$updateData = $updateData." flagFamily = '".$m_flagFamily."'";
			$query = $query.$updateData." WHERE userId = '".$mssqlEscapeString[$m_userId]."'";
			$objDB->execute_command($query);
		} 
	} 

	function Delete() {
		$query = "DELETE from missionary WHERE userId = '".$mssqlEscapeString[$m_userId]."'";
		$objDB->execute_command($query);
	} 

	function getFamilyCount($userId) {
		$query = "SELECT ";
		$query = $query." SUM(CASE WHEN familyType = 'F0001' THEN 1 ELSE 0 END) AS FamilyPrayCnt, ";
		$query = $query." SUM(CASE WHEN familyType = 'F0002' THEN 1 ELSE 0 END) AS FamilyFareCnt ";
		$query = $query." FROM family WHERE userId = '".$mssqlEscapeString[$userId]."'";
		$familyRS = $objDB->execute_query($query);

		$familyCount[0]=0;
		$familyCount[1]=0;
		if ((!!isset($familyRS["FamilyPrayCnt"]))) {
			$familyCount[0] = $familyRS["FamilyPrayCnt"];
		} 

		if ((!!isset($familyRS["FamilyFareCnt"]))) {
			$familyCount[1] = $familyRS["FamilyFareCnt"];
		} 

		return $familyCount;
	} 
} 
*/
?>
