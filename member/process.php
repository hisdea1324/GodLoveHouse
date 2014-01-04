<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

$mode = (isset($_REQUEST["mode"])) ? trim($_REQUEST["mode"]) : "";

switch ($mode) {
	case "login":
		login();
		break;
	case "logout":
		logout();
		break;
	case "editUser":
		editUser();
		break;
	case "deleteFamily":
		deleteFamily();
		break;
	case "withdrawal":
		deleteUser();
		break;
	case "changeReservStatus":
		changeReservStatus();
		break;
	case "reservation":
		needUserLv(1);
		reservation();
		break;
	case "editRoom":
		editRoom();
		break;
	case "deleteRoom":
		deleteRoom();
		break;
	default:
		header("Location: "."../index.php");
		break;
} 
debugFooter();

function editRoom() {
	$room = new RoomObject();

	$room->RoomID = $_REQUEST["roomId"];
	$room->HouseID = $_REQUEST["houseId"];
	$room->RoomName = $_REQUEST["roomName"];
	$room->Network = $_REQUEST["network"];
	$room->Kitchen = $_REQUEST["kitchen"];
	$room->Laundary = $_REQUEST["laundary"];
	$room->Limit = $_REQUEST["limit"];
	$room->Fee = $_REQUEST["fee"];

	$room->ImageID1 = $_REQUEST["idRoomImage1"];
	$room->ImageID2 = $_REQUEST["idRoomImage2"];
	$room->ImageID3 = $_REQUEST["idRoomImage3"];
	$room->ImageID4 = $_REQUEST["idRoomImage4"];

	$room->Update();

	header("Location: "."mypage_houseInfo.php?houseId=".$_REQUEST["houseId"]."&roomId=".$_REQUEST["roomId"]);
} 

function deleteRoom() {
	global $mysqli;
	
	$houseId = trim($_REQUEST["houseId"]);
	$roomId = trim($_REQUEST["roomId"]);

	$query = "DELETE FROM room WHERE roomId = ".$roomId;
	$deleteRs = $mysqli->query($query);
	$query = "UPDATE house SET roomCount = roomCount - 1 WHERE houseId = ".$houseId;
	$deleteRs = $mysqli->query($query);

	$deleteRs = null;

	header("Location: "."mypage_houseInfo.php?houseId=".$_REQUEST["houseId"]);
} 

function editUser() {
	# 비밀번호 체크, 아이디 체크 해야함
	$missionary = isset($_REQUEST["missionary"]) ? trim($_REQUEST["missionary"]) : "";

	if ($missionary == "1") {
		editUserMissionary();
	} 

	$result = editUserNormal();

	$getURL = $_SERVER["HTTP_REFERER"];

	$parsedURL = ParsingURL($getURL);
	if ($result === false) {
		alertBack("데이터에 문제가 있습니다. 다시 정확히 입력해 주세요.");
	} else if (strcmp($parsedURL["path"],"/member/join.php") == 0) {
		$returnURL = "http://".$_SERVER['HTTP_HOST']."/member/login.php";
		alertGoPage("가입 되었습니다.",$returnURL);
	} else {
		$returnURL = $parsedURL["path"];
		alertGoPage("수정 되었습니다.",$returnURL);
	} 
} 

function editUserNormal() {
	$member = new MemberObject($_REQUEST["userid"]);

	$member->Name = $_REQUEST["name"];
	$member->Nick = $_REQUEST["nickName"];
	
	if (isset($_REQUEST["password"]) && $_REQUEST["password"] != "") {
		$member->Password = $_REQUEST["password"];
	}
	if (isset($_REQUEST["password_quest"])) {
		$member->PasswordQuestion = $_REQUEST["password_quest"];
		$member->PasswordAnswer = $_REQUEST["password_answer"];
	}

	// $jumin[0] = $_REQUEST["jumin1"];
	// $jumin[1] = $_REQUEST["jumin2"];
	// $member->Jumin = $jumin;

	$member->Email = $_REQUEST["email1"]."@".$_REQUEST["email2"];

	$member->Post = $_REQUEST["post1"]."-".$_REQUEST["post2"];
	$member->Address1 = $_REQUEST["addr1"];
	$member->Address2 = $_REQUEST["addr2"];

	$member->Phone = $_REQUEST["tel1"]."-".$_REQUEST["tel2"]."-".$_REQUEST["tel3"];

	$member->Mobile = $_REQUEST["hp1"]."-".$_REQUEST["hp2"]."-".$_REQUEST["hp3"];

	$member->CheckMessageOption = isset($_REQUEST["smsOk"]) ? $_REQUEST["smsOk"] : "0";

	$member->userlv = $_REQUEST["level"];
	
	$missionary = isset($_REQUEST["missionary"]) ? $_REQUEST["missionary"] : "0";
	if ($missionary == "1" && $_REQUEST["level"] == 1) {
		$member->userlv = 3;
	} else if ($member->userlv == 3) {
		$member->userlv = 1;
	}

	return $member->Update();
} 

