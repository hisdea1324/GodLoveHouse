<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/class/CalendarBuilder.php");
//***************************************************************
// member edit page//
// last update date : 2009.12.28
// updated by blackdew
// To do List
//	 - 비밀번호 변경하는 페이지는 따로 추가해야 함
//	 - 자바 스크립트 추가 & update process 진행
//***************************************************************

checkUserLogin();

$toDate = isset($_REQUEST["toDate"]) ? trim($_REQUEST["toDate"]) : "";
$fromDate = isset($_REQUEST["fromDate"]) ? trim($_REQUEST["fromDate"]) : "";
$houseId = isset($_REQUEST["houseId"]) ? trim($_REQUEST["houseId"]) : "";
$roomId = isset($_REQUEST["roomId"]) ? trim($_REQUEST["roomId"]) : "";
$search = isset($_REQUEST["search"]) ? trim($_REQUEST["search"]) : "";
$page = isset($_REQUEST["page"]) ? trim($_REQUEST["page"]) : 1;

$m_Helper = new MemberHelper();
$member = $m_Helper->getMemberByUserId($_SESSION["userid"]);
$account = $m_Helper->getAccountInfoByUserId($_SESSION["userid"]);
$mission = $m_Helper->getMissionInfoByUserId($_SESSION["userid"]);

$c_Helper = new CodeHelper();
$codes = $c_Helper->getNationCodeList();

$h_Helper = new HouseHelper();
$houseList1 = $h_Helper->getHouseListByUserId($_SESSION["userid"], 1);

if ($_SESSION["userLv"] >= 7) {
	showHeader("HOME > 멤버쉽 > 개인정보","mypage_manager","tit_0801.gif");
} else if ($_SESSION["userLv"] >= 3) {
	showHeader("HOME > 멤버쉽 > 개인정보","mypage_missionary","tit_0801.gif");
} else {
	showHeader("HOME > 멤버쉽 > 개인정보","mypage_normal","tit_0801.gif");
} 

//******************************************************************
// 달력 세팅
$StartYear = 2012;

$calendar = new CalendarBuilder();
$calendar->CYear = isset($_REQUEST["y"]) ? trim($_REQUEST["y"]) : "";
$calendar->CMonth = isset($_REQUEST["m"]) ? trim($_REQUEST["m"]) : "";

$fromDate = $calendar->CurrentMonth.$calendar->DataFormat(1);
$toDate = $calendar->NextMonth.$calendar->DataFormat(1);
$s_Helper = new supportHelper();
$dailySupport = $s_Helper->getDailySupport($fromDate, $toDate);
$senders = $s_Helper->getSender($fromDate, $toDate);

//******************************************************************
body();
showFooter();

