<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

$mode = trim($_REQUEST["mode"]);

switch ($mode) {
	case "login":
		login();
		break;
	case "editUser":
		editUser();
		break;
	case "deleteUser":
		deleteUser();
		break;
	case "deleteFamily":
		deleteFamily();
		break;
	case "upCommon":
		changeUser(1);
		break;
	case "upMission":
		changeUser(3);
		break;
	case "upHouse":
		changeUser(7);
		break;
} 

function editUser() {
	# 비밀번호 체크, 아이디 체크 해야함
	$missionary = isset($_REQUEST["missionary"]) ? trim($_REQUEST["missionary"]) : "0";
	$userLv = isset($_REQUEST["userLv"]) ? trim($_REQUEST["userLv"]) : "0";

	if ($missionary == "1") {
		addUserMissionary();
	} 

	editUserNormal();
	
	header("Location: http://".$_SERVER['HTTP_HOST']."/manager/member/index.php?userLv=".$userLv);
} 

function editUserNormal() {
	$member = new MemberObject($_REQUEST["userid"]);

	$member->Name = $_REQUEST["name"];
	$member->Nick = $_REQUEST["nickName"];
	if (isset($_REQUEST["password"])) {
		$member->Password = Encrypt($_REQUEST["password"]);
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

	$missionary = isset($_REQUEST["missionary"]) ? trim($_REQUEST["missionary"]) : "0";
	if ($missionary == "1") {
		$member->UserLevel = 3;
	} else {
		$member->UserLevel = 1;
	}

	$member->Update();
} 

function addUserMissionary() {
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
	$mission->Approval = $_REQUEST["approval"];

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

	$mission->ImageID = $_REQUEST["idProfile"];
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
	$userid = trim($_REQUEST["userid"]);

	$ObjQuery = new DataManager();

	$ObjQuery->setTable("users");
	$ObjQuery->setCondition("userid = '".$userid."'");
	$ObjQuery->delete();

	$ObjQuery->setTable("missionary");
	$ObjQuery->delete();

	$ObjQuery = null;


	header("Location: "."index.php");
} 

function deleteFamily() {
	$familyId = trim($_REQUEST["familyId"]);
	$userid = trim($_REQUEST["userid"]);
	$userLv = trim($_REQUEST["userLv"]);

	$familyMember = new MissionaryFamily();
	$familyMember->FamilyID = $familyId;
	$familyMember->delete();

	$ObjQuery = null;


	header("Location: "."editForm.php?userLv=".$userLv."&userid=".$userid);
} 

function changeUser($userLv) {
	$member = new MemberObject();

	$userid = trim($_REQUEST["userid"]);
	$member->Open($userid);
	$member->UserLevel = $userLv;
	$member->Update();

	header("Location: "."index.php");
} 
?>
