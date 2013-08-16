<?php
function authority($limitLevel) {
	if ($_SESSION['userLv'] < $limitLevel) {
		$retValue=false;
	} else {
		$retValue=true;
	} 

	return $retValue;
} 

function showSimpleHeader($strNavi,$strSub,$strTitleImg) {
	global $Application;
	
	$strHeader = file_get_contents($_SERVER['DOCUMENT_ROOT']."/include/html/header_simple.php");
	$strHeader = str_replace("[TITLE]",$Application["Title"],$strHeader);
	$strHeader = str_replace("[WEBROOT]",$Application["WebRoot"],$strHeader);
	$strHeader = str_replace("[CHARSET]",$Application["Charset"],$strHeader);

	if ((strlen($_SESSION['userId'])==0)) {
		$strHeader = str_replace("[LOGIN_STATUS1]","gm_01",$strHeader);
		$strHeader = str_replace("[LOGIN_STATUS2]","gm_02",$strHeader);
		$strHeader = str_replace("[LOGIN_VALUE1]","1",$strHeader);
		$strHeader = str_replace("[LOGIN_VALUE2]","2",$strHeader);
	} else {
		$strHeader = str_replace("[LOGIN_STATUS1]","gm_logout",$strHeader);
		$strHeader = str_replace("[LOGIN_STATUS2]","gm_mypage",$strHeader);
		$strHeader = str_replace("[LOGIN_VALUE1]","4",$strHeader);
		$strHeader = str_replace("[LOGIN_VALUE2]","5",$strHeader);
	} 

	$strSubMenu = file_get_contents($_SERVER['DOCUMENT_ROOT']."/include/html/subMenu/".$strSub.".php");
	$strSubMenu = str_replace("[WEBROOT]",$Application["WebRoot"],$strSubMenu);
	$strSubMenu = str_replace("[TITLEIMG]",$strTitleImg,$strSubMenu);
	$strSubMenu = str_replace("[NAVIGATION]",$strNavi,$strSubMenu);

	print $strHeader.$strSubMenu;
} 

function showHeader($strNavi,$strSub,$strTitleImg) {
	global $Application;
	
	$strHeader = file_get_contents($_SERVER['DOCUMENT_ROOT']."/include/html/header.php");
	$strHeader = str_replace("[TITLE]",$Application["Title"],$strHeader);
	$strHeader = str_replace("[WEBROOT]",$Application["WebRoot"],$strHeader);
	$strHeader = str_replace("[CHARSET]",$Application["Charset"],$strHeader);

	if (isset($_SESSION['userid'])) {
		$strHeader = str_replace("[LOGIN_STATUS1]","gm_01",$strHeader);
		$strHeader = str_replace("[LOGIN_STATUS2]","gm_02",$strHeader);
		$strHeader = str_replace("[LOGIN_VALUE1]","1",$strHeader);
		$strHeader = str_replace("[LOGIN_VALUE2]","2",$strHeader);
	} else {
		$strHeader = str_replace("[LOGIN_STATUS1]","gm_logout",$strHeader);
		$strHeader = str_replace("[LOGIN_STATUS2]","gm_mypage",$strHeader);
		$strHeader = str_replace("[LOGIN_VALUE1]","4",$strHeader);
		$strHeader = str_replace("[LOGIN_VALUE2]","5",$strHeader);
	} 

	$strSubMenu = file_get_contents($_SERVER['DOCUMENT_ROOT']."/include/html/subMenu/".$strSub.".php");
	$strSubMenu = str_replace("[WEBROOT]",$Application["WebRoot"],$strSubMenu);
	$strSubMenu = str_replace("[TITLEIMG]",$strTitleImg,$strSubMenu);
	$strSubMenu = str_replace("[NAVIGATION]",$strNavi,$strSubMenu);

	print $strHeader.$strSubMenu;
} 

function showMenu() {
	global $Application;
	
	$strMenu = file_get_contents($_SERVER['DOCUMENT_ROOT']."/include/html/adminMenu.php");
	$strMenu = str_replace("[WEBROOT]",$Application["WebRoot"],$strMenu);

	print $strMenu;
} 