function body() {
	global $mysqli, $h_Helper, $member;
	global $search, $page, $houseId, $roomId, $houseList1;
	global $StartYear, $calendar, $dailySupport;
?>
		<!-- //content -->
		<!-- //정보 -->
		<div id="content">

				<H2><img src="../images/board/stit_house.gif"></H2>
				<!-- //tab -->
				<div class="Tab">
<?php 
	if (count($houseList1) > 0) { 
		$roomList = array();
		foreach ($houseList1 as $houseObj) {
			if (count($houseObj->RoomList) > 0) {
				if (strlen($houseId) == 0 || trim($houseId) == trim($houseObj->HouseID)) {
					$houseId = $houseObj->HouseID;
					$roomList = $houseObj->RoomList;
					$house = $houseObj;
					print "<a href=\"#\" class=\"on\" title=\"".$houseObj->HouseName."\"><span>".StrFormatByLength($houseObj->HouseName, 9)."</span></a>";
				} else {
					print "<a href=\"mypage_houseReserv.php?houseId=".$houseObj->HouseID."&y=".$calendar->CYear."&m=".$calendar->CMonth."\" class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" title=\"".$houseObj->HouseName."\"><span>".StrFormatByLength($houseObj->HouseName, 9)."</span></a>";
				} 

			} 
		}
?>
				</div>
				<div class="sTab">&nbsp;
					<a href="#" class="on"><span>::</span></a>
<?php 
		if (count($roomList) > 0 && strlen($roomId) == 0) {
			$roomId = $roomList[0]->RoomID;
		}
		foreach ($roomList as $roomObj) {
			print "<a href=\"mypage_houseReserv_a.php?houseId=".$houseId."&roomId=".$roomObj->RoomID."\" class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" title=\"".$houseObj->HouseName." - ".$roomObj->RoomName."\"><span>".StrFormatByLength($roomObj->RoomName, 8)."</span></a>";
		}
	}
?>
				</div>
				<!-- tab// -->

			<!-- //search -->
			<div id="search">
				<form name="schForm" action="mypage_houseReserv.php?houseId=<?php echo $houseId;?>">
	<input type="hidden" name="houseId" value="<?php echo $houseId;?>" />
				<span class="fc_01"><strong>년도</strong></span>
				<select name="y">
					<option value="">-- 년도를 선택하세요 --</option>
					<? for ($i = $StartYear-3; $i<=strftime("%Y",time())+3; $i = $i+1) { ?>
					<option value="<?php echo $i;?>"<? if ((intval($calendar->CYear)==intval($i))) { ?> selected <?php } ?>><?php echo $i;?></option>
					<?php } ?>
				</select>
				
				<span class="fc_01"><strong>월</strong></span>
				<select name="m">
					<option value="">-- 월을 선택하세요 --</option>
					<?	 for ($i=1; $i<=12; $i = $i+1) { ?>
					<option value="<?php echo $i;?>"<? if ((intval($calendar->CMonth)==intval($i))) { ?> selected <?php } ?>><?php echo $i;?>월</option>
					<?php } ?>
				</select>				
				<input type="submit" value="검색" style="cursor:pointer" />
				</form>
			</div>
			<!-- search// -->
			<!-- //list -->			
			<div class="bg_list">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_list">
					<tr>
						<th colspan="7"><?php echo $calendar->CYear;?>년 <?php echo $calendar->CMonth;?>월 </th>
					</tr>						
					<!--tr>
						<td class="fc_01 b bg_gray" width="14%">일</td>
						<td class="fc_01 b bg_gray" width="14%">월</td>
						<td class="fc_01 b bg_gray" width="14%">화</td>
						<td class="fc_01 b bg_gray" width="14%">수</td>
						<td class="fc_01 b bg_gray" width="14%">목</td>
						<td class="fc_01 b bg_gray" width="14%">금</td>
						<td class="fc_01 b bg_gray" width="14%">토</td>
					</tr-->
					<tr>
<?php 

	//--------------
	// 달력만드는 순서
	// 1. 이번달 1일의 요일을 계산한다.
	// 2. 이번달의 마지막 날짜를 계산한다. (이번달 날짜수 = 다음달 1일 - 이번달 1일)
	// 3. 이번 달 1일의 요일을 계산해서 해당셀에 1부터 차례대로 날짜수 만큼 뿌린다.
	//---------------
	//1일부터 마지막날까지 요일정보 
	//------------------------------
	print " <tr>".chr(13);
	print "<td>room name</td>";
	for ($i = 1; $i <= $calendar->LastDate; $i++) {
		print "<td>".$calendar->getWeekName($i)." <br /></td>";
	}

	print " </tr>".chr(13);

	//1일부터 마지막날까지 달력생성
	//------------------------------
	print " <tr>".chr(13);
	print "<td> </td>";
	for ($i = 1; $i <= $calendar->LastDate; $i++) {
		$sumPrice = "<br />";
		$timestamp = $calendar->firstday + (($i - 1) * 24 * 60 * 60);

		if (isset($dailySupport[$timestamp])) {
			$sumPrice = priceFormat($dailySupport[$timestamp], 2)."<br />";
		} 

		//토요일이면 줄을 바꾼다.	
		if ($calendar->IsWeekStart($i)) {
			print "<td><font color='red'>".$i." </font><br /></td>";
		} elseif ($calendar->IsWeekEnd($i)) {
			print "<td><font color='blue'>".$i." </font><br /></td>";
		} else {
			print "<td>".$i." <br /></td>";
		} 
	}

	print " </tr>".chr(13);

	//방별 예약 정보
	//------------------------------
	foreach ($roomList as $roomObj) {
		for ($j = 1; $j <= $calendar->LastDate; $j++) {
			$dateSet[$j] = "";
		}

		//------------------------
		// 룸 예약 정보 받아오기 
		//------------------------
		$query = "SELECT * FROM room B, reservation C WHERE B.roomId = '".$roomObj->RoomID."' AND B.roomId = C.roomId ";
		$query = $query." AND ((endDate >= '".$calendar->firstday."' AND startDate < '".$calendar->lastday."') ";
		$query = $query." OR (startDate < '".$calendar->firstday."' AND endDate >= '".$calendar->lastday."')) ";
		$query = $query." ORDER BY startDate";

		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				if (date('m', $row["startDate"]) == date('m', $calendar->firstday)) {
					$fromDate = date('d', $row["startDate"]);
				} else {
					$fromDate = 1;
				}

				if (date('m', $row["endDate"]) == date('m', $calendar->firstday)) {
					$toDate = date('d', $row["endDate"]);
				} else {
					$toDate = date('d', $calendar->lastday);
				} 

				for ($j = $fromDate; $j <= $toDate; $j++) {
					$dateSet[$j] = $row["reservStatus"];
				}
			}
			$result->close();
		}

		print " <tr>".chr(13);
		print "<td>".$roomObj->RoomName."</td>";
		for ($j = 1; $j <= $calendar->LastDate; $j++) {
			if ($dateSet[$j] == "S0001") {
				print "<td style=\"background-color:yellow\">W<br /></td>";
			} else if ($dateSet[$j] == "S0002") {
				print "<td style=\"background-color:green\">B<br /></td>";
			} else if ($dateSet[$j] == "S0003") {
				print "<td style=\"background-color:darkgray\">C<br /></td>";
			} else if ($dateSet[$j] == "S0004") {
				print "<td style=\"background-color:red\">R<br /></td>";
			} else {
				print "<td style=\"background-color:white\"> <br /></td>";
			} 
		}

		print " </tr>".chr(13);
	}

	//테이블 닫기
	print " <tr>".chr(13);
