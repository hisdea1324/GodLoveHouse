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
	function PAGE_UNIT() {
		return $this->m_pageUnit;
	} 

	function PAGE_COUNT() {
		return $this->m_pageCount;
	} 

	function PAGE_UNIT($value) {
		$this->m_pageUnit = $value;
	} 

	function PAGE_COUNT($value) {
		$this->m_pageCount = $value;
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
		}
			else
		if ((strlen($toDate)>0)) {
			$strWhere = $strWhere." AND hospitalId NOT IN (SELECT DISTINCT hospitalId FROM reservation WHERE startDate	<= '".$mssqlEscapeString[$toDate]."')";
		} 

		$m_StrConditionQuery = $strWhere;
	} 

	function setEtcCondition($regionCode) {
		$strWhere=" WHERE status = 'S2001' ";
		if ((strlen($regionCode)>0)) {
			$strWhere = $strWhere." AND regionCode = '".$mssqlEscapeString[$regionCode]."'";
		} 
		$m_StrConditionQuery = $strWhere;
	} 

	function makePagingHTML($curPage) {
		$query = "SELECT COUNT(*) AS recordCount FROM hospital".$m_StrConditionQuery;
		$countRS = $db->Execute($query);
		$total = $countRS["recordCount"];
		$countRS = null;

		return $makePagingN[$curPage][$m_pageCount][$m_pageUnit][$total];
	} 

	function getHospitalList($query) {
		$hospitalListRS = $db->Execute($query);

		if ((!$hospitalListRS->Eof && !$hospitalListRS->Bof)) {
			while(!(($hospitalListRS->EOF || $hospitalListRS->BOF))) {
				$hospitalInfo = new HospitalObject();
				$hospitalInfo->Open($hospitalListRS["hospitalId"]);

				$index=count($retValue);
				$retValue = $index;
				echo $hospitalInfo;
				$hospitalListRS->MoveNext;
			} 
		} 

		return $retValue;
	} 

	function getHospitalListByEtc() {
		$query = "SELECT hospitalId FROM hospital WHERE status = 'S2001'";
		return getHospitalList($query);
	} 

	function getHospitalListByRegion($regionCode) {
		if ((strlen($regionCode)==0)) {
			$query = "SELECT hospitalId FROM hospital";
		} else {
			$query = "SELECT hospitalId FROM hospital WHERE regionCode = '".$mssqlEscapeString[$regionCode]."'";
		} 

		return getHospitalList($query);
	} 

	function getHospitalListWithPaging($curPage) {
		$topNum = $m_pageCount*$curPage;

		$query = "SELECT top ".$topNum." hospitalId FROM hospital ".$m_StrConditionQuery." ORDER BY price ASC";
		$db->CursorLocation=3;
		$listRS = $db->Execute($query);

		if (($listRS->RecordCount>0)) {
			$listRS->PageSize = $m_pageCount;
			$listRS->AbsolutePage = $curPage;
			while(!(($listRS->EOF || $listRS->BOF))) {
				$hospitalInfo = new HospitalObject();
				$hospitalInfo->Open($listRS["hospitalId"]);

				$index=count($retValue);
				$retValue = $index;
				echo $hospitalInfo;
				$listRS->MoveNext;
			} 
		} 

		return $retValue;
	} 

	function getHospitalListByUserId($userId,$hospitalType) {
		if (($userId=="lovehouse")) {
			$query = "SELECT hospitalId FROM hospital WHERE AND status = 'S2002'";
		}
			else
		if (($hospitalType==1)) {
			$query = "SELECT hospitalId FROM hospital WHERE userId = '".$mssqlEscapeString[$userId]."' AND status = 'S2002'";
		} else {
			$query = "SELECT hospitalId FROM hospital WHERE userId = '".$mssqlEscapeString[$userId]."' AND status = 'S2001'";
		} 

		return getHospitalList($query);
	} 

	function setReservationListCondition_n($search,$hospitalId) {
		switch (($search)) {
			case "1":
				$m_StrConditionQuery=" AND C.reservStatus = 'S0001'	AND A.hospitalId = '".$hospitalId."'";
				break;
			case "2":
				$m_StrConditionQuery=" AND C.reservStatus = 'S0002'	 AND A.hospitalId = '".$hospitalId."'";
				break;
			case "3":
				$m_StrConditionQuery=" AND C.reservStatus = 'S0003'	 AND A.hospitalId = '".$hospitalId."'";
				break;
			case "4":
				$m_StrConditionQuery=" AND C.reservStatus = 'S0004'	 AND A.hospitalId = '".$hospitalId."'";
				break;
			default:
				$m_StrConditionQuery=" AND A.hospitalId = '".$hospitalId."'";
				break;
		} 
	} 

	function setReservationListCondition($search) {
		switch (($search)) {
			case "1":
				$m_StrConditionQuery=" AND C.reservStatus = 'S0001' ";
				break;
			case "2":
				$m_StrConditionQuery=" AND C.reservStatus = 'S0002' ";
				break;
			case "3":
				$m_StrConditionQuery=" AND C.reservStatus = 'S0003' ";
				break;
			case "4":
				$m_StrConditionQuery=" AND C.reservStatus = 'S0004' ";
				break;
			default:
				$m_StrConditionQuery="";
				break;
		} 
	} 

	function makeReservationListPagingHTML($curPage) {
		$sessions = new __construct();
		$query = "SELECT COUNT(*) AS recordCount FROM hospital A, reservation C ";
		if (($sessions->UserID=="lovehouse")) {
			$query = $query." WHERE A.hospitalId = C.hospitalId ".$m_StrConditionQuery;
		} else {
			$query = $query." WHERE A.hospitalId = C.hospitalId AND A.userId = '".$mssqlEscapeString[$sessions->UserID]."' ".$m_StrConditionQuery;
		} 

		$countRS = $db->Execute($query);
		$total = $countRS["recordCount"];
		$countRS = null;

		return $makePagingN[$curPage][$m_pageCount][$m_pageUnit][$total];
	} 

	function getReservationListWithPaging($curPage) {
		$sessions = new __construct();
		$topNum = $m_pageCount*$curPage;

		$query = "SELECT top ".$topNum." C.reservationNo FROM hospital A, reservation C ";
		if (($sessions->UserID=="lovehouse")) {
			$query = $query." WHERE A.hospitalId = C.hospitalId ".$m_StrConditionQuery;
		} else {
			$query = $query." WHERE A.hospitalId = C.hospitalId AND A.userId = '".$mssqlEscapeString[$sessions->UserID]."' ".$m_StrConditionQuery;
		} 

		$query = $query." ORDER BY C.reservationNo DESC";
		$db->CursorLocation=3;
		$reserveListRS = $db->Execute($query);

		if (($reserveListRS->RecordCount>0)) {
			$reserveListRS->PageSize = $m_pageCount;
			$reserveListRS->AbsolutePage = $curPage;
			while(!(($reserveListRS->EOF || $reserveListRS->BOF))) {
				$reservInfo = new ReservationObject();
				$reservInfo->Open($reserveListRS["reservationNo"]);

				$index=count($retValue);
				$retValue = $index;
				echo $reservInfo;
				$reserveListRS->MoveNext;
			} 
		} 

		return $retValue;
	} 

	function getReservationList($query) {
		$reserveListRS = $db->Execute($query);

		if ((!$reserveListRS->Eof && !$reserveListRS->Bof)) {
			while(!(($reserveListRS->EOF || $reserveListRS->BOF))) {
				$reserveInfo = new ReservationObject();
				$reserveInfo->Open($reserveListRS["reservationNo"]);

				$index=count($retValue);
				$retValue = $index;
				echo $reserveInfo;
				$reserveListRS->MoveNext;
			} 
		} 

		return $retValue;
	} 

	function getReservationListByManager($curPage) {
		$sessions = new __construct();
		$query = "SELECT C.reservationNo FROM hospital A, reservation C ";
		if (($sessions->UserID=="lovehouse")) {
			$query = $query."WHERE A.hospitalId = C.hospitalId";
		} else {
			$query = $query."WHERE A.hospitalId = C.hospitalId AND A.userId = '".$mssqlEscapeString[$sessions->UserID]."'";
		} 

		return getReservationList($query);
	} 
} 
?>
