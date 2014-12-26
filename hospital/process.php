<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

$mode = trim($_REQUEST["mode"]);

switch (($mode)) {
	case "registHospital":
		registHospital();
		break;
	case "reservation":
		$needUserLv[1];
		reservation();
		break;
	default:
		print "잘못된 접근";
		exit();
		break;
} 

function registHospital() {
	$hospital = new HospitalObject();

	$contact[0] = $_REQUEST["contact1"];
	$contact[1] = $_REQUEST["contact2"];
	$contact[2] = $_REQUEST["contact3"];
	$zipcode[0] = $_REQUEST["post1"];
	$zipcode[1] = $_REQUEST["post2"];

	if ((strlen($_REQUEST["hospitalId"])>0)) {
		$hospital->HospitalID = $_REQUEST["hospitalId"];
		$hospital->userid = $_REQUEST["userid"];
	} 

	$hospital->HospitalName = $_REQUEST["hospitalName"];
	$hospital->AssocName = $_REQUEST["assocName"];
	$hospital->Manager1 = $_REQUEST["manager"];
	$hospital->Contact1 = $contact;
	$hospital->Zipcode = $zipcode;
	$hospital->Address1 = $_REQUEST["addr1"];
	$hospital->Address2 = $_REQUEST["addr2"];
	$hospital->Price = $_REQUEST["price"];
	$hospital->PersonLimit = $_REQUEST["personLimit"];
	$hospital->Explain = $_REQUEST["explain"];
	$hospital->RegionCode = $_REQUEST["regionCode"];
	$hospital->Update();

	$hospital = null;


	if ((strlen($_REQUEST["hospitalId"])>0)) {
		alertGoPage("병원 정보수정이 처리 되었습니다.","/member/mypage_hospitalInfo.php");
	} else {
		alertGoPage("병원 등록요청이 되었습니다.","registHouse.php");
	} 
} 

function reservation() {
	$book = new reservationObject();

	$hospitalId = $_REQUEST["hospitalId"];
	$book->StartDate = $_REQUEST["startDate"];
	$book->EndDate = $_REQUEST["endDate"];
	$book->userid = $_SESSION["userid"];

	if ((!$book->checkId())) {
		header("Location: "."reservation.php?hospitalId=".$hospitalId);
	} else if ((!$book->checkDate())) {
		header("Location: "."reservationDetail.php?hospitalId=".$book->HospitalId);
	} 

	$book->Update();

	alertGoPage("예약요청 되었습니다.","reservation.php");
	# SMS 메세지 보내기	
	$hospital = new HospitalObject();
	$manager = new MemberObject();
	$hospital->Open($hospitalId);
	$manager->Open($hospital->userid);
	$from_number="07078076394";
	$message="병원 예약 신청이 들어왔습니다."." 병원 : ".$hospital->HospitalName." 예약날짜 : ".$_REQUEST["startDate"]." ~ ".$_REQUEST["endDate"];
	sendSMSMessage($from_number,$Join[$manager->Mobile][""],$message);
} 
?>
