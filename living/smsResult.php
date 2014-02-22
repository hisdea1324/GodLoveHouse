<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

$rcode = $_REQUEST["R_CODE"];
$pid = $_REQUEST["P_ID"];

//response.write "Result : " & rcode & " " & pid
alertGoPage("예약요청 되었습니다.","reservation.php");
?>
