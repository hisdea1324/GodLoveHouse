<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

global $Application;

# 현재 로그인 세션
$sessions = new __construct();
if (($sessions->isSessionAlived()==false)) {
	alertGoPage("로그인해 주세요",$Application["WebRoot"]."/member/login.php");
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

	$followId = $_SESSION['UserId'];
	$userId = trim($_REQUEST["userId"]);

	$ObjQuery = new DataManager();
	$fieldList = array("userId","followUserId","familyType");
	$valueList = array($userId,$followId,$familyType);
	$ObjQuery->setTable("family");
	$ObjQuery->setField($fieldList);
	$ObjQuery->setValue($valueList);
	$ObjQuery->setCondition("userId = '".$userId."' AND followUserId = '".$followId."'");
	$ObjQuery->delete();
	$ObjQuery->insert();
	$ObjQuery = null;

	header("Location: ".$Application["WebRoot"]."cooperate/familyDetail.php?userId=".trim($_REQUEST["userId"]));
} 

function editComment() {
	$comment = new CommentObject();
	$comment->Open(trim($_REQUEST["editCommentId"]));

	$comment->FollowId = $sessions->UserID;
	$comment->HostUserId = $_REQUEST["userId"];
	$comment->Comments = $_REQUEST["editComment"];
	$comment->Secret = $_REQUEST["editSecret"];
	$comment->parentID = $_REQUEST["parentId"];
	$comment->Update();

	$comment = null;

	header("Location: ".$Application["WebRoot"]."cooperate/familyDetail.php?userId=".trim($_REQUEST["userId"]));
} 

function deleteComment() {
	$comment = new CommentObject();
	$comment->Open($_REQUEST["editCommentId"]);
	$comment->Delete();

	header("Location: ".$Application["WebRoot"]."cooperate/familyDetail.php?userId=".trim($_REQUEST["userId"]));
} 
?>
