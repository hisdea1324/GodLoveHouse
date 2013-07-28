<?php 
# ************************************************************
#  Object : MemberObject
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************
# ************************************************************
#  MemberObject Class
#  description : get member data from member table 
# 				each member variable name is same with field name of the member table	
# ************************************************************







/*
class MemberObject {
	var $memberRS;

	#  class member variable
	# ***********************************************
	var $m_userid;
	var $m_nick;
	var $m_name;
	var $m_userLevel;
	var $m_email;
	var $m_jumin;
	var $m_address1;
	var $m_address2;
	var $m_zipcode;
	var $m_phone;
	var $m_mobile;
	var $m_msgOk;

	var $m_registDate;
	var $m_updateDate;
	var $m_LastLoginDate;

	var $m_password;
	var $m_passQuest;
	var $m_passAnswer;

	#  property getter
	# ***********************************************
	function MemberType() {

		switch ($m_userLevel) {
			case 1:
				$retValue = "일반회원";
				break;
			case 3:
				$retValue = "선교사";
				break;
			case 7:
				$retValue = "선교관관리자";
				break;
			case 9:
				$retValue = "전체관리자";
				break;
		} 
	} 

	function UserID() {
		$UserID = $m_userid;
	} 

	function Nick() {
		$Nick = $m_nick;
	} 

	function Name() {
		$Name = $m_name;
	} 

	function Level() {
		$Level = $m_userLevel;
	} 

	function UserLevel() {
		$UserLevel = $m_userLevel;
	} 

	function Email() {
		$Email = $m_email;
	} 

	function Jumin() {
		$Jumin = $m_jumin;
	} 

	function Address1() {
		$Address1 = $m_address1;
	} 

	function Address2() {
		$Address2 = $m_address2;
	} 

	function Zipcode() {
		$Zipcode = $m_zipcode;
	} 

	function Post() {
		$Post = $m_zipcode;
	} 

	function Phone() {
		$phone = $m_phone;
	} 

	function Mobile() {
		$Mobile = $m_mobile;
	} 

	function CheckMessageOption() {
		$CheckMessageOption = $m_msgOK;
	} 

	#  property setter
	# ***********************************************
	function UserID($value) {
		$m_userid = trim($value);
	} 

	function Nick($value) {
		$m_nick = trim($value);
	} 

	function Name($value) {
		$m_name = trim($value);
	} 

	function UserLevel($value) {
		$m_userLevel = trim($value);
	} 

	function Email($value) {
		$m_email[0] = trim($value[0]);
		$m_email[1] = trim($value[1]);
	} 

	function Jumin($value) {
		$m_jumin[0] = trim($value[0]);
		$m_jumin[1] = trim($value[1]);
	} 

	function Address1($value) {
		$m_address1 = trim($value);
	} 

	function Address2($value) {
		$m_address2 = trim($value);
	} 

	function Zipcode($value) {
		$m_zipcode[0] = $value[0];
		$m_zipcode[1] = $value[1];
	} 

	function Post($value) {
		$m_zipcode[0] = trim($value[0]);
		$m_zipcode[1] = trim($value[1]);
	} 

	function Phone($value) {
		$m_phone[0] = trim($value[0]);
		$m_phone[1] = trim($value[1]);
		$m_phone[2] = trim($value[2]);
	} 

	function Mobile($value) {
		$m_mobile[0] = trim($value[0]);
		$m_mobile[1] = trim($value[1]);
		$m_mobile[2] = trim($value[2]);
	} 

	function CheckMessageOption($value) {
		if ((strlen(trim($value))==0)) {
			$m_msgOK=0;
		} else {
			$m_msgOK = trim($value);
		}
	} 

	function Password($value) {
		$m_password = trim($value);
	}

	function PasswordQuestion($value) {
		$m_passQuest = trim($value);
	} 

	function PasswordAnswer($value) {
		$m_passAnswer = trim($value);
	} 

	#  class initialize
	# ***********************************************
	function __construct() {
		$m_userid="";
		$m_nick="";
		$m_name="";
		$m_userLevel=0;
		$m_email = array("","");
		$m_jumin[0]="";
		$m_jumin[1]="";
		$m_address1="";
		$m_address2="";
		$m_zipcode[0]="";
		$m_zipcode[1]="";
		$m_phone = array("","","");
		$m_mobile = array("","","");
		$m_msgOk=false;
		$m_password="";
		$m_passQuest="";
		$m_passAnswer="";
	} 

	function __destruct() {
		$memberRS = null;
	} 

	#  class method
	# ***********************************************
	function IsEnabledMessage() {
		return $m_msgOk;
	} 

	function setUserID($userid) {
		return Open($userid);
	}

	function Open($userid) {
		$query = "SELECT * from users WHERE userId = '".$mssqlEscapeString[$userid]."'";
		$memberRS = $objDB->execute_query($query);

		if ((!$memberRS->eof && !$memberRS->bof)) {
			$m_userid = $memberRS["userID"];
			$m_nick = $memberRS["nick"];
			$m_name = $memberRS["name"];
			$m_userLevel = $memberRS["userLv"];
			$m_email=explode("@",$memberRS["email"]);
			if ((count($m_email)<1)) {
				$m_email = array("","","");
			} 
			$m_jumin[0]=substr($memberRS["jumin"],0,6);
			$m_jumin[1]=substr($memberRS["jumin"],strlen($memberRS["jumin"])-(7));
			$m_address1 = $memberRS["address1"];
			$m_address2 = $memberRS["address2"];
			$m_zipcode[0]=substr($memberRS["zipcode"],0,3);
			$m_zipcode[1]=substr($memberRS["zipcode"],strlen($memberRS["zipcode"])-(3));
			$m_phone=explode("-",$memberRS["phone"]);
			if ((count($m_phone)<2)) {
				$m_phone = array("","","");
			} 
			$m_mobile=explode("-",$memberRS["mobile"]);
			if ((count($m_mobile)<2)) {
				$m_mobile = array("","","");
			} 
			$m_msgOk = $memberRS["msgOK"];
		}
	} 

	function OpenByNick($nick) {
		$query = "SELECT * from users WHERE nick = '".$mssqlEscapeString[$nick]."'";
		$memberRS = $objDB->execute_query($query);

		if ((!$memberRS->eof && !$memberRS->bof)) {
			$m_userid = $memberRS["userID"];
			$m_nick = $memberRS["nick"];
			$m_name = $memberRS["name"];
			$m_userLevel = $memberRS["userLv"];
			$m_email=explode("@",$memberRS["email"]);
			if ((count($m_email)<1)) {
				$m_email = array("","","");
			} 
			$m_jumin[0]=substr($memberRS["jumin"],0,6);
			$m_jumin[1]=substr($memberRS["jumin"],strlen($memberRS["jumin"])-(7));
			$m_address1 = $memberRS["address1"];
			$m_address2 = $memberRS["address2"];
			$m_zipcode[0]=substr($memberRS["zipcode"],0,3);
			$m_zipcode[1]=substr($memberRS["zipcode"],strlen($memberRS["zipcode"])-(3));
			$m_phone=explode("-",$memberRS["phone"]);
			if ((count($m_phone)<2)) {
				$m_phone = array("","","");
			} 
			$m_mobile=explode("-",$memberRS["mobile"]);
			if ((count($m_mobile)<2)) {
				$m_mobile = array("","","");
			} 
			$m_msgOk = $memberRS["msgOK"];
		} 
	}

	function Update() {
		$query = "SELECT * from users WHERE userId = '".$mssqlEscapeString[$m_userid]."'";
		$memberRS = $objDB->execute_query($query);

		if (($memberRS->eof || $memberRS->bof)) {
			$query = "INSERT INTO users (userId, password, passQuest, passAnswer, name, nick, userLv, email, jumin, address1, address2, zipcode, phone, mobile, msgOk) VALUES ";
			$insertData="'".$mssqlEscapeString[$m_userid]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_password]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_passQuest]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_passAnswer]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_name]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_nick]."', ";
			$insertData = $insertData."'".$m_userLevel."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$join[$m_email]["@"]]."', ";
			$insertData = $insertData."'".$m_jumin[0].$m_jumin[1]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_address1]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_address2]."', ";
			$insertData = $insertData."'".$m_zipcode[0].$m_zipcode[1]."', ";
			$insertData = $insertData."'".$join[$m_phone]["-"]."', ";
			$insertData = $insertData."'".$join[$m_mobile]["-"]."', ";
			$insertData = $insertData."'".$m_msgOK."'";
			$query = $query."(".$insertData.") ";
			$objDB->execute_command($query);
		} else {
			$query = "UPDATE users SET ";
			$updateData=" name = '".$mssqlEscapeString[$m_name]."', ";
			$updateData = $updateData." nick = '".$mssqlEscapeString[$m_nick]."', ";
			$updateData = $updateData." userLv = '".$m_userLevel."', ";
			$updateData = $updateData." email = '".$mssqlEscapeString[$join[$m_email]["@"]]."', ";
			$updateData = $updateData." jumin = '".$m_jumin[0].$m_jumin[1]."', ";
			$updateData = $updateData." address1 = '".$mssqlEscapeString[$m_address1]."', ";
			$updateData = $updateData." address2 = '".$mssqlEscapeString[$m_address2]."', ";
			$updateData = $updateData." zipcode = '".$m_zipcode[0].$m_zipcode[1]."', ";
			$updateData = $updateData." phone = '".$join[$m_phone]["-"]."', ";
			$updateData = $updateData." mobile = '".$join[$m_mobile]["-"]."', ";
			$updateData = $updateData." msgOK = '".$m_msgOK."'";
			$query = $query.$updateData." WHERE userId = '".$mssqlEscapeString[$m_userId]."'";
			$objDB->execute_command($query);
		}
	} 

	function Delete() {
		$query = "DELETE FROM users WHERE userId = '".$mssqlEscapeString[$m_userId]."'";
		$objDB->execute_command($query);
	}
} 
*/
?>
