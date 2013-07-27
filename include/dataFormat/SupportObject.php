<?php 
# ************************************************************
#  Object : SupportObject
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************
class SupportObject {
	#  class member variable
	# ***********************************************
	var $m_supportId;
	var $m_userid;
	var $m_name;
	var $m_jumin;
	var $m_supType;
	var $m_sumPrice;
	var $m_phone;
	var $m_mobile;
	var $m_email;
	var $m_address1;
	var $m_address2;
	var $m_zipcode;
	var $m_status;
	var $m_regDate;
	var $m_items;

	#  Get property
	# ***********************************************
	function SupportID() {
		$SupportID = $m_supportId;
	} 

	function UserID() {
		$UserID = $m_userid;
	} 

	function Name() {
		$Name = $m_name;
	} 

	function Jumin() {
		$Jumin = $m_jumin;
	} 

	function SupportType() {
		switch (($m_supType)) {
			case "03001":
				$retValue="특별후원";
				break;
			case "03002":
				$retValue="센터후원";
				break;
			case "03003":
				$retValue="자원봉사";
				break;
		} 
		$SupportType = $retValue;
	} 

	function SumPrice() {
		$SumPrice = $m_sumPrice;
	} 

	function Phone() {
		$Phone = $m_phone;
	} 

	function Mobile() {
		$Mobile = $m_mobile;
	} 

	function Email() {
		$Email = $m_email;
	} 

	function Zipcode() {
		$Zipcode = $m_zipcode;
	} 

	function Post() {
		$Post = $m_zipcode;
	} 

	function Address1() {
		$Address1 = $m_address1;
	} 

	function Address2() {
		$Address2 = $m_address2;
	} 

	function Status() {
		$Status = $m_status;
	} 

	function SupportItem() {
		$SupportItem = $m_items;
	} 

	#  Set property 
	# ***********************************************
	function SupportID($value) {
		$m_supportId=intval($value);
	} 

	function UserID($value) {
		$m_userid = trim($value);
	} 

	function Name($value) {
		$m_name = trim($value);
	} 

	function Jumin($value) {
		$m_jumin[0]=substr(trim($value),0,6);
		$m_jumin[1]=substr(trim($value),strlen(trim($value))-(7));
	} 

	function SupportType($value) {
		$m_supType = trim($value);
	} 

	function Phone($value) {
		$m_phone=explode("-",trim($value));
	} 

	function Mobile($value) {
		$m_mobile=explode("-",trim($value));
	} 

	function Email($value) {
		$m_email=explode("@",trim($value));
	} 

	function Zipcode($value) {
		$m_zipcode[0]=substr(trim($value),0,3);
		$m_zipcode[1]=substr(trim($value),strlen(trim($value))-(3));
	} 

	function Post($value) {
		$m_zipcode[0]=substr(trim($value),0,3);
		$m_zipcode[1]=substr(trim($valuea),strlen(trim($valuea))-(3));
	} 

	function Address1($value) {
		$m_address1 = trim($value);
	} 

	function Address2($value) {
		$m_address2 = trim($value);
	} 

	function Status($value) {
		$m_status = trim($value);
	} 

	#  class initialize
	# ***********************************************
	function __construct() {
		$c_Helper = new CodeHelper();

		$m_supportId=-1;
		$m_userid="";
		$m_name="";
		$m_jumin[0]="";
		$m_jumin[1]="";
		$m_supType = $c_Helper->getSupportCode(2);
		$m_sumPrice=0;
		$m_phone = array("","","");
		$m_mobile = array("","","");
		$m_email = array("","");
		$m_zipcode[0]="";
		$m_zipcode[1]="";
		$m_address1="";
		$m_address2="";
		$m_status = $c_Helper->getSupportStatusCode(1);
		$m_regDate="";
	} 

	function __destruct() {
	} 

	#  class method
	# ***********************************************
	function IsNew() {
		if (($m_supportId<0)) {
			return true;
		} else {
			return false;
		} 
	} 

	function OpenQuery($query) {
		$supportRS = $objDB->execute_query($query);

		if ((!$supportRS->eof && !$supportRS->bof)) {
			$m_supportId=intval($supportRS["supId"]);
			$m_userid = $supportRS["userid"];
			$m_name = $supportRS["name"];
			$m_supType = $supportRS["supportType"];
			$m_jumin[0]=substr($supportRS["jumin"],0,6);
			$m_jumin[1]=substr($supportRS["jumin"],strlen($supportRS["jumin"])-(7));
			$m_phone=explode("-",$supportRS["phone"]);
			if ((count($m_phone)<2)) {
				$m_phone = array("","","");
			} 
			$m_mobile=explode("-",$supportRS["mobile"]);
			if ((count($m_mobile)<2)) {
				$m_mobile = array("","","");
			} 
			$m_email=explode("@",$supportRS["email"]);
			if ((count($m_email)<1)) {
				$m_email = array("","","");
			} 
			$m_zipcode[0]=substr($supportRS["zipcode"],0,3);
			$m_zipcode[1]=substr($supportRS["zipcode"],strlen($supportRS["zipcode"])-(3));
			$m_address1 = $supportRS["address1"];
			$m_address2 = $supportRS["address2"];
			$m_status = $supportRS["status"];
			$m_regDate = $supportRS["regDate"];

			$query = "SELECT SUM(cost) as sumPrice FROM supportItem WHERE supId = '".$m_supportId."'";
			$supportRS = $objDB->execute_query($query);
			$m_sumPrice = $supportRS["sumPrice"];

			$query = "SELECT supItemId FROM supportItem WHERE supId = '".$m_supportId."'";
			$objDB->CursorLocation=3;
			$supportItemRS = $objDB->execute_query($query);

			$index = $supportItemRS->RecordCount-1;
			for ($i=0; $i <= $index; $i = $i+1) {
				$m_items = $i;				echo new SupportItemObject();
				$m_items[$i]->Open($supportItemRS["supItemId"]);
				$supportItemRS->MoveNext;

			}
		} 

		$supportRS = null;
		$supportItemRS = null;
	} 

