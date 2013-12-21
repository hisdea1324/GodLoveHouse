<?php
function authority($limitLevel) {
	if ($_SESSION['userLv'] < $limitLevel) {
		$retValue = false;
	} else {
		$retValue = true;
	} 

	return $retValue;
} 

function showHouseManagerHeader() {
	$strMenu = file_get_contents($_SERVER['DOCUMENT_ROOT']."/include/html/house_manager_header.php");
	$strMenu = str_replace("[WEBROOT]", "http://".$_SERVER['HTTP_HOST']."/", $strMenu);

	print $strMenu;
}

function showHouseManagerLeft() {
	global $room_color;
	if (!is_array($room_color)) {
		$room_color = array();
	}
	checkUserLogin();

	$m_Helper = new MemberHelper();
	$member = $m_Helper->getMemberByuserid($_SESSION["userid"]);
	$account = $m_Helper->getAccountInfoByuserid($_SESSION["userid"]);
	$mission = $m_Helper->getMissionInfoByuserid($_SESSION["userid"]);

	$c_Helper = new CodeHelper();
	$codes = $c_Helper->getNationCodeList();

	$h_Helper = new HouseHelper();
	$houseList1 = $h_Helper->getHouseListByuserid($_SESSION["userid"], 1);
	$houseList2 = $h_Helper->getHouseListByuserid($_SESSION["userid"], 2);

	$roomId = isset($_REQUEST['roomId']) ? $_REQUEST['roomId'] : "";
	
	echo "<!-- leftSec -->";
	echo "<div id=\"leftSec\">";
	$color_cnt = 0;
	foreach ($houseList1 as $house) {
		echo "	<h2><a href=\"reserve_2.php?houseId=".$house->houseId."\">".$house->HouseName."</a> <a href=\"mission_write2.php?houseId=".$house->houseId."\">+</a> </h2>";
		echo "	<ul>";
		foreach ($house->RoomList as $room) {
			if ($color_cnt >= 10)
				$color_cnt %= 10;
			
			$color_cnt++;
			//selected
			$room_color[$room->roomId] = $color_cnt;
			if ($room->roomId == $roomId) {
				echo "		<li class=\"on\"><a href=\"reserve_2.php?houseId=".$house->houseId."&roomId=".$room->roomId."\">".$room->RoomName."<div class=\"sColor c{$color_cnt}\"></div></a></li>";
			} else {
				echo "		<li><a href=\"reserve_2.php?houseId=".$house->houseId."&roomId=".$room->roomId."\">".$room->RoomName."<div class=\"sColor c{$color_cnt}\"></div></a></li>";
			}
		}
		//echo "		<li class=\"c_g\"><a href=\"mission_write2.php?houseId=".$house->houseId."\">방추가 +</a></li>";
		echo "	</ul>";
	}
	echo "	<h2 class=\"c_g\"><a href=\"mission_write.php\">선교관 추가 +</a></h2>";
	echo "</div>";
	echo "<!-- // leftSec -->";
}

function showHouseManagerFooter() {
	$strFooter = file_get_contents($_SERVER['DOCUMENT_ROOT']."/include/html/house_manager_footer.php");
	$strFooter = str_replace("[WEBROOT]", "http://".$_SERVER['HTTP_HOST']."/", $strFooter);

	print $strFooter;
	debugFooter();
}

