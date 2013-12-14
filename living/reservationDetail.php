<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

$roomId = (isset($_REQUEST["roomId"])) ? trim($_REQUEST["roomId"]) : "";
$houseId = (isset($_REQUEST["houseId"])) ? trim($_REQUEST["houseId"]) : "";
$toDate = (isset($_REQUEST["toDate"])) ? trim($_REQUEST["toDate"]) : "";
$fromDate = (isset($_REQUEST["fromDate"])) ? trim($_REQUEST["fromDate"]) : "";

$h_Helper = new HouseHelper();
$room = $h_Helper->getRoomInfoById($roomId);
$house = $h_Helper->getHouseInfoById($room->HouseID);

$m_Helper = new MemberHelper();
if (isset($_SESSION["userid"])) {
	$member = $m_Helper->getMemberByuserid($_SESSION["userid"]);
	$mission = $m_Helper->getMissionInfoByuserid($_SESSION["userid"]);
} else {
	$member = $m_Helper->getMemberByuserid();
	$mission = $m_Helper->getMissionInfoByuserid();
}

if ($house->StatusCode == "S2002") {
	showHeader("HOME > 선교관 > 예약종합안내","living","tit_0202.gif");
} else {
	showHeader("HOME > 선교관 > 기타 선교관 안내","living","tit_0201.gif");
} 

body();
showFooter();

