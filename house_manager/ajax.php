<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
checkUserLogin(7);

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
	case "updateMemo":
		updateMemo();
		break;
} 

function confirmuserid() {
	global $mysqli;

	$uid = isset($_REQUEST["userid"]) ? trim($_REQUEST["userid"]) : "";
	if (strlen($uid) == 0) {
		print "";
		return;
	} else if (strlen($uid) < 4) {
		print "<b><font color=red>아이디는 4자 이상만 가능합니다.</font></b>";
		return;
	} 

	$query = "SELECT * FROM users WHERE userid = '".$mysqli->real_escape_string($uid)."'";
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

function updateMemo() {
	$reservationNo = isset($_REQUEST["reservationNo"]) ? $_REQUEST["reservationNo"] : "";
	$resv = new ReservationObject($reservationNo);
	if (isset($_POST["memo"])) {
		$resv->memo = $_POST['memo'];
		$resv->Update();
		print "메모가 저장되었습니다.";
	} else {
		print "죄송합니다. 잠시 후에 다시 시도해 주세요.";
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
	print "<li> 파송기관 : {$mission->Church} ({$mission->ChurchContact})</li>";
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
	$history = $member->getReservHistory();

	print "<table class=\"table table-striped\">";
	print "<tr><th>회원아이디</th><td>".$member->userid."</td></tr>";
	print "<tr><th>회원이름(별명)</th><td>".$member->Name."(".$member->Nick.")</td></tr>";
	print "<tr><th>회원연락처</th><td>".implode('-',$member->phone)."</td></tr>";
	print "<tr><th>회원-Email</th><td>".implode('@',$member->email)."</td></tr>";
	if ($member->Name != $resv->resv_name) {
		print "<tr><th>예약자성명</th><td>".(($resv->resv_name) ? $resv->resv_name : "-")."</td></tr>";
	}
	if (implode('-',$member->mobile) != $resv->resv_phone) {
		print "<tr><th>국내연락처</th><td>".(($mission->managercontact) ? $mission->managercontact : "-")."</td></tr>";
	}
	print "<tr><th>인원수</th><td>".(($resv->people_number) ? $resv->people_number : "-")."</td></tr>";
	print "<tr><th>희망입주시간</th><td>".(($resv->arrival_time) ? $resv->arrival_time : "-")."</td></tr>";
	print "<tr><th>방문목적</th><td>".(($resv->purpose) ? $resv->purpose : "-")."</td></tr>";
	print "<tr><th>지역</th><td>".(($mission->Nation) ? $mission->Nation : "-")."</td></tr>";
	if ($mission->Church) {
		print "<tr><th>파송기관</th><td>{$mission->Church} ({$mission->ChurchContact})</td></tr>";
	} else {
		print "<tr><th>파송기관</th><td> - </td></tr>";
	}
	print "<tr><th>파송년도</th><td>".(($mission->SentYear) ? $mission->SentYear : "-")."</td></tr>";
	print "<tr><th>출생년도</th><td>".(($mission->BirthYear) ? $mission->BirthYear : "-")."</td></tr>";
	print "<tr><th>선교사 증명</th><td>".(($mission->attachFileName) ? "<a href='/upload/mission/{$mission->attachFileName}' target='_blank' style='color:white; text-decoration:underline;'>{$mission->attachFileName}</a>" : "-")."</td></tr>";
	print "<tr><th>메모</th><td><textarea id='update-memo' class='form-control' rows='7' style='resize:none;'>{$resv->memo}</textarea></td></tr>";
	print "<tr><th>신청자 예약 이력</th>";
	print "<td><ul class=\"list-group\">";
	foreach ($history as $row) {
		$status = "신규예약";
		$status = ($row['reservStatus'] == "S0002") ? "승인" : $status;
		$status = ($row['reservStatus'] == "S0003") ? "완료" : $status;
		$status = ($row['reservStatus'] == "S0004") ? "거절" : $status;
		print "<li class=\"list-group-item\"><strong>{$status}</strong> {$row['cnt']} 회</li>";
	}
	print "</ul></td></tr>";
	print "</table>";
}
?>