function showSimpleHeader($strNavi,$strSub,$strTitleImg) {
	global $Application;
	
	$strHeader = file_get_contents($_SERVER['DOCUMENT_ROOT']."/include/html/header_simple.php");
	$strHeader = str_replace("[TITLE]", $Application["Title"], $strHeader);
	$strHeader = str_replace("[WEBROOT]", "http://".$_SERVER['HTTP_HOST']."/", $strHeader);
	$strHeader = str_replace("[CHARSET]", $Application["Charset"], $strHeader);

	if ((strlen($_SESSION['userid'])==0)) {
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
	$strSubMenu = str_replace("[WEBROOT]", "http://".$_SERVER['HTTP_HOST']."/", $strSubMenu);
	$strSubMenu = str_replace("[TITLEIMG]",$strTitleImg,$strSubMenu);
	$strSubMenu = str_replace("[NAVIGATION]",$strNavi,$strSubMenu);

	print $strHeader.$strSubMenu;
}

function showHeader($strNavi, $strSub, $strTitleImg) {
	global $Application;
	
	$strHeader = file_get_contents($_SERVER['DOCUMENT_ROOT']."/include/html/header.php");
	$strHeader = str_replace("[TITLE]",$Application["Title"],$strHeader);
	$strHeader = str_replace("[WEBROOT]", "http://".$_SERVER['HTTP_HOST']."/", $strHeader);
	$strHeader = str_replace("[CHARSET]",$Application["Charset"],$strHeader);

	if (!isset($_SESSION['userid']) || $_SESSION['userid'] == "") {
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
	$strSubMenu = str_replace("[WEBROOT]", "http://".$_SERVER['HTTP_HOST']."/", $strSubMenu);
	$strSubMenu = str_replace("[TITLEIMG]",$strTitleImg,$strSubMenu);
	$strSubMenu = str_replace("[NAVIGATION]",$strNavi,$strSubMenu);

	echo $strHeader.$strSubMenu;
} 

function showMenu() {
	$strMenu = file_get_contents($_SERVER['DOCUMENT_ROOT']."/include/html/adminMenu.php");
	$strMenu = str_replace("[WEBROOT]", "http://".$_SERVER['HTTP_HOST']."/", $strMenu);

	print $strMenu;
} 

function showSimpleFooter() {
	$strFooter = file_get_contents($_SERVER['DOCUMENT_ROOT']."/include/html/footer_simple.php");
	$strFooter = str_replace("[WEBROOT]", "http://".$_SERVER['HTTP_HOST']."/", $strFooter);

	print $strFooter;
} 

function showFooter() {
	$strFooter = file_get_contents($_SERVER['DOCUMENT_ROOT']."/include/html/footer.php");
	$strFooter = str_replace("[WEBROOT]", "http://".$_SERVER['HTTP_HOST']."/", $strFooter);

	print $strFooter;
	debugFooter();
} 

function debugFooter() {
	if (!isset($_REQUEST['_TEST'])) return;

	global $_TEST;
	echo "<pre>";
	echo "Server : ";
	print_r($_SERVER);
	echo "Session : ";
	print_r($_SESSION);
	echo "Request : ";
	print_r($_REQUEST);
	echo "Test Value : ";
	print_r($_TEST);
	echo "</pre>";
}

function setTestValue($value) {
	if (!isset($_REQUEST['_TEST'])) return;

	global $_TEST;
	if (!is_array($_TEST)) {
		$_TEST = array();
	}

	$_TEST[] = $value;
}

function needUserLv($level) {
	if (!isset($_SESSION['userid'])) {
		header("Location: http://".$_SERVER["HTTP_HOST"]."/member/login.php");
		exit();
	}

	if ($_SESSION['userLv'] < $level) {
		alertBack("권한이 없습니다");
		exit();
	}

	return true;
}

function checkUserLogin($user_lv = 0) {
	if (isset($_SESSION['userid']) && $_SESSION['userid'] != "") {
		return;
	}
	if (isset($_SESSION['userName']) && $_SESSION['userName'] != "") {
		return;
	}
	if (isset($_SESSION['userLv']) && $_SESSION['userLv'] != "") {
		return;
	}
	
	if ($_SESSION['userLv'] >= $user_lv) {
		return;
	}
	
	header("Location: http://".$_SERVER["HTTP_HOST"]."/member/login.php");
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
	global $mysqli;

	if ($result = $mysqli->query($query)) {
	    /* determine number of rows result set */
	    $total = $result->num_rows;
	    /* close result set */
	    $result->close();
	} else {
		$total = 0;
	}

	$pathInfo = get_path_info();
	if (isset($_SERVER["QUERY_STRING"])) {
		$queryString = preg_replace('/(&*)page=(\d+)/i', '', $_SERVER["QUERY_STRING"]);
		$queryString = preg_replace('/^&/i', '?', $queryString);
		$queryString .= "&";
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

	$str = "<div class='paging'><a href='".$linkUrl."page=1'> <img src='http://".$_SERVER['HTTP_HOST']."/images/board/btn_pre_02.gif' alt=''/></a> <a href='".$linkUrl."&page=".$prevPage."'><img src='http://".$_SERVER['HTTP_HOST']."/images/board/btn_pre_01.gif' alt='' /></a> <span class='pagingText'>";
	for ($i = 1; $i <= $totalPage; $i++) {
		if ($i - $page == 0) {
			$str = $str."<b><a href='".$linkUrl."page=".$i."'>".$i."</a></b> | ";
		} else {
			$str = $str."<a href='".$linkUrl."page=".$i."'>".$i."</a> | ";
		} 
	}

	$str = substr($str, 0, strlen($str) - 2);
	$str = $str."</span> <a href='".$linkUrl."page=".$nextPage."'><img src='http://".$_SERVER['HTTP_HOST']."/images/board/btn_next_01.gif' alt='' /></a> <a href='".$linkUrl."page=".$totalPage."'><img src='http://".$_SERVER['HTTP_HOST']."/images/board/btn_next_02.gif' alt='' /></a> </div>";
	return $str;
} 

function makePagingN($page, $pageCount, $pageUnit, $total) {
	$pathInfo = get_path_info();
	if (isset($_SERVER["QUERY_STRING"])) {
		$queryString = preg_replace('/(&*)page=(\d+)/i', '', $_SERVER["QUERY_STRING"]);
		$queryString = preg_replace('/^&/i', '?', $queryString);
	} else {
		$queryString = "";
	} 

	$linkUrl = $pathInfo."?".$queryString;

	# 임시코드 : 나중에 수정합시다.
	$linkUrl=str_replace("?&","?",str_replace("?&","?",$linkUrl));
	$linkUrl=str_replace("&&","&",str_replace("&&","&",$linkUrl));

	if ($pageCount > 0) {
		$totalPage = round($total / $pageCount + 0.5);
	} else {
		$totalPage = 1;
	}
	if ($pageUnit > 0) {
		$prevPage = round($page / $pageUnit + 0.5) * 10 + 1;
	} else {
		$prevPage = 1;
	}
	$nextPage = $prevPage + 10;
	if ($nextPage > $totalPage) {
		$nextPage = $totalPage;
	} 

	$str = "<div class='paging'><a href='".$linkUrl."&page=1'> <img src='http://".$_SERVER['HTTP_HOST']."/images/board/btn_pre_02.gif' alt=''/></a> <a href='".$linkUrl."&page=".$prevPage."'><img src='http://".$_SERVER['HTTP_HOST']."/images/board/btn_pre_01.gif' alt='' /></a> <span class='pagingText'>";
	for ($i = 1; $i <= $totalPage; $i++) {
		if (($i-$page==0)) {
			$str = $str."<b><a href='".$linkUrl."&page=".$i."'>".$i."</a></b> | ";
		} else {
			$str = $str."<a href='".$linkUrl."&page=".$i."'>".$i."</a> | ";
		} 
	}

	$str=substr($str,0,strlen($str)-2);
	$str = $str."</span> <a href='".$linkUrl."&page=".$nextPage."'><img src='http://".$_SERVER['HTTP_HOST']."/images/board/btn_next_01.gif' alt='' /></a> <a href='".$linkUrl."&page=".$totalPage."'><img src='http://".$_SERVER['HTTP_HOST']."/images/board/btn_next_02.gif' alt='' /></a> </div>";
	return $str;
} 
?>
