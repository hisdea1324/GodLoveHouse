<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

$mode = trim($_REQUEST["mode"]);

switch (($mode)) {
	case "editHouse":
		editHouse();
		break;
	case "deleteHouse":
		deleteHouse();
		break;
	case "confirmHouse":
		confirmHouse();
		break;
	case "editRoom":
		editRoom();
		break;
	case "deleteRoom":
		deleteRoom();
		break;
	case "editHospital":
		editHospital();
		break;
	case "deleteHospital":
		deleteHospital();
		break;
	case "confirmHospital":
		confirmHospital();
		break;
} 

function editHouse() {
	$house = new HouseObject();

	$contact[0] = $_REQUEST["contact11"];
	$contact[1] = $_REQUEST["contact12"];
	$contact[2] = $_REQUEST["contact13"];
	$contact2[0] = $_REQUEST["contact21"];
	$contact2[1] = $_REQUEST["contact22"];
	$contact2[2] = $_REQUEST["contact23"];
	$zipcode[0] = $_REQUEST["post1"];
	$zipcode[1] = $_REQUEST["post2"];

	$house->houseId = $_REQUEST["houseId"];
	$house->userId = $_REQUEST["userId"];
	$house->houseName = $_REQUEST["houseName"];
	$house->assocName = $_REQUEST["assocName"];
	$house->manager1 = $_REQUEST["manager1"];
	$house->contact1 = $contact;
	$house->contact2 = $contact2;
	$house->buildingType = $_REQUEST["buildType"];
	$house->zipcode = $zipcode;
	$house->address1 = $_REQUEST["addr1"];
	$house->address2 = $_REQUEST["addr2"];
	$house->price = $_REQUEST["price"];
	$house->personLimit = $_REQUEST["personLimit"];
	$house->roomLimit = $_REQUEST["roomLimit"];
	$house->explain = $_REQUEST["explain"];
	$house->regionCode = $_REQUEST["region"];
	$house->status = $_REQUEST["status"];
	$house->documentId = $_REQUEST["idDocument"];
	$house->document = $_REQUEST["txtDocument"];
	$house->homepage = $_REQUEST["homepage"];
	$house->Update();

	$house = null;

	if (strlen($_REQUEST["status"]) > 0) {
		alertGoPage("처리되었습니다.","index.php?status=".$_REQUEST["status"]);
	} else {
		alertGoPage("처리되었습니다.","index.php");
	} 

} 

function editRoom() {
	$room = new RoomObject();

	$room->roomId = $_REQUEST["roomId"];
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

	header("Location: roomList.php?houseId=".$_REQUEST["houseId"]);
} 

function deleteRoom() {
	global $mysqli;
	
	$houseId = trim($_REQUEST["houseId"]);
	$roomId = trim($_REQUEST["roomId"]);

	$query = "DELETE FROM room WHERE roomId = ".$roomId;
	$deleteRs = $mysqli->query($query);
	$query = "UPDATE house SET roomCount = roomCount - 1 WHERE houseId = ".$houseId;
	$deleteRs = $mysqli->query($query);

	header("Location: roomList.php?houseId={$houseId}");
} 

function deleteHouse() {
	$houseId = trim($_REQUEST["houseId"]);
	$query = "DELETE FROM house WHERE houseId = ".$houseId;
	$rs = $db->execute($query);
	$query = "DELETE FROM room WHERE houseId = ".$houseId;
	$rs = $db->execute($query);

	header("Location: index.php");
} 

function confirmHouse() {
	global $mysqli;
	
	$houseId = trim($_REQUEST["houseId"]);
	$value = trim($_REQUEST["value"]);
	$query = "UPDATE house SET status = '".$value."' WHERE houseId = ".$houseId;
	$result = $mysqli->query($query);

	header("Location: "."index.php");
} 

function editHospital() {
	$hospital = new HospitalObject();

	$contact[0] = $_REQUEST["contact11"];
	$contact[1] = $_REQUEST["contact12"];
	$contact[2] = $_REQUEST["contact13"];
	$contact2[0] = $_REQUEST["contact21"];
	$contact2[1] = $_REQUEST["contact22"];
	$contact2[2] = $_REQUEST["contact23"];
	$zipcode[0] = $_REQUEST["post1"];
	$zipcode[1] = $_REQUEST["post2"];

	$hospital->HospitalID = $_REQUEST["hospitalId"];
	$hospital->UserID = $_REQUEST["userId"];
	$hospital->HospitalName = $_REQUEST["hospitalName"];
	$hospital->AssocName = $_REQUEST["assocName"];
	$hospital->Manager1 = $_REQUEST["manager1"];
	$hospital->Contact1 = $contact;
	$hospital->Contact2 = $contact2;
	$hospital->Zipcode = $zipcode;
	$hospital->Address1 = $_REQUEST["addr1"];
	$hospital->Address2 = $_REQUEST["addr2"];
	$hospital->Price = $_REQUEST["price"];
	$hospital->PersonLimit = $_REQUEST["personLimit"];
	$hospital->Explain = $_REQUEST["explain"];
	$hospital->RegionCode = $_REQUEST["region"];
	$hospital->Status = $_REQUEST["status"];
	$hospital->DocumentId = $_REQUEST["idDocument"];
	$hospital->Document = $_REQUEST["txtDocument"];
	$hospital->Homepage = $_REQUEST["homepage"];

	$hospital->ImageID1 = $_REQUEST["idHospitalImage1"];
	$hospital->ImageID2 = $_REQUEST["idHospitalImage2"];
	$hospital->ImageID3 = $_REQUEST["idHospitalImage3"];
	$hospital->ImageID4 = $_REQUEST["idHospitalImage4"];

	$hospital->Update();

	$hospital = null;

	if ((strlen($_REQUEST["status"])>0)) {
alertGoPage("처리되었습니다.","hospital.php?status=".$_REQUEST["status"]);
	} else {
alertGoPage("처리되었습니다.","hospital.php");
	} 

} 

function deleteHospital() {
	$hospitalId = trim($_REQUEST["hospitalId"]);
	$query = "DELETE FROM hospital WHERE hospitalId = ".$hospitalId;
	$rs = $db->execute($query);

	header("Location: "."hospital.php");
} 

function confirmHospital() {
	$hospitalId = trim($_REQUEST["hospitalId"]);
	$value = trim($_REQUEST["value"]);
	$query = "UPDATE hospital SET status = '".$value."' WHERE hospitalId = ".$hospitalId;
	$rs = $db->execute($query);

	header("Location: "."index.php");
} 

?>
