<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

$roomId = (isset($_REQUEST["roomId"])) ? trim($_REQUEST["roomId"]) : "";
$houseId = (isset($_REQUEST["houseId"])) ? trim($_REQUEST["houseId"]) : "";
$toDate = (isset($_REQUEST["toDate"])) ? trim($_REQUEST["toDate"]) : "";
$fromDate = (isset($_REQUEST["fromDate"])) ? trim($_REQUEST["fromDate"]) : "";

$h_Helper = new HouseHelper();
$room = $h_Helper->getRoomInfoById($roomId);
$house = $h_Helper->getHouseInfoById($room->HouseID);

if ($house->StatusCode == "S2002") {
	showHeader("HOME > 선교관 > 예약종합안내","living","tit_0202.gif");
} else {
	showHeader("HOME > 선교관 >	기타 선교관 안내","living","tit_0201.gif");
} 

body();
showFooter();

function body() {
	global $roomId, $houseId, $fromDate, $toDate;
	global $room, $house;
?>
	<!-- //content -->
	<div id="content">

		<!-- //search -->
		<div id="search">
			<img src="../images/board/img_search.gif" align="absmiddle">
			<img src="../images/board/txt_reserve.gif" align="absmiddle" class="m5">
			<select name="room" id="room" onchange="selectRoom()">
				<option value=''>-- 객실선택 --</option>
<?php 
	foreach ($house->RoomList as $roomInfo) {
		if (trim($roomId) == trim($roomInfo->RoomID)) {
?>
				<option value='<?php echo $roomInfo->RoomID;?>' selected><?php echo $roomInfo->RoomName;?></option>
<?php 
		} else {
?>
				<option value='<?php echo $roomInfo->RoomID;?>'><?php echo $roomInfo->RoomName;?></option>
<?php
		} 
	}

?>
			</select>
		</div>
		<!-- search// -->

		<H2><img src="../images/board/stit_reserve_01.gif"></H2>
		<div id="calendar">
			<!-- //photo -->
			<div class="photo">
				<p class="img01"><img src="<?php echo $room->Image1;?>" width="320" id="mainImage" /></p>
				<div class="img02">
				<ul>
					<li><img src="<?php echo $room->Image1;?>" width="70" border="0" onclick="changeImage('<?php echo $room->Image1;?>')" style="cursor:pointer;"></li>
					<li><img src="<?php echo $room->Image2;?>" width="70" border="0" onclick="changeImage('<?php echo $room->Image2;?>')" style="cursor:pointer;"></li>
					<li><img src="<?php echo $room->Image3;?>" width="70" border="0" onclick="changeImage('<?php echo $room->Image3;?>')" style="cursor:pointer;"></li>
					<li><img src="<?php echo $room->Image4;?>" width="70" border="0" onclick="changeImage('<?php echo $room->Image4;?>')" style="cursor:pointer;"></li>
				</ul>
				</div>
			</div>
			<!-- photo// -->
	
			<!-- //calenar -->
			<div class="cal" name='reservationCal' id='reservationCal'>
			</div>
			<!-- calendar// -->
		</div>

<?php
	if ($house->StatusCode == "S2002") {
?>
		<form action="process.php" method="post" name="frmReserve" id="frmReserve">
			<input type="hidden" name="mode" id="mode" value="reservation" />
			<input type="hidden" name="roomId" id="roomId" value="<?php echo $roomId;?>" />
			<input type="hidden" name="houseId" id="roomId" value="<?php echo $houseId;?>" />
			<h2><img src="../images/board/stit_reserve_03.gif"></h2>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_reserve">
				<col width="15%">
				<col />
				<tr>
					<td class="td01"><p class="reserve"><b>날짜입력</b></td>
					<td>
						<input type="text" name="startDate" id="startDate" value="<?php echo $fromDate;?>" class="input" readonly onclick="calendar('startDate')">
						<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('startDate')"> ~
						<input type="text" name="endDate" id="endDate" class="input" value="<?php echo $toDate;?>" readonly onclick="calendar('endDate')">
						<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('endDate')">
						<img src="../images/board/btn_reserve.gif" border="0" align="absmiddle" class="m5" onclick="reserveSubmit()"></p>
						<label class="fs11" type="text" name='resultMessage1' id='resultMessage1'></label>
					</td>
				</tr>
			</table>
		</form>
		<br /><br />
		<?php } ?>
	
		<H2><img src="../images/board/stit_reserve_02.gif"></H2>
		<!-- //view-->
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_reserve">
			<col width="15%">
			<col />
			<col width="15%">
			<col />
			<col width="20%">
			<col />
			<tr>
				<td class="td01">주거형태</td>
				<td><?php echo $house->BuildingType;?></td>
				<td class="td01">최대인원</td>
				<td><?php echo $room->Limit;?>명</td>
				<td class="td01">가격(1일기준) </td>
				<td><?php echo priceFormat($room->Fee, 1);?></td>
			</tr>
			<tr>
				<td class="td01">세탁시설</td>
				<td><?php echo $room->Laundary;?></td>
				<td class="td01">주방시설</td>
				<td><?php echo $room->Kitchen;?></td>
				<td class="td01">인터넷</td>
				<td><?php echo $room->Network;?></td>
			</tr>
			<tr>
				<td class="td01">제출서류</td>
				<td colspan="5"><?php echo $house->Document;?><br></td>
			</tr>
			<tr>
				<td class="td01">선교관 소개</td>
				<td colspan="5"><?php echo $house->Explain;?></td>
			</tr>
			<tr>
				<td class="td01">주소</td>
				<td colspan="5">
					[<?php echo $house->Zipcode?>]
					<a href="#" Onclick="javascript:window.open('../navermaps/a5.php?Naddr=<?php echo rawurlencode($house->Address1.$house->Address2);?>','win','top=0, left=500, width=550,height=450')"><?php echo $house->Address1;?> <?php echo $house->Address2;?></A>
				</td>
			</tr>
			<tr>
				<td class="td01">홈페이지</td>
				<td colspan="5">
					<?php echo $house->HomePage;?>
				</td>
			</tr>
			<tr>
				<td class="td01">담당자</td>
				<td colspan="5">
					<?php echo $house->showContactInfo();?>
				</td>
			</tr>
		</table>
		<!-- view// -->
		<p class="btn_right"><img src="../images/board/btn_list.gif" border="0" class="m2" onclick="goRoomList();"></p>	
		</div>
	</div>
	<!-- content// -->
<?php } ?>

