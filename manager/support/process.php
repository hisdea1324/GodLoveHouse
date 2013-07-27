<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

$mode = trim($_REQUEST["mode"]);

switch (($mode)) {
	case "editRequest":
editRequest();
		break;
	case "deleteRequest":
deleteRequest();
		break;
	case "editRequestDetail":
editRequestDetail();
		break;
	case "deleteRequestDetail":
deleteRequestDetail();
		break;
	case "statusChange":
statusChange();
		break;
} 

function editRequest() {
	$requestObj = new RequestObject();

	$supportType = $_REQUEST["supportType"];
	$requestObj->RequestID = $_REQUEST["reqId"];
	$requestObj->SupportType = $supportType;
	$requestObj->Title = $_REQUEST["title"];
	$requestObj->Explain = $_REQUEST["explain"];
	$requestObj->ImageID = $_REQUEST["idImageFile"];
	$requestObj->Update();

	switch (($supportType)) {
		case "03001":
			$requestAdd = new RequestAddInfo();
			$requestAdd->RequestID = $requestObj->RequestID;
			$requestAdd->UserID = $_REQUEST["userId"];
			$requestAdd->Due = $_REQUEST["dueDate"];
			$requestAdd->NationCode = $_REQUEST["nationCode"];
			$requestAdd->Update();
			$retURL="index.php";
			break;
		case "03002":
			$retURL="center.php";
			break;
		case "03003":
			$retURL="service.php";
			break;
		default:
			print "에러";
			exit();

			break;
	} 

	$requestObj = null;

	$requestAdd = null;

	header("Location: ".$retURL);
} 

function deleteRequest() {
	$supportType = trim($_REQUEST["supportType"]);
	switch (($supportType)) {
		case "03001":
			$requestAdd = new RequestAddInfo();
			$requestAdd->Open($_REQUEST["reqId"]);
			$requestAdd->Delete();
			$retURL="index.php";
			break;
		case "03002":
			$retURL="center.php";
			break;
		case "03003":
			$retURL="service.php";
			break;
		default:
			print "에러";
			exit();

			break;
	} 

	$requestObj = new RequestObject();
	$requestObj->Open($_REQUEST["reqId"]);
	$requestObj->Delete();

	$requestObj = null;

	$requestAdd = null;

	header("Location: ".$retURL);
} 

function editRequestDetail() {
	$requestItemObj = new RequestItemObject();
	$requestItemObj->RequestItemID = $_REQUEST["id"];
	$requestItemObj->RequestID = $_REQUEST["reqId"];
	$requestItemObj->RequestItem = $_REQUEST["item"];
	$requestItemObj->Descript = $_REQUEST["descript"];
	$requestItemObj->Cost = $_REQUEST["cost"];
	$requestItemObj->Status = $_REQUEST["status"];
	$requestItemObj->Update();

	header("Location: "."subRequest.php?reqId=".$requestItemObj->RequestID);
	$requestItemObj = null;

} 

function deleteRequestDetail() {
	$requestItemObj = new RequestItemObject();
	$requestItemObj->Open($_REQUEST["id"]);
	$requestItemObj->Delete();

	header("Location: "."subRequest.php?reqId=".$requestItemObj->RequestID);
} 

function statusChange() {
	$support = new SupportObject();
	$support->OpenWithSupId($_REQUEST["supId"]);
	$support->ChangeStatus();
	header("Location: "."supportList.php?wait=".$_REQUEST["wait"]);
} 
?>
