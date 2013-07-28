<?php 
#************************************************************
# Object : AccountObject
#
# editor : Sookbun Lee 
# last update date : 2010.03.04
#************************************************************
class AccountObject {
	var $rs_account;

	# class member variable
	#***********************************************
	var $m_index;
	var $m_userid;
	var $m_name;
	var $m_bank;
	var $m_method;
	var $m_number;
	var $m_jumin;
	var $m_sendDate;
	var $m_expectDate;
	var $m_regDate;

	# Get property
	#***********************************************
	function UserID() {
		$UserID = $m_userid;
	} 

	function Bank() {
		$Bank = $m_bank;
	} 

	function Name() {
		$Name = $m_name;
	} 

	function Method() {
		switch ($m_method) {
			case 1:
				$retValue="CMS";
				break;
			case 2:
				$retValue="DIRECT";
				break;
			default:
				$retValue="GIRO";
				break;
		} 

		$Method = $retValue;
	} 

	function Jumin() {
		$Jumin = $m_jumin;
	} 

	function MethodCode() {
		$MethodCode = $m_method;
	} 

	function Number() {
		$Number = $m_number;
	} 

	function SendDate() {
		$SendDate = $m_sendDate;
	} 

	function ExpectDate() {
		$ExpectDate = $m_expectDate;
	} 

	# Set property
	#***********************************************
	function UserID($value) {
		$m_userid = trim($value);
	} 

	function Bank($value) {
		$m_bank = trim($value);
	} 

	function Name($value) {
		$m_name = trim($value);
	} 

	function Method($value) {
		switch (trim($value)) {
			case "CMS":
				$m_method=1;
				break;
			case "DIRECT":
				$m_method=2;
				break;
			default:
				$m_method=3;
				break;
		} 
	} 

	function Jumin($value) {
		$m_jumin[0]=substr(trim($value),0,6);
		$m_jumin[1]=substr(trim($value),strlen(trim($value))-(7));
	} 

	function Number($value) {
		$m_number = trim($value);
	} 

	function SendDate($value) {
		$m_sendDate=intval($value);
	} 

	function ExpectDate($value) {
		$m_expectDate=intval($value);
	} 

	# class initialize
	#***********************************************
	function __construct() {
		$m_index=-1;
		$m_userid="";
		$m_name="";
		$m_bank="";
		$m_method=1;
		$m_number="";
		$m_jumin[2] = array("","");
		$m_sendDate=5;
		$m_expectDate=1;
		$m_regDate="";
	} 

	function __destruct() {
		$rs_account = null;
	}

	# class method
	#***********************************************
	function IsEnabledMessage() {
		return $m_msgOk;
	} 

	function Open($userid) {
		$m_userid = $userid;
		$query = "SELECT * from account WHERE userId = '".$mssqlEscapeString[$m_userid]."'";
		$rs_account = $objDB->execute_query($query);
		if ((!$rs_account->eof && !$rs_account->bof)) {
			$m_index=intval($rs_account["id"]);
			$m_userid = $rs_account["userid"];
			$m_name = $rs_account["name"];
			$m_bank = $rs_account["bank"];
			$m_method=intval($rs_account["method"]);
			$m_number = $rs_account["number"];
			$m_jumin[0]=substr($rs_account["nid"],0,6);
			$m_jumin[1]=substr($rs_account["nid"],strlen($rs_account["nid"])-(7));
			$m_sendDate=intval($rs_account["sendDate"]);
			$m_expectDate=intval($rs_account["expectDate"]);
			$m_regDate = $rs_account["regDate"];
		} 

	} 

	function Update() {
		if (($m_index==-1)) {
			#New Data
			$query = "INSERT INTO account (userId, name, bank, method, number, nid, sendDate, expectDate) VALUES ";
			$insertData="'".$mssqlEscapeString[$m_userid]."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_name]."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_bank]."',";
			$insertData = $insertData."'".$m_method."',";
			$insertData = $insertData."'".$m_number."',";
			$insertData = $insertData."'".$m_jumin[0].$m_jumin[1]."',";
			$insertData = $insertData."'".$m_sendDate."', ";
			$insertData = $insertData."'".$m_expectDate."'";
			$query = $query."(".$insertData.")";
			$objDB->execute_command($query);

			$query = "SELECT MAX(id) AS new_id FROM account WHERE userId = '".$m_userid."'";
			$rs_account = $objDB->execute_query($query);
			if ((!$rs_account->eof && !$rs_account->bof)) {
				$m_index=intval($rs_account["new_id"]);
			} 
		} else {
			$query = "UPDATE account SET ";
			$updateData="name = '".$mssqlEscapeString[$m_name]."', ";
			$updateData = $updateData."bank = '".$mssqlEscapeString[$m_bank]."', ";
			$updateData = $updateData."method = '".$m_method."', ";
			$updateData = $updateData."number = '".$m_number."', ";
			$updateData = $updateData."nid = '".$m_jumin[0].$m_jumin[1]."', ";
			$updateData = $updateData."sendDate = '".$m_sendDate."', ";
			$updateData = $updateData."expectDate = '".$m_expectDate."' ";
			$query = $query.$updateData." WHERE id = ".$m_index;
			$objDB->execute_command($query);
		} 
	} 

	function Delete() {
		if (($m_index>-1)) {
			$query = "DELETE FROM account WHERE id = ".$mssqlEscapeString[$m_index];
			$objDB->execute_command($query);
		} 
	}

	function setUserID($userid) {
		return Open($userid);
	} 
} 
?>
