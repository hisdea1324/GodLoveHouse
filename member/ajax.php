<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
$mode = trim($_REQUEST["mode"]);
switch (($mode)) {
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
getUserProfile();
		break;
} 

function confirmuserid() {
	global $mysqli;

	$userid = trim($_REQUEST["userid"]);
	if (strlen($userid) < 4) {
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

	$nick = trim($_REQUEST["nick"]);
	if (strlen($nick) == 0) {
		print "";
		return;
	} else if (strlen($nick) < 2) {
		print "<b><font color=red>닉네임은 2자 이상만 가능합니다.</font></b>";
		return;
	}

	$query = "SELECT * FROM users WHERE nick = '".$mysqli->real_escape_string($nick)."'";
	$result = $mysqli->query($query)

	if ($result && $result->num_rows > 0) {
		print "<b><font color=red>이 닉네임은 사용할 수 없습니다.</font></b>";
	} else {
		print "사용 가능한 닉네임입니다.";
	} 
} 

function confirmPassword() {
	$pw1 = trim($_REQUEST["pw1"]);
	$pw2 = trim($_REQUEST["pw2"]);

	if (strlen($pw2) == 0 || strlen($pw2) != strlen($pw1)) {
		print "";
	} else if ($pw2 == $pw1) {
		print "확인 되었습니다.";
	} else {
		print "<b><font color=red>비밀번호와 일치하지 않습니다.</font></b>";
	} 

	$Rs = null;

} 

function getUserProfile() {
	$m_helper = new MemberHelper();
	$member = $m_helper->getMemberByuserid($trim[$_REQUEST["userid"]]);
	$mission = $m_helper->getMissionInfoByuserid($trim[$_REQUEST["userid"]]);

	print "<table width=200><tr><td>";
	print "<ul>";
	print "<li> 이름 : ".$member->Name."</li>";
	print "<li> 선교사명 : ".$mission->MissionName."</li>";
	print "<li> 지역 : ".$mission->Nation."</li>";
	print "<li> 파송교회 : ".$mission->Church."</li>";
	print "<li> 파송단체 : ".$mission->Ngo."</li>";
	print "</ul>";
	print "</td></tr></table>";

} 
?>