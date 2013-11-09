<?php 
#************************************************************
# name : BoardHelper Class
#
# editor : Sookbun Lee 
# last update date : 2009/12/30
#************************************************************
class BoardHelper {
	protected $record = array();

	# property
	#***********************************************
	
	
	public function __set($name,$value) { 
		$this->record[$name] = $value;
	}
	
	public function __get($name) { 
		return $this->record[$name];
	}
	
	public function __isset($name) {
		return isset($this->record[$name]); 
  }

  function __construct() {
		$this->record['total'] = 0;
		$this->record['pageCount'] = 5;
		$this->record['pageUnit'] = 10;
		$this->record['strConditionQuery'] = "";
	}
	
	/*
	function __construct() {
		$this->m_eHandler = new ErrorHandler();
		$this->m_pageCount = 5;
		$this->m_pageUnit = 10;
		$this->m_total = 0;
	} 
	*/

	function __destruct() {

	} 

	function getBoardGroupByGroupId($groupId) {
		return new BoardGroup($groupId);
	} 

	function getReplyInfoById($index) {
		if (strlen(trim($index)) == 0) {
			$index=-1;
		}
		
		$board = new BoardObject();
		$replyBoard = new BoardObject();
		$board->Open($index);
		$replyBoard->answerId = $board->answerId;
		$replyBoard->answerNum = $board->answerNum - 1;
		$replyBoard->answerLv = $board->answerLv + 1;
		$replyBoard->title="[Re]".$board->title;
		$replyBoard->contents="<P>===================================================================</P>".$board->Contents."<P>==================================================================</P>";

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

		$this->record['strConditionQuery'] = $strWhere;
	} 

	function makePagingHTML($curPage) {
		global $mysqli;
		$query = "SELECT COUNT(*) AS recordCount from board".$this->record['strConditionQuery'];
		if ($result = $mysqli->query($query)) {
			while($row = $result->fetch_array()) {
				$this->record['total'] = $row["recordCount"];
			}
		}

		return makePagingN($curPage, $this->record['pageCount'], $this->record['pageUnit'], $this->record['total']);
	} 

	function getBoardListWithPaging($curPage) {
		global $mysqli;
		
		$topNum = $this->record['pageCount'] * $curPage;
		$board = array();
		
		$query = "SELECT * FROM board ".$this->record['strConditionQuery']." ORDER BY answerId DESC, answerNum DESC LIMIT {$topNum}, ".$this->record['pageCount'];
		
		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_array()) {
				$board[] = new BoardObject($row["id"]);
			}
		}
		
		return $board;
	} 
} 
?>
