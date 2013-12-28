<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

$mode = isset($_REQUEST["mode"]) ? trim($_REQUEST["mode"]) : "editPost";

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

	$board->GroupID = isset($_REQUEST["groupId"]) ? $_REQUEST["groupId"] : "";
	$board->Title = isset($_REQUEST["title"]) ? $_REQUEST["title"] : "";
	$board->userid = isset($_REQUEST["userid"]) ? $_REQUEST["userid"] : "";
	$board->Contents = isset($_REQUEST["contents"]) ? $_REQUEST["contents"] : "";
	$board->attachFile = isset($_REQUEST["idboardFile"]) ? $_REQUEST["idboardFile"] : "";

	$field = isset($_REQUEST["field"]) ? trim($_REQUEST["field"]) : "";
	$keyword = isset($_REQUEST["keyword"]) ? trim($_REQUEST["keyword"]) : "";
	$page = isset($_REQUEST["page"]) ? trim($_REQUEST["page"]) : 1;

	if (!$boardGrp->WritePermission()) {
		alertGoPage("권한이 없습니다.","board.php?groupId=".$board->GroupID."&page=".$page."&field=".$field."&keyword=".$keyword);
	} else if ((!$board->checkEditPermission())) {
		alertGoPage("권한이 없습니다.","board.php?groupId=".$board->GroupID."&page=".$page."&field=".$field."&keyword=".$keyword);
	} 

	$board->Update();
	header("Location: "."view.php?groupId=".$board->GroupID."&id=".$board->BoardID."&page=".$page."&field=".$field."&keyword=".$keyword);
} 

function replyPost() {
	$b_Helper = new BoardHelper();
	$boardGrp = $b_Helper->getBoardGroupByGroupId($_REQUEST["groupId"]);
	$board = $b_Helper->getBoardInfoById($_REQUEST["id"]);

	$board->GroupID = isset($_REQUEST["groupId"]) ? $_REQUEST["groupId"] : "";
	$board->userid = isset($_REQUEST["userid"]) ? $_REQUEST["userid"] : "";
	$board->Title = isset($_REQUEST["title"]) ? $_REQUEST["title"] : "";
	$board->Contents = isset($_REQUEST["contents"]) ? $_REQUEST["contents"] : "";
	$board->attachFile = isset($_REQUEST["idboardFile"]) ? $_REQUEST["idboardFile"] : "";
	$board->AnswerID = isset($_REQUEST["answerId"]) ? $_REQUEST["answerId"] : "";
	$board->AnswerNum = isset($_REQUEST["answerNum"]) ? $_REQUEST["answerNum"] : "";
	$board->AnswerLv = isset($_REQUEST["answerLv"]) ? $_REQUEST["answerLv"] : "";

	$field = isset($_REQUEST["field"]) ? trim($_REQUEST["field"]) : "";
	$keyword = isset($_REQUEST["keyword"]) ? trim($_REQUEST["keyword"]) : "";
	$page = isset($_REQUEST["page"]) ? trim($_REQUEST["page"]) : 1;

	# 쓰기권한 체크
	if (!$boardGrp->WritePermission()) {
		alertGoPage("권한이 없습니다.","board.php?groupId=".$board->GroupID."&page=".$page."&field=".$field."&keyword=".$keyword);
	} 

	$board->Update();

	header("Location: "."board.php?groupId=".$board->GroupID."&page=".$page."&field=".$field."&keyword=".$keyword);
} 

function deletePost() {
	$b_Helper = new BoardHelper();
	$boardGrp = $b_Helper->getBoardGroupByGroupId($_REQUEST["groupId"]);
	$board = $b_Helper->getBoardInfoById($_REQUEST["id"]);

	$field = isset($_REQUEST["field"]) ? trim($_REQUEST["field"]) : "";
	$keyword = isset($_REQUEST["keyword"]) ? trim($_REQUEST["keyword"]) : "";
	$page = isset($_REQUEST["page"]) ? trim($_REQUEST["page"]) : 1;

	if (!$boardGrp->WritePermission()) {
		alertGoPage("권한이 없습니다.","board.php?groupId=".$board->GroupID."&page=".$page."&field=".$field."&keyword=".$keyword);
	} else if (!$board->checkEditPermission()) {
		alertGoPage("권한이 없습니다.","board.php?groupId=".$board->GroupID."&page=".$page."&field=".$field."&keyword=".$keyword);
	} 


	$board->Delete();
	header("Location: "."board.php?groupId=".$board->GroupID."&page=".$page."&field=".$field."&keyword=".$keyword);
} 

?>
