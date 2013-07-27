<?php 
# ************************************************************
#  name : HouseHelper Class
#  description : 
#  		help to use HouseObject & RoomObject
# 		create a HouseObject & RoomObject
# 
#  editor : Sookbun Lee 
#  last update date : 2009/12/30
# ************************************************************
class HouseHelper {
	var $m_eHandler;

	var $m_pageCount;
	var $m_pageUnit;
	var $m_StrConditionQuery;

	function __construct() {
		$m_eHandler = new ErrorHandler();
	} 

	function __destruct() {
	} 

	#  property
	# ***********************************************
	function PAGE_UNIT() {
		$PAGE_UNIT = $m_pageUnit;
	} 

	function PAGE_COUNT() {
		$PAGE_COUNT = $m_pageCount;
	} 

	function PAGE_UNIT($value) {
		$m_pageUnit = $value;
	} 

	function PAGE_COUNT($value) {
		$m_pageCount = $value;
	} 

	#  method : return one Object
	# ***********************************************
	function getHouseInfoById($houseId) {
		$house = new HouseObject();

		if (($house->Open($houseId)==false)) {
			$m_eHandler->ignoreError("House Not Found.");
		} 

		return $house;
	} 

	function getRoomInfoById($roomId) {
		$room = new RoomObject();

		if (($room->Open($roomId)==false)) {
			$m_eHandler->ignoreError("Room Not Found.");
		} 

		return $room;
	} 

	#  method : Return Object List
	# ************************************************************
	function setCondition($houseId,$regionCode,$fromDate,$toDate) {
		$strWhere=" WHERE B.status = 'S2002' AND A.houseId = B.houseId ";
		if ((strlen($houseId)>0)) {
			$strWhere = $strWhere." AND A.houseId = '".$mssqlEscapeString[$houseId]."'";
		} 
		if ((strlen($regionCode)>0)) {
			$strWhere = $strWhere." AND B.regionCode = '".$mssqlEscapeString[$regionCode]."'";
		} 
		if ((strlen($fromDate)>0 && strlen($toDate)>0)) {
			$strWhere = $strWhere." AND A.roomId NOT IN (SELECT DISTINCT roomId FROM reservation WHERE startDate	<= '".$mssqlEscapeString[$toDate]."' AND endDate >= '".$mssqlEscapeString[$fromDate]."' )";
		}
			else
		if ((strlen($fromDate)>0)) {
			$strWhere = $strWhere." AND A.roomId NOT IN (SELECT DISTINCT roomId FROM reservation WHERE endDate >= '".$mssqlEscapeString[$fromDate]."')";
		}
			else
		if ((strlen($toDate)>0)) {
			$strWhere = $strWhere." AND A.roomId NOT IN (SELECT DISTINCT roomId FROM reservation WHERE startDate	<= '".$mssqlEscapeString[$toDate]."')";
		} 

		$m_StrConditionQuery = $strWhere;
	} 

	function setEtcCondition($regionCode) {
		$strWhere=" WHERE B.status = 'S2001' AND A.houseId = B.houseId ";
		if ((strlen($regionCode)>0)) {
			$strWhere = $strWhere." AND B.regionCode = '".$mssqlEscapeString[$regionCode]."'";
		} 
		$m_StrConditionQuery = $strWhere;
	} 

	function makePagingHTML($curPage) {
		$query = "SELECT COUNT(*) AS recordCount FROM room A, house B".$m_StrConditionQuery;
		$countRS = $db->Execute($query);
		$total = $countRS["recordCount"];
		$countRS = null;

		return $makePagingN[$curPage][$m_pageCount][$m_pageUnit][$total];
	} 

	function getRoomListWithPaging($curPage) {
		$topNum = $m_pageCount*$curPage;

		$query = "SELECT top ".$topNum." A.roomId, B.houseId FROM room A, house B ".$m_StrConditionQuery." ORDER BY A.fee ASC";
		$db->CursorLocation=3;
		$listRS = $db->Execute($query);

		if (($listRS->RecordCount>0)) {
			$listRS->PageSize = $m_pageCount;
			$listRS->AbsolutePage = $curPage;
			while(!(($listRS->EOF || $listRS->BOF))) {
				$roomInfo = new RoomObject();
				$roomInfo->Open($listRS["roomId"]);

				$index=count($retValue);
				$retValue = $index;
				echo $roomInfo;
				$listRS->MoveNext;
			} 
		} 

		return $retValue;
	} 

	function getHouseList($query) {
		$houseListRS = $db->Execute($query);

		if ((!$houseListRS->Eof && !$houseListRS->Bof)) {
			while(!(($houseListRS->EOF || $houseListRS->BOF))) {
				$houseInfo = new HouseObject();
				$houseInfo->Open($houseListRS["houseId"]);

				$index=count($retValue);
				$retValue = $index;
				echo $houseInfo;
				$houseListRS->MoveNext;
			} 
		} 

		return $retValue;
	} 

	function getHouseListByEtc() {
		$query = "SELECT houseId FROM house WHERE (status = 'S2001' OR roomCount = 0)";
		return getHouseList($query);
	} 

	function getHouseListByRegion($regionCode) {
		if ((strlen($regionCode)==0)) {
			$query = "SELECT houseId FROM house WHERE houseId IN (SELECT distinct houseId FROM room)";
		} else {
			$query = "SELECT houseId FROM house WHERE houseId IN (SELECT distinct houseId FROM room) AND regionCode = '".$mssqlEscapeString[$regionCode]."'";
		} 

		return getHouseList($query);
	} 

