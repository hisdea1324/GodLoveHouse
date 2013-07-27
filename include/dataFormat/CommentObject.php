<?php 
//************************************************************
// Object : CommentObject
//
// editor : Sookbun Lee 
// last update date : 2010.03.04
//************************************************************
class CommentObject {
	var $m_index;
	var $m_parentId;
	var $m_hostUserId;
	var $m_followId;
	var $m_comments;
	var $m_regDate;
	var $m_secret;
	var $m_replyComments;
	var $m_replyExist;

	// Get property
	//***********************************************
	function ID() {
		$ID = $m_index;
	} 

	function HostUserId() {
		$HostUserId = $m_hostUserId;
	} 

	function FollowId() {
		$FollowId = $m_followId;
	} 

	function Comments() {
		$Comments = $m_comments;
	} 

	function RegDate() {
		$RegDate = $m_regDate;
	} 

	function Secret() {
		$Secret = $m_secret;
	} 

	function ReplyComments() {
		$ReplyComments = $m_replyComments;
	} 

	function ReplyCommentsExist() {
		$ReplyCommentsExist = $m_replyExist;
	} 

	// Set property
	//***********************************************
	function HostUserId($value) {
		$m_hostUserId = trim($value);
	} 

	function FollowId($value) {
		$m_followId = trim($value);
	} 

	function ParentID($value) {
		$m_parentId=intval($value);
	} 

	function Comments($value) {
		$m_comments = trim($value);
	} 

	function Secret($value) {
		if ((strlen(trim($value))==0)) {
			$value=0;
		} 

		$m_secret=intval($value);
	} 

	// class initialize
	//***********************************************
	function __construct() {
		$m_index=-1;
		$m_parentId=-1;
		$m_hostUserId="";
		$m_followId="";
		$m_comments="";
		$m_regDate="";
		$m_secret=0;
		$m_replyExist=false;
	} 

	function __destruct() {
		$replyCmtRS = null;
		$commentRS = null;
	} 

	// class method
	//***********************************************
	function Open($idx) {
		$query = "SELECT id, parentId, hostUserId, followId, comments, regDate, secret FROM familyComment WHERE id = '".$mssqlEscapeString[$idx]."'";
		$commentRS = $objDB->execute_query($query);

		if ((!$commentRS->eof && !$commentRS->bof)) {
			$m_index=intval($commentRS["id"]);
			$m_parentId=intval($commentRS["parentId"]);
			$m_hostUserId = $commentRS["hostUserId"];
			$m_followId = $commentRS["followId"];
			$m_comments = $commentRS["comments"];
			$m_regDate = $commentRS["regDate"];
			$m_secret=intval($commentRS["secret"]);

			$query = "SELECT id FROM familyComment WHERE parentId = '".$mssqlEscapeString[$idx]."'";
			$objDB->CursorLocation=3;
			$replyCmtRS = $objDB->execute_query($query);

			if ((!$replyCmtRS->eof && !$replyCmtRS->bof)) {
				$index = $replyCmtRS->RecordCount-1;
				for ($i=0; $i <= $index; $i = $i+1) {
					$m_replyComments = $i;					echo new CommentObject();
					$m_replyComments[$i]->$Open[$replyCmtRS["id"]];
					$replyCmtRS->MoveNext;

				}

				$m_replyExist=true;
			} 
		} 
	} 

	function Update() {
		if (($m_index==-1)) {
			//New Data
			$query = "INSERT INTO familyComment (parentId, hostUserId, followId, comments, secret) VALUES ";
			$insertData="'".$m_parentId."', ";
			$insertData = $insertData."'".$m_hostUserId."', ";
			$insertData = $insertData."'".$m_followId."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_comments]."', ";
			$insertData = $insertData."'".$m_secret."'";
			$query = $query."(".$insertData.")";
			$objDB->execute_command($query);

			$query = "SELECT MAX(id) AS new_id FROM familyComment WHERE followId = '".$m_followId."'";
			$commentRS = $objDB->execute_query($query);
			if ((!$commentRS->eof && !$commentRS->bof)) {
				$m_index=intval($commentRS["new_id"]);
			} 

		} else {
			$query = "UPDATE familyComment SET ";
			$updateData="parentId = '".$m_parentId."', ";
			$updateData = $updateData."hostUserId = '".$m_hostUserId."', ";
			$updateData = $updateData."followId = '".$m_followId."', ";
			$updateData = $updateData."comments = '".$mssqlEscapeString[$m_comments]."', ";
			$updateData = $updateData."secret = '".$m_secret."' ";
			$query = $query.$updateData." WHERE id = '".$m_index."'";
			$objDB->execute_command($query);
		}
	} 

	function Delete() {
		if (($m_index>-1)) {
			$query = "DELETE FROM familyComment WHERE id = ".$mssqlEscapeString[$m_index];
			$objDB->execute_command($query);
		}
	} 
} 
?>
