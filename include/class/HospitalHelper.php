<?php 
# ************************************************************
#  name : HospitalHelper Class
#  description : help to use HospitalObject
# 
#  editor : Sookbun Lee 
#  last update date : 2012/02/11
# ************************************************************
class HospitalHelper {
	var $m_eHandler;

	var $m_pageCount;
	var $m_pageUnit;
	var $m_StrConditionQuery;

	function __construct() {
		$this->m_eHandler = new ErrorHandler();
	} 

	function __destruct() {
	} 
	
	#  property
	# ***********************************************
	public function __get($name) {
		switch ($name) {
			case "PAGE_UNIT":
				return $this->m_pageUnit;
			case "PAGE_COUNT":
				return $this->m_pageCount;
			default: 
				return null;
		}
	}
	
	public function __set($name, $value) {
 		switch ($name) {
			case "PAGE_UNIT" :
				$this->m_pageUnit = $value;
				break;
			case "PAGE_COUNT" :
				$this->m_pageCount = $value;
				break;
		}
		 
	}

	#  method : return one Object
	# ***********************************************
	function getHospitalInfoById($hospitalId) {
		$hospital = new HospitalObject();

		if ($hospital->Open($hospitalId)==false) {
			$this->m_eHandler->ignoreError("Hospital Not Found.");
		} 


		return $hospital;
	} 

	#  method : Return Object List
	# ************************************************************
	function setCondition($hospitalId, $regionCode, $fromDate, $toDate) {
		$strWhere=" WHERE status = 'S2002' ";
		if (strlen($hospitalId) > 0) {
			$strWhere = $strWhere." AND hospitalId = '{$hospitalId}'";
		} 
		if (strlen($regionCode) > 0) {
			$strWhere = $strWhere." AND regionCode = '{$regionCode}'";
		} 
		
		if (strlen($fromDate) > 0 && strlen($toDate) > 0) {
			$strWhere = $strWhere." AND hospitalId NOT IN (SELECT DISTINCT hospitalId FROM reservation WHERE startDate	<= '{$toDate}' AND endDate >= '{$fromDate}' )";
		} elseif (strlen($fromDate) > 0) {
			$strWhere = $strWhere." AND hospitalId NOT IN (SELECT DISTINCT hospitalId FROM reservation WHERE endDate >= '{$fromDate}')";
		} elseif (strlen($toDate) > 0) {
			$strWhere = $strWhere." AND hospitalId NOT IN (SELECT DISTINCT hospitalId FROM reservation WHERE startDate	<= '{$toDate}')";
		}

		$this->m_StrConditionQuery = $strWhere;
	} 

	function setEtcCondition($regionCode) {
		$strWhere=" WHERE status = 'S2001' ";
		if (strlen($regionCode) > 0) {
			$strWhere = $strWhere." AND regionCode = '{$regionCode}'";
		}
		$this->m_StrConditionQuery = $strWhere;
	} 

