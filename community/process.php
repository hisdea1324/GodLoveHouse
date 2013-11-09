<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

$mode = isset($_REQUEST["mode"]) ? trim($_REQUEST["mode"]) : "editPost";

echo "$mode";
switch ($mode) {
	case "replyPost":
		replyPost();
		break;
	case "editPost":
		editPost();
		break;
	case "deletePost":
		deletePost();
		break;
	default:
		print "잘못된 접근";
		exit();

		break;
} 

function editPost() {
	$b_Helper = new BoardHelper();
	$boardGrp = $b_Helper->getBoardGroupByGroupId($_REQUEST["groupId"]);
	$board = $b_Helper->getBoardInfoById($_REQUEST["id"]);
	$board->GroupID = $_REQUEST["groupId"];
	$board->Title = $_REQUEST["title"];
	$board->userid = $_REQUEST["userid"];
	$board->Contents = $_REQUEST["contents"];

	# 쓰기권한 체크	
	$field = trim($_REQUEST["field"]);
	$keyword = trim($_REQUEST["keyword"]);
	$page = trim($_REQUEST["page"]);

	if ((!$boardGrp->WritePermission)) {
		alertGoPage("권한이 없습니다.","board.php?groupId=".$board->GroupID."&page=".$page."&field=".$field."&keyword=".$keyword);
	} else if ((!$board->checkEditPermission())) {
		alertGoPage("권한이 없습니다.","board.php?groupId=".$board->GroupID."&page=".$page."&field=".$field."&keyword=".$keyword);
	} 

	$board->Update();
	header("Location: "."view.php?groupId=".$board->GroupID."&id=".$board->BoardID."&page=".$page."&field=".$field."&keyword=".$keyword);

	$board = null;
	$boardGrp = null;
	$b_Helper = null;
} 

function replyPost() {
	$b_Helper = new BoardHelper();
	$boardGrp = $b_Helper->getBoardGroupByGroupId($_REQUEST["groupId"]);
	$board = $b_Helper->getBoardInfoById($_REQUEST["id"]);
	$board->GroupID = $_REQUEST["groupId"];
	$board->userid = $_REQUEST["userid"];
	$board->Title = $_REQUEST["title"];
	$board->Contents = $_REQUEST["contents"];
	$board->AnswerID = $_REQUEST["answerId"];
	$board->AnswerNum = $_REQUEST["answerNum"];
	$board->AnswerLv = $_REQUEST["answerLv"];

	$field = trim($_REQUEST["field"]);
	$keyword = trim($_REQUEST["keyword"]);
	$page = trim($_REQUEST["page"]);

	# 쓰기권한 체크
	if ((!$boardGrp->WritePermission)) {
		alertGoPage("권한이 없습니다.","board.php?groupId=".$board->GroupID."&page=".$page."&field=".$field."&keyword=".$keyword);
	} 
	$board->Update();
	header("Location: "."board.php?groupId=".$board->GroupID."&page=".$page."&field=".$field."&keyword=".$keyword);
	$board = null;
	$boardGrp = null;
	$b_Helper = null;
} 

function deletePost() {
	$b_Helper = new BoardHelper();
	$boardGrp = $b_Helper->getBoardGroupByGroupId($_REQUEST["groupId"]);
	$board = $b_Helper->getBoardInfoById($_REQUEST["id"]);

	$field = trim($_REQUEST["field"]);
	$keyword = trim($_REQUEST["keyword"]);
	$page = trim($_REQUEST["page"]);

	if ((!$boardGrp->WritePermission)) {
		alertGoPage("권한이 없습니다.","board.php?groupId=".$board->GroupID."&page=".$page."&field=".$field."&keyword=".$keyword);
	} else if ((!$board->checkEditPermission())) {
		alertGoPage("권한이 없습니다.","board.php?groupId=".$board->GroupID."&page=".$page."&field=".$field."&keyword=".$keyword);
	} 


	$board->Delete();
	header("Location: "."board.php?groupId=".$board->GroupID."&page=".$page."&field=".$field."&keyword=".$keyword);
	$board = null;
	$boardGrp = null;
	$b_Helper = null;
} 
?>
