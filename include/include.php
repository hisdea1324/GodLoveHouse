<?php
session_start();

require_once($_SERVER['DOCUMENT_ROOT']."/include/config.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/dbconn.php");

#require "class/dbHelper.php";
require_once($_SERVER['DOCUMENT_ROOT']."/include/class/ErrorHandler.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/class/tableBuilder.php");
#require "class/dataManager.php";
#require "class/BoardHelper.php";
require_once($_SERVER['DOCUMENT_ROOT']."/include/class/CodeHelper.php");
#require "class/CommentHelper.php";
#require "class/SupportHelper.php";
require_once($_SERVER['DOCUMENT_ROOT']."/include/class/HouseHelper.php");
#require "class/HospitalHelper.php";
require_once($_SERVER['DOCUMENT_ROOT']."/include/function/global.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/function/converter.php");
#require "function/validator.php";
#require "function/math.php";
#require "function/file.php";
require_once($_SERVER['DOCUMENT_ROOT']."/include/function/script.php");
#require "function/fileuploadComm.php";
#require "function/string.php";
#require "function/debug.php";
#require "dataFormat/AttachFile.php";
require_once($_SERVER['DOCUMENT_ROOT']."/include/dataFormat/CodeObject.php");
#require "dataFormat/CommentObject.php";
require_once($_SERVER['DOCUMENT_ROOT']."/include/dataFormat/MemberObject.php");
#require "dataFormat/MissionObject.php";
#require "dataFormat/MissionaryFamily.php";
#require "dataFormat/AccountObject.php";
#require "dataFormat/SupportObject.php";
#require "dataFormat/SupportItemObject.php";
#require "dataFormat/RequestObject.php";
#require "dataFormat/RequestAddInfo.php";
#require "dataFormat/RequestItemObject.php";
require_once($_SERVER['DOCUMENT_ROOT']."/include/dataFormat/HouseObject.php");
#require "dataFormat/HospitalObject.php";
#require "dataFormat/RoomObject.php";
#require "dataFormat/ReservationObject.php";
#require "dataFormat/BoardObject.php";
#require "dataFormat/BoardGroup.php";

error_reporting(-1);
ini_set('display_errors', 'On');
?>