<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
//***************************************************************
// member edit page//
// last update date : 2009.12.28
// updated by blackdew// To do List
//	 - 비밀번호 변경하는 페이지는 따로 추가해야 함
//	 - 자바 스크립트 추가 & update process 진행
//***************************************************************

checkUserLogin();
$toDate = isset($_REQUEST["toDate"]) ? trim($_REQUEST["toDate"]) : "";
$fromDate = isset($_REQUEST["fromDate"]) ? trim($_REQUEST["fromDate"]) : "";
$houseId = isset($_REQUEST["houseId"]) ? trim($_REQUEST["houseId"]) : "";
$roomId = isset($_REQUEST["roomId"]) ? trim($_REQUEST["roomId"]) : "";

$m_Helper = new MemberHelper();
$member = $m_Helper->getMemberByuserid($_SESSION["userid"]);
$account = $m_Helper->getAccountInfoByuserid($_SESSION["userid"]);
$mission = $m_Helper->getMissionInfoByuserid($_SESSION["userid"]);

$c_Helper = new CodeHelper();
$codes = $c_Helper->getNationCodeList();

$h_Helper = new HouseHelper();
$houseList1 = $h_Helper->getHouseListByuserid($_SESSION["userid"], 1);
$houseList2 = $h_Helper->getHouseListByuserid($_SESSION["userid"], 2);

foreach ($houseList1 as $house) {
	if (trim($houseId) == trim($house->HouseID)) {
		if ($house->RoomCount <= 0) {
			header("Location: "."mypage_houseInfo_edit.php?houseId=".$houseId);
		}
	}
}

if ($_SESSION["userLv"] >= 7) {
	showHeader("HOME > 멤버쉽 > 개인정보","mypage_manager","tit_0801.gif");
} else if ($_SESSION["userLv"] >= 3) {
	showHeader("HOME > 멤버쉽 > 개인정보","mypage_missionary","tit_0801.gif");
} else {
	showHeader("HOME > 멤버쉽 > 개인정보","mypage_normal","tit_0801.gif");
} 

body();
showFooter();

