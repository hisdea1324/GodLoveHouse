<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

$mode = (isset($_REQUEST["mode"])) ? trim($_REQUEST["mode"]) : "";
$status = (isset($_REQUEST["status"])) ? trim($_REQUEST["status"]) : "S0001";

switch ($mode) {
	case "changeStatus":
		changeStatus();
		break;
	case "editReserv":
		editReserv();
		break;
	case "deleteReserv":
		deleteReserv();
		break;
} 

function changeStatus() {
	global $mysqli, $status;
	
	$reservId = trim($_REQUEST["reservId"]);
	$value = trim($_REQUEST["value"]);
	$query = "UPDATE reservation SET reservStatus = '".$value."' WHERE reservationNo = ".$reservId;
	$rs = $mysqli->query($query);

	header("Location: "."index.php?status=".$status);
} 

function deleteReserv() {
	global $status;
	
	$reservId = trim($_REQUEST["reservId"]);

	$ObjQuery = new DataManager();

	$ObjQuery->setTable("reservation");
	$ObjQuery->setCondition("reservationNo = ".$reservId);
	$ObjQuery->delete();

	$ObjQuery = null;

	header("Location: "."index.php?status=".$status);
} 

function editReserv() {
	global $status;
	
	$reservId = trim($_REQUEST["reservId"]);
	$fromDate = explode('-', trim($_REQUEST["fromDate"]));
	$fromDate = mktime(0, 0, 0, $fromDate[1], $fromDate[2], $fromDate[0]);
	$toDate = explode('-', trim($_REQUEST["toDate"]));
	$toDate = mktime(0, 0, 0, $toDate[1], $toDate[2], $toDate[0]);

	$ObjQuery = new DataManager();
	
	$fieldList = array("startDate","endDate");
	$valueList = array($fromDate, $toDate);

	$ObjQuery->setTable("reservation");
	$ObjQuery->setField($fieldList);
	$ObjQuery->setValue($valueList);
	$ObjQuery->setCondition("reservationNo = ".$reservId);
	$ObjQuery->update();

	$ObjQuery = null;

	header("Location: "."index.php?status=".$status);
} 
?>
