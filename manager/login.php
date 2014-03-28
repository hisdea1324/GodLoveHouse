<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
global $mysqli, $Application;

$uid = isset($_REQUEST["userid"]) ? trim($_REQUEST["userid"]) : "";
$password = isset($_REQUEST["password"]) ? trim($_REQUEST["password"]) : "";

#값이 제대로 들어있지 않다면 이전 페이지로 돌아간다.
if (strlen($uid) == 0 || strlen($password) == 0) {
	//header("Location: "."index.php");
	MoveToPage("index.php");
}

$member = new MemberObject($uid);
if (Encrypt($Application["admin_pw"]) != Encrypt($password)) {
	header("Location: "."index.php?userid=".$uid);
	echo "no matched password!!";
	//MoveToPage("index.php?userid=".$userid);
} else {
	$_SESSION['userid'] = $member->userid;
	$_SESSION['userLv'] = $member->userLv;
	header("Location: "."main.php");
	echo "{$uid} loggined!!";
	//MoveToPage("main.php");
}

/* free result set */
$result->close();
?>
