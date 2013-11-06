<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
$mode = isset($_REQUEST["mode"]) ? trim($_REQUEST["mode"]) : "";

switch ($mode) {
	case "checkUserId":
		confirmUserId();
		break;
	case "checkNick":
		confirmNick();
		break;
	case "checkNID":
		confirmNID();
		break;
	case "checkPassword":
		confirmPassword();
		break;
	case "getUserProfile":
		getUserProfile2();
		break;
} 

function confirmUserId() {
	$userId = trim($_REQUEST["userId"]);
	$query = "SELECT * FROM users WHERE userId = '".$userId."'";
	$rs = $db->execute($query);

	if ((strlen($userId)<4)) {
		print "<b><font color=red>아이디는 4자 이상만 가능합니다.</font></b>";
	} else if (($rs->eof || $rs->bof)) {
		print "사용 가능한 아이디입니다.";
	} else if ((strlen($userId)>0)) {
		print "<b><font color=red>이 아이디는 사용할 수 없습니다.</font></b>";
	} 


	$rs = null;

} 

function confirmNick() {
	$nick = trim($_REQUEST["nick"]);
	$query = "SELECT * FROM users WHERE nick = '".$nick."'";
	$rs = $db->execute($query);

	if ((strlen($nick)==0)) {
		print "";
	} else if ((strlen($nick)<2)) {
		print "<b><font color=red>닉네임은 2자 이상만 가능합니다.</font></b>";
	} else if (($Rs->EOF || $Rs->BOF)) {
		print "사용 가능한 닉네임입니다.";
	} else {
		print "<b><font color=red>이 닉네임은 사용할 수 없습니다.</font></b>";
	} 

	$Rs = null;

} 

function confirmNID() {
	$nid1 = trim($_REQUEST["nid1"]);
	$nid2 = trim($_REQUEST["nid2"]);
	$query = "SELECT * FROM users WHERE jumin = '".$nid1.$nid2."'";
	$rs = $db->execute($query);

	if ((strlen($nid1)==0 && strlen($nid2)==0)) {
		print "";
	} else if ((strlen($nid1)!=6 || strlen($nid2)!=7)) {
		print "<b><font color=red>정확히 입력해 주세요</font></b>";
	} else if (($Rs->EOF || $Rs->BOF)) {
		print "사용 가능합니다.";
	} else {
		print "<b><font color=red>이미 사용중인 번호입니다.</font></b>";
	} 

	$Rs = null;

} 

function confirmPassword() {
	$pw1 = trim($_REQUEST["pw1"]);
	$pw2 = trim($_REQUEST["pw2"]);

	if ((strlen($pw2)==0 || strlen($pw2)!=strlen($pw1))) {
		print "";
	} else if (($pw2 == $pw1)) {
		print "확인 되었습니다.";
	} else {
		print "<b><font color=red>비밀번호와 일치하지 않습니다.</font></b>";
	} 

	$Rs = null;

} 

function getUserProfile() {
	$m_helper = new MemberHelper();
	$member = $m_helper->getMemberByUserId($trim[$_REQUEST["userid"]]);
	$mission = $m_helper->getMissionInfoByUserId($trim[$_REQUEST["userid"]]);

	print "<div class=\"tit\">";
	print "	신청자 정보";
	//print "	<span class=\"btn1w\" style=\"position:absolute; right:0px; top:-3px\"><a href=\"#\">상세보기</a></span>";
	print "</div>";
	print "<ul>";
	print "<li> 이름 : ".$member->Name."</li>";
	print "<li> 선교사명 : ".$mission->MissionName."</li>";
	print "<li> 지역 : ".$mission->Nation."</li>";
	print "<li> 파송교회 : ".$mission->Church."</li>";
	print "<li> 파송단체 : ".$mission->Ngo."</li>";
	print "</ul>";

	$mission = null;
	$member = null;
	$m_helper = null;
} 

function getUserProfile2() {
	$reservationNo = isset($_REQUEST["reservationNo"]) ? $_REQUEST["reservationNo"] : "";
	$resv = new ReservationObject($reservationNo);

	print "<div class=\"tit\">";
	print "	신청자 정보";
	//print "	<span class=\"btn1w\" style=\"position:absolute; right:0px; top:-3px\"><a href=\"#\">상세보기</a></span>";
	print "</div>";
	print "<ul>";
	print "	<li><p>성명</p> {$resv->resv_name}</li>";
	print "	<li><p>연락처</p> {$resv->resv_phone}</li>";
	print "	<li><p>파송국가</p> {$resv->resv_nation}</li>";
	print "	<li><p>지원단체</p> {$resv->resv_assoc}</li>";
	print "</ul>";

}
?>