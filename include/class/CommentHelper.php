<?php 
class CommentHelper {
	var $m_hostUserId;
	var $m_pageCount;
	var $m_pageUnit;

	#  Get property
	# ***********************************************
	function HostUserId() {
		$HostUserId = $this->m_hostUserId;
	} 

	#  Set property 
	# ***********************************************
	function HostUserId($value) {
		$this->m_hostUserId = $value;
	} 

	function __construct() {
		$this->m_hostUserId = "";
		$this->m_pageCount = 5;
		$this->m_pageUnit = 10;
	} 

	function __destruct() {
		

	} 

	function getCommentList($curPage) {
		if (strlen($this->m_hostUserId)>0) {
			$topNum = $this->m_pageCount * $curPage;

			$query = "SELECT top {$topNum} id FROM familyComment WHERE hostUserId = '".$this->m_hostUserId."' AND parentId = -1 ORDER BY regDate DESC";
			$commentsRS = $mysqli->Execute($query);
			if (($commentsRS->RecordCount>0)) {
				$commentsRS->PageSize = $this->m_pageCount;
				$commentsRS->AbsolutePage = $curPage;
			} 

			$retValue = 0;
			if (!$commentsRS->Eof && !$commentsRS->Bof) {
				while(!($commentsRS->EOF || $commentsRS->BOF)) {
					$comment = new CommentObject();
					$comment->Open($commentsRS["id"]);

					$index=count($retValue);
					$retValue = $index;	
					echo $comment;

					$commentsRS->MoveNext;
				} 
			} 

		} 

		return $retValue;
	} 

	function makePagingHTML($curPage) {
		$query = "SELECT COUNT(*) AS recordCount from familyComment WHERE hostUserId = '".$this->m_hostUserId."' AND parentId = -1";
		$countRS = $mysqli->Execute($query);
		$total = $countRS["recordCount"];

		return makePagingN($curPage, $this->m_pageCount, $this->m_pageUnit, $this->total);
	} 
} 
?>
