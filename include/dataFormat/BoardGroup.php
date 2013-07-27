<?php 
# ************************************************************
#  Object : BoardGroup
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************
class BoardGroup {
	var $boardRS;
	#  class member variable
	# ***********************************************
	var $m_groupId;
	var $m_managerId;
	var $m_authReadLv;
	var $m_authWriteLv;
	var $m_authCommentLv;
	var $m_countList;
	var $m_name;

	#  Get property
	# ***********************************************
	function GroupID() {
		$GroupID = $m_groupId;
	} 

	function ManagerID() {
		$ManagerID = $m_managerId;
	} 

	function CountList() {
		$CountList = $m_countList;
	} 

	function Name() {
		$Name = $m_name;
	} 

	function WritePermission() {
		$sessions = new __construct();
		if (($m_authWriteLv <= $sessions->UserLevel)) {
			$WritePermission=true;
		} else {
			$WritePermission=false;
		} 

		$sessions = null;

	} 

	function ReadPermission() {
		$sessions = new __construct();
		if (($m_authReadLv <= $sessions->UserLevel)) {
			$ReadPermission=true;
		} else {
			$ReadPermission=false;
		} 

		$sessions = null;

	} 

	function CommentPermission() {
		$sessions = new __construct();
		if (($m_authCommentLv <= $sessions->UserLevel)) {
			$CommentPermission=true;
		} else {
			$CommentPermission=false;
		} 

		$sessions = null;

	} 

	#  Set property 
	# ***********************************************
	function GroupID($value) {
		$m_groupId = trim($value);
	} 

	function ManagerID($value) {
		$m_managerId = trim($value);
	} 

	function AuthReadLv($value) {
		$m_authReadLv=intval($value);
	} 

	function AuthWriteLv($value) {
		$m_authWriteLv=intval($value);
	} 

	function AuthCommentLv($value) {
		$m_authCommentLv=intval($value);
	} 

	function Name($value) {
		$m_name = trim($value);
	} 

	#  class initialize
	# ***********************************************
	function __construct() {
		$m_groupId="";
		$m_title="";
		$m_contents="";
		$m_password="";
		$m_regDate="";
		$m_editDate="";
		$m_userId="";
		$m_countView=0;
		$m_countComment=0;
		$m_answerId=-1;
		$m_answerNum=0;
		$m_answerLv=0;
	} 

	function __destruct() {
		$boardRS = null;

	} 

	#  class method
	# ***********************************************
	function Open($groupId) {
		$query = "SELECT * from boardGroup WHERE groupId = '".$mssqlEscapeString[$groupId]."'";
		$boardRS = $objDB->execute_query($query);
		if ((!$boardRS->eof && !$boardRS->bof)) {
			$m_groupId = $boardRS["groupId"];
			$m_managerId = $boardRS["managerId"];
			$m_authReadLv=intval($boardRS["authReadLv"]);
			$m_authWriteLv=intval($boardRS["authWriteLv"]);
			$m_authCommentLv=intval($boardRS["authCommentLv"]);
			$m_countList=intval($boardRS["countList"]);
			$m_name = $boardRS["name"];
		} 
	} 

	function Update() {
		$query = "SELECT * FROM boardGroup WHERE groupId = '".$mssqlEscapeString[$m_groupId]."'";
		$boardRS = $objDB->execute_query($query);

		if (($memberRS->eof || $memberRS->bof)) {
			# New Data
			$query = "INSERT INTO boardGroup (groupId, managerId, authReadLv, authWriteLv, authCommentLv, countList, name) VALUES ";
			$insertData="'".$mssqlEscapeString[$m_groupId]."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_managerId]."',";
			$insertData = $insertData."'".$m_authReadLv."',";
			$insertData = $insertData."'".$m_authWriteLv."',";
			$insertData = $insertData."'".$m_authCommentLv."', ";
			$insertData = $insertData."'".$m_name."'";
			$query = $query."(".$insertData.")";
		} else {
			$query = "UPDATE board SET ";
			$updateData="managerId = '".$mssqlEscapeString[$m_managerId]."', ";
			$updateData = $updateData."authReadLv = '".$m_authReadLv."', ";
			$updateData = $updateData."authWriteLv = '".$m_authWriteLv."', ";
			$updateData = $updateData."authCommentLv = '".$m_authCommentLv."', ";
			$updateData = $updateData."countList = '".$m_countList."', ";
			$updateData = $updateData."name = '".$mssqlEscapeString[$m_name]."' ";
			$query = $query.$updateData." WHERE groupId = '".$mssqlEscapeString[$m_groupId]."'";
		} 

		$objDB->execute_command($query);
	} 

	function AddList() {
		$m_countList = $m_countList+1;
	} 

	function Delete() {
		$query = "DELETE FROM boardGroup WHERE groupId = '".$mssqlEscapeString[$m_groupId]."'";
		$objDB->execute_command($query);
	} 
} 
?>
