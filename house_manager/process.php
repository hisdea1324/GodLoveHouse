<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

checkUserLogin();

$mode = (isset($_REQUEST["mode"])) ? trim($_REQUEST["mode"]) : "";

switch ($mode) {
	case "regist":
		registHouse();
		break;
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
	case "edit_date":
		editReservationDate();
		break;
	default:
		header("Location: "."../index.php");
		break;
} 

debugFooter();

function registHouse() {
	$houseId = isset($_REQUEST["houseId"]) ? $_REQUEST["houseId"] : "";

	$contact[0] = isset($_REQUEST["contact11"]) ? $_REQUEST["contact11"] : "";
	$contact[1] = isset($_REQUEST["contact12"]) ? $_REQUEST["contact12"] : "";
	$contact[2] = isset($_REQUEST["contact13"]) ? $_REQUEST["contact13"] : "";
	$zipcode[0] = isset($_REQUEST["post1"]) ? $_REQUEST["post1"] : "";
	$zipcode[1] = isset($_REQUEST["post2"]) ? $_REQUEST["post2"] : "";

	$house = new HouseObject($houseId);
	$house->userid = isset($_REQUEST["userid"]) ? $_REQUEST["userid"] : "";
	$house->houseName = isset($_REQUEST["houseName"]) ? $_REQUEST["houseName"] : "";
	$house->assocName = isset($_REQUEST["assocName"]) ? $_REQUEST["assocName"] : "";
	$house->manager1 = isset($_REQUEST["manager"]) ? $_REQUEST["manager"] : "";
	$house->Contact1 = implode("-", $contact);
	$house->buildType = isset($_REQUEST["buildType"]) ? $_REQUEST["buildType"] : "";
	$house->zipcode = implode("-", $zipcode);
	$house->addr1 = isset($_REQUEST["addr1"]) ? $_REQUEST["addr1"] : "";
	$house->addr2 = isset($_REQUEST["addr2"]) ? $_REQUEST["addr2"] : "";
	$house->price = isset($_REQUEST["price"]) ? $_REQUEST["price"] : "";
	$house->personLimit = isset($_REQUEST["personLimit"]) ? $_REQUEST["personLimit"] : 0;
	$house->personLimit1 = isset($_REQUEST["personLimit1"]) ? $_REQUEST["personLimit1"] : 0;
	$house->roomLimit = isset($_REQUEST["roomLimit"]) ? $_REQUEST["roomLimit"] : 0;
	$house->roomLimit1 = isset($_REQUEST["roomLimit1"]) ? $_REQUEST["roomLimit1"] : 0;
	$house->explain = isset($_REQUEST["explain"]) ? $_REQUEST["explain"] : "";
	$house->regionCode = isset($_REQUEST["regionCode"]) ? $_REQUEST["regionCode"] : "";
	$house->Update();

	alertGoPage("선교관 등록요청이 되었습니다.", "mission_write.php?houseId=$houseId");
} 

function editRoom() {
	$room = new RoomObject();

	$room->RoomID = isset($_REQUEST["roomId"]) ? $_REQUEST["roomId"] : "";
	$room->HouseID = isset($_REQUEST["houseId"]) ? $_REQUEST["houseId"] : "";
	$room->RoomName = isset($_REQUEST["roomName"]) ? $_REQUEST["roomName"] : "";
	$room->Network = isset($_REQUEST["network"]) ? $_REQUEST["network"] : "";
	$room->Kitchen = isset($_REQUEST["kitchen"]) ? $_REQUEST["kitchen"] : "";
	$room->Laundary = isset($_REQUEST["laundary"]) ? $_REQUEST["laundary"] : "";
	$room->bed = isset($_REQUEST["bed"]) ? $_REQUEST["bed"] : "";
	$room->Limit = isset($_REQUEST["limit"]) ? $_REQUEST["limit"] : "";
	$room->Fee = isset($_REQUEST["fee"]) ? $_REQUEST["fee"] : "";

	$room->ImageID1 = isset($_REQUEST["idRoomImage1"]) ? $_REQUEST["idRoomImage1"] : "";
	$room->ImageID2 = isset($_REQUEST["idRoomImage2"]) ? $_REQUEST["idRoomImage2"] : "";
	$room->ImageID3 = isset($_REQUEST["idRoomImage3"]) ? $_REQUEST["idRoomImage3"] : "";
	$room->ImageID4 = isset($_REQUEST["idRoomImage4"]) ? $_REQUEST["idRoomImage4"] : "";
	$room->Update();

	header("Location: "."mission_write2.php?houseId=".$room->houseId."&roomId=".$room->roomId);
} 