function body() {
	global $roomId, $houseId, $fromDate, $toDate;
	global $room, $house, $member, $mission;
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
				<option value='<?=$roomInfo->RoomID?>' selected><?=$roomInfo->RoomName?></option>
<?php 
		} else {
?>
				<option value='<?=$roomInfo->RoomID?>'><?=$roomInfo->RoomName?></option>
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
				<p class="img01"><img src="<?=$room->Image1?>" width="320" id="mainImage" /></p>
				<div class="img02">
				<ul>
					<li><img src="<?=$room->Image1?>" width="70" border="0" onclick="changeImage('<?=$room->Image1?>')" style="cursor:pointer;"></li>
					<li><img src="<?=$room->Image2?>" width="70" border="0" onclick="changeImage('<?=$room->Image2?>')" style="cursor:pointer;"></li>
					<li><img src="<?=$room->Image3?>" width="70" border="0" onclick="changeImage('<?=$room->Image3?>')" style="cursor:pointer;"></li>
					<li><img src="<?=$room->Image4?>" width="70" border="0" onclick="changeImage('<?=$room->Image4?>')" style="cursor:pointer;"></li>
				</ul>
				</div>
			</div>
			<!-- photo// -->
			<!-- //calenar -->
			<div class="cal" name='reservationCal' id='reservationCal'>
			</div>
			<!-- calendar// -->
		</div>
   		
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
				<td><?=$house->building?></td>
				<td class="td01">최대인원</td>
				<td><?=$room->Limit?>명</td>
				<td class="td01">가격(1일기준) </td>
				<td colspan="3"><?=$room->showFee()?></td>
			</tr>
			<tr>
				<td class="td01">세탁시설</td>
				<td><?=$room->Laundary?></td>
				<td class="td01">주방시설</td>
				<td><?=$room->Kitchen?></td>
				<td class="td01">인터넷</td>
				<td><?=$room->Network?></td>
				<td class="td01">침대</td>
				<td><?=$room->bed?></td>
			</tr>
			<tr>
				<td class="td01">제출서류</td>
				<td colspan="7"><?=$house->document_link?><br></td>
			</tr>
			<tr>
				<td class="td01">선교관 소개</td>
				<td colspan="7"><?=$house->Explain?></td>
			</tr>
			<tr>
				<td class="td01">주소</td>
				<td colspan="7">
					[<?=implode('-', $house->Zipcode)?>]
					<a href="javascript:void(0)" Onclick="javascript:window.open('../navermaps/a5.php?Naddr=<?=rawurlencode($house->Address1.$house->Address2)?>','win','top=0, left=500, width=550,height=450')"><?=$house->Address1?></A>
					&nbsp;&nbsp;&nbsp;<span class="btn1"><a href="javascript:void(0)" onclick="javascript:window.open('../navermaps/a5.php?Naddr=<?=rawurlencode($house->Address1.$house->Address2)?>','win','top=0, left=500, width=550,height=450')">지도 보기</a></span>
				</td>
			</tr>
			<tr>
				<td class="td01">홈페이지</td>
				<td colspan="7">
					<?=$house->HomePage?>
				</td>
			</tr>
			<?
			/*
			<tr>
				<td class="td01">담당자</td>
				<td colspan="7">
					<?=$house->showContactInfo()?>
				</td>
			</tr>
			 */
			 ?>
		</table>
<br>
<?php
	if ($house->status == "승인") {
?>
	
			<form action="process.php" method="post" name="frmReserve" id="frmReserve">
			<input type="hidden" name="mode" id="mode" value="reservation" />
			<input type="hidden" name="roomId" id="roomId" value="<?=$roomId?>" />
			<input type="hidden" name="houseId" id="houseId" value="<?=$houseId?>" />
			<h2><img src="../images/board/stit_reserve_03.gif"></h2>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_reserve">
				<col width="20%">
				<col />
				<tr>
					<td class="td01"><p class="reserve"><b>희망 사용 기간</b></td>
					<td>
						<input type="text" name="startDate" id="startDate" value="<?=$fromDate?>" class="input" readonly onclick="calendar('startDate')">
						<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('startDate')"> ~
						<input type="text" name="endDate" id="endDate" class="input" value="<?=$toDate?>" readonly onclick="calendar('endDate')">
						<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('endDate')">
						<label class="fs11" type="text" name='resultMessage1' id='resultMessage1'></label>
					</td>
				</tr>
				<tr>
					<td class="td01"><p class="reserve"><b>희망 입주 시간</b></td>
					<td>
						<select type="text" name="arrival_time" id="arrival_time">
							<? 
							for ($i = 6; $i <= 22; $i++) {
								echo "<option value='{$i}시'>{$i}시</option>";
							} 
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="td01"><p class="reserve"><b>이름</b></td>
					<td>
						<input type="text" name="resv_name" id="resv_name" value="<?=$member->name?>" class="input">
					</td>
				</tr>
				<tr>
					<td class="td01"><p class="reserve"><b>연락처</b></td>
					<td>
						<input type="text" name="resv_phone" id="resv_phone" value="<?=$member->mobile[0]?>-<?=$member->mobile[1]?>-<?=$member->mobile[2]?>" class="input">
					</td>
				</tr>
				<tr>
					<td class="td01"><p class="reserve"><b>입주 인원수</b></td>
					<td>
						<select type="text" name="people_number" id="people_number">
							<? 
							for ($i = 1; $i <= 10; $i++) {
								echo "<option value='{$i}명'>{$i}명</option>";
							} 
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="td01"><p class="reserve"><b>방문 목적</b></td>
					<td>
						
						<input type="checkbox" name="purpose[]" id="purpose[]" value="병원" class="input"> 병원
						<input type="checkbox" name="purpose[]" id="purpose[]" value="단체행사" class="input"> 단체행사 
						<input type="checkbox" name="purpose[]" id="purpose[]" value="안식년" class="input"> 안식년 
						<input type="checkbox" name="purpose[]" id="purpose[]" value="자녀교육" class="input"> 자녀교육 
						<input type="checkbox" name="purpose[]" id="purpose[]" value="기타" class="input"> 기타 
					</td>
				</tr>
				<tr>
					<td class="td01"><p class="reserve"><b>메모</b></td>
					<td>
						<textarea type="text" name="memo" id="memo"></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<img src="../images/board/btn_reserve.gif" border="0" align="right" align="absmiddle" class="m5" onclick="reserveSubmit()">
					</td>
				</tr>
			</table>
		</form>

		<?php } ?>     
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
		
		location.href = "reservationDetail.php?houseId=<?=$houseId?>&roomId=" + room;
	}
	
	function goRoomList() {
		history.back(-1);
		//location.href = "reservation.php?houseId=<?=$houseId?>";
	}
	
	function changeImage(imgName) {
		document.getElementById("mainImage").src = imgName;
	}
	
	function callPage(y, m) {
		var url = '/common/ajax/calendar.php?roomId=<?=$roomId?>&year='+y+'&month='+m;
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

