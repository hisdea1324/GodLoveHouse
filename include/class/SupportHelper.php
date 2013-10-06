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
	function getSupportByUserId($userId, $supType) {
		return new SupportObject($userId, $supType);
	} 

	function getSpecialSupportByUserId($userId) {
		return $this->getSupportByUserId($userId,"03001");
	} 

	function getCenterSupportByUserId($userId) {
		return $this->getSupportByUserId($userId,"03002");
	} 

	function getServiceSupportByUserId($userId) {
		return $this->getSupportByUserId($userId,"03003");
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
		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$this->record['recordCount'] = $row['recordCount'];
			}
			$result->close();
		}

		return makePagingN($curPage, $this->record["pageCount"], $this->record["pageUnit"], $this->record['recordCount']);
	} 

	function getRequestList($query) {
		global $mysqli;

		$requestInfo = array();
		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$requestInfo[] = new RequestObject($row["reqId"]);
			} 
		} 

		return $requestInfo;
	} 

	function getRequestListWithPaging($query, $curPage) {
		global $mysqli;

		$requestInfo = array();
		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$requestInfo[] = new RequestObject($row["reqId"]);
			} 
		}

		return $requestInfo;
	} 

	function getSpecialList($curPage) {
		$topNum = $this->record["pageCount"] * $curPage;
		$query = "SELECT TOP ".$topNum." A.reqId FROM requestInfo A, requestAddInfo B WHERE A.reqId = B.reqId AND A.supportType = '03001'".$this->record["strConditionQuery"]." ORDER BY A.regDate DESC";

		return $this->getRequestListWithPaging($query,$curPage);
	} 

	function getCenterList() {
		$query = "SELECT reqId FROM requestInfo WHERE supportType = '03002'";
		return $this->getRequestList($query);
	} 

	function getCenterListWithCond($services) {
		$query = "SELECT reqId FROM requestInfo WHERE supportType = '03002' AND reqId IN (".$services.")";
		return $this->getRequestList($query);
	} 

	function getServiceList() {
		$query = "SELECT reqId FROM requestInfo WHERE supportType = '03003'";
		return $this->getRequestList($query);
	} 

	function getReqListForCenter($userId) {
		global $mysqli;
		$query = "SELECT B.reqId FROM supportInfo A, supportItem B WHERE A.supId = B.supId AND A.supportType = '03002' AND A.userId = '".$mysqli->real_escape_string($userId)."'";
		return $this->getRequestList($query);
	} 

	function getReqListForService($userId) {
		global $mysqli;
		$query = "SELECT B.reqId FROM supportInfo A, supportItem B WHERE A.supId = B.supId AND A.supportType = '03003' AND A.userId = '".$mysqli->real_escape_string($userId)."'";
		return $this->getRequestList($query);
	} 

	function getReqListForSpecial($userId) {
		global $mysqli;
		$query = "SELECT DISTINCT A.reqId FROM requestInfo A, requestItem B WHERE A.reqId = B.reqId AND A.supportType = '03001' AND B.userId = '".$mysqli->real_escape_string($userId)."'";
		return $this->getRequestList($query);
	} 

	#  method : Return Object Request Item List
	# ************************************************************
	function getRequestItemList($query) {
		global $mysqli;

		$requestItem = array();
		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$requestItem[] = new RequestItemObject($row["reqItemId"]);
			} 
		} 

		return $requestItem;
	} 

	function getReqItemListByReqId($reqId) {
		global $mysqli;
		$query = "SELECT reqItemId FROM RequestItem WHERE reqId = '".$mysqli->real_escape_string($reqId)."'";
		return $this->getRequestItemList($query);
	} 

	#  method : Support Statistics
	# ************************************************************
	function getMonthlySupport($year) {
		#  $objDic is of type "Scripting.Dictionary"
		global $mysqli;

		$objDic = array();
		$query = "SELECT LEFT(CONVERT(char(8), regDate, 112), 6) AS sumDate, SUM(sumPrice) AS sumTotal FROM supportInfo ";
		$query = $query."WHERE regDate > '".strftime("%Y", "0101' AND regDate < '".(strftime("%Y",+1))."0101' GROUP BY Left(CONVERT(char(8), regDate, 112), 6)");
		echo $query;
		
		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$objDic[$row["sumDate"]] = $row["sumTotal"];
			} 
			$result->close();
		}

		return $objDic;
	} 

	function getDailySupport($fromDate, $toDate) {
		#  $objDic is of type "Scripting.Dictionary"
		global $mysqli;

		$objDic = array();
		$query = "SELECT regDate, SUM(sumPrice) AS sumTotal FROM supportInfo ";
		$query = $query."WHERE regDate > '".dateToTimestamp($fromDate)."' AND regDate < '".dateToTimestamp($toDate)."' GROUP BY regDate";
		
		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$objDic[$row["regDate"]] = $row["sumTotal"];
			} 
			$result->close();
		}

		return $objDic;
	} 

	function getSender($fromDate, $toDate) {
		global $mysqli;

		$senderInfo = array();
		$query = "SELECT userid FROM supportInfo WHERE regDate > '".$mysqli->real_escape_string($fromDate)."' AND regDate < '".$mysqli->real_escape_string($toDate)."' GROUP BY userid";

		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				$senderInfo = new MemberObject($row["userid"]);
			} 
			$result->close();
		}

		return $senderInfo;
	} 

	#  method : Support Item Delete
	# ************************************************************
	function delSupItemListBySupId($supId) {
		global $mysqli;

		$query = "DELETE FROM supportItem WHERE supId = '".$mysqli->real_escape_string($supId)."'";
		if ($result = $mysqli->query($query)) {
			$result->close();
		} else {
			echo "delSupItemListBySupId function no data";
		}
	}
} 
?>
