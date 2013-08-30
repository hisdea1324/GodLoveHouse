<?php 
class SupportHelper {
	protected $record = array();
	protected $eHandler;

	#  creater
	# ***********************************************
	function __construct() {
		$this->record["pageCount"] = 0;
		$this->record["pageUnit"] = 0;
		$this->record["strConditionQuery"] = "";
		$this->record["strOrderQuery"] = "";
		$this->eHandler = new ErrorHandler();
	} 

	#  destoryer
	# ***********************************************
	function __destruct() {
	} 

	#  method : return one Account Object
	# ***********************************************
	function getAccountInfoByUserId($userId) {
		$account = new AccountObject($userId);
		if (!$account) {
			$this->eHandler->ignoreError("Account Not Found.");
		} 
		return $account;
	} 

	#  method : return one Support Object
	# ***********************************************
	function getSupportByUserId($userId,$supType) {
		$support = new SupportObject();

		if ($support->Open($userId, $supType) == false) {
			$eHandler->ignoreError("Supporter Not Found.");
		} 


		return $support;
	} 

	function getSpecialSupportByUserId($userId) {
		return getSupportByUserId($userId,"03001");
	} 

	function getCenterSupportByUserId($userId) {
		return getSupportByUserId($userId,"03002");
	} 

	function getServiceSupportByUserId($userId) {
		return getSupportByUserId($userId,"03003");
	} 

	function getSupportBySupId($supId) {
		$supInfo = new SupportObject();

		if ($supInfo->OpenWithSupId($supId) == false) {
			$eHandler->ignoreError("Supporter Not Found.");
		} 


		Return $supInfo;
	} 

	#  method : return one Request Object
	# ***********************************************
	function getRequestInfoByReqID($reqId) {
		$reqInfo = new RequestObject();

		if ($reqInfo->Open($reqId) == false) {
			$this->eHandler->ignoreError("Request Infomation Not Found.");
		} 


		return $reqInfo;
	} 

	function getRequestAddInfoByReqID($reqId) {
		$reqAddInfo = new RequestAddInfo();

		if ($reqAddInfo->Open($reqId) == false) {
			$this->eHandler->ignoreError("Request Additional Infomation Not Found.");
		} 


		return $reqAddInfo;
	} 

	function getRequestItemByID($reqItemId) {
		$reqItem = new RequestItemObject();

		if ($reqItem->Open($reqItemId) == false) {
			$eHandler->ignoreError("Request Detail Item Infomation Not Found.");
		} 


		return $reqItem;
	} 

	#  method : Return Object Support List
	# ************************************************************
	function setCondition($userLv, $field, $keyword) {
		if ($userLv > 0) {
			$strWhere=" WHERE userLv = '$userLv'";
		} else {
			$strWhere=" WHERE userLv between 0 and 8 ";
		} 

		if (strlen($field) > 0 && strlen($keyword) > 0) {
			$strWhere = $strWhere." AND ".$field." LIKE '%{$keyword}%'";
		} 

		return $strWhere;
	} 

	function setOrder($order) {
		return " ORDER BY ".$order;
	} 

	function makePagingHTML($curPage) {
		global $mysqli;
		$query = "SELECT COUNT(*) AS recordCount from supportInfo".$this->record["strConditionQuery"];
		$stmt = $mysqli->prepare($query);
		$stmt->execute();
		$stmt->bind_result($this->record['recordCount']);
	
		return makePagingN($curPage, $this->record["pageCount"], $this->record["pageUnit"], $total);
	} 

	function getSupporterList($curPage) {
		global $mysqli;
		$topNum = $this->record["pageCount"] * $curPage;

		$query = "SELECT * FROM supportInfo ".$this->record["strConditionQuery"].$this->record["strOrderQuery"]." LIMIT $topNum, ".$this->record["pageCount"];
		$stmt = $mysqli->prepare($query);
		$stmt->execute();
		
		
		/*
		$listRS = $mysqli->Execute($query);

		if ($listRS->RecordCount > 0) {
			$listRS->PageSize = $this->record["pageCount"];
			$listRS->AbsolutePage = $curPage;
		} 
		*/

		return $mysqli->Execute($query);
	} 

	#  method : Return Object Request List
	# ************************************************************
	function setConditionRequestInfo($field,$keyword) {
		$strWhere="";
		if (strlen($field) > 0 && strlen($keyword) > 0) {
			$strWhere = $strWhere." AND ".$field." LIKE '%{$keyword}%'";
		} 

		return $strWhere;
	} 

