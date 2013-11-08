
<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

$mode = trim($_REQUEST["mode"]);
$groupId = trim($_REQUEST["groupId"]);
if (strlen($groupId)==0) {
	alertGoPage("잘못된 접근입니다","/index.php");
} 

$field = trim($_REQUEST["field"]);
$keyword = trim($_REQUEST["keyword"]);
$page = trim($_REQUEST["page"]);

switch (($mode)) {
	case "addPost":
		addPost();
		break;
	case "editPost":
		editPost();
		break;
	case "replyPost":
		replyPost();
		break;
	case "deletePost":
		deletePost();
		break;
	case "editGroup":
		editGroup();
		break;
} 

function addPost() {
	$id = trim($_REQUEST["id"]);
	$title = trim($_REQUEST["title"]);
	$contents = trim($_REQUEST["contents"]);
	$title=str_replace("'","''",$title);
	$contents=str_replace("'","''",$contents);

	$query = "SELECT MAX(id) + 1 AS newId FROM board";
	$rs = $mysqli->execute($query);
	if ($Rs->Eof || $Rs->Bof) {
		$answerId=1;
	} else {
		$answerId = $Rs["newId"];
	} 

	$Rs = null;


	$ObjQuery = new DataManager();
	$fieldList = array("title","contents","userid","groupId");
	$fieldNList = array("answerId","answerNum","answerLv");
	$valueList = array($title,$contents,$_SESSION['userid'],$groupId);
	$valueNList = array($answerId,0,0);

	$ObjQuery->setTable("board");
	$ObjQuery->setField($fieldList);
	$ObjQuery->setFieldNum($fieldNList);
	$ObjQuery->setValue($valueList);
	$ObjQuery->setValueNum($valueNList);
	$ObjQuery->insert();
	$ObjQuery = null;


	header("Location: "."boardList.php?groupId=".$groupId."&page=".$page."&field=".$field."&keyword=".$keyword);
} 

function replyPost() {
	global $mysqli;

	$id = trim($_REQUEST["id"]);
	$title = trim($_REQUEST["title"]);
	$contents = trim($_REQUEST["contents"]);
	$title=str_replace("'","''",$title);
	$contents=str_replace("'","''",$contents);

	$query = "SELECT MAX(answerNum) + 1 AS answerNum FROM board WHERE groupId = '".$groupId."' AND answerId = ".$id." GROUP BY answerId";
	$answerNum = 1;
	if ($result = $mysqli->query($query)) {
		while ($row = $result->fetch_assoc()) {
			$answerNum = $row["answerNum"];
		}
	}

	$query = "SELECT answerLv + 1 AS answerLv FROM board WHERE groupId = '".$groupId."' AND id = ".$id;
	$result = $mysqli->query($query);
	if ($result = $mysqli->query($query)) {
		while ($row = $result->fetch_assoc()) {
			$answerNum = $row["answerNum"];
		}
	}

	$ObjQuery = new DataManager();
	$fieldList = array("title","contents","userid","groupId");
	$fieldNList = array("answerId","answerNum","answerLv");
	$valueList = array($title,$contents,$_SESSION['userid'],$groupId);
	$valueNList = array($id,$answerNum,$answerLv);

	$ObjQuery->setTable("board");
	$ObjQuery->setField($fieldList);
	$ObjQuery->setFieldNum($fieldNList);
	$ObjQuery->setValue($valueList);
	$ObjQuery->setValueNum($valueNList);
	$ObjQuery->insert();
	$ObjQuery = null;


	header("Location: "."boardList.php?groupId=".$groupId."&page=".$page."&field=".$field."&keyword=".$keyword);
} 

function editPost() {
	$id = trim($_REQUEST["id"]);
	$title = trim($_REQUEST["title"]);
	$contents = trim($_REQUEST["contents"]);
	$title=str_replace("'","''",$title);
	$contents=str_replace("'","''",$contents);

	$ObjQuery = new DataManager();
	$fieldList = array("contents","title");
	$valueList = array($contents,$title);

	$ObjQuery->setTable("board");
	$ObjQuery->setField($fieldList);
	$ObjQuery->setValue($valueList);
	$ObjQuery->setCondition("groupId = '".$groupId."' AND id = ".$id);
	$ObjQuery->update();

	$ObjQuery = null;


	header("Location: "."boardList.php?groupId=".$groupId."&page=".$page."&field=".$field."&keyword=".$keyword);
} 

function deletePost() {
	global $mysqli;
	$id = trim($_REQUEST["id"]);
	$Sql = "delete from board where id=".$id;
	$result = $mysqli->query($sql);

	header("Location: "."boardList.php?groupId=".$groupId."&page=".$page."&field=".$field."&keyword=".$keyword);
} 

function editGroup() {
	$groupid = trim($_REQUEST["groupId"]);
	$id = trim($_REQUEST["id"]);
	$authReadLv = trim($_REQUEST["authRead"]);
	$authWriteLv = trim($_REQUEST["authWrite"]);
	$managerId = trim($_REQUEST["managerId"]);

	$ObjQuery = new DataManager();
	$fieldList = array("managerId");
	$fieldNList = array("authReadLv","authWriteLv");
	$valueList = array($managerId);
	$valueNList = array($authReadLv,$authWriteLv);

	$ObjQuery->setTable("boardGroup");
	$ObjQuery->setField($fieldList);
	$ObjQuery->setFieldNum($fieldNList);
	$ObjQuery->setValue($valueList);
	$ObjQuery->setValueNum($valueNList);
	$ObjQuery->setCondition("groupid = '".$groupid."'");
	$ObjQuery->update();
	$ObjQuery = null;


	header("Location: "."index.php");
} 
?>
