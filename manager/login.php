<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
global $mysqli;

$userid = isset($_REQUEST["userid"]) ? trim($_REQUEST["userid"]) : "";
$password = isset($_REQUEST["password"]) ? trim($_REQUEST["password"]) : "";

#값이 제대로 들어있지 않다면 이전 페이지로 돌아간다.
if (strlen($userid) == 0 || strlen($password) == 0) {
	//header("Location: "."index.php");
	MoveToPage("index.php");
}

$member = new MemberObject($userid);
if ($member->password != Encrypt($password)) {
	echo "no matched password!!";
	//header("Location: "."index.php?userid=".$userid);
	MoveToPage("index.php?userid=".$userid);
} else {
	$_SESSION['userid'] = $userid;
	$_SESSION['userLv'] = $obj->userLv;
	echo "{$userid} loggined!!";
	//header("Location: "."main.php");
	MoveToPage("main.php");
}

/* free result set */
$result->close();
?>
