<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
checkUserLogin(7);

$roomId = (isset($_REQUEST["roomId"])) ? trim($_REQUEST["roomId"]) : "";
$houseId = (isset($_REQUEST["houseId"])) ? trim($_REQUEST["houseId"]) : "";

showHouseManagerHeader();
showHouseManagerLeft();
body();
showHouseManagerFooter();

function body() {
	global $mysqli;
	global $houseId, $roomId, $room_color;

	$toDate = isset($_REQUEST["toDate"]) ? trim($_REQUEST["toDate"]) : "";
	$fromDate = isset($_REQUEST["fromDate"]) ? trim($_REQUEST["fromDate"]) : "";

	$h_Helper = new HouseHelper();
	$room = $h_Helper->getRoomInfoById($roomId);
	$house = $h_Helper->getHouseInfoById($houseId);

	$m_Helper = new MemberHelper();
	$member = $m_Helper->getMemberByuserid($_SESSION["userid"]);
	$mission = $m_Helper->getMissionInfoByuserid($_SESSION["userid"]);

	//******************************************************************
	// 달력 세팅
	$calendar['year'] = isset($_REQUEST["year"]) ? trim($_REQUEST["year"]) : date("Y");
	$calendar['month'] = isset($_REQUEST["month"]) ? trim($_REQUEST["month"]) : date("m");
	setTestValue($calendar);

	$fromDate = mktime(0, 0, 0, $calendar['month'], 1, $calendar['year']);
	$toDate = mktime(0, 0, 0, $calendar['month'] + 1, 1, $calendar['year']);

	// year month correcting
	$calendar['year'] = date('Y', $fromDate);
	$calendar['month'] = date('m', $fromDate);

	$s_Helper = new supportHelper();
	$dailySupport = $s_Helper->getDailySupport($fromDate, $toDate);
	$senders = $s_Helper->getSender($fromDate, $toDate);
	//******************************************************************

	//******************************************************************
	// request query pre setting
	$q[0] = "houseId=".$houseId;
	$q[1] = "roomId=".$roomId;
	$q[2] = "year=".($calendar['year'] - 1);
	$q[3] = "year=".$calendar['year'];
	$q[4] = "year=".($calendar['year'] + 1);
	$q[5] = "month=".($calendar['month'] - 1);
	$q[6] = "month=".($calendar['month']);
	$q[7] = "month=".($calendar['month'] + 1);
	//******************************************************************

	$available_date_list = 'a'.str_pad('', 31, '0');
?>
							<!-- rightSec -->
							<div id="rightSec">
								<div class="lnb">
									<strong>Home</strong> <? 
									echo "&gt; {$house->houseName} ";
									if ($room->roomId != -1) {
										echo "&gt; {$room->roomName} ";
									}
									echo "&gt; 예약 현황 보기";
								?></div>
								<div id="content">
									<div class="list_year"> <!-- list_year -->
										<ul class="mr1">
											<li><a href="reserve_2.php?<?="{$q[0]}&{$q[1]}&{$q[2]}&{$q[6]}"?>"><img src="images/btn_yprev.gif" alt="이전년도" /></a></li>
											<li class="txt"><?=$calendar['year']?></li>
											<li><a href="reserve_2.php?<?="{$q[0]}&{$q[1]}&{$q[4]}&{$q[6]}"?>"><img src="images/btn_ynext.gif" alt="다음년도" /></a></li>
										</ul>
										<ul>
											<li><a href="reserve_2.php?<?="{$q[0]}&{$q[1]}&{$q[3]}&{$q[5]}"?>"><img src="images/btn_yprev.gif" alt="이전달" /></a></li>
											<li class="txt"><?=$calendar['month']?></li>
											<li><a href="reserve_2.php?<?="{$q[0]}&{$q[1]}&{$q[3]}&{$q[7]}"?>"><img src="images/btn_ynext.gif" alt="다음달" /></a></li>
										</ul>
										<ul class="tabs mt30">
											<? if ($room->roomId != -1) { ?>
												<li class="on"><a href="reserve_2.php?houseId=<?=$houseId?>&roomId=<?=$roomId?>">예약 현황 보기</a></li>
												<li><a href="mission_write2.php?houseId=<?=$houseId?>&roomId=<?=$roomId?>">정보수정</a></li>
											<? } else { ?>
												<li class="on"><a href="reserve_2.php?houseId=<?=$houseId?>">예약 현황 보기</a></li>
												<li><a href="mission_write.php?houseId=<?=$houseId?>">정보수정</a></li>
												<li><a href="mission_write2.php?houseId=<?=$houseId?>">방 추가하기</a></li>
											<? } ?>
										</ul>
									</div> <!-- // list_year -->
									<!-- cal_month -->
									<div class="cal_month2 mt20"> 
										<table>
<?
	echo "											<colgroup>\r\n";
	echo "												<col width=\"7%\" />\r\n";
	for ($i = $fromDate; $i < $toDate; $i += 86400) {
		echo "												<col width=\"3%\" />\r\n";
	}
	echo "											</colgroup>\r\n";
	echo "											<thead>\r\n";

	//--------------
	// 달력만드는 순서
	// 1. 이번달 1일의 요일을 계산한다.
	// 2. 이번달의 마지막 날짜를 계산한다. (이번달 날짜수 = 다음달 1일 - 이번달 1일)
	// 3. 이번 달 1일의 요일을 계산해서 해당셀에 1부터 차례대로 날짜수 만큼 뿌린다.
	//---------------
	//1일부터 마지막날까지 요일정보 
	//------------------------------
	echo "												<tr>\r\n";
	echo "													<th scope=\"col\" rowspan=\"2\" class=\"fs2\">방이름</th>\r\n";
	for ($i = $fromDate; $i < $toDate; $i += 86400) {
		switch (date('w', $i)) {
			case 1:
				print "													<th scope=\"col\">월</th>\r\n";
				break;
			case 2:
				print "													<th scope=\"col\">화</th>\r\n";
				break;
			case 3:
				print "													<th scope=\"col\">수</th>\r\n";
				break;
			case 4:
				print "													<th scope=\"col\">목</th>\r\n";
				break;
			case 5:
				print "													<th scope=\"col\">금</th>\r\n";
				break;
			case 6:
				print "													<th scope=\"col\"><p class=\"blue\">토</p></th>\r\n";
				break;
			default:
				print "													<th scope=\"col\"><p class=\"red\">일</p></th>\r\n";
				break;
		}
	}
	echo "												</tr>\r\n";

	//1일부터 마지막날까지 달력생성
	//------------------------------
	print "												<tr>\r\n";

	for ($i = $fromDate; $i < $toDate; $i += 86400) {
		switch (date('w', $i)) {
			case 6:
				print "													<th scope=\"col\"><p class=\"blue\">".date('j', $i)."</p></th>\r\n";
				break;
			case 0:
				print "													<th scope=\"col\"><p class=\"red\">".date('j', $i)."</p></th>\r\n";
				break;
			default:
				print "													<th scope=\"col\">".date('j', $i)."</th>\r\n";
				break;
		} 
	}
	print "												</tr>\r\n";
	print "											</thead>\r\n";


	//방별 예약 정보
	//------------------------------
	print "											<tbody>\r\n";
	foreach ($house->RoomList as $aRoom) {
		//------------------------
		// 룸 예약 정보 받아오기 
		//------------------------
		$query = "SELECT * FROM room B, reservation C WHERE B.roomId = '".$aRoom->RoomID."' AND B.roomId = C.roomId AND C.reservStatus <> 'S0004' ";
		$query = $query." AND ((endDate >= {$fromDate} AND startDate < {$toDate}) ";
		$query = $query." OR (startDate < {$fromDate} AND endDate >= {$toDate})) ";
		$query = $query." ORDER BY startDate";

		print "												<tr>\r\n";
		if ($aRoom->roomId == $roomId) {
			print "												<th bgcolor=\"#FFFFAA\"><strong><a href=\"reserve_2.php?houseId={$houseId}&roomId={$aRoom->roomId}\">{$aRoom->roomName}</a></strong></th>\r\n";
		} else {
			print "												<th><a href=\"reserve_2.php?houseId={$houseId}&roomId={$aRoom->roomId}\">{$aRoom->roomName}</th>\r\n";
		}
		print "												<td colspan=\"31\">\r\n";
		if ($result = $mysqli->query($query)) {
			$prev_margin = 0;
			while ($row = $result->fetch_assoc()) {
				setTestValue($row);
				if (date('m', $row["startDate"]) == $calendar['month']) {
					$start = date('d', $row["startDate"]);
				} else {
					$start = 1;
				}

				if (date('m', $row["endDate"]) == $calendar['month']) {
					$end = date('d', $row["endDate"]);
				} else {
					$end = date('d', $toDate - 86400);
				}

				if ($aRoom->roomId == $roomId) {
					$available_date_list = substr_replace($available_date_list, str_pad('', $end - $start + 1, '1'), $start, $end - $start + 1);
				}

				$margin_left = ($start - 1) / date('d', $toDate - 86400) * 100 - $prev_margin;
				$width = ($end - $start + 1) / date('d', $toDate - 86400) * 100;
				$prev_margin += $margin_left + $width;
				if ($row["resv_name"]) {
					print "<div class=\"check cb".$room_color[$aRoom->RoomID]."\" id=\"_pf1_".$row["reservationNo"]."\" style=\"width:{$width}%; margin-left:{$margin_left}%\" onmouseover=\"showProfile('pf1_', '".$row["reservationNo"]."', event)\" onmouseout=\"unshowProfile('pf1_', '".$row["reservationNo"]."')\" >".$row["resv_name"]."</div>\r\n";
				} else {
					print "<div class=\"check cb".$room_color[$aRoom->RoomID]."\" id=\"_pf1_".$row["reservationNo"]."\" style=\"width:{$width}%; margin-left:{$margin_left}%\" onmouseover=\"showProfile('pf1_', '".$row["reservationNo"]."', event)\" onmouseout=\"unshowProfile('pf1_', '".$row["reservationNo"]."')\" >".$row["userid"]."</div>\r\n";
				}

				print "<div class=\"view\" id=\"pf1_".$row["reservationNo"]."\" style=\"position:absolute; visibility:hidden; top:38px;\"></div>\r\n";
			}

			$result->close();
		}
		print "												</td>\r\n";
		print "												</tr>\r\n";
	}
	print "											</tbody>\r\n";
?>
										</table>
									</div> <!-- // cal_month -->

<?php 
	$search = isset($_REQUEST["search"]) ? trim($_REQUEST["search"]) : "";
	$page = isset($_REQUEST["page"]) ? trim($_REQUEST["page"]) : 1;

	$h_Helper->PAGE_UNIT = 10; //하단 페이징 단위
	$h_Helper->PAGE_COUNT = 30; //한페이지에 보여줄 리스트 갯수
	$h_Helper->setReservationListConditionWithRoom($search, $houseId, $roomId, $fromDate, $toDate);
	$strPage = $h_Helper->makeReservationListPagingHTML($page);
	$reservList = $h_Helper->getReservationListWithPaging($page);
?>

		<div class="search">
			<span class="mr20"><strong>SEARCH ></strong></span>
			<select name="room" id="room" onchange="searchRoom(this.value)">
				<option value="0">전체</option>
<? foreach ($house->RoomList as $aRoom) { ?>
				<option value="<?=$aRoom->roomid?>" <?php if ($aRoom->roomId == $roomId) { ?> selected <? } ?>><?=$aRoom->roomName?></option>
<? } ?>
			</select>
			<select name="status" id="status" onchange="search(this.value)">
				<option value="">전체</option>
				<option value="1" <? if ($search == "1") { ?> selected <? } ?>>신규예약</option>
				<option value="2" <? if ($search == "2") { ?> selected <? } ?>>승인</option>
				<option value="3" <? if ($search == "3") { ?> selected <? } ?>>완료</option>
				<option value="4" <? if ($search == "4") { ?> selected <? } ?>>거절</option>
			</select>
			<!--span class="btn1"><a href="javascript:void(0)" onclick="search(this.value)">검색</a></span-->
		</div>
				
		<!-- //list -->
		<div class="list mt10">
			<table>
				<colgroup>
					<col width="10%" />
					<col width="10%" />
					<col width="20%" />
					<col width="35%" />
					<col width="25%" />
				</colgroup>
				<thead>
					<tr>
						<th>예약번호</th>
						<th>이름</th>
						<th>선교관 / 방이름</th>
						<th>기간 / 일정</th>
						<th>상태</th>
					</tr>
				</thead>
				<tbody>
<?php 
	if (count($reservList) == 0) {
		echo "			<tr>\r\n";
		echo "				<td colspan=\"5\">리스트가 없습니다</td>\r\n";
		echo "			</tr>\r\n";
	} else {
		foreach ($reservList as $aResv) {
?>
					<tr>
						<td><?=$aResv->BookNo?></td>
						<td>
							<label id="_pf2_<?=$aResv->BookNo?>" onmouseover="showProfile('pf2_', '<?=$aResv->BookNo?>', event)" onmouseout="unshowProfile('pf2_', '<?=$aResv->BookNo?>')" style="cursor:prointer"><? 
							if ($aResv->resv_name) { 
								echo $aResv->resv_name; 
							} else { 
								echo $member->Nick;
							} 
							?></label>
							<div class="view" id="pf2_<?=$aResv->BookNo?>" style="position:absolute;visibility:hidden; top:38px; "></div>
						</td>
						<td><?=$aResv->HouseName?> / <?=$aResv->RoomName?></td>
						<td><? 
							if ($aResv->Status != "거절") {?>
							<form method="post" name="frmEditDate<?=$aResv->BookNo?>" id="frmEditDate<?=$aResv->BookNo?>">
							<?=$aResv->duration?>일 / 
							<input type="hidden" name="mode" id="mode" value="edit_date" />
							<input type="hidden" name="roomId" id="roomId" value="<?=$roomId?>" />
							<input type="hidden" name="houseId" id="roomId" value="<?=$houseId?>" />
							<input type="hidden" name="bookNo" id="bookNo" value="<?=$aResv->BookNo?>" />
							<input type="text" size="12" name="startDate<?=$aResv->BookNo?>" id="startDate<?=$aResv->BookNo?>" value="<?=date("Y-m-d", $aResv->StartDate)?>" class="input" readonly onclick="calendar('startDate<?=$aResv->BookNo?>')">
							<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('startDate<?=$aResv->BookNo?>')"> ~
							<input type="text" size="12" name="endDate<?=$aResv->BookNo?>" id="endDate<?=$aResv->BookNo?>" class="input" value="<?=date("Y-m-d", $aResv->EndDate)?>" readonly onclick="calendar('endDate<?=$aResv->BookNo?>')">
							<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('endDate<?=$aResv->BookNo?>')">
							</form>
							<? } else { ?>
							<?=$aResv->duration?>일  / <?=date("Y.m.d", $aResv->StartDate)?> ~ <?=date("Y.m.d", $aResv->EndDate)?>
							<? } ?>
						</td>
						<td><?
							// echo "<span class=\"btn\">{$aResv->Status}</span>";
							if ($aResv->Status == "신규예약") {
							 	echo "<span class=\"btn1g\"><a href=\"javascript:void(0)\" onclick=\"edit_date({$aResv->BookNo})\">날짜 변경</a></span>\r\n";
							 	echo "<span class=\"btn1g\"><a href=\"javascript:void(0)\" onclick=\"allow({$aResv->BookNo})\">승인</a></span>\r\n";
							 	echo "<span class=\"btn1g\"><a href=\"javascript:void(0)\" onclick=\"deny({$aResv->BookNo})\">거절</a></span>\r\n";
							} else if ($aResv->Status == "승인") {
							 	echo "<span class=\"btn1g\"><a href=\"javascript:void(0)\" onclick=\"edit_date({$aResv->BookNo})\">날짜 변경</a></span>\r\n";
							 	echo "<span class=\"btn1g\"><a href=\"javascript:void(0)\" onclick=\"complete({$aResv->BookNo})\">완료</a></span>\r\n";
							 	echo "<span class=\"btn1g\"><a href=\"javascript:void(0)\" onclick=\"deny({$aResv->BookNo})\">거절</a></span>\r\n";
							} else {
							 	echo "<span class=\"btn1g\"><a href=\"javascript:void(0)\" onclick=\"deny({$aResv->BookNo})\">삭제</a></span>\r\n";
							}
						?></td>
					</tr>
<?
		}
	}
?>
				</tbody>
			</table>
		</div>
		<!-- list// -->
		<!--div class="paging">
			<a href="#"><img src="images/btn_page_first.gif" alt="처음" /></a>
			<a href="#"><img src="images/btn_page_prev.gif" alt="이전" /></a>
			<a href="#"><strong>1</strong></a>
			<a href="#">2</a>
			<a href="#">3</a>
			<a href="#">4</a>
			<a href="#">5</a>
			<a href="#">6</a>
			<a href="#">7</a>
			<a href="#">8</a>
			<a href="#">9</a>
			<a href="#">10</a>
			<a href="#"><img src="images/btn_page_next.gif" alt="다음" /></a>
			<a href="#"><img src="images/btn_page_last.gif" alt="마지막" /></a>
		</div-->
		<!-- // content -->


		<div>
		<form method="post" name="frmReserve" id="frmReserve">
			<input type="hidden" name="mode" id="mode" value="reservation" />
			<input type="hidden" name="roomId" id="roomId" value="<?=$roomId?>" />
			<input type="hidden" name="houseId" id="roomId" value="<?=$houseId?>" />
			<br />
			<h2><img src="../images/board/stit_reserve_03.gif"></h2>
			<table class="write mt30" width="100%" border="0" cellpadding="0" cellspacing="0" class="board_reserve">
				<col width="15%">
				<col />
<?php
	if ($roomId != "" && $house->status == "승인") {
?>
				<tr>
					<th><p class="reserve"><b>날짜입력</b></th>
					<td>
						<input type="text" name="startDate" id="startDate" value="" class="input" readonly onclick="calendar('startDate')">
						<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('startDate')"> ~
						<input type="text" name="endDate" id="endDate" class="input" value="" readonly onclick="calendar('endDate')">
						<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('endDate')">
						<label class="fs11" type="text" name='resultMessage1' id='resultMessage1'></label>
					</td>
				</tr>
				<tr>
					<th><p class="reserve"><b>희망 입주 시간</b></th>
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
					<th><p class="reserve"><b>회원아이디</b></th>
					<td>
						<input type="text" name="userid" id="userid" value="" class="input" readonly onclick="searchUser();" />
					</td>
				</tr>
				<tr>
					<th><p class="reserve"><b>이름</b></th>
					<td>
						<input type="text" name="resv_name" id="resv_name" value="" class="input">
					</td>
				</tr>
				<tr>
					<th><p class="reserve"><b>연락처</b></th>
					<td>
						<input type="text" name="resv_phone" id="resv_phone" value="" class="input">
					</td>
				</tr>
				<tr>
					<th><p class="reserve"><b>입주 인원수</b></th>
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
					<th><p class="reserve"><b>방문 목적</b></th>
					<td>
						
						<input type="checkbox" name="purpose[]" id="purpose[]" value="병원" class="input"> 병원
						<input type="checkbox" name="purpose[]" id="purpose[]" value="단체행사" class="input"> 단체행사 
						<input type="checkbox" name="purpose[]" id="purpose[]" value="안식년" class="input"> 안식년 
						<input type="checkbox" name="purpose[]" id="purpose[]" value="자녀교육" class="input"> 자녀교육 
						<input type="checkbox" name="purpose[]" id="purpose[]" value="기타" class="input"> 기타 
					</td>
				</tr>
				<tr>
					<th><p class="reserve"><b>메모</b></th>
					<td>
						<textarea type="text" name="memo" id="memo"></textarea>
					</td>
				</tr>
				<tr>
					<td> </td>
					<td align="right" width="100%">
						<img src="../images/board/btn_reserve.gif" border="0" align="absmiddle" class="m5" onclick="reserveSubmit('<?=$available_date_list?>')">
					</td>
				</tr>
<? } else { ?>
				<tr>
					<td>
							예약을 위해 방을 선택해 주세요<br /><br /><br /><br /><br /><br /><br /><br /><br />
					</td>
				</tr>
<? } ?>
			</table>
		</form>
		</div>

	</div>
</div>
<!-- // rightSec -->
<!-- // rightSec -->
<?php } ?>

