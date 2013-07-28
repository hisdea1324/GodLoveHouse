<?php 
# ************************************************************
#  Object : RequestAddInfo
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************
class RequestAddInfo {
	var $reqItemRS;
	var $reqInfoRS;

	var $m_reqId;
	var $m_dueDate;
	var $m_userId;
	var $m_nick;
	var $m_nation;
	var $m_email;
	var $m_nationCode;
	var $m_currentCost;
	var $m_totalCost;
	var $m_status;
	var $m_items;
	var $m_itemCount;

	#  Get property
	# ***********************************************
	function RequestID() {
		$RequestID = $m_reqId;
	} 

	function UserID() {
		$UserID = $m_userId;
	} 

	function Due() {
		$Due = $m_dueDate;
	} 

	function Nick() {
		$Nick = $m_nick;
	} 

	function Nation() {
		$Nation = $m_nation;
	} 

	function NationCode() {
		$NationCode = $m_nationCode;
	} 

	function Email() {
		$Email = $m_email;
	} 

	function CurrentCost() {
		$CurrentCost = $m_currentCost;
	} 

	function TotalCost() {
		if ((strlen($m_totalCost)==0)) {
			$m_totalCost=0;
		} 

		$TotalCost = $m_totalCost;
	} 

	function SupportRatio() {
		if (($m_totalCost>0)) {
			$retValue=round(($m_currentCost*100) / $m_totalCost);
		} else {
			$retValue=0;
		} 

		$SupportRatio = $retValue;
	} 

	function RequestItem() {
		$RequestItem = $m_items;
	} 

	#  Set property
	# ***********************************************
	function RequestID($value) {
		$m_reqId=intval($value);
	} 

	function UserID($value) {
		$m_userid = trim($value);
	} 

	function Due($value) {
		$dateValue = trim($value);
		if ((strlen($dateValue)==8)) {
			$dateValue=substr($dateValue,0,4)."-".substr($dateValue,3,2)."-".substr($dateValue,strlen($dateValue)-(2));
		} 

		$m_dueDate = $dateValue;
	} 

	function NationCode($value) {
		$m_nationCode = trim($value);
	} 

	function Status($value) {
		$m_status = trim($value);
	} 

	#  class initialize
	# ***********************************************
	function __construct() {
		$m_reqId=-1;
		$m_due="";
		$m_nick="";
		$m_nation="";
		$m_currentCost=0;
		$m_totalCost=0;
		$m_status="05001";
		$m_email="";
		$m_userId="";
		$m_itemCount=0;
	} 

	function __destruct() {
		$reqItemRS = null;
		$reqInfoRS = null;
	} 

	#  class method
	# ***********************************************
	function Open($reqId) {
		$query = "SELECT A.reqId, A.userId, B.nick, A.status, A.nationCode, C.name, A.dueDate, B.email, ";
		$query = $query." (SELECT SUM(cost) FROM requestItem WHERE reqId = A.reqId) AS totalCost, ";
		$query = $query." (SELECT SUM(cost) FROM requestItem WHERE reqId = A.reqId AND userId > '') AS currentCost ";
		$query = $query." FROM requestAddInfo A, users B, code C ";
		$query = $query." WHERE A.reqId = '".$mssqlEscapeString[$reqId]."' AND A.userId = B.userId AND A.nationCode = C.code";
		$reqInfoRS = $objDB->execute_query($query);

		if ((!$reqInfoRS->eof && !$reqInfoRS->bof)) {
			$m_reqId=intval($reqInfoRS["reqId"]);
			$m_userId = $reqInfoRS["userId"];
			$m_nick = $reqInfoRS["nick"];
			$m_status = $reqInfoRS["status"];
			$m_nationCode = $reqInfoRS["nationCode"];
			$m_nation = $reqInfoRS["name"];
			$m_dueDate = $reqInfoRS["dueDate"];
			$m_email = $reqInfoRS["email"];
			if ((!!isset($reqInfoRS["currentCost"]))) {
				$m_currentCost = $reqInfoRS["currentCost"];
			} 

			$m_totalCost = $reqInfoRS["totalCost"];

			$query = "SELECT reqItemId FROM requestItem WHERE reqId = '".$mssqlEscapeString[$reqId]."'";
			$objDB->CursorLocation=3;
			$reqItemRS = $objDB->execute_query($query);

			$m_itemCount = $reqItemRS->RecordCount;
			for ($i=0; $i <= $m_itemCount-1; $i = $i+1) {
				$m_items = $i;				echo new RequestItemObject();
				$m_items[$i]->$Open[$reqItemRS["reqItemId"]];
				$reqItemRS->MoveNext;
			}
		} 
	} 

	function Update() {
		if (($m_reqId==-1)) {
			# New Data
			$query = "INSERT INTO requestAddInfo (reqId, userId, status, dueDate, nationCode) VALUES ";
			$insertData="'".$m_reqId."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_userId]."', ";
			$insertData = $insertData."'".$m_status."', ";
			$insertData = $insertData."'".$m_dueDate."', ";
			$insertData = $insertData."'".$m_nationCode."' ";
			$query = $query."(".$insertData.")";
			$objDB->execute_command($query);

			$query = "SELECT MAX(reqId) AS new_id FROM requestAddInfo WHERE userId = '".$mssqlEscapeString[$m_userId]."'";
			$reqInfoRS = $objDB->execute_query($query);
			if ((!$reqInfoRS->eof && !$reqInfoRS->bof)) {
				$m_reqId=intval($reqInfoRS["new_id"]);
			} 

			if (($m_itemCount>0)) {
				for ($i=0; $i<=count($m_items); $i = $i+1) {
					$m_items[$i]->$insert($m_userid, $m_supType);
				}
			}
		} else {
			$query = "UPDATE requestAddInfo SET ";
			$updateData="userId = '".$mssqlEscapeString[$m_userId]."', ";
			$updateData = $updateData."status = ".$m_status.", ";
			$updateData = $updateData."dueDate = '".$m_dueDate."',";
			$updateData = $updateData."nationCode = '".$m_nationCode."'";
			$query = $query.$updateData." WHERE reqId = ".$m_reqId;
			$objDB->execute_command($query);

			#  Request Item Update
			if (($m_itemCount>0)) {
				for ($i=0; $i<=count($m_items); $i = $i+1) {
					$m_items[$i]->$update[$m_reqId];
				}
			}
		} 
	} 

	function Delete() {
		if (($m_reqId>-1)) {
			$query = "delete from requestAddInfo where reqId = '".$mssqlEscapeString[$m_reqId]."'";
			$objDB->execute_command($query);
		} 
	} 
} 
?>
