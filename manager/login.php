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
$result = $mysqli->query($query);

if (!$result) {
	echo "db access something wrong!!";
	header("Location: "."index.php?userid=".$userid);
}

if ($result->num_rows > 0) {
	$obj = $result->fetch_object();
	echo "<br>".$obj->password."<br>";
	echo crypt($password, $obj->userId)."<br>";
	
	if ($obj->password != crypt($password, $obj->userId)) {
		echo "no matched password!!";
		header("Location: "."index.php?userid=".$userid);
	} else {
		$_SESSION['userId'] = $userid;
		$_SESSION['userLv'] = $obj->userLv;
		echo "{$userid} loggined!!";
		header("Location: "."main.php");
	}
	
	/* free result set */
	$result->close();
} else {
	# 관리자 생성
	$member = new MemberObject();
	$member->userId = "lovehouse";
	$member->password = "6394";
	$member->userLv = "9";
	$member->Update();
	echo "new manager created!!";
	header("Location: "."index.php?userid=".$userid);
}
?>