function showSimpleFooter() {
	global $Application;
	
	$strFooter = file_get_contents($_SERVER['DOCUMENT_ROOT']."/include/html/footer_simple.php");
	$strFooter = str_replace("[WEBROOT]",$Application["WebRoot"],$strFooter);

	print $strFooter;
} 

function showFooter() {
	global $Application;
	
	$strFooter = file_get_contents($_SERVER['DOCUMENT_ROOT']."/include/html/footer.php");
	$strFooter = str_replace("[WEBROOT]",$Application["WebRoot"],$strFooter);

	print $strFooter;
} 

function needUserLv($level) {
	global $Application;
	
	if (isset($_SESSION['userId'])) {
		header("Location: ".$Application["WebRoot"]."member/login.php");
	} 

	if ($_SESSION['userLv'] < $level) {
		alertBack("권한이 없습니다");
	} 
} 

function checkUserLogin() {
	global $Application;
	
	if (strlen($_SESSION['UserId']) == 0) {
		header("Location: ".$Application["WebRoot"]."member/login.php");
	} 
} 

function checkAuthorize($groupId, $checkMode) {
	if (strlen($groupId) == 0) {
		return false;
	}

	$query = "select * from boardGroup where groupId='".$groupId."'";

	$authRS = array();
	switch ($checkMode) {
		case "W":
			$authLv = $authRS["authWriteLv"];
			break;
		case "R":
			$authLv = $authRS["authReadLv"];
			break;
		case "C":
			$authLv = $authRS["authCommentLv"];
			break;
		default:
			return false;
			break;
	} 

	if ($authLv > $_SESSION['UserLv']) {
		return false;
	} else {
		return true;
	} 
} 

function get_path_info() {
    return $_SERVER['SCRIPT_NAME'];    
}

function makePaging($page, $pageCount, $pageUnit, $query) {
	global $Application, $mysqli;

	if ($result = $mysqli->query($query)) {
	    /* determine number of rows result set */
	    $total = $result->num_rows;
	    /* close result set */
	    $result->close();
	} else {
		$total = 0;
	}

	$pathInfo = get_path_info();
	if (strlen($_SERVER["QUERY_STRING"]) > 0) {
		if ((strpos($_SERVER["QUERY_STRING"],"page=") ? strpos($_SERVER["QUERY_STRING"], "page=") + 1 : 0) > 0) {
			$tempString = substr($_SERVER["QUERY_STRING"], strlen($_SERVER["QUERY_STRING"]) - (strlen($_SERVER["QUERY_STRING"])-(strpos($_SERVER["QUERY_STRING"],"page=") ? strpos($_SERVER["QUERY_STRING"],"page=")+1 : 0)+1));
			if (strpos($tempString,"&") ? strpos($tempString,"&") + 1 : 0) {
				$queryString=substr($_SERVER["QUERY_STRING"],0,(strpos($_SERVER["QUERY_STRING"],"page=") ? strpos($_SERVER["QUERY_STRING"],"page=")+1 : 0)-1).substr($tempString,strlen($tempString)-(strlen($tempString)-(strpos($tempString,"&") ? strpos($tempString,"&")+1 : 0)+1))."&";
			} else {
				$queryString=substr($_SERVER["QUERY_STRING"],0,(strpos($_SERVER["QUERY_STRING"],"page=") ? strpos($_SERVER["QUERY_STRING"],"page=")+1 : 0)-1);
			} 
		} else {
			$queryString = $_SERVER["QUERY_STRING"]."&";
		} 
	} else {
		$queryString = "";
	}

	$linkUrl = $pathInfo."?".$queryString;

	# 임시코드 : 나중에 수정합시다.
	$linkUrl = str_replace("?&", "?", str_replace("?&", "?", $linkUrl));
	$linkUrl = str_replace("&&", "&", str_replace("&&", "&", $linkUrl));

	$totalPage = round($total / $pageCount);
	$prevPage = round($page / $pageUnit) * 10 + 1;
	$nextPage = $prevPage + 10;
	if ($nextPage > $totalPage) {
		$nextPage = $totalPage;
	} 

	$str = "<div class='paging'><a href='".$linkUrl."page=1'> <img src='".$Application["WebRoot"]."images/board/btn_pre_02.gif' alt=''/></a> <a href='".$linkUrl."page=".$prevPage."'><img src='".$Application["WebRoot"]."images/board/btn_pre_01.gif' alt='' /></a> <span class='pagingText'>";
	for ($i = 1; $i <= $totalPage; $i++) {
		if ($i - $page == 0) {
			$str = $str."<b><a href='".$linkUrl."page=".$i."'>".$i."</a></b> | ";
		} else {
			$str = $str."<a href='".$linkUrl."page=".$i."'>".$i."</a> | ";
		} 
	}

	$str = substr($str, 0, strlen($str) - 2);
	$str = $str."</span> <a href='".$linkUrl."page=".$nextPage."'><img src='".$Application["WebRoot"]."images/board/btn_next_01.gif' alt='' /></a> <a href='".$linkUrl."page=".$totalPage."'><img src='".$Application["WebRoot"]."images/board/btn_next_02.gif' alt='' /></a> </div>";
	return $str;
} 