<script type="text/javascript">
//<![CDATA[
	calendar_init();

	function reserveSubmit(available_date_list) {
		var endDate = document.getElementById("endDate").value;
		var startDate = document.getElementById("startDate").value;
		var userid = document.getElementById("userid").value;

		// var start = parseInt(startDate.substring(8, 10));
		// var end = parseInt(endDate.substring(8, 10));
		// var available = parseInt(available_date_list.substring(start, end + 1));

		// if (available > 0) {
			// alert('예약이 되어있는 기간입니다.');
			// return;
		// }
		
		if (userid.length == 0) {
			alert('회원을 선택해 주세요');
			return;
		}

		if (startDate.length == 0 || endDate.length == 0) {
			alert('숙박 기간을 정확히 입력해 주세요');
			return;
		}

		if (startDate.replace(/-/g,'') >= endDate.replace(/-/g,'')) {
			alert('기간이 잘못되었습니다.');
			return;
		}
		document.getElementById("frmReserve").action = "process.php";
		document.getElementById("frmReserve").submit();
	}

	function allow(value) {
		if (confirm('예약을 승인합니다.'))
			location.href = 'process.php?mode=changeReservStatus&houseId=<?=$houseId?>&roomId=<?=$roomId?>&status=2&bookNo=' + value;
	}

	function deny(value) {
		if (confirm('예약을 거절합니다.'))
			location.href = 'process.php?mode=changeReservStatus&houseId=<?=$houseId?>&roomId=<?=$roomId?>&status=4&bookNo=' + value;
	}

	function complete(value) {
		if (confirm('예약을 완료합니다.'))
			location.href = 'process.php?mode=changeReservStatus&houseId=<?=$houseId?>&roomId=<?=$roomId?>&status=3&bookNo=' + value;
	}

	function edit_date(value) {
		document.getElementById('frmEditDate' + value).action = "process.php";
		document.getElementById('frmEditDate' + value).submit();
	}
	
	function searchRoom(room) {
		if (room != 0) {
			location.href = 'reserve_2.php?houseId=<?=$houseId?>&roomId=' + room;
		} else {
			location.href = 'reserve_2.php?houseId=<?=$houseId?>';
		}
	}
	
	function search(value) {
		location.href = 'reserve_2.php?houseId=<?=$houseId?>&roomId=<?=$roomId?>&search=' + value;
	}

	var element_name;
	function showProfile(element, num, e) {
		element_name = element + num;
		var _oProfile = document.getElementById("_" + element_name);
		var oProfile = document.getElementById(element_name);
		//var oId = document.getElementById('profileId' + num);
		if (oProfile.style.visibility == "hidden") {
			var url = 'ajax.php?mode=getUserProfile&reservationNo='+num;

			var myAjax = new Ajax.Request(url, {method: 'post', parameters: '', onComplete: resultProfile});
			oProfile.style.left = _oProfile.offsetLeft + (_oProfile.offsetWidth / 2) - (oProfile.offsetWidth / 2) + "px";
			if (element == "pf1_") {
				oProfile.style.top = _oProfile.parentNode.offsetTop + _oProfile.parentNode.offsetHeight + "px";
			} else {
				oProfile.style.top = _oProfile.offsetTop + _oProfile.offsetHeight + 5 + "px";
			}
			oProfile.style.visibility = "visible";
		}
	}
	
	function resultProfile(reqResult) {
		var addHtml = reqResult.responseText;
		var oProfile = document.getElementById(element_name);
		oProfile.innerHTML = addHtml;
	}
	
	function unshowProfile(element, num) {
		oProfile = document.getElementById(element + num);
		oProfile.style.visibility = "hidden";
	}
//]]>
</script>