<?php 
function showAdminHeader($strTitle, $strJSFile1, $strJSFile2, $strCSSFile) {
	global $Application;
	$defaultTitle="::: God Love House Manager Page :::";
	if (strlen($strTitle)==0) {
		$strTitle = $defaultTitle;
	} 

	$defaulltJS1 = $Application["WebRoot"]."include/js/flash.js";
	$strJS1="<script language='javascript' src='[JS_FILE1]'></script>";
	if (strlen($strJSFile1)>0) {
		$strJS1=str_replace("[JS_FILE1]",$strJSFile1,$strJS1);
	} else {
		$strJS1=str_replace("[JS_FILE1]",$defaulltJS1,$strJS1);
	} 

	$defaulltJS2 = $Application["WebRoot"]."include/js/function.js";
	$strJS2="<script language='javascript' src='[JS_FILE2]'></script>";
	if (strlen($strJSFile2)>0) {
		$strJS2=str_replace("[JS_FILE2]",$strJSFile2,$strJS2);
	} else {
		$strJS2=str_replace("[JS_FILE2]",$defaulltJS2,$strJS2);
	} 

	$defaultCSS = $Application["WebRoot"]."include/css/style_admin.css";
	$strCSS="<link href='[CSS_FILE]' rel='StyleSheet' type='text/css' title='css'>";
	if (strlen($strCSSFile)>0) {
		$strCSS=str_replace("[CSS_FILE]",$strCSSFile,$strCSS);
	} else {
		$strCSS=str_replace("[CSS_FILE]",$defaultCSS,$strCSS);
	} 
	
	$strHeader = file_get_contents($_SERVER['DOCUMENT_ROOT']."/include/html/adminHeader.php");
	$strHeader = str_replace("[TITLE]", $strTitle, $strHeader);
	$strHeader = str_replace("[INCLUDE_JS1]", $strJS1, $strHeader);
	$strHeader = str_replace("[INCLUDE_JS2]", $strJS2, $strHeader);
	$strHeader = str_replace("[INCLUDE_CSS]", $strCSS, $strHeader);
	$strHeader = str_replace("[WEBROOT]", $Application["WebRoot"], $strHeader);
	$strHeader = str_replace("[CHARSET]", $Application["Charset"], $strHeader);

	print $strHeader;
} 

function showAdminMenu() {
	global $Application;
	
	$strHeader = file_get_contents($_SERVER['DOCUMENT_ROOT']."/include/html/adminMenu.php");
	$strMenu=str_replace("[WEBROOT]", $Application["WebRoot"], $strMenu);

	print $strMenu;
} 

function showAdminFooter() {
	print file_get_contents($_SERVER['DOCUMENT_ROOT']."/include/html/adminFooter.php");
} 
?>
