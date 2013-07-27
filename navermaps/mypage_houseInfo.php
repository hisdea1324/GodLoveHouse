<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
//***************************************************************// member edit page//// last update date : 2009.12.28// updated by blackdew// To do List//	 - 비밀번호 변경하는 페이지는 따로 추가해야 함//	 - 자바 스크립트 추가 & update process 진행//***************************************************************
checkUserLogin();
$toDate = trim($_REQUEST["toDate"]);
$fromDate = trim($_REQUEST["fromDate"]);
$houseId = trim($_REQUEST["houseId"]);
$roomId = trim($_REQUEST["roomId"]);

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
			if (trim($houseId) == trim($houseObj->HouseID)) {
				$house = $houseObj;
				$roomList = $house->RoomList;
				if ($house->RoomCount<=0) {
					header("Location: "."mypage_houseInfo_edit.php?houseId=".$houseId);
				} 

				print "<a href=\"mypage_houseInfo.php?houseId=".$house->HouseID."\" class=\"on\" title=\"".$house->HouseName."\"><span>".$StrFormatByLength[$house->HouseName][10]."</span></a>";
			} else {
				print "<a href=\"mypage_houseInfo.php?houseId=".$houseObj->HouseID."\" class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" title=\"".$houseObj->HouseName."\"><span>".$StrFormatByLength[$houseObj->HouseName][10]."</span></a>";
			} 


		}

?>
			</div>
		<div class="sTab">
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
				print "<a href=\"mypage_houseInfo.php?houseId=".$houseId."&roomId=".$roomObj->RoomID."\" class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" title=\"".$houseObj->HouseName." - ".$roomObj->RoomName."\"><span>".$StrFormatByLength[$roomObj->RoomName][8]."</span></a>";
			} 


		}

?>
		</div>
		<!-- tab// -->
	 <?php } ?>

		<a href="../living/registHouse.php?houseId=<?php echo $houseId;?>" class="on">[선교관 수정]</a>
				<a href="mypage_houseInfo_edit.php?mode = new&houseId=<?php echo $houseId;?>" class="on">[방 추가]</a>		
		<a href="mypage_houseInfo_edit.php?houseId=<?php echo $houseId;?>&roomId=<?php echo $roomId;?>" class="on"><span>[방정보 수정]</span></a> 
		<a href="process.php?mode=deleteRoom&houseId=<?php echo $houseId;?>&roomId=<?php echo $roomId;?>" class="on"><span>[방정보 삭제]</span></a> 
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
				<td><?php echo $house->BuildingType;?></td>
				<td class="td01">최대인원</td>
				<td><?php echo $room->Limit;?>명</td>
				<td class="td01">가격(1일기준) </td>
				<td><?php echo $priceFormat[$room->Fee][1];?></td>
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
					<?		 $zipcode = $house->Zipcode;?>
					[<?php echo $zipcode[0];?>-<?php echo $zipcode[1];?>]
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
		
		location.href = "reservationDetail.php?houseId=<?php echo $houseId;?><%&roomId=" + room;
	}
	
	function goRoomList() {
		history.back(-1);
		//location.href = "reservation.php?houseId=<?php echo $houseId;?><%";
	}
	
	function changeImage(imgName) {
		document.getElementById("mainImage").src = imgName;
	}
	
	function callPage(y, m) {
		var url = 'ajax.php?mode=getCalendar&roomId=<?php echo $roomId;?><%&year='+y+'&month='+m;
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
//]]>
</script>

