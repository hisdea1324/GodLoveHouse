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
		$PAGE_UNIT = $m_pageUnit;
	} 

	function PAGE_COUNT() {
		$PAGE_COUNT = $m_pageCount;
	} 

	function TOTAL_COUNT() {
		$TOTAL_COUNT = $m_total;
	} 

	function PAGE_UNIT($value) {
		$m_pageUnit = $value;
	} 

	function PAGE_COUNT($value) {
		$m_pageCount = $value;
	} 

	function __construct() {
		$m_eHandler = new ErrorHandler();
		$m_pageCount=5;
		$m_pageUnit=10;
		$m_total=0;
	} 

	function __destruct() {

	} 

	function getBoardGroupByGroupId($groupId) {
		$boardGrp = new BoardGroup();
		$boardGrp->Open($groupId);
		return $boardGrp;
	} 

	function getReplyInfoById($index) {
		if ((strlen(trim($index)) == 0)) {
			$index=-1;
		}
		
		$board = new BoardObject();
		$replyBoard = new BoardObject();
		$board->Open($index);
		$replyBoard->AnswerId = $board->AnswerId;
		$replyBoard->AnswerNum = $board->AnswerNum-1;
		$replyBoard->AnswerLv = $board->AnswerLv+1;
		$replyBoard->Title="[Re]".$board->Title;
		$replyBoard->Contents="<P>===================================================================</P>".$board->Contents."<P>==================================================================</P>";

		$board = null;

		return $replyBoard;
	} 

	function getBoardInfoById($index) {
		if ((strlen(trim($index)) == 0)) {
			$index = -1;
		}
		$board = new BoardObject();
		$board->Open($index);
		return $board;
	} 

	function setCondition($field,$keyword,$groupId) {
		$strWhere=" WHERE groupId = '".$mssqlEscapeString[$groupId]."'";
		if ((strlen($field)>0 && strlen($keyword)>0)) {
			$strWhere = $strWhere." AND ".$field." LIKE '%".$mssqlEscapeForLikeSearch[$keyword]."%'";
		} 

		$m_StrConditionQuery = $strWhere;
	} 

	function makePagingHTML($curPage) {
		$query = "SELECT COUNT(*) AS recordCount from board".$m_StrConditionQuery;
		$countRS = $db->Execute($query);
		$m_total = $countRS["recordCount"];
		$countRS = null;

		return $makePagingN[$curPage][$m_pageCount][$m_pageUnit][$m_total];
	} 

	function getBoardListWithPaging($curPage) {
		$topNum = $m_pageCount*$curPage;

		$query = "SELECT top ".$topNum." * FROM board".$m_StrConditionQuery." ORDER BY answerId DESC, answerNum DESC";
		$db->CursorLocation=3;
		$boardRS = $db->Execute($query);
		if (($boardRS->RecordCount>0)) {
			$boardRS->PageSize = $m_pageCount;
			$boardRS->AbsolutePage = $curPage;
		} 

		if (!$boardRS->Eof && !$boardRS->Bof) {
			while(!($boardRS->EOF || $boardRS->BOF)) {
				$board = new BoardObject();
				$board->Open($boardRS["id"]);
				$index=count($retValue);
				$retValue = $index;
				echo $board;

				$boardRS->MoveNext;
			} 
		} 

		return $retValue;
	} 
} 
?>
