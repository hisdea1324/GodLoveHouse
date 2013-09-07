<?php
require "include/include.php";

$mode = (trim($_REQUEST["mode"])) ? trim($_REQUEST["mode"]) : "";

switch ($mode) {
	case "board":
		shortList();
		break;
	default:
		break;
} 

function shortList() {
	global $Application, $mysqli;
	
	$groupId = (trim($_REQUEST["groupId"])) ? trim($_REQUEST["groupId"]) : "";
	$query = "SELECT top 3 * FROM board WHERE groupId = '".$groupId."' ORDER BY regDate DESC";

	if ($result = $mysqli->query($query)) {
		while ($row = $result->fetched_array()) {
			print "<li><span>".dateFormat($row["regDate"], 1)."</span> ";
			print "<a href='http://".$_SERVER['SERVER_NAME']."/community/view.php?groupId=".$groupId."&id=".$row["id"]."'>".titleFormat($row["title"], 18);
			print "</a></li>";
		}
	} else {
		print "<li>작성된 글이 없습니다.</li>";
	}
} 
?>
