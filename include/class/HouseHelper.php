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
	public $m_eHandler;

	public $m_pageCount;
	public $m_pageUnit;
	public $m_StrConditionQuery;

	public function __construct() {
		$this->m_eHandler = new ErrorHandler();
	} 

	public function __destruct() {
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
	function getHouseInfoById($houseId) {
		if ($house = new HouseObject($houseId)) {
			return $house;
		} 

		$this->m_eHandler->ignoreError("House Not Found.");
		return null;
	} 

	function getRoomInfoById($roomId) {
		$room = new RoomObject();

		if ($room->Open($roomId) == false) {
			$this->m_eHandler->ignoreError("Room Not Found.");
		} 

		return $room;
	} 

	#  method : Return Object List
	# ************************************************************
	function setCondition($houseId,$regionCode,$fromDate,$toDate) {
		$strWhere = " WHERE B.status = 'S2002' AND A.houseId = B.houseId ";
		if (strlen($houseId) > 0) {
			$strWhere = $strWhere." AND A.houseId = '{$houseId}'";
		} 
		if (strlen($regionCode) > 0) {
			$strWhere = $strWhere." AND B.regionCode = '{$regionCode}'";
		} 
		if (strlen($fromDate) > 0 && strlen($toDate) > 0) {
			$strWhere = $strWhere." AND A.roomId NOT IN (SELECT DISTINCT roomId FROM reservation WHERE startDate <= '{$toDate}' AND endDate >= '{$fromDate}' )";
		} elseif (strlen($fromDate) > 0) {
			$strWhere = $strWhere." AND A.roomId NOT IN (SELECT DISTINCT roomId FROM reservation WHERE endDate >= '{$fromDate}')";
		} elseif (strlen($toDate) > 0) {
			$strWhere = $strWhere." AND A.roomId NOT IN (SELECT DISTINCT roomId FROM reservation WHERE startDate <= '{$toDate}')";
		} 

		$this->m_StrConditionQuery = $strWhere;
	} 

	function setEtcCondition($regionCode) {
		$strWhere=" WHERE B.status = 'S2001' AND A.houseId = B.houseId ";
		if (strlen($regionCode) > 0) {
			$strWhere = $strWhere." AND B.regionCode = '{$regionCode}'";
		} 
		$this->m_StrConditionQuery = $strWhere;
	} 

	function makePagingHTML($curPage) {
		global $mysqli;
		$query = "SELECT COUNT(*) AS recordCount FROM room A, house B ".$this->m_StrConditionQuery;

		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_array()) {
				$total = $row["recordCount"];
			}
		}

		return makePagingN($curPage, $this->m_pageCount, $this->m_pageUnit, $total);
	} 

	function getRoomListWithPaging($curPage) {
		global $mysqli;
		$rooms = array();
		
		$topNum = $this->m_pageCount * ($curPage - 1);
		$query = "SELECT A.roomId, B.houseId FROM room A, house B {$this->m_StrConditionQuery} ORDER BY A.fee ASC LIMIT {$topNum}, {$this->m_pageCount}";

		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_array()) {
				$rooms[] = new RoomObject($row["roomId"]);
			}
		}
		
		return $rooms;
	} 

	function getHouseList($query) {
		global $mysqli;

		$houses = array();
		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_array()) {
				$houses[] = new HouseObject($row["houseId"]);
			}
		}

		return $houses;
	} 

	function getHouseListByEtc() {
		$query = "SELECT houseId FROM house WHERE (status = 'S2001' OR roomCount = 0)";
		return $this->getHouseList($query);
	} 

	function getHouseListByRegion($regionCode) {
		if (strlen($regionCode) == 0) {
			$query = "SELECT houseId FROM house WHERE houseId IN (SELECT distinct houseId FROM room)";
		} else {
			$query = "SELECT houseId FROM house WHERE houseId IN (SELECT distinct houseId FROM room) AND regionCode = '{$regionCode}'";
		} 

		return $this->getHouseList($query);
	} 

	function getHouseListByUserId($userId, $houseType) {
		if ($userId == "lovehouse") {
			$query = "SELECT houseId FROM house WHERE status = 'S2002'";
		} elseif ($houseType == 1) {
			$query = "SELECT houseId FROM house WHERE userId = '$userId' AND status = 'S2002'";
		} else {
			$query = "SELECT houseId FROM house WHERE userId = '$userId' AND (status = 'S2001')";
		} 

		return $this->getHouseList($query);
	} 

	function setReservationListConditionWithHouse($search,$houseId) {
		switch ($search) {
			case "1":
				$this->m_StrConditionQuery=" AND C.reservStatus = 'S0001' AND A.houseId = '".$houseId."'";
				break;
			case "2":
				$this->m_StrConditionQuery=" AND C.reservStatus = 'S0002' AND A.houseId = '".$houseId."'";
				break;
			case "3":
				$this->m_StrConditionQuery=" AND C.reservStatus = 'S0003' AND A.houseId = '".$houseId."'";
				break;
			case "4":
				$this->m_StrConditionQuery=" AND C.reservStatus = 'S0004' AND A.houseId = '".$houseId."'";
				break;
			default:
				$this->m_StrConditionQuery=" AND C.reservStatus <> 'S0004' AND A.houseId = '".$houseId."'";
				break;
		} 
	} 

	function setReservationListConditionWithDate($search,$houseId,$roomId) {
		switch ($search) {
			case "1":
				$this->m_StrConditionQuery = " AND C.reservStatus = 'S0001' AND A.houseId = '".$houseId."' AND B.roomId = '".$roomId."'";
				break;
			case "2":
				$this->m_StrConditionQuery = " AND C.reservStatus = 'S0002' AND A.houseId = '".$houseId."' AND B.roomId = '".$roomId."'";
				break;
			case "3":
				$this->m_StrConditionQuery = " AND C.reservStatus = 'S0003' AND A.houseId = '".$houseId."' AND B.roomId = '".$roomId."'";
				break;
			case "4":
				$this->m_StrConditionQuery = " AND C.reservStatus = 'S0004' AND A.houseId = '".$houseId."' AND B.roomId = '".$roomId."'";
				break;
			default:
				$this->m_StrConditionQuery = " AND C.reservStatus <> 'S0004' AND A.houseId = '".$houseId."' AND B.roomId = '".$roomId."'";
				break;
		} 
	} 

	function setReservationListCondition_n($search,$houseId,$roomId) {
		switch ($search) {
			case "1":
				$this->m_StrConditionQuery = " AND C.reservStatus = 'S0001' AND A.houseId = '".$houseId."' AND B.roomId = '".$roomId."'";
				break;
			case "2":
				$this->m_StrConditionQuery = " AND C.reservStatus = 'S0002' AND A.houseId = '".$houseId."' AND B.roomId = '".$roomId."'";
				break;
			case "3":
				$this->m_StrConditionQuery=" AND C.reservStatus = 'S0003' AND A.houseId = '".$houseId."' AND B.roomId = '".$roomId."'";
				break;
			case "4":
				$this->m_StrConditionQuery = " AND C.reservStatus = 'S0004' AND A.houseId = '".$houseId."' AND B.roomId = '".$roomId."'";
				break;
			default:
				$this->m_StrConditionQuery = " AND C.reservStatus <> 'S0004' AND A.houseId = '".$houseId."' AND B.roomId = '".$roomId."'";
				break;
		} 
	} 

	function setReservationListCondition($search) {
		switch ($search) {
			case "1":
				$this->m_StrConditionQuery = " AND C.reservStatus = 'S0001' ";
				break;
			case "2":
				$this->m_StrConditionQuery = " AND C.reservStatus = 'S0002' ";
				break;
			case "3":
				$this->m_StrConditionQuery = " AND C.reservStatus = 'S0003' ";
				break;
			case "4":
				$this->m_StrConditionQuery = " AND C.reservStatus = 'S0004' ";
				break;
			default:
				$this->m_StrConditionQuery = " AND C.reservStatus <> 'S0004' ";
				break;
		} 
	} 

	function makeReservationListPagingHTML($curPage) {
		global $mysqli;
		
		$query = "SELECT COUNT(*) AS recordCount FROM house A, room B, reservation C ";
		if ($_SESSION['userid'] == "lovehouse") {
			$query = $query." WHERE A.houseId = B.houseId AND B.roomId = C.roomId ".$this->m_StrConditionQuery;
		} else {
			$query = $query." WHERE A.houseId = B.houseId AND B.roomId = C.roomId AND A.userId = '".$_SESSION['userid']."' ".$this->m_StrConditionQuery;
		} 

		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_array()) {
				$total = $row["recordCount"];
			}
		}

		return makePagingN($curPage, $this->m_pageCount, $this->m_pageUnit, $total);
	} 

	function getReservationListWithPaging($curPage) {
		global $mysqli;
		
		$reserveInfo = array();
		$topNum = $this->m_pageCount * $curPage;

		$query = "SELECT top ".$topNum." C.reservationNo FROM house A, room B, reservation C ";

		if ($_SESSION['userid'] == "lovehouse") {
			$query = $query." WHERE A.houseId = B.houseId AND B.roomId = C.roomId ".$this->m_StrConditionQuery;
		} else {
			$query = $query." WHERE A.houseId = B.houseId AND B.roomId = C.roomId AND A.userId = '".$mysqli->real_escape_string($_SESSION['userid'])."' ".$this->m_StrConditionQuery;
		} 

		$query = $query." ORDER BY C.reservationNo DESC";

		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$reserveInfo[] = new ReservationObject($row["reservationNo"]);
			}
			$result->close();
		}

		return $reserveInfo;
	} 

	function getReservationList($query) {
		global $mysqli;
		
		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_array()) {
				$reserveInfo = new ReservationObject();
				$reserveInfo->Open($row["reservationNo"]);
			}
		}

		return $reserveInfo;
	} 

	function getReservationListByManager($curPage) {
		global $mysqli;

		$query = "SELECT C.reservationNo FROM house A, room B, reservation C ";
		if ($_SESSION['userid'] == "lovehouse") {
			$query = $query."WHERE A.houseId = B.houseId AND B.roomId = C.roomId";
		} else {
			$query = $query."WHERE A.houseId = B.houseId AND B.roomId = C.roomId AND A.userId = '".$mysqli->real_escape_string($_SESSION['userid'])."'";
		} 

		return $this->getReservationList($query);
	} 

	function getReservationListByUser($curPage) {
		global $mysqli;

		$query = "SELECT C.reservationNo FROM house A, room B, reservation C ";
		$query = $query."WHERE A.houseId = B.houseId AND B.roomId = C.roomId AND C.userId = '".$mysqli->real_escape_string($_SESSION['userid'])."' ";
		$query = $query."ORDER BY C.regDate DESC";
		return $this->getReservationList($query);
	} 
} 
?>
