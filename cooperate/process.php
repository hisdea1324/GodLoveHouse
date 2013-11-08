<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

# 현재 로그인 세션
if ($_SESSION["userid"]) {
	alertGoPage("로그인해 주세요", "http://".$_SERVER['HTTP_HOST']."/member/login.php");
}

$mode = trim($_REQUEST["mode"]);
if ($mode=="editComment") {
	editComment();
} else if ($mode=="deleteComment") {
	deleteComment();
} else if ($mode=="addFamily01") {
	addFamily("F0001");
} else if ($mode=="addFamily02") {
	addFamily("F0002");
} 


function addFamily($familyType) {

	$followId = $_SESSION['userid'];
	$userid = trim($_REQUEST["userid"]);

	$ObjQuery = new DataManager();
	$fieldList = array("userid","followuserid","familyType");
	$valueList = array($userid,$followId,$familyType);
	$ObjQuery->setTable("family");
	$ObjQuery->setField($fieldList);
	$ObjQuery->setValue($valueList);
	$ObjQuery->setCondition("userid = '".$userid."' AND followuserid = '".$followId."'");
	$ObjQuery->delete();
	$ObjQuery->insert();
	$ObjQuery = null;

	header("Location: http://".$_SERVER['HTTP_HOST']."/cooperate/familyDetail.php?userid=".trim($_REQUEST["userid"]));
} 

function editComment() {
	$comment = new CommentObject();
	$comment->Open(trim($_REQUEST["editCommentId"]));

	$comment->FollowId = $_SESSION["userid"];
	$comment->Hostuserid = $_REQUEST["userid"];
	$comment->Comments = $_REQUEST["editComment"];
	$comment->Secret = $_REQUEST["editSecret"];
	$comment->parentID = $_REQUEST["parentId"];
	$comment->Update();

	$comment = null;

	header("Location: http://".$_SERVER['HTTP_HOST']."/cooperate/familyDetail.php?userid=".trim($_REQUEST["userid"]));
} 

function deleteComment() {
	$comment = new CommentObject();
	$comment->Open($_REQUEST["editCommentId"]);
	$comment->Delete();

	header("Location: http://".$_SERVER['HTTP_HOST']."/cooperate/familyDetail.php?userid=".trim($_REQUEST["userid"]));
} 
?>