function makePagingN($page, $pageCount, $pageUnit, $total) {
	global $Application;
	
	$pathInfo = get_path_info();
	if (strlen($_SERVER["QUERY_STRING"]) > 0) {
		if ((strpos($_SERVER["QUERY_STRING"],"page=") ? strpos($_SERVER["QUERY_STRING"],"page=")+1 : 0) > 0) {
			$tempString=substr($_SERVER["QUERY_STRING"],strlen($_SERVER["QUERY_STRING"])-(strlen($_SERVER["QUERY_STRING"])-(strpos($_SERVER["QUERY_STRING"],"page=") ? strpos($_SERVER["QUERY_STRING"],"page=")+1 : 0)+1));
			if ((strpos($tempString,"&") ? strpos($tempString,"&")+1 : 0)) {
				$queryString=substr($_SERVER["QUERY_STRING"],0,(strpos($_SERVER["QUERY_STRING"],"page=") ? strpos($_SERVER["QUERY_STRING"],"page=")+1 : 0)-1).substr($tempString,strlen($tempString)-(strlen($tempString)-(strpos($tempString,"&") ? strpos($tempString,"&")+1 : 0)+1))."&";
			} else {
				$queryString=substr($_SERVER["QUERY_STRING"],0,(strpos($_SERVER["QUERY_STRING"],"page=") ? strpos($_SERVER["QUERY_STRING"],"page=")+1 : 0)-1);
			} 
		} else {
			$queryString = $_SERVER["QUERY_STRING"]."&";
		} 
	} else {
		$queryString="";
	} 

	$linkUrl = $pathInfo."?".$queryString;

	# 임시코드 : 나중에 수정합시다.
	$linkUrl=str_replace("?&","?",str_replace("?&","?",$linkUrl));
	$linkUrl=str_replace("&&","&",str_replace("&&","&",$linkUrl));

	$totalPage = round($total / $pageCount) + 1;
	$prevPage = round($page / $pageUnit) * 10 + 1;
	$nextPage = $prevPage + 10;
	if ($nextPage > $totalPage) {
		$nextPage = $totalPage;
	} 

	$str="<div class='paging'><a href='".$linkUrl."page=1'> <img src='".$Application["WebRoot"]."images/board/btn_pre_02.gif' alt=''/></a> <a href='".$linkUrl."page=".$prevPage."'><img src='".$Application["WebRoot"]."images/board/btn_pre_01.gif' alt='' /></a> <span class='pagingText'>";
	for ($i=1; $i <= $totalPage; $i = $i+1) {
		if (($i-$page==0)) {
			$str = $str."<b><a href='".$linkUrl."page=".$i."'>".$i."</a></b> | ";
		} else {
			$str = $str."<a href='".$linkUrl."page=".$i."'>".$i."</a> | ";
		} 
	}

	$str=substr($str,0,strlen($str)-2);
	$str = $str."</span> <a href='".$linkUrl."page=".$nextPage."'><img src='".$Application["WebRoot"]."images/board/btn_next_01.gif' alt='' /></a> <a href='".$linkUrl."page=".$totalPage."'><img src='".$Application["WebRoot"]."images/board/btn_next_02.gif' alt='' /></a> </div>";
	return $str;
} 
?>
