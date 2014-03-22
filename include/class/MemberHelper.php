<?php 
class MemberHelper {
	var $m_eHandler;
	var $m_pageCount;
	var $m_pageUnit;
	var $m_StrConditionQuery;
	var $m_StrOrderQuery;

	#  property
	# ***********************************************
	public function __set($name, $value) { 
		switch ($name) {
			case "PAGE_UNIT":
				$this->m_pageUnit = $value;
				break;
			case "PAGE_COUNT":
				$this->m_pageCount = $value;
				break;
		}
	}

	public function __get($name) { 
		switch ($name) {
			case "PAGE_UNIT":
				return $this->m_pageUnit;
			case "PAGE_COUNT":
				return $this->m_pageCount;
			default:
				return "";
		}
	}

	#  creater
	# ***********************************************
	function __construct() {
		$this->m_eHandler = new ErrorHandler();
		$this->m_pageCount=5;
		$this->m_pageUnit=10;
	} 

	#  destoryer
	# ***********************************************
	function __destruct() {
	} 

	#  method
	# ***********************************************
	function getMemberByuserid($userid = "") {
		$member = new MemberObject($userid);

		if ($member->userid != "") {
			$this->m_eHandler->ignoreError("Member Not Found.");
		} 

		return $member;
	} 

	function getMemberByUserNick($nick = "") {
		$member = new MemberObject();

		if ($member->OpenByNick($nick) == false) {
			$this->m_eHandler->ignoreError("Member Not Found.");
		} 


		return $member;
	} 

	function getMissionInfoByuserid($userid = "") {
		$mission = new MissionObject($userid);
		return $mission;
	} 

	function getAccountInfoByuserid($userid = "") {
		$account = new AccountObject($userid);
		return $account;
	} 

	function getSupportByuserid($userid) {
		$support = new SupportObject();

		if ($support->Open($userid) == false) {
			$this->m_eHandler->ignoreError("Supporter Not Found.");
		} 


		return $support;
	} 

	function getFamilyType($missionId, $userid) {
		global $mysqli;

		$retValue = false;
		
		if (strlen($userid) > 0) {
			$query = "SELECT familyType FROM family WHERE userid = '".$missionId."' AND followuserid = '".$userid."'";
			if ($result = $mysqli->query($query)) {
				while ($row = $result->fetch_assoc()) {
					$retValue = $row["familyType"];
				}
			}
		}


		return $retValue;
	} 

	#  method list Helper
	# *********************************************************
	function setCondition($userLv,$field,$keyword) {
		if ($userLv > 0) {
			$strWhere=" WHERE userLv = '".$userLv."'";
		} else {
			$strWhere=" WHERE userLv between 0 and 8 ";
		} 

		if (strlen($field) > 0 && strlen($keyword) > 0) {
			$strWhere = $strWhere." AND ".$field." LIKE '%".$keyword."%'";
		} 

		return $strWhere;
	} 

	function setOrder($order) {
		return " ORDER BY ".$order;
	} 

	function makePagingHTML($curPage) {
		$query = "SELECT COUNT(*) AS recordCount from users".$this->m_StrConditionQuery;
		$countRS = $mysqli->Execute($query);
		$total = $countRS["recordCount"];
		$countRS = null;

		return makePagingN($curPage, $this->m_pageCount, $this->m_pageUnit, $total);
	} 

	function getMemberListWithPageing($curPage) {
		$topNum = $this->m_pageCount * $curPage;

		$query = "SELECT TOP ".$topNum." * FROM users ".$this->m_StrConditionQuery.$this->m_StrOrderQuery;
		$listRS = $mysqli->Execute($query);
		if ($listRS->RecordCount > 0) {
			$listRS->PageSize = $this->m_pageCount;
			$listRS->AbsolutePage = $curPage;
		} 


		return $mysqli->Execute($query);
	} 

	function setMissionListCondition($field, $keyword) {
		global $mysqli;

		$strWhere = "";
		if (strlen($field) > 0 && strlen($keyword) > 0) {
			$strWhere = $strWhere." AND ".$mysqli->real_escape_string($field)." LIKE '%".$mysqli->real_escape_string($keyword)."%'";
		} 

		$this->m_StrConditionQuery = "WHERE approval = 1".$strWhere;
	} 

	function makePagingMissionList($curPage) {
		global $mysqli;

		$query = "SELECT COUNT(*) AS recordCount from missionary ".$this->m_StrConditionQuery;

		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$total = $row["recordCount"];
			}
			$result->close();
		}

		return makePagingN($curPage, $this->m_pageCount, $this->m_pageUnit, $total);
	} 

	function getMissionListWithPageing($curPage) {
		global $mysqli;

		$mission = array();
		$start = $this->m_pageCount * ($curPage - 1);
		$number = $this->m_pageCount;
		$query = "SELECT userid FROM missionary ".$this->m_StrConditionQuery." ORDER BY missionName LIMIT {$start}, {$number}";
		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$mission[] = new MissionObject($row["userid"]);
			}
			$result->close();
		}

		return $mission;
	} 

	function getMissionList($query) {
		global $mysqli;

		$mission_list = array();
		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$mission_list[] = new MissionObject($row["userid"]);
			}
		}

		return $mission_list;
	} 

	function getMemberListByPrayer($userid) {
		global $mysqli;
		$query = "SELECT userid FROM family WHERE familytype = 'F0002' AND followuserid = '".$mysqli->real_escape_string($userid)."'";
		return $this->getMissionList($query);
	} 

	function getMemberListByRegular($userid) {
		global $mysqli;
		$query = "SELECT userid FROM family WHERE familytype = 'F0001' AND followuserid = '".$mysqli->real_escape_string($userid)."'";
		return $this->getMissionList($query);
	}
} 
?>
