<?php 
class MemberHelper {
	var $m_eHandler;
	var $m_pageCount;
	var $m_pageUnit;
	var $m_StrConditionQuery;
	var $m_StrOrderQuery;

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

	#  creater
	# ***********************************************
	function __construct() {
		$m_eHandler = new ErrorHandler();
		$m_pageCount=5;
		$m_pageUnit=10;
	} 

	#  destoryer
	# ***********************************************
	function __destruct() {
	} 

	#  method
	# ***********************************************
	function getMemberByUserId($userId) {
		$member = new MemberObject();

		if (($member->Open($userId)==false)) {
			$m_eHandler->ignoreError("Member Not Found.");
		} 


		$getMemberByUserId = $member;
	} 

	function getMemberByUserNick($nick) {
		$member = new MemberObject();

		if (($member->OpenByNick($nick)==false)) {
			$m_eHandler->ignoreError("Member Not Found.");
		} 


		$getMemberByUserNick = $member;
	} 

	function getMissionInfoByUserId($userId) {
		$mission = new MissionObject();

		if (($mission->Open($userId)==false)) {
			$m_eHandler->ignoreError("Member Not Found.");
		} 


		$getMissionInfoByUserId = $mission;
	} 

	function getAccountInfoByUserId($userId) {
		$account = new AccountObject();

		if (($account->Open($userId)==false)) {
			$m_eHandler->ignoreError("Account Not Found.");
		} 


		$getAccountInfoByUserId = $account;
	} 

	function getSupportByUserId($userId) {
		$support = new SupportObject();

		if (($support->Open($userId)==false)) {
			$m_eHandler->ignoreError("Supporter Not Found.");
		} 


		$getSupportByUserId = $support;
	} 

	function getFamilyType($missionId,$userId) {

		if ((strlen($userId)>0)) {
			$query = "SELECT familyType FROM family WHERE userId = '".$missionId."' AND followUserId = '".$userId."'";
			$familyRS = $db->Execute($query);
			if (!$familyRS->EOF && !$familyRS->BOF) {
				$retValue = $familyRS["familyType"];
			} else {
				$retValue=false;
			} 

			$familyRS = null;

		} else {
			$retValue=false;
		} 


		$getFamilyLevel = $retValue;
	} 

	#  method list Helper
	# *********************************************************
	function setCondition($userLv,$field,$keyword) {
		if (($userLv>0)) {
			$strWhere=" WHERE userLv = '".$userLv."'";
		} else {
			$strWhere=" WHERE userLv between 0 and 8 ";
		} 

		if ((strlen($field)>0 && strlen($keyword)>0)) {
			$strWhere = $strWhere." AND ".$field." LIKE '%".$keyword."%'";
		} 

		$m_StrConditionQuery = $strWhere;
	} 

	function setOrder($order) {
		$m_StrOrderQuery=" ORDER BY ".$order;
	} 

	function makePagingHTML($curPage) {
		$query = "SELECT COUNT(*) AS recordCount from users".$m_StrConditionQuery;
		$countRS = $db->Execute($query);
		$total = $countRS["recordCount"];
		$countRS = null;

		return $makePagingN[$curPage][$m_pageCount][$m_pageUnit][$total];
	} 

	function getMemberListWithPageing($curPage) {
		$topNum = $m_pageCount*$curPage;

		$query = "SELECT TOP ".$topNum." * FROM users ".$m_StrConditionQuery.$m_StrOrderQuery;
		$db->CursorLocation=3;
		$listRS = $db->Execute($query);
		if (($listRS->RecordCount>0)) {
			$listRS->PageSize = $m_pageCount;
			$listRS->AbsolutePage = $curPage;
		} 


		$getMemberListWithPageing = $db->Execute($query);
	} 

	function setMissionListCondition($field,$keyword) {
		if ((strlen($field)>0 && strlen($keyword)>0)) {
			$strWhere = $strWhere." AND ".$field." LIKE '%".$keyword."%'";
		} 

		$m_StrConditionQuery="WHERE approval = 1".$strWhere;
	} 

	function makePagingMissionList($curPage) {
		$query = "SELECT COUNT(*) AS recordCount from missionary ".$m_StrConditionQuery;
		$countRS = $db->Execute($query);
		$total = $countRS["recordCount"];
		$countRS = null;

		return $makePagingN[$curPage][$m_pageCount][$m_pageUnit][$total];
	} 

	function getMissionListWithPageing($curPage) {

		$topNum = $m_pageCount*$curPage;

		$query = "SELECT top ".$topNum." userid FROM missionary ".$m_StrConditionQuery." ORDER BY missionName";
		$db->CursorLocation=3;
		$missionRS = $db->Execute($query);
		if (($missionRS->RecordCount>0)) {
			$missionRS->PageSize = $m_pageCount;
			$missionRS->AbsolutePage = $curPage;
		} 


		if (!$missionRS->Eof && !$missionRS->Bof) {
			while(!($missionRS->EOF || $missionRS->BOF)) {
				$mission = new MissionObject();
				$mission->Open($missionRS["userid"]);

				$index=count($retValue);
				$retValue = $index;
				echo $mission;

				$missionRS->MoveNext;
			} 
		} 


		return $retValue;
	} 

	function getMissionList($query) {
		$memberListRS = $db->Execute($query);

		if ((!$memberListRS->Eof && !$memberListRS->Bof)) {
			while(!(($memberListRS->EOF || $memberListRS->BOF))) {
				$memberInfo = new MissionObject();
				$memberInfo->Open($memberListRS["userId"]);

				$index=count($retValue);
				$retValue = $index;
				echo $memberInfo;
				$memberListRS->MoveNext;
			} 
		} 

		return $retValue;
	} 

	function getMemberListByPrayer($userId) {
		$query = "SELECT userId FROM family WHERE familyType = 'F0002' AND followUserId = '".$userId."'";
		return getMissionList($query);
	} 

	function getMemberListByRegular($userId) {
		$query = "SELECT userId FROM family WHERE familyType = 'F0001' AND followUserId = '".$userId."'";
		return getMissionList($query);
	} 
} 
?>
