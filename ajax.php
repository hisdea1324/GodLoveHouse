<?php
require "include/include.php";

$mode = trim($_REQUEST["mode"]);

switch (($mode)) {
	case "board":
		shortList();
		break;
	default:
		break;
} 

function shortList() {
	global $Application;
	
	$groupId = trim($_REQUEST["groupId"]);
	$query = "SELECT top 3 * FROM board WHERE groupId = '".$groupId."' ORDER BY regDate DESC";

	if ($listRS->EOF || $listRS->BOF) {
		print "<li>작성된 글이 없습니다.</li>";
	} else {
		while(false) {
			print "<li><span>".dateFormat($listRS["regDate"], 1)."</span> ";
			print "<a href='".$Application["WebRoot"]."community/view.php?groupId=".$groupId."&id=".$listRS["id"]."'>".$titleFormat[$listRS["title"]][18];
			print "</a></li>";
			$listRS->MoveNext;
		} 
	} 

	$listRS = null;
} 
?>
