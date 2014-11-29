<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");
checkAuth();

sendSMSMessage("01085916394", "01087249504;", "테스트입니다.", "http://godlovehouse.net/", true, "문자");
?>