function body() {
	global $houseId, $roomId, $toDate, $fromDate;
	global $member, $account;
	global $houseList1, $houseList2, $roomList;
?>
		<!-- //content -->
	<!-- //정보 -->
		<div id="content">
		<!--div class="mypage b20">
			<p class="hi"><strong><?php echo $member->Name;?></strong>님, 안녕하세요</p>
		<ul class="txt01">
			<li><strong>회원ID</strong> <?php echo $member->userid;?></li>
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

		<!-- //tab -->
		<div class="Tab">
		<?php 
		$roomList = array();
		if (count($houseList1) > 0 && strlen($houseId) == 0) {
			$houseId = $houseList1[0]->HouseID;
		}
		foreach ($houseList1 as $house) {
			if (trim($houseId) == trim($house->HouseID)) {
				$house_selected = $house;
				$roomList = $house->RoomList;
				print "<a href=\"mypage_houseInfo.php?houseId=".$house->HouseID."\" class=\"on\" title=\"".$house->HouseName."\"><span>".StrFormatByLength($house->HouseName, 9)."</span></a>";
			} else {
				print "<a href=\"mypage_houseInfo.php?houseId=".$house->HouseID."\" class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" title=\"".$house->HouseName."\"><span>".StrFormatByLength($house->HouseName, 9)."</span></a>";
			}
		}
		?>
			</div>
		<div class="sTab">
		<?php 
		if (count($roomList) > 0 && strlen($roomId) == 0) {
			$roomId = $roomList[0]->RoomID;
		}
		foreach ($roomList as $room) {
			if (trim($roomId) == trim($room->RoomID)) {
				$room_selected = $room;
				print "<a href=\"#\" class=\"on\" title=\"".$house_selected->HouseName." - ".$room->RoomName."\"><span>".StrFormatByLength($room->RoomName, 8)."</span></a>";
			} else {
				print "<a href=\"mypage_houseInfo.php?houseId=".$houseId."&roomId=".$room->RoomID."\" class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" title=\"".$house_selected->HouseName." - ".$room->RoomName."\"><span>".StrFormatByLength($room->RoomName, 8)."</span></a>";
			} 
		}
		?>
		</div>
		<!-- tab// -->

		<a href="../living/registHouse.php?houseId=<?php echo $houseId;?>" class="on">[선교관 수정]</a>
				<a href="mypage_houseInfo_edit.php?mode=new&houseId=<?php echo $houseId;?>" class="on">[방 추가]</a>		
		<a href="mypage_houseInfo_edit.php?houseId=<?php echo $houseId;?>&roomId=<?php echo $roomId;?>" class="on"><span>[방정보 수정]</span></a> 
		<a href="process.php?mode=deleteRoom&houseId=<?php echo $houseId;?>&roomId=<?php echo $roomId;?>" class="on"><span>[방정보 삭제]</span></a> 
		<H2><img src="../images/board/stit_reserve_01.gif"></H2>
	<?php if (count($houseList1) > 0) {
?>
			<div id="calendar">
				<!-- //photo -->
				<div class="photo">
					<p class="img01"><img src="<?php echo $room_selected->Image1;?>" width="320" id="mainImage" /></p>
					<div class="img02">
						<ul>
					<li><img src="<?php echo $room_selected->Image1;?>" width="70" border="0" onclick="changeImage('<?php echo $room_selected->Image1;?>')" style="cursor:pointer;"></li>
					<li><img src="<?php echo $room_selected->Image2;?>" width="70" border="0" onclick="changeImage('<?php echo $room_selected->Image2;?>')" style="cursor:pointer;"></li>
					<li><img src="<?php echo $room_selected->Image3;?>" width="70" border="0" onclick="changeImage('<?php echo $room_selected->Image3;?>')" style="cursor:pointer;"></li>
					<li><img src="<?php echo $room_selected->Image4;?>" width="70" border="0" onclick="changeImage('<?php echo $room_selected->Image4;?>')" style="cursor:pointer;"></li>
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

		<H2><img src="../images/board/stit_reserve_02.gif"></H2>
 	<?php if ((count($houseList1)>0)) {
?>
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
				<td><?php echo $house_selected->BuildingType;?></td>
				<td class="td01">최대인원</td>
				<td><?php echo $room_selected->Limit;?>명</td>
				<td class="td01">가격(1일기준) </td>
				<td><?php echo priceFormat($room_selected->Fee, 1);?></td>
			</tr>
			<tr>
				<td class="td01">세탁시설</td>
				<td><?php echo $room_selected->Laundary;?></td>
				<td class="td01">주방시설</td>
				<td><?php echo $room_selected->Kitchen;?></td>
				<td class="td01">인터넷</td>
				<td><?php echo $room_selected->Network;?></td>
			</tr>
			<tr>
				<td class="td01">제출서류</td>
				<td colspan="5"><?php echo $house_selected->Document;?><br></td>
			</tr>
			<tr>
				<td class="td01">선교관 소개</td>
				<td colspan="5"><?php echo $house_selected->Explain;?></td>
			</tr>
			<tr>
				<td class="td01">주소</td>
				<td colspan="5">
					<? $zipcode = $house_selected->Zipcode;?>
					[<?php echo $zipcode[0];?>-<?php echo $zipcode[1];?>]
					<a href="#" Onclick="javascript:window.open('../navermaps/a5.php?Naddr=<?php echo rawurlencode($house_selected->Address1.$house_selected->Address2);?>','win','top=0, left=500, width=550,height=450')"><?php echo $house_selected->Address1;?> <?php echo $house_selected->Address2;?></A>
				</td>
			</tr>
			<tr>
				<td class="td01">홈페이지</td>
				<td colspan="5">
					<?php echo $house_selected->HomePage;?>
				</td>
			</tr>
			<tr>
				<td class="td01">담당자</td>
				<td colspan="5">
					<?php echo $house_selected->showContactInfo();?>
				</td>
			</tr>
		</table>
			<!-- view// -->	
		</div>
	 <?php } ?>
	 
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
//]]>
</script>