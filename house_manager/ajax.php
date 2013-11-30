<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
$mode = isset($_REQUEST["mode"]) ? trim($_REQUEST["mode"]) : "";

switch ($mode) {
	case "checkuserid":
		confirmuserid();
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

function confirmuserid() {
	global $mysqli;

	$userid = isset($_REQUEST["userid"]) ? trim($_REQUEST["userid"]) : "";
	if (strlen($userid) == 0) {
		print "";
		return;
	} else if (strlen($userid) < 4) {
		print "<b><font color=red>아이디는 4자 이상만 가능합니다.</font></b>";
		return;
	} 

	$query = "SELECT * FROM users WHERE userid = '".$mysqli->real_escape_string($userid)."'";
	$result = $mysqli->query($query);
	if ($result && $result->num_rows > 0) {
		print "<b><font color=red>이 아이디는 사용할 수 없습니다.</font></b>";
	} else {
		print "사용 가능한 아이디입니다.";
	} 
} 

function confirmNick() {
	global $mysqli;

	$nick = isset($_REQUEST["nick"]) ? trim($_REQUEST["nick"]) : "";
	if (strlen($nick) == 0) {
		print "";
		return;
	} else if (strlen($nick) < 2) {
		print "<b><font color=red>닉네임은 2자 이상만 가능합니다.</font></b>";
		return;
	} 

	$query = "SELECT * FROM users WHERE nick = '".$mysqli->real_escape_string($nick)."'";
	$result = $mysqli->query($query);
	if ($result && $result->num_rows > 0) {
		print "<b><font color=red>이 닉네임은 사용할 수 없습니다.</font></b>";
	} else {
		print "사용 가능한 닉네임입니다.";
	} 
} 

function confirmPassword() {
	$pw1 = isset($_REQUEST["pw1"]) ? trim($_REQUEST["pw1"]) : "";
	$pw2 = isset($_REQUEST["pw2"]) ? trim($_REQUEST["pw2"]) : "";

	if (strlen($pw2) == 0 || strlen($pw2) != strlen($pw1)) {
		print "";
	} else if ($pw2 == $pw1) {
		print "확인 되었습니다.";
	} else {
		print "<b><font color=red>비밀번호와 일치하지 않습니다.</font></b>";
	} 
} 

function getUserProfile() {
	$m_helper = new MemberHelper();
	$member = $m_helper->getMemberByuserid($trim[$_REQUEST["userid"]]);
	$mission = $m_helper->getMissionInfoByuserid($trim[$_REQUEST["userid"]]);

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

	$m_helper = new MemberHelper();
	$member = $m_helper->getMemberByuserid($resv->userid);
	$mission = $m_helper->getMissionInfoByuserid($resv->userid);
	
	print "<div class=\"tit\">";
	print "	신청자 정보";
	//print "	<span class=\"btn1w\" style=\"position:absolute; right:0px; top:-3px\"><a href=\"#\">상세보기</a></span>";
	print "</div>";
	print "<ul>";
	print "<li><p>회원아이디</p> ".$member->userid."</li>";
	print "<li><p>회원이름(별명)</p> ".$member->Name."(".$member->Nick.")</li>";
	print "<li><p>회원연락처</p> ".implode('-',$member->mobile)."</li>";
	if ($member->Name != $resv->resv_name) {
		print "<li><p>예약자성명</p> ".(($resv->resv_name) ? $resv->resv_name : "-")."</li>";
	}
	if (implode('-',$member->mobile) != $resv->resv_phone) {
		print "<li><p>예약자연락처</p> ".(($resv->resv_phone) ? $resv->resv_phone : "-")."</li>";
	}
	print "<li><p>인원수</p> ".(($resv->people_number) ? $resv->people_number : "-")."</li>";
	print "<li><p>희망입주시간</p> ".(($resv->arrival_time) ? $resv->arrival_time : "-")."</li>";
	print "<li><p>방문목적</p> ".(($resv->purpose) ? $resv->purpose : "-")."</li>";
	print "<li><p>메모</p> ".(($resv->memo) ? $resv->memo : "-")."</li>";
	print "<li><p>지역</p> ".(($mission->Nation) ? $mission->Nation : "-")."</li>";
	print "<li><p>파송교회</p> ".(($mission->Church) ? $mission->Church : "-")."</li>";
	print "<li><p>파송단체</p> ".(($mission->Ngo) ? $mission->Ngo : "-")."</li>";
	print "</ul>";
}

?>