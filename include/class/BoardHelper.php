<?php 
#************************************************************
# name : BoardHelper Class
#
# editor : Sookbun Lee 
# last update date : 2009/12/30
#************************************************************
class BoardHelper {
	protected $record = array();

	public $m_pageCount = 0;
	public $m_pageUnit = 0;
	public $m_total = 0;
	public $m_StrConditionQuery = "";

	public function __construct() {
		$this->m_eHandler = new ErrorHandler();
	} 

	public function __destruct() {
	} 

	#  property
	# ***********************************************
	public function __get($name) {
		switch ($name) {
			case "PAGE_UNIT":
				return $this->m_pageUnit;
			case "PAGE_COUNT":
				return $this->m_pageCount;
			default: 
				return null;
		}
	}
	
	public function __set($name, $value) {
 		switch ($name) {
			case "PAGE_UNIT" :
				$this->m_pageUnit = $value;
				break;
			case "PAGE_COUNT" :
				$this->m_pageCount = $value;
				break;
		}
	}

	public function __isset($name) {
		return isset($this->record[$name]); 
	}

	function getBoardGroupByGroupId($groupId) {
		return new BoardGroup($groupId);
	} 

	function getReplyInfoById($index) {
		if (strlen(trim($index)) == 0) {
			$index=-1;
		}
		
		$board = new BoardObject($index);
		$replyBoard = new BoardObject();
		$replyBoard->answerId = $board->answerId;
		$replyBoard->answerNum = $board->answerNum - 1;
		$replyBoard->answerLv = $board->answerLv + 1;
		$replyBoard->title = "[Re]".$board->title;
		$replyBoard->contents = "=========================================================\r\n".$board->Contents."\r\n=========================================================\r\n";

		return $replyBoard;
	} 

	function getBoardInfoById($index) {
		if (strlen(trim($index)) == 0) {
			$index = -1;
		}
		return new BoardObject($index);
	} 

	function setCondition($field, $keyword, $groupId) {
		$strWhere=" WHERE groupId = '{$groupId}'";
		if (strlen($field) > 0 && strlen($keyword) > 0) {
			$strWhere = $strWhere." AND ".$field." LIKE '%{$keyword}%'";
		} 

		$this->m_StrConditionQuery = $strWhere;
	} 

	function makePagingHTML($curPage) {
		global $mysqli;
		$query = "SELECT COUNT(*) AS recordCount from board".$this->m_StrConditionQuery;
		if ($result = $mysqli->query($query)) {
			while($row = $result->fetch_array()) {
				$this->m_total = $row["recordCount"];
			}
		}

		return makePagingN($curPage, $this->PAGE_COUNT, $this->PAGE_UNIT, $this->m_total);
	} 

	function getBoardListWithPaging($curPage) {
		global $mysqli;
		
		$topNum = $this->PAGE_COUNT * ($curPage - 1);
		$board = array();
		
		$query = "SELECT * FROM board ".$this->m_StrConditionQuery." ORDER BY answerId DESC, answerNum DESC LIMIT {$topNum}, ".$this->PAGE_COUNT;

		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_array()) {
				$board[] = new BoardObject($row["id"]);
			}
		}
		
		return $board;
	} 
	
	function getNoticeList() {
		global $mysqli;
		
		$query = "SELECT * FROM board WHERE groupId = 'notice' order by editDate desc limit 1";

		$board = array();
		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_array()) {
				$board[] = new BoardObject($row["id"]);
			}
		}
		
		return $board;
	} 
} 
?>