	function OpenWithSupId($supId) {
		$query = "SELECT supId, userId, name, supportType, status, jumin, phone, mobile, email, zipcode, address1, address2, regDate ";
		$query = $query."FROM supportInfo WHERE supId = '".$mssqlEscapeString[$supId]."'";
		OpenQuery($query);
	} 

	function Open($userid,$supType) {
		$m_userid = $userid;
		$query = "SELECT supId, userId, name, supportType, status, jumin, phone, mobile, email, zipcode, address1, address2, regDate FROM supportInfo ";
		$query = $query." WHERE userId = '".$mssqlEscapeString[$userid]."' AND supportType = '".$mssqlEscapeString[$supType]."'";
		OpenQuery($query);
	} 

	function Update() {
		if (($m_supportId==-1)) {
			# New Data
			$query = "INSERT INTO supportInfo (userId, supportType, status, name, jumin, phone, mobile, email, zipcode, address1, address2) VALUES ";
			$insertData="'".$mssqlEscapeString[$m_userid]."', ";
			$insertData = $insertData."'".$m_supType."', ";
			$insertData = $insertData."'".$m_status."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_name]."', ";
			$insertData = $insertData."'".$m_jumin[0].$m_jumin[1]."', ";
			$insertData = $insertData."'".$m_phone[0]."-".$m_phone[1]."-".$m_phone[2]."', ";
			$insertData = $insertData."'".$m_mobile[0]."-".$m_mobile[1]."-".$m_mobile[2]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_email[0]."@".$m_email[1]]."', ";
			$insertData = $insertData."'".$m_zipcode[0].$m_zipcode[1]."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_address1]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_address2]."'";
			$query = $query."(".$insertData.")";
			$objDB->execute_command($query);

			$query = "SELECT MAX(supId) AS maxSupId FROM supportInfo WHERE userid = '".$mssqlEscapeString[$m_userid]."' AND name = '".$mssqlEscapeString[$m_name]."'";
			$supportRS = $objDB->execute_query($query);
			if ((!$supportRS->eof && !$supportRS->bof)) {
				$m_supportId=intval($supportRS["maxSupId"]);
			} 
		} else {
			$query = "UPDATE supportInfo SET ";
			$updateData="name = '".$mssqlEscapeString[$m_name]."', ";
			$updateData = $updateData."supportType = '".$m_supType."',";
			$updateData = $updateData."status = '".$m_status."',";
			$updateData = $updateData."jumin = '".$m_jumin[0].$m_jumin[1]."',";
			$updateData = $updateData."phone = '".$m_phone[0]."-".$m_phone[1]."-".$m_phone[2]."',";
			$updateData = $updateData."mobile = '".$m_mobile[0]."-".$m_mobile[1]."-".$m_mobile[2]."',";
			$updateData = $updateData."email = '".$mssqlEscapeString[$m_email[0]."@".$m_email[1]]."',";
			$updateData = $updateData."zipcode = '".$m_zipcode[0].$m_zipcode[1]."',";
			$updateData = $updateData."address1 = '".$mssqlEscapeString[$m_address1]."',";
			$updateData = $updateData."address2 = '".$mssqlEscapeString[$m_address2]."'";
			$query = $query.$updateData." WHERE supId = ".$m_supportId;
			$objDB->execute_command($query);
		} 

		$supportRS = null;
	} 

	function Delete($supId,$reqId) {
		$query = "DELETE FROM supportInfo WHERE supId = ".$m_supportId;
		$objDB->execute_command($query);
	} 

	function setUserID($userid) {
		$m_userid = $userid;
		Open($userid);
	} 

	function Support($item) {
		$retValue=false;

		if (($m_supportId>0)) {
			for ($i=0; $i<=count($m_items); $i = $i+1) {
				if (($m_items[$i]->requestId == $item)) {
					$retValue=true;
				} 
			}
		} 

		return $retValue;
	} 

	function getItemCost($item) {
		$retValue=0;

		for ($i=0; $i<=count($m_items); $i = $i+1) {
			if (($m_items[$i]->requestId == $item)) {
				$retValue = $m_items[$i]->$Cost;
			} 
		}

		return $retValue;
	} 

	function changeStatus() {
		if ((strcmp($m_status,"S2001")==0)) {
			$m_status="S2002";
		} else {
			$m_status="S2001";
		} 

		Update();
	} 
} 
?>
