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

	$Application["DBPass"]="";
	$Application["DBUser"]="";
	$Application["DBName"]="";
	$Application["DBSource"]="";

	$Application["DefaultPageSize"];
	$Application["DefaultPageBlock"];

	$Application["QueryDebug"]=true;
} 

function Application_OnEnd() {

} 
?>

