<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

$userid = trim($_REQUEST["userid"]);
$password = trim($_REQUEST["password"]);

//값이 제대로 들어있지 않다면 이전 페이지로 돌아간다.
if (strlen($userid)==0 || strlen($password)==0) {
	header("Location: "."index.php");
} 

$query = "SELECT * FROM users WHERE userid = '".mysqlEscapeString($userid)."' AND password = '".mysqlEscapeString($password)."'";
$rs = $mysqli->execute($query);

if (!$Rs->eof || !$Rs->bof) {
	$_SESSION['userId'] = $userid;
	$_SESSION['userLv'] = $Rs["userLv"];
	header("Location: "."main.php");
} else {
	header("Location: "."index.php?userid=".$userid);
} 

?>
