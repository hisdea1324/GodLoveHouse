<?php 
#************************************************************
# name : BoardHelper Class
#
# editor : Sookbun Lee 
# last update date : 2009/12/30
#************************************************************
class BoardHelper {
	var $m_eHandler;

	var $m_total;
	var $m_pageCount;
	var $m_pageUnit;
	var $m_StrConditionQuery;

	# property
	#***********************************************
	function PAGE_UNIT() {
		return $this->m_pageUnit;
	} 

	function PAGE_COUNT() {
		return $this->m_pageCount;
	} 

	function TOTAL_COUNT() {
		return $this->m_total;
	} 

	function PAGE_UNIT($value) {
		$this->m_pageUnit = $value;
	} 

	function PAGE_COUNT($value) {
		$this->m_pageCount = $value;
	} 

	function __construct() {
		$this->m_eHandler = new ErrorHandler();
		$this->m_pageCount = 5;
		$this->m_pageUnit = 10;
		$this->m_total = 0;
	} 

	function __destruct() {

	} 

	function getBoardGroupByGroupId($groupId) {
		$boardGrp = new BoardGroup();
		$boardGrp->Open($groupId);
		return $boardGrp;
	} 

	function getReplyInfoById($index) {
		if (strlen(trim($index)) == 0) {
			$index=-1;
		}
		
		$board = new BoardObject();
		$replyBoard = new BoardObject();
		$board->Open($index);
		$replyBoard->AnswerId = $board->AnswerId;
		$replyBoard->AnswerNum = $board->AnswerNum - 1;
		$replyBoard->AnswerLv = $board->AnswerLv + 1;
		$replyBoard->Title="[Re]".$board->Title;
		$replyBoard->Contents="<P>===================================================================</P>".$board->Contents."<P>==================================================================</P>";

		return $replyBoard;
	} 

	function getBoardInfoById($index) {
		if (strlen(trim($index)) == 0) {
			$index = -1;
		}
		$board = new BoardObject();
		$board->Open($index);
		return $board;
	} 

	function setCondition($field, $keyword, $groupId) {
		$strWhere=" WHERE groupId = '{$groupId}'";
		if (strlen($field) > 0 && strlen($keyword) > 0) {
			$strWhere = $strWhere." AND ".$field." LIKE '%{$keyword}%'";
		} 

		$this->m_StrConditionQuery = $strWhere;
	} 

	function makePagingHTML($curPage) {
		$query = "SELECT COUNT(*) AS recordCount from board".$this->m_StrConditionQuery;
		$countRS = $mysqli->Execute($query);
		$this->m_total = $countRS["recordCount"];

		return makePagingN($curPage, $this->m_pageCount, $this->m_pageUnit, $this->m_total);
	} 

	function getBoardListWithPaging($curPage) {
		$topNum = $htis->m_pageCount * $curPage;

		$query = "SELECT top {$topNum} * FROM board ".$this->m_StrConditionQuery." ORDER BY answerId DESC, answerNum DESC";
		$boardRS = $mysqli->Execute($query);
		if ($boardRS->RecordCount > 0) {
			$boardRS->PageSize = $this->m_pageCount;
			$boardRS->AbsolutePage = $curPage;
		} 

		$retValue = 0;
		if (!$boardRS->Eof && !$boardRS->Bof) {
			while(!($boardRS->EOF || $boardRS->BOF)) {
				$board = new BoardObject();
				$board->Open($boardRS["id"]);
				$index = count($retValue);
				$retValue = $index;
				echo $board;

				$boardRS->MoveNext;
			} 
		} 

		return $retValue;
	} 
} 
?>
