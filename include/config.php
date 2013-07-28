<?php
	session_start();
	session_register("UserID_session");
	session_register("UserId_session");
	session_register("UserLv_session");
	session_register("userId_session");
	header("Content-type: "."text/html;charset=utf-8");

	global $Application;
	$Application["FileRoot"]="F:\\HOME\\npngjjh\\www\\";
	$Application["WebRoot"]="http://ec2-54-248-246-249.ap-northeast-1.compute.amazonaws.com/";
	$Application["Title"]="::: God's LoveHouse :::";
	$Application["Charset"]="utf-8";

	$Application["DBPass"]="admin6394";
	$Application["DBUser"]="npngjjh";
	$Application["DBName"]="npngjjh";
	$Application["DBSource"]="sql-008.cafe24.com";

	$Application["QueryDebug"]=true;
?>