function editUserMissionary() {
	$mission = new MissionObject();

	$mission->userid = $_REQUEST["userid"];
	$mission->Church = $_REQUEST["church"];
	$mission->MissionName = $_REQUEST["missionName"];
	$mission->Ngo = $_REQUEST["ngo"];
	$mission->NationCode = $_REQUEST["nation"];
	$mission->Homepage = $_REQUEST["homepage"];
	$mission->Manager = $_REQUEST["manager"];
	$mission->AccountNo = $_REQUEST["accountNo"];
	$mission->Bank = $_REQUEST["bank"];
	$mission->AccountName = $_REQUEST["accountName"];
	$mission->Memo = $_REQUEST["memo"];
	$mission->PrayList = $_REQUEST["prayList"];
	$mission->FlagFamily = $_REQUEST["flagFalily"];

	$managerEmail1 = $_REQUEST["managerEmail1"];
	$managerEmail2 = $_REQUEST["managerEmail2"];
	$mission->ManagerEmail = $managerEmail1."@".$managerEmail2;

	$churchContact1 = $_REQUEST["churchContact1"];
	$churchContact2 = $_REQUEST["churchContact2"];
	$churchContact3 = $_REQUEST["churchContact3"];
	$mission->ChurchContact = $churchContact1."-".$churchContact2."-".$churchContact3;

	$ngoContact1 = $_REQUEST["ngoContact1"];
	$ngoContact2 = $_REQUEST["ngoContact2"];
	$ngoContact3 = $_REQUEST["ngoContact3"];
	$mission->NgoContact = $ngoContact1."-".$ngoContact2."-".$ngoContact3;

	$managerContact1 = $_REQUEST["managerContact1"];
	$managerContact2 = $_REQUEST["managerContact2"];
	$managerContact3 = $_REQUEST["managerContact3"];
	$mission->ManagerContact = $managerContact1."-".$managerContact2."-".$managerContact3;

	$mission->Update();

	$familyName = $_REQUEST["familyName"];
	$familyAge = $_REQUEST["familyAge"];
	$familySex = $_REQUEST["familySex"];
	$familyRelation = $_REQUEST["familyRelation"];
	$familyId = isset($_REQUEST["familyId"]) ? $_REQUEST["familyId"] : array();
	
	for ($i = 0; $i < count($familyName); $i++) {
		$familyMember = (count($familyId) > 0) ? new MissionaryFamily($familyId[$i]) : new MissionaryFamily();
		$familyMember->userid = $_REQUEST["userid"];
		$familyMember->Name = $familyName[$i];
		$familyMember->Age = $familyAge[$i];
		$familyMember->Sex = $familySex[$i];
		$familyMember->Relation = $familyRelation[$i];
		$familyMember->Update();
	}
} 

function deleteUser() {
	# 비밀번호 체크, 아이디 체크 해야함;
	logout();
} 

