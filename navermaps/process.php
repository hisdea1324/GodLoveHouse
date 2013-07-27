<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

$mode = trim($_REQUEST["mode"]);
switch (($mode)) {
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
		$needUserLv[1];
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
	$houseId = trim($_REQUEST["houseId"]);
	$roomId = trim($_REQUEST["roomId"]);

	$query = "DELETE FROM room WHERE roomId = ".$roomId;
	$deleteRs = $db->Execute($query);
	$query = "UPDATE house SET roomCount = roomCount - 1 WHERE houseId = ".$houseId;
	$deleteRs = $db->Execute($query);

	$deleteRs = null;

	header("Location: "."mypage_houseInfo.php?houseId=".$_REQUEST["houseId"]);
} 

function editUser() {
	# $비밀번호$체크	$아이디$체크$해야함;
	$missionary = trim($_REQUEST["missionary"]);

	if (($missionary=="1")) {
		editUserMissionary();
	} 

	editUserNormal();

	$getURL = $_SERVER["HTTP_REFERER"];

	$parsedURL = $ParsingURL[$getURL];
	if ((strcmp($parsedURL->Item("path"),"/member/join.php")==0)) {
		$returnURL = $Application["WebRoot"]."member/login.php";
	} else {
		$returnURL = $parsedURL->Item("path");
	} 


alertGoPage("가입 되었습니다.",$returnURL);
} 

function editUserNormal() {
	$member = new MemberObject();

	$member->UserID = $_REQUEST["userId"];
	$member->Name = $_REQUEST["name"];
	$member->Nick = $_REQUEST["nickName"];
	$member->Password = $Encrypt[$_REQUEST["password"]];
	$member->PasswordQuestion = $_REQUEST["password_quest"];
	$member->PasswordAnswer = $_REQUEST["password_answer"];

	$jumin[0] = $_REQUEST["jumin1"];
	$jumin[1] = $_REQUEST["jumin2"];
	$member->Jumin = $jumin;

	$email[0] = $_REQUEST["email1"];
	$email[1] = $_REQUEST["email2"];
	$member->Email = $email;

	$post[0] = $_REQUEST["post1"];
	$post[1] = $_REQUEST["post2"];
	$member->Post = $post;
	$member->Address1 = $_REQUEST["addr1"];
	$member->Address2 = $_REQUEST["addr2"];

	$tel[0] = $_REQUEST["tel1"];
	$tel[1] = $_REQUEST["tel2"];
	$tel[2] = $_REQUEST["tel3"];
	$member->Phone = $tel;

	$mobile[0] = $_REQUEST["hp1"];
	$mobile[1] = $_REQUEST["hp2"];
	$mobile[2] = $_REQUEST["hp3"];
	$member->Mobile = $mobile;

	$member->CheckMessageOption = $_REQUEST["smsOk"];

	$member->UserLevel = $_REQUEST["level"];
	if (($_REQUEST["missionary"]=="1" && $_REQUEST["level"]==1)) {
		$member->UserLevel=3;
	} 


	$member->Update();
} 

function editUserMissionary() {
	$mission = new MissionObject();

	$mission->UserID = $_REQUEST["userId"];
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
		$familyMember->UserID = $_REQUEST["userId"];
		$familyMember->familyID = $familyId[$i];
		$familyMember->Name = $familyName[$i];
		$familyMember->Age = $familyAge[$i];
		$familyMember->Sex = $familySex[$i];
		$familyMember->Relation = $familyRelation[$i];
		$familyMember->Update();

	}


} 

function deleteUser() {
	# $비밀번호$체크	$아이디$체크$해야함;
	logout();
} 

function login() {

	$userid = trim($_REQUEST["userid"]);
	$password = trim($_REQUEST["password"]);
	$backURL = trim($_REQUEST["backURL"]);

	$loginSession = new __construct();
	$result = $loginSession->setBasicInfo($userid, $password);

//값이 제대로 들어있지 않다면 이전 페이지로 돌아간다.
	switch ($result) {
		case 0:
			if ((strlen($backURL)>0)) {
				header("Location: ".$URLDecode[$backURL]);
			} else {
				header("Location: "."../index.php");
			} 

			break;
		case 1:
			header("Location: "."login.php?userid=".$userid."&backURL=".$backURL);
			break;
		default:
			header("Location: "."login.php");
			break;
	} 

	header("Location: "."../index.php");
} 

function deleteFamily() {
	$familyId = trim($_REQUEST["familyId"]);
	$userId = trim($_REQUEST["userId"]);
	$userLv = trim($_REQUEST["userLv"]);

	$familyMember = new MissionaryFamily();
	$familyMember->FamilyID = $familyId;
	$familyMember->delete();

	$ObjQuery = null;


	header("Location: "."mypage_member.php?userLv=".$userLv."&userId=".$userId);
} 

function logout() {
	$loginSession = new __construct();
	$loginSession->expireSession();

	header("Location: ".$Application["WebRoot"]."index.php");
} 

function changeReservStatus() {
	$status = trim($_REQUEST["status"]);
	$houseId = trim($_REQUEST["houseId"]);
	$roomId = trim($_REQUEST["roomId"]);
	if (($status<2 || $status>4)) {
		$alertBack["잘못된 값입니다"];
	} 
	$bookNo = trim($_REQUEST["bookNo"]);

	$reserv = new ReservationObject();
	$reserv->Open($bookNo);
	if (($status=="2")) {
		$reserv->Status="S0002";
	} elseif (($status=="3")) {
		$reserv->Status="S0003";
	} else {
		$reserv->Status="S0004";
	} 

	$reserv->Update();

	$reserv = null;


	header("Location: ".$Application["WebRoot"]."member/mypage_houseReserv.php?houseId=".$houseId."&roomId=".$roomId);
} 

function reservation() {
	$sessions = new __construct();
	$book = new reservationObject();

	$houseId = $_REQUEST["houseId"];
	$book->StartDate = $_REQUEST["startDate"];
	$book->EndDate = $_REQUEST["endDate"];
	$book->RoomId = $_REQUEST["roomId"];
	$book->UserId = $sessions->UserID;

	if ((!$book->checkId())) {
		header("Location: "."mypage_houseReserv.php?houseId=".$houseId);
	} else if ((!$book->checkDate())) {
		header("Location: "."mypage_houseReserv.php?houseId=".$houseId."&roomId=".$book->RoomId);
	} 


	$book->Update();

// SMS 메세지 보내기alertGoPage("예약요청 되었습니다.","mypage_houseReserv.php?houseId=".$houseId."&roomId=".$book->RoomId);
	$house = new HouseObject();
	$manager = new MemberObject();
	$house->Open($houseId);
	$manager->Open($house->UserID);
	$from_number="01010041004";
	$message="선교관 예약 신청이 들어왔습니다."." 선교관 : ".$house->HouseName." 예약날짜 : ".$_REQUEST["startDate"]." ~ ".$_REQUEST["endDate"];
sendSMSMessage($from_number,$Join[$manager->Mobile][""],$message);

} 
?>