<script type="text/javascript">
//<![CDATA[
	calendar_init();
	
	var date = new Date;
	var year = date.getFullYear();
	var month = date.getMonth();
	callPage(year, month + 1);
	
	function selectRoom() {
		var room = document.getElementById("room").value;
		if (room.length == 0) {
			return;
		}
		
		location.href = "reservationDetail.php?houseId=<?php echo $houseId;?>&roomId=" + room;
	}
	
	function goRoomList() {
		history.back(-1);
		//location.href = "reservation.php?houseId=<?php echo $houseId;?>";
	}
	
	function changeImage(imgName) {
		document.getElementById("mainImage").src = imgName;
	}
	
	function callPage(y, m) {
		var url = '/common/ajax/calendar.php?roomId=<?php echo $roomId;?>&year='+y+'&month='+m;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: '', onComplete: insertCalendar});
	}

	function insertCalendar(reqResult) {
		var addHtml = reqResult.responseText;
		document.getElementById("reservationCal").innerHTML = addHtml;
	}
	
	function reserveSubmit() {
<?php 
if (isset($_SESSION['UserLv']) && $_SESSION['UserLv'] <= 1) {
?>
			alert('선교사 혹은 선교관리자 회원이 되어야 예약이 가능합니다.');
			return;
<?php 
} else {
?>
		var endDate = document.getElementById("endDate").value;
		var startDate = document.getElementById("startDate").value;

		if (startDate.length == 0 || endDate.length == 0) {
			alert('숙박 기간을 정확히 입력해 주세요');
			return;
		}

		if (startDate.replace(/-/g,'') >= endDate.replace(/-/g,'')) {
			alert('기간이 잘못되었습니다.');
			return;
		}

		document.getElementById("frmReserve").submit();
<?php } ?>
	}
//]]>
</script>