	function makePagingHTMLRequestInfo($curPage) {
		global $mysqli;
		$query = "SELECT COUNT(*) AS recordCount from requestInfo WHERE supportType = '03001'".$this->record["strConditionQuery"];
		$stmt = $mysqli->prepare($query);
		$stmt->execute();
		$stmt->bind_result($this->record['recordCount']);
		//$total = $countRS["recordCount"];
		//$countRS = null;

		return makePagingN($curPage, $this->record["pageCount"], $this->record["pageUnit"], $total);
	} 

	function getRequestList($query) {

		$listRS = $mysqli->Execute($query);

		if (!$listRS->eof && !$listRS->bof) {
			while(!($listRS->eof || $listRS->bof)) {
				$requestInfo = new RequestObject();
				$requestInfo->Open($listRS["reqId"]);
			} 
		} 


		return $requestInfo;
	} 

	function getRequestListWithPaging($query,$curPage) {
		$listRS = $mysqli->Execute($query);

		if ($listRS->RecordCount > 0) {
			$listRS->PageSize = $this->record["pageCount"];
			$listRS->AbsolutePage = $curPage;
			while(!($listRS->eof || $listRS->bof)) {
				$requestInfo = new RequestObject();
				$requestInfo->Open($listRS["reqId"]);
			} 
		} 


		return $requestInfo;
	} 

	function getSpecialList($curPage) {
		$topNum = $this->record["pageCount"] * $curPage;
		$query = "SELECT TOP ".$topNum." A.reqId FROM requestInfo A, requestAddInfo B WHERE A.reqId = B.reqId AND A.supportType = '03001'".$this->record["strConditionQuery"]." ORDER BY A.regDate DESC";

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
		$query = "SELECT B.reqId FROM supportInfo A, supportItem B WHERE A.supId = B.supId AND A.supportType = '03002' AND A.userId = '{$userId}'";
		return getRequestList($query);
	} 

	function getReqListForService($userId) {
		$query = "SELECT B.reqId FROM supportInfo A, supportItem B WHERE A.supId = B.supId AND A.supportType = '03003' AND A.userId = '{$userId}'";
		return getRequestList($query);
	} 

	function getReqListForSpecial($userId) {
		$query = "SELECT DISTINCT A.reqId FROM requestInfo A, requestItem B WHERE A.reqId = B.reqId AND A.supportType = '03001' AND B.userId = '{$userId}'";
		return getRequestList($query);
	} 

	#  method : Return Object Request Item List
	# ************************************************************
	function getRequestItemList($query) {

		$listRS = $mysqli->Execute($query);

		if (!$listRS->eof && !$listRS->bof) {
			while(!($listRS->eof || $listRS->bof)) {
				$requestItem = new RequestItemObject();
				$requestItem->Open($listRS["reqItemId"]);
			} 
		} 


		return $requestItem;
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
		$dateSumRS = $mysqli->Execute($query);

		while(!($dateSumRS->Eof || $dateSumRS->Bof)) {
			$objDic[($dateSumRS["sumDate"])]=($dateSumRS["sumTotal"]);
		} 

		return $objDic;
	} 

	function getDailySupport($fromDate,$toDate) {
		#  $objDic is of type "Scripting.Dictionary"

		$query = "SELECT CONVERT(char(8), regDate, 112) AS sumDate, SUM(sumPrice) AS sumTotal FROM supportInfo ";
		$query = $query."WHERE regDate > '".$fromDate."' AND regDate < '".$toDate."' GROUP BY CONVERT(char(8), regDate, 112)";
		$dateSumRS = $mysqli->Execute($query);

		while(!(($dateSumRS->Eof || $dateSumRS->Bof))) {
			$objDic[($dateSumRS["sumDate"])]=($dateSumRS["sumTotal"]);
		} 

		return $objDic;
	} 

	function getSender($fromDate,$toDate) {
		$query = "SELECT userid FROM supportInfo WHERE regDate > '".$fromDate."' AND regDate < '".$toDate."' GROUP BY userid";
		$listRS = $mysqli->Execute($query);

		if ($listRS->RecordCount > 0) {
			while(!($listRS->eof || $listRS->bof)) {
				$senderInfo = new MemberObject();
				$senderInfo->Open($listRS["userid"]);
			} 
		} 
		return $senderInfo;
	} 

	#  method : Support Item Delete
	# ************************************************************
	function delSupItemListBySupId($supId) {
		$query = "DELETE FROM supportItem WHERE supId = '".$supId."'";
		$mysqli->Execute($query);
	}
} 
?>
