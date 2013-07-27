<?php 
# ************************************************************
#  Object : ReservationObject
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************
class ReservationObject {
	var $m_reservationNo;
	var $m_userId;
	var $m_roomId;
	var $m_hospitalId;
	var $m_reservStatus;
	var $m_regDate;
	var $m_startDate;
	var $m_endDate;

	var $m_houseName;
	var $m_roomName;
	var $m_hospitalName;

	#  property getter
	# ***********************************************
	function BookNo() {
		$BookNo = $m_reservationNo;
	} 

	function UserID() {
		$UserID = $m_userId;
	} 

	function RoomID() {
		$RoomID = $m_roomId;
	} 

	function HospitalID() {
		$HospitalID = $m_hospitalId;
	} 

	function Status() {
		switch (($m_reservStatus)) {
			case "S0001":
				$retValue="신규예약";
				break;
			case "S0002":
				$retValue="승인";
				break;
			case "S0003":
				$retValue="완료";
				break;
			case "S0004":
				$retValue="거절";
				break;
		} 
		$Status = $retValue;
	} 

	function StatusCode() {
		$StatusCode = $m_reservStatus;
	} 

	function StartDate() {
		$StartDate = $m_startDate;
	} 

	function EndDate() {
		$EndDate = $m_endDate;
	} 

	function RoomName() {
		$RoomName = $m_roomName;
	} 

	function HouseName() {
		$HouseName = $m_houseName;
	} 

	function HospitalName() {
		$HouseName = $m_hospitalName;
	} 

	function RegDate() {
		$RegDate = $m_regDate;
	} 

	#  property setter
	# ***********************************************
	function BookNo($value) {
		$m_reservationNo=intval($value);
	} 

	function UserID($value) {
		$m_userId = trim($value);
	} 

	function RoomID($value) {
		$m_roomId=intval($value);
	} 

	function HospitalID($value) {
		$m_hospitalId=intval($value);
	} 

	function Status($value) {
		$m_reservStatus = trim($value);
	} 

	function StartDate($value) {
		$m_startDate = trim($value);
	} 

	function EndDate($value) {
		$m_endDate = trim($value);
	} 

	function RegDate($value) {
		$m_regDate = trim($value);
	} 

	#  class initialize
	# ***********************************************
	function __construct() {
		$m_reservationNo=-1;
		$m_userId="";
		$m_roomId=-1;
		$m_hospitalId=-1;
		$m_reservStatus="S0001";
		$m_startDate="";
		$m_endDate="";
		$m_regDate="";
	} 

	function __destruct() {
	} 

	function Open($number) {
		$query = "SELECT A.*, B.houseName, C.roomName from reservation A, house B, room C ";
		$query = $query."WHERE A.roomId = C.roomId AND C.houseId = B.houseId AND A.reservationNo = '".$mssqlEscapeString[$number]."'";
		$bookRS = $objDB->execute_query($query);
		if ((!$bookRS->eof && !$bookRS->bof)) {
			$m_reservationNo=intval($bookRS["reservationNo"]);
			$m_userId = $bookRS["userId"];
			$m_roomId=intval($bookRS["roomId"]);
			$m_reservStatus = $bookRS["reservStatus"];
			$m_startDate = $bookRS["startDate"];
			$m_endDate = $bookRS["endDate"];
			$m_regDate = $bookRS["regDate"];
			$m_roomName = $bookRS["roomName"];
			$m_houseName = $bookRS["houseName"];
		} 

		$bookRS = null;
	} 

	function OpenHospitalReserv($number) {
		$query = "SELECT A.*, B.hospitalName from reservation A, hospital B ";
		$query = $query."WHERE A.hospitalId = B.hospitalId AND A.reservationNo = '".$mssqlEscapeString[$number]."'";
		$bookRS = $objDB->execute_query($query);
		if ((!$bookRS->eof && !$bookRS->bof)) {
			$m_reservationNo=intval($bookRS["reservationNo"]);
			$m_userId = $bookRS["userId"];
			$m_hospitalId=intval($bookRS["hospitalId"]);
			$m_reservStatus = $bookRS["reservStatus"];
			$m_startDate = $bookRS["startDate"];
			$m_endDate = $bookRS["endDate"];
			$m_regDate = $bookRS["regDate"];
			$m_hospitalName = $bookRS["hospitalName"];
		} 

		$bookRS = null;
	} 

	function Update() {
		if (($m_reservationNo==-1)) {
			# New Data
			$query = "INSERT INTO reservation (userId, roomId, hospitalId, reservStatus, startDate, endDate, regDate) VALUES ";
			$insertData="'".$mssqlEscapeString[$m_userId]."', ";
			$insertData = $insertData."'".$m_roomId."', ";
			$insertData = $insertData."'".$m_hospitalId."', ";
			$insertData = $insertData."'".$m_reservStatus."', ";
			$insertData = $insertData."'".$m_startDate."', ";
			$insertData = $insertData."'".$m_endDate."', ";
			$insertData = $insertData."getDate()";
			$query = $query."(".$insertData.") ";
			$objDB->execute_command($query);

			$query = "SELECT MAX(reservationNo) AS new_id FROM reservation WHERE userId = '".$mssqlEscapeString[$m_userId]."'";
			$reservRS = $objDB->execute_query($query);
			if ((!$reservRS->eof && !$reservRS->bof)) {
				$m_reservationNo=intval($reservRS["new_id"]);
			} 
		} else {
			$query = "UPDATE reservation SET ";
			$updateData=" userId = '".$mssqlEscapeString[$m_userId]."', ";
			$updateData = $updateData." roomId = '".$m_roomId."', ";
			$updateData = $updateData." hospitalId = '".$m_hospitalId."', ";
			$updateData = $updateData." reservStatus = '".$m_reservStatus."', ";
			$updateData = $updateData." startDate = '".$m_startDate."', ";
			$updateData = $updateData." endDate = '".$m_endDate."'";
			$query = $query.$updateData." WHERE reservationNo = '".$m_reservationNo."'";
			$objDB->execute_command($query);
		} 

		$bookRS = null;
	} 

	function Delete() {
		$query = "delete from reservation where reservationNo = '".$m_reservationNo."'";
		$objDB->execute_command($query);
	} 

	function checkId() {
		if ((strlen($m_userId)==0 || strlen($m_roomId)==0)) {
			$retValue=false;
		} else {
			$retValue=true;
		} 

		return $retValue;
	} 

	function checkDate() {
		if ((strlen($m_startDate)==0 || strlen($m_endDate)==0)) {
			$retValue=false;
		} else {
			$retValue=true;
		} 

		return $retValue;
	} 
} 
?>