function login() {
	$uid = isset($_REQUEST["userid"]) ? trim($_REQUEST["userid"]) : "";
	$password = isset($_REQUEST["password"]) ? trim($_REQUEST["password"]) : "";
	$backURL = isset($_REQUEST["backURL"]) ? trim($_REQUEST["backURL"]) : "";

	global $mysqli;
	$query = "SELECT userid, nick, name, userLv FROM users WHERE userid = '".$mysqli->real_escape_string($uid)."' AND password = '".$mysqli->real_escape_string(Encrypt($password))."'";
	$result = $mysqli->query($query);

	if ($result->num_rows == 0) {
		$returnURL = "login.php?userid=".$uid."&backURL=".$backURL;
		alertGoPage("로그인 실패하였습니다. 아이디와 비밀번호를 확인해 주세요", $returnURL);
		exit();
	}

	while ($row = $result->fetch_array()) {
		$_SESSION['userid'] = $row['userid'];
		$_SESSION['userNick'] = $row["nick"];
		$_SESSION['userName'] = $row["name"];
		$_SESSION['userLv'] = $row["userLv"];

		switch ($_SESSION['userLv']) {
			case 9:
				$_SESSION['userTitle'] = "Super";
				break;
			case 7:
				$_SESSION['userTitle'] = "Manager";
				break;
			case 3:
				$_SESSION['userTitle'] = "Missionary";
				break;
			default:
				$_SESSION['userTitle'] = "Normal";
				break;
		}
		if (strlen($backURL) > 0 && strpos($backURL, "login") === False) {
			header("Location: ".URLDecode($backURL));
		} else {
			header("Location: "."../index.php");
		} 
		exit();
	}

	//값이 제대로 들어있지 않다면 이전 페이지로 돌아간다.	
	header("Location: "."../index.php");
} 

function deleteFamily() {
	$familyId = trim($_REQUEST["familyId"]);
	$uid = trim($_REQUEST["userid"]);
	$userLv = trim($_REQUEST["userLv"]);

	$familyMember = new MissionaryFamily();
	$familyMember->FamilyID = $familyId;
	$familyMember->delete();

	$ObjQuery = null;


	header("Location: "."mypage_member.php?userLv=".$userLv."&userid=".$uid);
} 

function logout() {
	$_SESSION['userid'] = '';
	$_SESSION['userNick'] = '';
	$_SESSION['userName'] = '';
	$_SESSION['userLv'] = '';
	$_SESSION['userTitle'] = '';

	header("Location: http://".$_SERVER["HTTP_HOST"]."/index.php");
} 


function changeReservStatus() {
	$status = trim($_REQUEST["status"]);
	$houseId = trim($_REQUEST["houseId"]);
	$roomId = trim($_REQUEST["roomId"]);
	if ($status < 2 || $status > 4) {
		alertBack("잘못된 값입니다");
	} 
	$bookNo = trim($_REQUEST["bookNo"]);

	$reserv = new ReservationObject($bookNo);
	if ($status == "2") {
		$reserv->Status="S0002";
	} else if ($status == "3") {
		$reserv->Status = "S0003";
	} else {
		$reserv->Status = "S0004";
	} 

	$reserv->Update();
	$reserv = null;

	header("Location: http://".$_SERVER['HTTP_HOST']."/member/mypage_missionary.php");
} 

function reservation() {
	$book = new reservationObject();

	$houseId = isset($_REQUEST["houseId"]) ? $_REQUEST["houseId"] : "";
	$book->StartDate = isset($_REQUEST["startDate"]) ? $_REQUEST["startDate"] : "";
	$book->EndDate = isset($_REQUEST["endDate"]) ? $_REQUEST["endDate"] : "";
	$book->RoomId = isset($_REQUEST["roomId"]) ? $_REQUEST["roomId"] : "";
	$book->userid = $_SESSION["userid"];

	if (!$book->checkId()) {
		header("Location: "."mypage_houseReserv.php?houseId=".$houseId);
	} else if (!$book->checkDate()) {
		header("Location: "."mypage_houseReserv.php?houseId=".$houseId."&roomId=".$book->RoomId);
	} 

	$book->Update();

	// SMS 메세지 보내기alertGoPage("예약요청 되었습니다.","mypage_houseReserv.php?houseId=".$houseId."&roomId=".$book->RoomId);
	// $house = new HouseObject($houseId);
	// $manager = new MemberObject($house->userid);
	// $from_number="01010041004";
	// $message="선교관 예약 신청이 들어왔습니다."." 선교관 : ".$house->HouseName." 예약날짜 : ".$_REQUEST["startDate"]." ~ ".$_REQUEST["endDate"];
	//sendSMSMessage($from_number, join($manager->Mobile, ""),$message);
} 
?>