	function getHouseListByUserId($userId,$houseType) {
		if (($userId=="lovehouse")) {
			$query = "SELECT houseId FROM house WHERE status = 'S2002'";
		}
			else
		if (($houseType==1)) {
			$query = "SELECT houseId FROM house WHERE userId = '".$mssqlEscapeString[$userId]."' AND status = 'S2002'";
		} else {
			$query = "SELECT houseId FROM house WHERE userId = '".$mssqlEscapeString[$userId]."' AND (status = 'S2001')";
		} 

		return getHouseList($query);
	} 

	function setReservationListConditionWithHouse($search,$houseId) {
		switch (($search)) {
			case "1":
				$m_StrConditionQuery=" AND C.reservStatus = 'S0001' AND A.houseId = '".$houseId."'";
				break;
			case "2":
				$m_StrConditionQuery=" AND C.reservStatus = 'S0002' AND A.houseId = '".$houseId."'";
				break;
			case "3":
				$m_StrConditionQuery=" AND C.reservStatus = 'S0003' AND A.houseId = '".$houseId."'";
				break;
			case "4":
				$m_StrConditionQuery=" AND C.reservStatus = 'S0004' AND A.houseId = '".$houseId."'";
				break;
			default:
				$m_StrConditionQuery=" AND C.reservStatus <> 'S0004' AND A.houseId = '".$houseId."'";
				break;
		} 
	} 

	function setReservationListConditionWithDate($search,$houseId,$roomId) {
		switch (($search)) {
			case "1":
				$m_StrConditionQuery=" AND C.reservStatus = 'S0001' AND A.houseId = '".$houseId."' AND B.roomId = '".$roomId."'";
				break;
			case "2":
				$m_StrConditionQuery=" AND C.reservStatus = 'S0002' AND A.houseId = '".$houseId."' AND B.roomId = '".$roomId."'";
				break;
			case "3":
				$m_StrConditionQuery=" AND C.reservStatus = 'S0003' AND A.houseId = '".$houseId."' AND B.roomId = '".$roomId."'";
				break;
			case "4":
				$m_StrConditionQuery=" AND C.reservStatus = 'S0004' AND A.houseId = '".$houseId."' AND B.roomId = '".$roomId."'";
				break;
			default:
				$m_StrConditionQuery=" AND C.reservStatus <> 'S0004' AND A.houseId = '".$houseId."' AND B.roomId = '".$roomId."'";
				break;
		} 
	} 

	function setReservationListCondition_n($search,$houseId,$roomId) {
		switch (($search)) {
			case "1":
				$m_StrConditionQuery=" AND C.reservStatus = 'S0001' AND A.houseId = '".$houseId."' AND B.roomId = '".$roomId."'";
				break;
			case "2":
				$m_StrConditionQuery=" AND C.reservStatus = 'S0002' AND A.houseId = '".$houseId."' AND B.roomId = '".$roomId."'";
				break;
			case "3":
				$m_StrConditionQuery=" AND C.reservStatus = 'S0003' AND A.houseId = '".$houseId."' AND B.roomId = '".$roomId."'";
				break;
			case "4":
				$m_StrConditionQuery=" AND C.reservStatus = 'S0004' AND A.houseId = '".$houseId."' AND B.roomId = '".$roomId."'";
				break;
			default:
				$m_StrConditionQuery=" AND C.reservStatus <> 'S0004' AND A.houseId = '".$houseId."' AND B.roomId = '".$roomId."'";
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
				$m_StrConditionQuery=" AND C.reservStatus <> 'S0004' ";
				break;
		} 
	} 

	function makeReservationListPagingHTML($curPage) {
		$sessions = new __construct();
		$query = "SELECT COUNT(*) AS recordCount FROM house A, room B, reservation C ";
		if (($sessions->UserID=="lovehouse")) {
			$query = $query." WHERE A.houseId = B.houseId AND B.roomId = C.roomId ".$m_StrConditionQuery;
		} else {
			$query = $query." WHERE A.houseId = B.houseId AND B.roomId = C.roomId AND A.userId = '".$mssqlEscapeString[$sessions->UserID]."' ".$m_StrConditionQuery;
		} 

		$countRS = $db->Execute($query);
		$total = $countRS["recordCount"];
		$countRS = null;

		return $makePagingN[$curPage][$m_pageCount][$m_pageUnit][$total];
	} 

	function getReservationListWithPaging($curPage) {
		$sessions = new __construct();
		$topNum = $m_pageCount*$curPage;

		$query = "SELECT top ".$topNum." C.reservationNo FROM house A, room B, reservation C ";
		if (($sessions->UserID=="lovehouse")) {
			$query = $query." WHERE A.houseId = B.houseId AND B.roomId = C.roomId ".$m_StrConditionQuery;
		} else {
			$query = $query." WHERE A.houseId = B.houseId AND B.roomId = C.roomId AND A.userId = '".$mssqlEscapeString[$sessions->UserID]."' ".$m_StrConditionQuery;
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
		$query = "SELECT C.reservationNo FROM house A, room B, reservation C ";
		if (($sessions->UserID=="lovehouse")) {
			$query = $query."WHERE A.houseId = B.houseId AND B.roomId = C.roomId";
		} else {
			$query = $query."WHERE A.houseId = B.houseId AND B.roomId = C.roomId AND A.userId = '".$mssqlEscapeString[$sessions->UserID]."'";
		} 

		return getReservationList($query);
	} 

	function getReservationListByUser($curPage) {
		$query = "SELECT C.reservationNo FROM house A, room B, reservation C ";
		$query = $query."WHERE A.houseId = B.houseId AND B.roomId = C.roomId AND C.userId = '".$mssqlEscapeString[$sessions->UserID]."' ";
		$query = $query."ORDER BY C.regDate DESC";
		return getReservationList($query);
	} 
} 
?>

