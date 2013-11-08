<?php 
class CommentHelper {
	var $m_hostuserid;
	var $m_pageCount;
	var $m_pageUnit;

	#  Get property
	# ***********************************************
	function Hostuserid() {
		$Hostuserid = $this->m_hostuserid;
	} 

	#  Set property 
	# ***********************************************
	function Hostuserid($value) {
		$this->m_hostuserid = $value;
	} 

	function __construct() {
		$this->m_hostuserid = "";
		$this->m_pageCount = 5;
		$this->m_pageUnit = 10;
	} 

	function __destruct() {
		

	} 

	function getCommentList($curPage) {
		if (strlen($this->m_hostuserid)>0) {
			$topNum = $this->m_pageCount * $curPage;

			$query = "SELECT top {$topNum} id FROM familyComment WHERE hostuserid = '".$this->m_hostuserid."' AND parentId = -1 ORDER BY regDate DESC";
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
		$query = "SELECT COUNT(*) AS recordCount from familyComment WHERE hostuserid = '".$this->m_hostuserid."' AND parentId = -1";
		$countRS = $mysqli->Execute($query);
		$total = $countRS["recordCount"];

		return makePagingN($curPage, $this->m_pageCount, $this->m_pageUnit, $this->total);
	} 
} 
?>
