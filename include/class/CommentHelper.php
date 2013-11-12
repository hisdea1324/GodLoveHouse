<?php 
class CommentHelper {
	var $m_hostuserid;
	var $m_pageCount;
	var $m_pageUnit;

	#  property
	# ***********************************************
	public function __set($name, $value) { 
		switch ($name) {
			case "hostuserid":
				$this->m_hostuserid = $value;
				break;
		}
	}

	public function __get($name) { 
		switch ($name) {
			case "hostuserid":
				return $this->m_hostuserid;
			default:
				return "";
		}
	}

	function __construct() {
		$this->m_hostuserid = "";
		$this->m_pageCount = 5;
		$this->m_pageUnit = 10;
	} 

	function __destruct() {
		

	} 

	function getCommentList($curPage) {
		global $mysqli;
		$ret_value = 0;	

		if (strlen($this->m_hostuserid) > 0) {
			$topNum = $this->m_pageCount * $curPage;
			$query = "SELECT top {$topNum} id FROM familyComment WHERE hostuserid = '".$this->m_hostuserid."' AND parentId = -1 ORDER BY regDate DESC";

			if ($result = $mysqli->query($query)) {
				while ($row = $result->fetch_assoc()) {
					$comment = new CommentObject($row["id"]);

					$index = count($ret_value);
					$ret_value = $index;	
				}
			}
		} 

		return $ret_value;
	} 

	function makePagingHTML($curPage) {
		global $mysqli;

		$query = "SELECT COUNT(*) AS recordCount from familyComment WHERE hostuserid = '".$this->m_hostuserid."' AND parentId = -1";
		$result = $mysqli->query($query);
		while ($row = $result->fetch_assoc()) {
			$total = $row["recordCount"];
		}	

		return makePagingN($curPage, $this->m_pageCount, $this->m_pageUnit, $this->total);
	} 
} 
?>
