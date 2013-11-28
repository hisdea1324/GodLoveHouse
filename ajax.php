<?php
require "include/include.php";

$mode = isset($_REQUEST["mode"]) ? trim($_REQUEST["mode"]) : "";

switch ($mode) {
	case "board":
		shortList();
		break;
	default:
		break;
}

function shortList() {
	global $mysqli;
	
	$groupId = (trim($_REQUEST["groupId"])) ? trim($_REQUEST["groupId"]) : "";
	$query = "SELECT * FROM board WHERE groupId = '".$groupId."' ORDER BY regDate DESC limit 3";

	if ($result = $mysqli->query($query)) {
		while ($row = $result->fetch_array()) {
			print "<li><span>".date("Y.m.d", $row["regDate"])."</span> ";
			print "<a href='/community/view.php?groupId=".$groupId."&id=".$row["id"]."'>".titleFormat($row["title"], 18);
			print "</a></li>";
		}
	} else {
		print "<li>작성된 글이 없습니다.</li>";
	}
}
?>