?>
					</tr>
				</table>
		(W:예약, B:승인, C:완료, R:거절)
			</div>					
			<!-- list// -->							

			<h2 style="margin-top:30px"><img src="../images/board/stit_ok.gif"></h2>
				<!-- //search -->
<?php 
	$h_Helper->PAGE_UNIT = 10; //하단 페이징 단위
	$h_Helper->PAGE_COUNT = 30; //한페이지에 보여줄 리스트 갯수
	$h_Helper->setReservationListConditionWithHouse($search, $houseId);
	$strPage = $h_Helper->makeReservationListPagingHTML($page);
	$reservList = $h_Helper->getReservationListWithPaging($page);
?>
		<div id="search">
			<img src="../images/board/img_search.gif" class="r5" align="absmiddle" /><span class="fc_01"><strong>예약 처리 상황</strong></span>
			<select name="status" id="status" onchange="search(this.value)">
				<option value="0">전체</option>
				<option value="1" <?php if (($search=="1")) { ?> selected <?php } ?>>신규예약</option>
				<option value="2" <?php if (($search=="2")) { ?> selected <?php } ?>>승인</option>
				<option value="3" <?php if (($search=="3")) { ?> selected <?php } ?>>완료</option>
				<option value="4" <?php if (($search=="4")) { ?> selected <?php } ?>>거절</option>
			</select>
		</div>
		<!-- search// -->
				
		<!-- //list -->
		<div class="bg_list">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_list">
			<tr>
				<th>예약번호</th>
				<th class="th01">별명</th>
				<th class="th01">선교관 / 선교관 방이름</th>
				<th class="th01">일정</th>
				<th class="th01" width="20%">&nbsp;</th>
			</tr>
