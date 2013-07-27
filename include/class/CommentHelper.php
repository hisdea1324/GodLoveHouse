<?php 
class CommentHelper {
	var $m_hostUserId;
	var $m_pageCount;
	var $m_pageUnit;

	#  Get property
	# ***********************************************
	function HostUserId() {
		$HostUserId = $m_hostUserId;
	} 

	#  Set property 
	# ***********************************************
	function HostUserId($value) {
		$m_hostUserId = $value;
	} 

	function __construct() {
		$m_hostUserId="";
		$m_pageCount=5;
		$m_pageUnit=10;
	} 

	function __destruct() {
		

	} 

	function getCommentList($curPage) {
		if ((strlen($m_hostUserId)>0)) {
			$topNum = $m_pageCount*$curPage;

			$query = "SELECT top ".$topNum." id FROM familyComment WHERE hostUserId = '".$mssqlEscapeString[$m_hostUserId]."' AND parentId = -1 ORDER BY regDate DESC";
			$db->CursorLocation=3;
			$commentsRS = $db->Execute($query);
			if (($commentsRS->RecordCount>0)) {
				$commentsRS->PageSize = $m_pageCount;
				$commentsRS->AbsolutePage = $curPage;
			} 

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
		$query = "SELECT COUNT(*) AS recordCount from familyComment WHERE hostUserId = '".$mssqlEscapeString[$m_hostUserId]."' AND parentId = -1";
		$countRS = $db->Execute($query);
		$total = $countRS["recordCount"];
		$countRS = null;

		return $makePagingN[$curPage][$m_pageCount][$m_pageUnit][$total];
	} 
} 
?>
