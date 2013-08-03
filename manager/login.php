<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
global $mysqli;

$userid = trim($_REQUEST["userid"]);
$password = trim($_REQUEST["password"]);

#값이 제대로 들어있지 않다면 이전 페이지로 돌아간다.
if (strlen($userid) == 0 || strlen($password) == 0) {
	header("Location: "."index.php");
}

$query = "SELECT * FROM member WHERE userid = '".$userid."'";
if (!($result = $mysqli->real_query($query)) || $result->num_rows == 0) {
	echo "no user!!";
	echo "insert new manager!";
	//header("Location: "."index.php?userid=".$userid);
} 

$obj = $result->fetch_object();
if ($obj->password != crypt($password)) {
	echo "no matched password!!";
	//header("Location: "."index.php?userid=".$userid);
} 

$_SESSION['userId'] = $userid;
$_SESSION['userLv'] = $obj->userLv;
header("Location: "."main.php");

/* free result set */
$result->close();

?>
