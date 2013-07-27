<?php 
class SupportHelper {
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
	} 

	#  destoryer
	# ***********************************************
	function __destruct() {
	} 

	#  method : return one Account Object
	# ***********************************************
	function getAccountInfoByUserId($userId) {
		$account = new AccountObject();

		if (($account->Open($userId)==false)) {
			$m_eHandler->ignoreError("Account Not Found.");
		} 


		$getAccountInfoByUserId = $account;
	} 

	#  method : return one Support Object
	# ***********************************************
	function getSupportByUserId($userId,$supType) {
		$support = new SupportObject();

		if (($support->Open($userId, $supType)==false)) {
			$m_eHandler->ignoreError("Supporter Not Found.");
		} 


		return $support;
	} 

	function getSpecialSupportByUserId($userId) {
		$getSpecialSupportByUserId=getSupportByUserId($userId,"03001");
	} 

	function getCenterSupportByUserId($userId) {
		$getCenterSupportByUserId=getSupportByUserId($userId,"03002");
	} 

	function getServiceSupportByUserId($userId) {
		$getServiceSupportByUserId=getSupportByUserId($userId,"03003");
	} 

	function getSupportBySupId($supId) {
		$supInfo = new SupportObject();

		if (($supInfo->OpenWithSupId($supId)==false)) {
			$m_eHandler->ignoreError("Supporter Not Found.");
		} 


		$getSupportBySupId = $supInfo;
	} 

	#  method : return one Request Object
	# ***********************************************
	function getRequestInfoByReqID($reqId) {
		$reqInfo = new RequestObject();

		if (($reqInfo->Open($reqId)==false)) {
			$m_eHandler->ignoreError("Request Infomation Not Found.");
		} 


		$getRequestInfoByReqID = $reqInfo;
	} 

	function getRequestAddInfoByReqID($reqId) {
		$reqAddInfo = new RequestAddInfo();

		if (($reqAddInfo->Open($reqId)==false)) {
			$m_eHandler->ignoreError("Request Additional Infomation Not Found.");
		} 


		$getRequestAddInfoByReqID = $reqAddInfo;
	} 

	function getRequestItemByID($reqItemId) {
		$reqItem = new RequestItemObject();

		if (($reqItem->Open($reqItemId)==false)) {
			$m_eHandler->ignoreError("Request Detail Item Infomation Not Found.");
		} 


		$getRequestItemByID = $reqItem;
	} 

	#  method : Return Object Support List
	# ************************************************************
	function setCondition($userLv,$field,$keyword) {
		if (($userLv>0)) {
			$strWhere=" WHERE userLv = '".$mssqlEscapeString[$userLv]."'";
		} else {
			$strWhere=" WHERE userLv between 0 and 8 ";
		} 

		if ((strlen($field)>0 && strlen($keyword)>0)) {
			$strWhere = $strWhere." AND ".$field." LIKE '%".$mssqlEscapeForLikeSearch[$keyword]."%'";
		} 

		$m_StrConditionQuery = $strWhere;
	} 

	function setOrder($order) {
		$m_StrOrderQuery=" ORDER BY ".$order;
	} 

	function makePagingHTML($curPage) {
		$query = "SELECT COUNT(*) AS recordCount from supportInfo".$m_StrConditionQuery;
		$countRS = $db->Execute($query);
		$total = $countRS["recordCount"];
		$countRS = null;

		return $makePagingN[$curPage][$m_pageCount][$m_pageUnit][$total];
	} 

	function getSupporterList($curPage) {
		$topNum = $m_pageCount*$curPage;

		$query = "select top ".$topNum." * from supportInfo ".$m_StrConditionQuery.$m_StrOrderQuery;
		$db->CursorLocation=3;
		$listRS = $db->Execute($query);
		if (($listRS->RecordCount>0)) {
			$listRS->PageSize = $m_pageCount;
			$listRS->AbsolutePage = $curPage;
		} 


		$getSupporterList = $db->Execute($query);
	} 

	#  method : Return Object Request List
	# ************************************************************
	function setConditionRequestInfo($field,$keyword) {
		$strWhere="";
		if ((strlen($field)>0 && strlen($keyword)>0)) {
			$strWhere = $strWhere." AND ".$field." LIKE '%".$mssqlEscapeForLikeSearch[$keyword]."%'";
		} 

		$m_StrConditionQuery = $strWhere;
	} 

	function makePagingHTMLRequestInfo($curPage) {
		$query = "SELECT COUNT(*) AS recordCount from requestInfo WHERE supportType = '03001'".$m_StrConditionQuery;
		$countRS = $db->Execute($query);
		$total = $countRS["recordCount"];
		$countRS = null;

		return $makePagingN[$curPage][$m_pageCount][$m_pageUnit][$total];
	} 

	function getRequestList($query) {

		$listRS = $db->Execute($query);

		if ((!$listRS->eof && !$listRS->bof)) {
			while(!(($listRS->eof || $listRS->bof))) {
				$requestInfo = new RequestObject();
				$requestInfo->Open($listRS["reqId"]);

				$index=count($retValue);
				$retValue = $index;				echo $requestInfo;
				$listRS->MoveNext;
			} 
		} 


		return $retValue;
	} 

	function getRequestListWithPaging($query,$curPage) {

		$db->CursorLocation=3;
		$listRS = $db->Execute($query);

		if (($listRS->RecordCount>0)) {
			$listRS->PageSize = $m_pageCount;
			$listRS->AbsolutePage = $curPage;
			while(!(($listRS->eof || $listRS->bof))) {
				$requestInfo = new RequestObject();
				$requestInfo->Open($listRS["reqId"]);

				$index=count($retValue);
				$retValue = $index;				echo $requestInfo;
				$listRS->MoveNext;
			} 
		} 


		return $retValue;
	} 

	function getSpecialList($curPage) {
		$topNum = $m_pageCount*$curPage;
		$query = "SELECT TOP ".$topNum." A.reqId FROM requestInfo A, requestAddInfo B WHERE A.reqId = B.reqId AND A.supportType = '03001'".$m_StrConditionQuery." ORDER BY A.regDate DESC";

		return getRequestListWithPaging($query,$curPage);
	} 

	function getCenterList() {
		$query = "SELECT reqId FROM requestInfo WHERE supportType = '03002'";
		return getRequestList($query);
	} 

	function getCenterListWithCond($services) {
		$query = "SELECT reqId FROM requestInfo WHERE supportType = '03002' AND reqId IN (".$services.")";
		return getRequestList($query);
	} 

	function getServiceList() {
		$query = "SELECT reqId FROM requestInfo WHERE supportType = '03003'";
		return getRequestList($query);
	} 

	function getReqListForCenter($userId) {
		$query = "SELECT B.reqId FROM supportInfo A, supportItem B WHERE A.supId = B.supId AND A.supportType = '03002' AND A.userId = '".$mssqlEscapeString[$userId]."'";
		return getRequestList($query);
	} 

	function getReqListForService($userId) {
		$query = "SELECT B.reqId FROM supportInfo A, supportItem B WHERE A.supId = B.supId AND A.supportType = '03003' AND A.userId = '".$mssqlEscapeString[$userId]."'";
		return getRequestList($query);
	} 

	function getReqListForSpecial($userId) {
		$query = "SELECT DISTINCT A.reqId FROM requestInfo A, requestItem B WHERE A.reqId = B.reqId AND A.supportType = '03001' AND B.userId = '".$mssqlEscapeString[$userId]."'";
		return getRequestList($query);
	} 

	#  method : Return Object Request Item List
	# ************************************************************
	function getRequestItemList($query) {

		$listRS = $db->Execute($query);

		if ((!$listRS->eof && !$listRS->bof)) {
			while(!(($listRS->eof || $listRS->bof))) {
				$requestItem = new RequestItemObject();
				$requestItem->Open($listRS["reqItemId"]);

				$index=count($retValue);
				$retValue = $index;				echo $requestItem;
				$listRS->MoveNext;
			} 
		} 


		return $retValue;
	} 

	function getReqItemListByReqId($reqId) {
		$query = "SELECT reqItemId FROM RequestItem WHERE reqId = ".$reqId;
		return getRequestItemList($query);
	} 

	#  method : Support Statistics
	# ************************************************************
	function getMonthlySupport($year) {
		#  $objDic is of type "Scripting.Dictionary"

		$query = "SELECT LEFT(CONVERT(char(8), regDate, 112), 6) AS sumDate, SUM(sumPrice) AS sumTotal FROM supportInfo ";
		$query = $query."WHERE regDate > '".strftime("%Y", "0101' AND regDate < '".(strftime("%Y",+1))."0101' GROUP BY Left(CONVERT(char(8), regDate, 112), 6)");
		$dateSumRS = $db->Execute($query);

		while(!(($dateSumRS->Eof || $dateSumRS->Bof))) {
			$objDic[($dateSumRS["sumDate"])]=($dateSumRS["sumTotal"]);
			$dateSumRS->MoveNext;
		} 

		$dateSumRS = null;

		$getMonthlySupport = $objDic;
	} 

	function getDailySupport($fromDate,$toDate) {
		#  $objDic is of type "Scripting.Dictionary"

		$query = "SELECT CONVERT(char(8), regDate, 112) AS sumDate, SUM(sumPrice) AS sumTotal FROM supportInfo ";
		$query = $query."WHERE regDate > '".$fromDate."' AND regDate < '".$toDate."' GROUP BY CONVERT(char(8), regDate, 112)";
		$dateSumRS = $db->Execute($query);

		while(!(($dateSumRS->Eof || $dateSumRS->Bof))) {
			$objDic[($dateSumRS["sumDate"])]=($dateSumRS["sumTotal"]);
			$dateSumRS->MoveNext;
		} 

		$dateSumRS = null;

		$getDailySupport = $objDic;
	} 

	function getSender($fromDate,$toDate) {

		$db->CursorLocation=3;
		$query = "SELECT userid FROM supportInfo WHERE regDate > '".$fromDate."' AND regDate < '".$toDate."' GROUP BY userid";
		$listRS = $db->Execute($query);

		if (($listRS->RecordCount>0)) {
			while(!(($listRS->eof || $listRS->bof))) {
				$senderInfo = new MemberObject();
				$senderInfo->Open($listRS["userid"]);

				$index=count($retValue);
				$retValue = $index;
				echo $senderInfo;
				$listRS->MoveNext;
			} 
		} 

		$listRS = null;
		return $retValue;
	} 

	#  method : Support Item Delete
	# ************************************************************
	function delSupItemListBySupId($supId) {
		$query = "DELETE FROM supportItem WHERE supId = '".$supId."'";
		$db->Execute($query);
	} 
} 
?>