function deleteRoom() {
	global $mysqli;

	$houseId = isset($_REQUEST["houseId"]) ? trim($_REQUEST["houseId"]) : "";
	$roomId = isset($_REQUEST["roomId"]) ? trim($_REQUEST["roomId"]) : "";

	$query = "DELETE FROM room WHERE roomId = ".$mysqli->real_escape_string($roomId);
	$result = $mysqli->query($query);

	$query = "UPDATE house SET roomCount = roomCount - 1 WHERE houseId = ".$mysqli->real_escape_string($houseId);
	$result = $mysqli->query($query);

	header("Location: "."reserve_2.php?houseId=".$houseId);
} 

function editUser() {
	# 비밀번호 체크, 아이디 체크 해야함
	editUserNormal();

	$getURL = $_SERVER["HTTP_REFERER"];

	$parsedURL = ParsingURL($getURL);
	if (strcmp($parsedURL["path"],"/member/join.php") == 0) {
		$returnURL = "http://".$_SERVER['HTTP_HOST']."/member/login.php";
	} else {
		$returnURL = $parsedURL["path"];
	} 

	alertGoPage("변경 되었습니다.",$returnURL);
} 

function editUserNormal() {
	$member = new MemberObject($_REQUEST["userid"]);

	$member->userid = isset($_REQUEST["userid"]) ? $_REQUEST["userid"] : "";
	$member->Name = isset($_REQUEST["name"]) ? $_REQUEST["name"] : "";
	$member->Nick = isset($_REQUEST["nickName"]) ? $_REQUEST["nickName"] : "";

	$email[0] = isset($_REQUEST["email1"]) ? $_REQUEST["email1"] : "";
	$email[1] = isset($_REQUEST["email2"]) ? $_REQUEST["email2"] : "";
	$member->Email = $email[0]."@".$email[1];

	$post[0] = isset($_REQUEST["post1"]) ? $_REQUEST["post1"] : "";
	$post[1] = isset($_REQUEST["post2"]) ? $_REQUEST["post2"] : "";
	$member->Post = $post[0]."-".$post[1];
	$member->Address1 = isset($_REQUEST["addr1"]) ? $_REQUEST["addr1"] : "";
	$member->Address2 = isset($_REQUEST["addr2"]) ? $_REQUEST["addr2"] : "";

	$tel[0] = isset($_REQUEST["tel1"]) ? $_REQUEST["tel1"] : "";
	$tel[1] = isset($_REQUEST["tel2"]) ? $_REQUEST["tel2"] : "";
	$tel[2] = isset($_REQUEST["tel3"]) ? $_REQUEST["tel3"] : "";
	$member->Phone = $tel[0]."-".$tel[1]."-".$tel[2];

	$hp[0] = isset($_REQUEST["hp1"]) ? $_REQUEST["hp1"] : "";
	$hp[1] = isset($_REQUEST["hp2"]) ? $_REQUEST["hp2"] : "";
	$hp[2] = isset($_REQUEST["hp3"]) ? $_REQUEST["hp3"] : "";
	$member->Mobile = $hp[0]."-".$hp[1]."-".$hp[2];

	$member->Update();
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
	$mission->FlagFamily = $_REQUEST["flagFamily"];

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

	$familyId=explode(",",$_REQUEST["familyId"]);
	$familyName=explode(",",$_REQUEST["familyName"]);
	$familyAge=explode(",",$_REQUEST["familyAge"]);
	$familySex=explode(",",$_REQUEST["familySex"]);
	$familyRelation=explode(",",$_REQUEST["familyRelation"]);
	for ($i=0; $i<=count($familyName); $i = $i+1) {
		$familyMember = new MissionaryFamily();
		$familyMember->userid = $_REQUEST["userid"];
		$familyMember->familyID = $familyId[$i];
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
	$userid = isset($_REQUEST["userid"]) ? trim($_REQUEST["userid"]) : "";
	$password = isset($_REQUEST["password"]) ? trim($_REQUEST["password"]) : "";
	$backURL = isset($_REQUEST["backURL"]) ? trim($_REQUEST["backURL"]) : "";

	global $mysqli;
	$query = "SELECT nick, name, userLv FROM users WHERE userid = '".$mysqli->real_escape_string($userid)."' AND password = '".$mysqli->real_escape_string(Encrypt($password))."'";
	$result = $mysqli->query($query);

	if ($result->num_rows == 0) {
		header("Location: "."login.php?userid=".$userid."&backURL=".$backURL);
		return;
	}

	while ($row = $result->fetch_array()) {
		$_SESSION['userid'] = $userid;
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
		if (strlen($backURL) > 0) {
			header("Location: ".URLDecode($backURL));
		} else {
			header("Location: "."../index.php");
		} 
		return;
	}

	//값이 제대로 들어있지 않다면 이전 페이지로 돌아간다.	
	header("Location: "."../index.php");
} 

function deleteFamily() {
	$familyId = trim($_REQUEST["familyId"]);
	$userid = trim($_REQUEST["userid"]);
	$userLv = trim($_REQUEST["userLv"]);

	$familyMember = new MissionaryFamily();
	$familyMember->FamilyID = $familyId;
	$familyMember->delete();

	$ObjQuery = null;


	header("Location: "."mypage_member.php?userLv=".$userLv."&userid=".$userid);
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
	$status = isset($_REQUEST["status"]) ? $_REQUEST["status"] : "";
	$houseId = isset($_REQUEST["houseId"]) ? $_REQUEST["houseId"] : "";
	$roomId = isset($_REQUEST["roomId"]) ? $_REQUEST["roomId"] : "";
	$bookNo = isset($_REQUEST["bookNo"]) ? $_REQUEST["bookNo"] : "";

	if ($status < 2 || $status > 4) {
		alertBack("잘못된 값입니다");
	} 

	$reserv = new ReservationObject($bookNo);
	if ($status == "2") {
		$reserv->Status = "S0002";
	} else if ($status == "3") {
		$reserv->Status = "S0003";
	} else {
		$reserv->Status = "S0004";
	} 

	$reserv->Update();
	$reserv = null;

	header("Location: reserve_2.php?houseId=".$houseId."&roomId=".$roomId);
} 

function editReservationDate() {
	$houseId = isset($_REQUEST["houseId"]) ? $_REQUEST["houseId"] : "";
	$roomId = isset($_REQUEST["roomId"]) ? $_REQUEST["roomId"] : "";
	$bookNo = isset($_REQUEST["bookNo"]) ? $_REQUEST["bookNo"] : "";

	$start = isset($_REQUEST["startDate".$bookNo]) ? $_REQUEST["startDate".$bookNo] : "";
	$end = isset($_REQUEST["endDate".$bookNo]) ? $_REQUEST["endDate".$bookNo] : "";

	$book = new ReservationObject($bookNo);
	$book->StartDate = $start;
	$book->EndDate = $end;
	$book->Update();

	header("Location: reserve_2.php?houseId=".$houseId."&roomId=".$book->RoomId);
}

function reservation() {
	$book = new ReservationObject();

	$houseId = isset($_REQUEST["houseId"]) ? $_REQUEST["houseId"] : "";
	$book->StartDate = isset($_REQUEST["startDate"]) ? $_REQUEST["startDate"] : "";
	$book->EndDate = isset($_REQUEST["endDate"]) ? $_REQUEST["endDate"] : "";
	$book->RoomId = isset($_REQUEST["roomId"]) ? $_REQUEST["roomId"] : "";
	$book->userid = $_SESSION["userid"];
	$book->resv_name = isset($_REQUEST["resv_name"]) ? $_REQUEST["resv_name"] : "";
	$book->resv_phone = isset($_REQUEST["resv_phone"]) ? $_REQUEST["resv_phone"] : "";
	$book->resv_nation = isset($_REQUEST["resv_nation"]) ? $_REQUEST["resv_nation"] : "";
	$book->resv_assoc = isset($_REQUEST["resv_assoc"]) ? $_REQUEST["resv_assoc"] : "";

	if (!$book->checkId()) {
		header("Location: reserve_2.php?houseId=".$houseId);
	} else if (!$book->checkDate()) {
		header("Location: reserve_2.php?houseId=".$houseId."&roomId=".$book->RoomId);
	}

	$book->Update();
	header("Location: reserve_2.php?houseId=".$houseId."&roomId=".$book->RoomId);

	// SMS 메세지 보내기
	alertGoPage("예약요청 되었습니다.","reserve_2.php?houseId=".$houseId."&roomId=".$book->RoomId);
	$house = new HouseObject($houseId);
	$manager = new MemberObject($house->userid);
	$from_number="01010041004";
	$message="선교관 예약 신청이 들어왔습니다."." 선교관 : ".$house->HouseName." 예약날짜 : ".$_REQUEST["startDate"]." ~ ".$_REQUEST["endDate"];
	// sendSMSMessage($from_number, join($manager->Mobile, ""),$message);
} 
?>

