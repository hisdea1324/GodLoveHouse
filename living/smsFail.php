<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

$rcode = $_REQUEST["RCODE"];
$pid = $_REQUEST["PID"];

print "Fail : ".$rcode." ".$pid;
alertGoPage("예약요청이 실패 하였습니다.","reservation.php");
?>