<?php 
	if (count($reservList) == 0) {
?>
			<tr>
				<td colspan="4">리스트가 없습니다</td>
			</tr>
<?php 
	} else {
		for ($i=0; $i<=count($reservList)-1; $i = $i+1) {
			$reservObj = $reservList[$i];
?>
			<tr>
				<td><?php echo $reservObj->BookNo;?></td>
				<td class="ltd">
					<label id="profileId<?=$i?>" onmouseover="showProfile('<?=$i?>', event)" onmouseout="unshowProfile('<?=$i?>')" style="cursor:prointer"><?php echo $member->Nick;?><?php //=member.Name ?><?php //=reservObj.UserID ?></label>
					<div id="profile<?=$i?>" style="position:absolute;visibility:hidden;border:1px solid black;color:#FFF;"></div>
				</td>
				<td><?php echo $reservObj->HouseName;?> / <?php echo $reservObj->RoomName;?></td>
				<td><?php echo $reservObj->StartDate;?> ~ <?php echo $reservObj->EndDate;?> <!--a href="#"><img src="../images/board/btn_modify_date.gif" align="absmiddle"></a--></td>
				<td>
<?php 
			if (($reservObj->Status=="신규예약")) {
?>
					<img src="../images/board/btn_accept.gif" class="r5" onclick="allow(<?php echo $reservObj->BookNo;?>)" />
					<img src="../images/board/btn_reject.gif" class="r5" onclick="deny(<?php echo $reservObj->BookNo;?>)" />
<?php 
			}
				else
			if (($reservObj->Status=="승인")) {
?>
					<input type="button" value="완료하기" onclick="complete(<?php echo $reservObj->BookNo;?>)" style="cursor:pointer;" />
					<a href="#" onclick="deny(<?php echo $reservObj->BookNo;?>)">삭제하기</a>
<?php 
			}
				else
			if (($reservObj->Status=="거절")) {
?>
				 삭제 
<?php 
			} else {
?>
					완료됨 / <a href="#" onclick="deny(<?php echo $reservObj->BookNo;?>)">삭제하기</a>
<?php 
			} 

?>
				</td>
			</tr>
<?php 

		}

	} 

?>
			</table>
		</div>
		<!-- list// -->

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
	
	function callPage(y, m) {
		var url = '/common/ajax/calendar.php?roomId=<?php echo $roomId;?>&year='+y+'&month='+m;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: '', onComplete: insertCalendar});
	}

	function insertCalendar(reqResult) {
		var addHtml = reqResult.responseText;
		document.getElementById("reservationCal").innerHTML = addHtml;
	}
	
	function reserveSubmit() {
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
	}
		
	function allow(value) {
		if (confirm('예약을 승인합니다.'))
			location.href = 'process.php?mode=changeReservStatus&houseId=<?php echo $houseId;?>&roomId=<?php echo $roomId;?>&status=2&bookNo=' + value;
	}

	function deny(value) {
		if (confirm('예약을 거절합니다.'))
			location.href = 'process.php?mode=changeReservStatus&houseId=<?php echo $houseId;?>&roomId=<?php echo $roomId;?>&status=4&bookNo=' + value;
	}

	function complete(value) {
		if (confirm('예약을 완료합니다.'))
			location.href = 'process.php?mode=changeReservStatus&houseId=<?php echo $houseId;?>&roomId=<?php echo $roomId;?>&status=3&bookNo=' + value;
	}
	
	function search(value) {
		location.href = 'mypage_houseReserv.php?houseId=<?php echo $houseId;?>&roomId=<?php echo $roomId;?>&search=' + value;
	}

	var obj_num;
	function showProfile(num, e) {
		obj_num = num;
		var oProfile = document.getElementById('profile' + num);
		var oId = document.getElementById('profileId' + num);
		if (oProfile.style.visibility == "hidden") {
			var url = 'ajax.php?mode=getUserProfile&userid='+oId.innerText;

			var myAjax = new Ajax.Request(url, {method: 'post', parameters: '', onComplete: resultProfile});
			oProfile.style.left = e.clientX;
			oProfile.style.top = e.clientY;
			oProfile.style.visibility = "visible";
		}
	}
	
	function resultProfile(reqResult) {
		var addHtml = reqResult.responseText;
		var oProfile = document.getElementById('profile' + obj_num);
		oProfile.innerHTML = addHtml;
	}
	
	function unshowProfile(num) {
		oProfile = document.getElementById('profile' + num);
		oProfile.style.visibility = "hidden";
	}
//]]>
</script>

