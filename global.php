<?php 
function Session_OnStart() {	
} 

function Session_OnEnd() {	
} 

function Application_OnStart() {
	$Application["FileRoot"]="F:\\HOME\\npngjjh\\www\\";
	$Application["WebRoot"]="http://www.godlovehouse.net/";
	$Application["Title"]="::: God's LoveHouse :::";
	$Application["Charset"]="utf-8";

	$Application["DBPass"]="admin6394";
	$Application["DBUser"]="npngjjh";
	$Application["DBName"]="npngjjh";
	$Application["DBSource"]="sql-008.cafe24.com";

	$Application["DefaultPageSize"];
	$Application["DefaultPageBlock"];

	$Application["QueryDebug"]=true;
} 

function Application_OnEnd() {	
} 
?>