	function makePagingHTML($curPage) {
		global $mysqli;
		$query = "SELECT COUNT(*) AS recordCount FROM hospital ".$this->m_StrConditionQuery;

		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_array()) {
				$total = $row["recordCount"];
			}
		}

		return makePagingN($curPage, $this->m_pageCount, $this->m_pageUnit, $total);
	} 
	
	function getHospitalListWithPaging($curPage) {
		global $mysqli;
		$hospitals = array();

		$topNum = $this->m_pageCount * ($curPage - 1);
		$query = "SELECT hospitalId FROM hospital {$this->m_StrConditionQuery} ORDER BY price ASC LIMIT {$topNum}, {$this->m_pageCount}";

		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_array()) {
				$hospitals[] = new HospitalObject($row["hospitalId"]);
			}
		}

		return $hospitals;
	} 
	
	function getHospitalList($query) {
		global $mysqli;
		
		$hospital = array();
		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_array()) {
				$hospital[] = new HospitalObject($row["hospitalId"]);
			}
		}
		
		return $hospital;
	} 

	function getHospitalListByEtc() {
		$query = "SELECT hospitalId FROM hospital WHERE status = 'S2001'";
		return getHospitalList($query);
	} 

	function getHospitalListByRegion($regionCode) {
		if (strlen($regionCode) == 0) {
			$query = "SELECT hospitalId FROM hospital";
		} else {
			$query = "SELECT hospitalId FROM hospital WHERE regionCode = '{$regionCode}";
		} 

		return getHospitalList($query);
	} 

	function getHospitalListByuserid($userid, $hospitalType) {
		if ($userid == "lovehouse") {
			$query = "SELECT hospitalId FROM hospital WHERE AND status = 'S2002'";
		}
			else
		if ($hospitalType == 1) {
			$query = "SELECT hospitalId FROM hospital WHERE userid = '{$userid}' AND status = 'S2002'";
		} else {
			$query = "SELECT hospitalId FROM hospital WHERE userid = '{$userid}' AND status = 'S2001'";
		} 

		return getHospitalList($query);
	} 

	function setReservationListCondition_n($search, $hospitalId) {
		switch ($search) {
			case "1":
				$this->m_StrConditionQuery=" AND C.reservStatus = 'S0001'	AND A.hospitalId = '".$hospitalId."'";
				break;
			case "2":
				$this->m_StrConditionQuery=" AND C.reservStatus = 'S0002'	 AND A.hospitalId = '".$hospitalId."'";
				break;
			case "3":
				$this->m_StrConditionQuery=" AND C.reservStatus = 'S0003'	 AND A.hospitalId = '".$hospitalId."'";
				break;
			case "4":
				$this->m_StrConditionQuery=" AND C.reservStatus = 'S0004'	 AND A.hospitalId = '".$hospitalId."'";
				break;
			default:
				$this->m_StrConditionQuery=" AND A.hospitalId = '".$hospitalId."'";
				break;
		} 
	} 

	function setReservationListCondition($search) {
		switch ($search) {
			case "1":
				$this->m_StrConditionQuery=" AND C.reservStatus = 'S0001' ";
				break;
			case "2":
				$this->m_StrConditionQuery=" AND C.reservStatus = 'S0002' ";
				break;
			case "3":
				$this->m_StrConditionQuery=" AND C.reservStatus = 'S0003' ";
				break;
			case "4":
				$this->m_StrConditionQuery=" AND C.reservStatus = 'S0004' ";
				break;
			default:
				$this->m_StrConditionQuery="";
				break;
		} 
	} 

	function makeReservationListPagingHTML($curPage) {
		global $mysqli;

		$query = "SELECT COUNT(*) AS recordCount FROM hospital A, reservation C ";
		if ($_SESSION["userid"] == "lovehouse") {
			$query = $query." WHERE A.hospitalId = C.hospitalId ".$this->m_StrConditionQuery;
		} else {
			$query = $query." WHERE A.hospitalId = C.hospitalId AND A.userid = '".$_SESSION["userid"]."' ".$this->m_StrConditionQuery;
		} 

		$result = $mysqli->query($query);
		while ($row = $result->fetch_assoc()) {
			$total = $row["recordCount"];
		}

		return makePagingN($curPage, $this->m_pageCount, $this->m_pageUnit, $total);
	} 

	function getReservationListWithPaging($curPage) {
		global $mysqli;

		$topNum = $this->m_pageCount * $curPage;

		$query = "SELECT top ".$topNum." C.reservationNo FROM hospital A, reservation C ";
		if ($_SESSION["userid"] == "lovehouse") {
			$query = $query." WHERE A.hospitalId = C.hospitalId ".$this->m_StrConditionQuery;
		} else {
			$query = $query." WHERE A.hospitalId = C.hospitalId AND A.userid = '".$_SESSION["userid"]."' ".$this->m_StrConditionQuery;
		} 

		$query = $query." ORDER BY C.reservationNo DESC";

		if ($result = $mysqli->query($query)) {
			while($row = $result->fetch_assoc()) {
				$reservInfo = new ReservationObject($row["reservationNo"]);
			} 
		} 

		return $reservInfo;
	} 

	function getReservationList($query) {
		global $mysqli;

		if ($result = $mysqli->query($query)) {
			while($row = $result->fetch_assoc()) {
				$reserveInfo = new ReservationObject($row["reservationNo"]);
			} 
		} 

		return $reserveInfo;
	} 

	function getReservationListByManager($curPage) {
		$query = "SELECT C.reservationNo FROM hospital A, reservation C ";

		if ($_SESSION["userid"] =="lovehouse") {
			$query = $query."WHERE A.hospitalId = C.hospitalId";
		} else {
			$query = $query."WHERE A.hospitalId = C.hospitalId AND A.userid = '".$_SESSION["userid"]."'";
		} 

		return getReservationList($query);
	} 
} 
?>