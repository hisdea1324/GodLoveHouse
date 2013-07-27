<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

$mode = trim($_REQUEST["mode"]);
if (trim($mode)=="") {
	$mode="addReserv";
} 

$reservId = trim($_REQUEST["reservId"]);
$field = trim($_REQUEST["field"]);
$keyword = trim($_REQUEST["keyword"]);
$page = trim($_REQUEST["page"]);

if ((strlen($reservId)>0)) {
	$query = "SELECT A.startDate, A.endDate, B.nick, B.name, B.phone, B.mobile, C.RoomName, D.houseName, D.assocName, D.manager1, D.contact1, D.manager2, D.contact2, E.name AS regionName FROM reservation A, users B, room C, house D, code E ";
	$query = $query."WHERE A.userId = B.userId AND A.roomId = C.roomId AND C.houseId = D.houseId AND D.regionCode = E.code AND A.reservationNo = ".$reservId;
	$reservRS = $db->Execute($query);
	$fromDate = $reservRS["startDate"];
	$toDate = $reservRS["endDate"];
	$nick = $reservRS["nick"];
	$userName = $reservRS["name"];
	$phone = $reservRS["phone"];
	$mobile = $reservRS["mobile"];
	$houseName = $reservRS["houseName"];
	$assocName = $reservRS["assocName"];
	$roomName = $reservRS["roomName"];
	$contact1 = $reservRS["contact1"]."(".$reservRS["manager1"].")";
	$contact2 = $reservRS["contact2"]."(".$reservRS["manager2"].")";
	$regionName = $reservRS["regionName"];
	$reservRS = null;

} 


checkAuth();
showAdminHeader("관리툴 - 예약 정보 수정","","","");
body();
showAdminFooter();

function body() {
?>
	<div class="sub">
	<a href="index.php?status=S0001">신규예약</a> | 
	<a href="index.php?status=S0002">승인</a> | 
	<a href="index.php?status=S0003">완료</a> |
	<a href="index.php?status=S0004">승인불가</a>
	</div>
	</div>
	<div id="wrap">
		<div class="lSec">
		<ul>
			<li><img src="/images/manager/lm_0300.gif"></li>
		<li><a href="index.php?status=S0001"><img src="/images/manager/lm_0301.gif"></a></li>
		<li><a href="index.php?status=S0002"><img src="/images/manager/lm_0302.gif"></a></li>
		<li><a href="index.php?status=S0003"><img src="/images/manager/lm_0303.gif"></a></li>
		<li><a href="index.php?status=S0004"><img src="/images/manager/lm_0304.gif"></a></li>
		<li><img src="/images/manager/lm_bot.gif"></li>
		</ul>
	</div>
		
	<div class="rSec">
		<dl>
		<form name="dataForm" method="post">
		<input type="hidden" name="field" value="<?php echo $field;?>" />
		<input type="hidden" name="keyword" value="<?php echo $keyword;?>" />
		<input type="hidden" name="page" value="<?php echo $page;?>" />
		<input type="hidden" name="mode" value="<?php echo $mode;?>" />
		<input type="hidden" name="reservId" value="<?php echo $reservId;?>" />
			<dt>
				예약날짜
			<dd>
				<input type="text" name="fromDate" id="fromDate" value="<?php echo $fromDate;?>" style="width:100px" class="input" readonly onclick="calendar('fromDate')">
				<img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('fromDate')" style="cursor:hand;"> ~
				<input type="text" name="toDate" id="toDate" value="<?php echo $toDate;?>" style="width:100px" class="input" readonly onclick="calendar('toDate')">
				<img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('toDate')" style="cursor:hand;">
			<dt>
				예약자 정보
			<dd>
				<ol>
				<li>회원이름 : <?php echo $userName;?> (<?php echo $nick;?>)</li>
				<li>전화 : <?php echo $phone;?></li>
				<li>핸드폰 : <?php echo $mobile;?></li>
				</ol>
			<dt>
				선교관 & 방 정보
			<dd>
				<ol>
				<li>방 : <?php echo $roomName;?></li>
				<li>선교관 : <?php echo $houseName;?> (<?php echo $assocname;?>)</li>
				<li>션교관 연락처1 : <?php echo $contact1;?></li>
				<li>션교관 연락처2 : <?php echo $contact2;?></li>
				<li>지역 : <?php echo $regionName;?></li>
				</ol>
			<dt>
			<dd>
				<img src="/images/button/btn_ok.gif" border="0" onclick="check();" style="cursor:hand;">&nbsp;&nbsp;&nbsp;
				<img src="/images/button/btn_cancel.gif" border="0" onclick="history.back(-1);" style="cursor:hand"></a>
		</form>
		</dl>
	</div>

<?php } ?>

<script language='javascript' src='<?php echo "http://".$_SERVER['SERVER_NAME'];?>/include/js/calendar.js'></script>
<script type="text/javascript">
//<![CDATA[
	calendar_init();

	function check() {
		document.getElementById("dataForm").action="process.php";
		document.getElementById("dataForm").submit();
	}
//]]>
</script>
