<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
//***************************************************************// member edit page//// last update date : 2009.12.28// updated by blackdew// To do List//	 - 비밀번호 변경하는 페이지는 따로 추가해야 함//	 - 자바 스크립트 추가 & update process 진행//***************************************************************
checkUserLogin();
$toDate = trim($_REQUEST["toDate"]);
$fromDate = trim($_REQUEST["fromDate"]);
$houseId = trim($_REQUEST["houseId"]);
$roomId = trim($_REQUEST["roomId"]);
$search = trim($_REQUEST["search"]);
$page = trim($_REQUEST["page"]);
if ((strlen($page)==0)) {
	$page=1;
} 

$sessions = new __construct();
$m_Helper = new MemberHelper();
$member = $m_Helper->getMemberByUserId($sessions->UserID);
$account = $m_Helper->getAccountInfoByUserId($sessions->UserID);
$mission = $m_Helper->getMissionInfoByUserId($sessions->UserID);

$c_Helper = new CodeHelper();
$codes = $c_Helper->getNationCodeList();

$h_Helper = new HouseHelper();
$houseList1 = $h_Helper->getHouseListByUserId($sessions->UserID);
$houseList2 = $h_Helper->getHouseListByUserId($sessions->UserID);

if (($sessions->authority(7))) {
showHeader("HOME > 멤버쉽 > 개인정보","mypage_manager","tit_0801.gif");
} else if (($sessions->authority(3))) {
showHeader("HOME > 멤버쉽 > 개인정보","mypage_missionary","tit_0801.gif");
} else {
showHeader("HOME > 멤버쉽 > 개인정보","mypage_normal","tit_0801.gif");
} 


body();
showFooter();

function body() {
?>
		<!-- //content -->
	<!-- //정보 -->
		<div id="content">
		<!--div class="mypage b20">
			<p class="hi"><strong><?php echo $member->Name;?></strong>님, 안녕하세요</p>
		<ul class="txt01">
			<li><strong>회원ID</strong> <?php echo $member->UserID;?></li>
			<li class="btn">
			<img src="../images/sub/btn_out.gif" onclick="clickTopNavi(10)" class="r5">
			<img src="../images/sub/btn_logout.gif" onclick="clickTopNavi(4)" class="r5">
			</li>
		</ul>
		<ul class="txt02">
			<li class="tit"><?php echo $account->Method;?></li>
			<li>은행 : <?php echo $account->Bank;?></li>
			<li>예금주 : <?php echo $account->Name;?></li>
			<li>계좌번호 : <?php echo $account->Number;?></li>
			<li>이체일 : <?php echo $account->SendDate;?> 일</li>
		</ul> 
		</div-->
		<!-- 정보// -->

		<H2><img src="../images/board/stit_house.gif"></H2>
			
	<?php if ((count($houseList1)>0)) {
?>
		<!-- //tab -->
		<div class="Tab">
		<?php 
		if ((strlen($houseId)==0)) {
			$houseId = $houseList1[0]->$HouseID;
		}
		for ($i=0; $i<=count($houseList1)-1; $i = $i+1) {
			$houseObj = $houseList1[$i];
			if (count($houseObj->RoomList)>0) {
				if (trim($houseId) == trim($houseObj->HouseID)) {
					$roomList = $houseObj->RoomList;
					$house = $houseObj;
					print "<a href=\"mypage_houseReserv.php?houseId=".$houseObj->HouseID."\" class=\"on\" title=\"".$houseObj->HouseName."\"><span>".$StrFormatByLength[$houseObj->HouseName][10]."</span></a>";
				}
					else
				{
					print "<a href=\"mypage_houseReserv.php?houseId=".$houseObj->HouseID."\" class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" title=\"".$houseObj->HouseName."\"><span>".$StrFormatByLength[$houseObj->HouseName][10]."</span></a>";
				} 

			} 


		}

?>
			</div>
		<div class="sTab">
					<a href="#" class="on"><span>::</span></a>
		<?php 
		if ((strlen($roomId)==0)) {
			$roomId = $roomList[1]->$RoomID;
		}
		for ($i=1; $i<=count($roomList); $i = $i+1) {
			$roomObj = $roomList[$i];
			if (trim($roomId) == trim($roomObj->RoomID)) {
				$room = $roomObj;
				print "<a href=\"#\" class=\"on\" title=\"".$houseObj->HouseName." - ".$roomObj->RoomName."\"><span>".$StrFormatByLength[$roomObj->RoomName][8]."</span></a>";
			} else {
				print "<a href=\"mypage_houseReserv_a.php?houseId=".$houseId."&roomId=".$roomObj->RoomID."\" class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" title=\"".$houseObj->HouseName." - ".$roomObj->RoomName."\"><span>".$StrFormatByLength[$roomObj->RoomName][8]."</span></a>";
			} 


		}

?>
		</div>
		<!-- tab// -->
	 <?php } ?>
	 
		<H2><img src="../images/board/stit_reserve_01.gif"></H2>
	<?php if ((count($houseList1)>0)) {
?>
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
		<?php } ?>

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
<?php 
	$h_Helper->PAGE_UNIT=10; //하단 페이징 단위	 $h_Helper->PAGE_COUNT=7; //한페이지에 보여줄 리스트 갯수	 $h_Helper->setReservationListCondition_n$search	$houseId	$roomId;
	$strPage = $h_Helper->makeReservationListPagingHTML($page);
	$reservList = $h_Helper->getReservationListWithPaging($page);
?>
		<h2 style="margin-top:30px"><img src="../images/board/stit_ok.gif"></h2>
		<!-- //search -->
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
	if ((count($reservList)==0)) {
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
					<label id="profileId<?php echo $i;?>" onmouseover="showProfile('<?php echo $i;?>', event)" onmouseout="unshowProfile('<?php echo $i;?>')" style="cursor:prointer"><?php echo $member->Nick;?><?php //=member.Name ?><?php //=reservObj.UserID ?></label>
					<div id="profile<?php echo $i;?>" style="position:absolute;visibility:hidden;border:1px solid black;color:#FFF;"></div>
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
					완료
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

		<!-- //page -->
		<?php echo $strPage;?>
		<!-- page// -->
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
		var url = 'ajax.php?mode=getCalendar&roomId=<?php echo $roomId;?>&year='+y+'&month='+m;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: '', onComplete: insertCalendar]);
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
		location.href = 'mypage_houseReserv_a.php?houseId=<?php echo $houseId;?>&roomId=<?php echo $roomId;?>&search=' + value;
	}

	var obj_num;
	function showProfile(num, e) {
		obj_num = num;
		var oProfile = document.getElementById('profile' + num);
		var oId = document.getElementById('profileId' + num);
		if (oProfile.style.visibility == "hidden") {
			var url = 'ajax.php?mode=getUserProfile&userid='+oId.innerText;

			var myAjax = new Ajax.Request(url, {method: 'post', parameters: '', onComplete: resultProfile]);
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

