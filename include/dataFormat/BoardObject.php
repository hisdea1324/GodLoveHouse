<?php 
# ************************************************************
#  Object : BoardObject
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************
class BoardObject {
	var $boardRS;

	#  class member variable
	# ***********************************************
	var $m_index;
	var $m_groupId;
	var $m_title;
	var $m_contents;
	var $m_password;
	var $m_regDate;
	var $m_editDate;
	var $m_userId;
	var $m_countView;
	var $m_countComment;
	var $m_answerId;
	var $m_answerNum;
	var $m_answerLv;

	#  Get property
	# ***********************************************
	function BoardID() {
		$BoardID = $m_index;
	} 

	function GroupID() {
		$GroupID = $m_groupId;
	} 

	#  답변 글일 때, reply image의 html tag를 return 하는 property
	#  reply level에 따라서 앞에 빈공간을 조절한다.	
	function ReplyImage() {
		$replyImageTag="";

		if (($m_answerLv==0)) {
			$ReplyImage = $replyImageTag;
		} else {
			for ($i=1; $i <= $m_answerLv; $i = $i+1) {
				$replyImageTag = $replyImageTag."&nbsp;&nbsp;";

			}

			$replyImageTag = $replyImageTag."<img src=\"../images/board/icon_board_reply.gif\" border=\"0\"> ";
			$ReplyImage = $replyImageTag;
		} 

	} 

	function Title() {
		$Title = $m_title;
	} 

	function Contents() {
		$Contents = $m_contents;
	} 

	function RegDate() {
		$RegDate = $m_regDate;
	} 

	function EditDate() {
		$EditDate = $m_editDate;
	} 

	function UserID() {
		$UserID = $m_userId;
	} 

	function CountView() {
		$CountView = $m_countView;
	} 

	function CountComment() {
		$CountComment = $m_countComment;
	} 

	function AnswerID() {
		$AnswerID = $m_answerId;
	} 

	function AnswerNum() {
		$AnswerNum = $m_answerNum;
	} 

	function AnswerLv() {
		$AnswerLv = $m_answerLv;
	} 

	#  Set property 
	# ***********************************************
	function BoardID($value) {
		$m_index=intval($value);
	} 

	function GroupID($value) {
		$m_groupId = trim($value);
	} 

	function Title($value) {
		$m_title = trim($value);
	} 

	function Contents($value) {
		$m_contents = trim($value);
	} 

	function UserID($value) {
		$m_userId = trim($value);
	} 

	function AnswerID($value) {
		$m_answerId=intval($value);
	} 

	function AnswerNum($value) {
		$m_answerNum=intval($value);
	} 

	function AnswerLv($value) {
		$m_answerLv=intval($value);
	} 

	#  class initialize
	# ***********************************************
	function __construct() {
		$sessions = new __construct();
		$m_index=-1;
		$m_groupId="";
		$m_title="";
		$m_contents="";
		$m_password="";
		$m_regDate="";
		$m_editDate="";
		$m_userId = $sessions->UserID;
		$m_countView=0;
		$m_countComment=0;
		$m_answerId=-1;
		$m_answerNum=1000;
		$m_answerLv=0;
	} 

	function __destruct() {
		$boardRS = null;

	} 

	#  class method
	# ***********************************************
	function Open($index) {
		$query = "SELECT * from board WHERE id = '".$mssqlEscapeString[$index]."'";
		$boardRS = $objDB->execute_query($query);
		if ((!$boardRS->eof && !$boardRS->bof)) {
			$m_index=intval($boardRS["id"]);
			$m_groupId = $boardRS["groupId"];
			$m_title = $boardRS["title"];
			$m_contents = $boardRS["contents"];
			$m_password = $boardRS["password"];
			$m_regDate = $boardRS["regDate"];
			$m_editDate = $boardRS["editDate"];
			$m_userId = $boardRS["userId"];
			$m_countView=intval($boardRS["countView"]);
			$m_countComment=intval($boardRS["countComment"]);
			$m_answerId=intval($boardRS["answerId"]);
			$m_answerNum=intval($boardRS["answerNum"]);
			$m_answerLv=intval($boardRS["answerLv"]);
		} 

	} 

	function Update() {
		if (($m_index==-1)) {
			# New Data
			# transation이 필요함 나중에 처리	
			$query = "INSERT INTO board (groupId, title, contents, password, userId, answerId, answerNum, answerLv) VALUES ";
			$insertData="'".$mssqlEscapeString[$m_groupId]."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_title]."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_contents]."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_password]."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_userId]."', ";
			$insertData = $insertData."'".$m_answerId."', ";
			$insertData = $insertData."'".$m_answerNum."', ";
			$insertData = $insertData."'".$m_answerLv."'";
			$query = $query."(".$insertData.")";
			$objDB->execute_command($query);

			$query = "SELECT MAX(id) AS new_id FROM board WHERE userId = '".$m_userid."'";
			$boardRS = $objDB->execute_query($query);
			if ((!$boardRS->eof && !$boardRS->bof)) {
				$m_index=intval($boardRS["new_id"]);
			} 

			if (($m_answerId==-1)) {
				$query = "UPDATE board SET answerId = id WHERE id = '".$m_index."'";
				$m_answerId = $m_index;
			} else {
				$query = "UPDATE board SET answerNum = answerNum - 1 ";
				$query = $query."WHERE answerId = '".$mssqlEscapeString[$m_answerId]."' AND answerNum <= '".$mssqlEscapeString[$m_answerNum]."' ";
				$query = $query."AND answerLv > 0 AND id <> '".$m_index."'";
			} 

			$objDB->execute_command($query);
		} else {
			$query = "UPDATE board SET ";
			$updateData="groupId = '".$mssqlEscapeString[$m_groupId]."', ";
			$updateData = $updateData."title = '".$mssqlEscapeString[$m_title]."', ";
			$updateData = $updateData."contents = '".$mssqlEscapeString[$m_contents]."', ";
			$updateData = $updateData."password = '".$mssqlEscapeString[$m_password]."', ";
			$updateData = $updateData."editDate = getDate(), ";
			$updateData = $updateData."userId = '".$mssqlEscapeString[$m_userId]."', ";
			$updateData = $updateData."countView = '".$m_countView."', ";
			$updateData = $updateData."countComment = '".$m_countComment."', ";
			$updateData = $updateData."answerId = '".$m_answerId."', ";
			$updateData = $updateData."answerNum = '".$m_answerNum."', ";
			$updateData = $updateData."answerLv = '".$m_answerLv."' ";
			$query = $query.$updateData." WHERE id = '".$m_index."'";
			$objDB->execute_command($query);
		} 

	} 

	function Delete() {
		if (($m_index>-1)) {
			$query = "DELETE FROM board WHERE id = '".$m_index."'";
			$objDB->execute_command($query);
		} 

	} 

	function AddView() {
		$sessions = new __construct();
		if (($m_index>-1 && !$sessions->checkReadList($m_index))) {
			$query = "UPDATE board SET countView = countView + 1 WHERE id = '".$m_index."'";
			$objDB->execute_command($query);
			$m_countView = $m_countView+1;
		} 

		$sessions = null;

	} 

	function AddComment() {
		if (($m_index>-1)) {
			$query = "UPDATE board SET countComment = countComment + 1 WHERE id = '".$m_index."'";
			$objDB->execute_command($query);
			$m_countComment = $m_countComment+1;
		} 

	} 

	function checkEditPermission() {
		$sessions = new __construct();
		if (($m_index==-1 || strcmp($sessions->UserID,$m_userId)==0 || $sessions->UserLevel==9)) {
			return true;
		} else {
			return false;
		} 

	} 
} 
?>
