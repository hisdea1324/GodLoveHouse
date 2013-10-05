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
$member = $m_Helper->getMemberByUserId($_SESSION["userid"]);
$account = $m_Helper->getAccountInfoByUserId($_SESSION["userid"]);
$mission = $m_Helper->getMissionInfoByUserId($_SESSION["userid"]);

$c_Helper = new CodeHelper();
$codes = $c_Helper->getNationCodeList();

$h_Helper = new HouseHelper();
$houseList1 = $h_Helper->getHouseListByUserId($_SESSION["userid"], 1);
$houseList2 = $h_Helper->getHouseListByUserId($_SESSION["userid"], 2);

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
	global $houseList1, $houseList2;
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

		<!-- //tab -->
		<div class="Tab">
		<?php 
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
				print "<a href=\"mypage_houseInfo_edit.php?houseId=".$houseId."&roomId=".$room->RoomID."\" class=\"on\" title=\"".$house_selected->HouseName." - ".$room->RoomName."\"><span>".StrFormatByLength($room->RoomName, 8)."</span></a>";
			} else {
				print "<a href=\"mypage_houseInfo_edit.php?houseId=".$houseId."&roomId=".$room->RoomID."\" class=\"off\" onmouseover=\"this.className='on'\" onmouseout=\"this.className='off'\" title=\"".$house_selected->HouseName." - ".$room->RoomName."\"><span>".StrFormatByLength($room->RoomName, 8)."</span></a>";
			} 
		} 

		if ($house_selected->RoomCount == 0 || (isset($_REQUEST["mode"]) && $_REQUEST["mode"] == "new")) {
			$room_selected = new RoomObject();
		} 
		?>
		</div>
		<!-- tab// -->

		<a href="../living/registHouse.php?houseId=<?php echo $houseId;?>" class="on">[선교관 수정]</a>
				<a href="mypage_houseInfo_edit.php?mode = new&houseId=<?php echo $houseId;?>" class="on">[방 추가]</a>	
				
	<div class="rSec">
		<dl>
		<form name="dataForm" id="dataForm" method="post">
		<input type="hidden" name="field" value="<?php echo $field;?>" />
		<input type="hidden" name="keyword" value="<?php echo $keyword;?>" />
		<input type="hidden" name="page" value="<?php echo $page;?>" />
		<input type="hidden" name="mode" value="editRoom" />
		<input type="hidden" name="roomId" value="<?php echo $room_selected->RoomId;?>" />
			<dt>
				선교관 이름 
			<dd>
				<?php echo $house_selected->HouseName;?>&nbsp;&nbsp;
				<input type="hidden" name="houseId" value="<?php echo $house_selected->HouseID;?>" />
			<dt>
				방 이름	
			<dd>
				<input type="text" name="roomName" size="20" maxlength=20 value="<?php echo $room_selected->RoomName;?>" />&nbsp;&nbsp;
			<dt>
				인터넷 유무
			<dd>
				<input type="text" name="network" size="20" value="<?php echo $room_selected->Network;?>" />
			<dt>
				취사 여부
			<dd>
				<input type="text" name="kitchen" size="20" value="<?php echo $room_selected->Kitchen;?>" />
			<dt>
				세탁여부
			<dd>
				<input type="text" name="laundary" size="20" value="<?php echo $room_selected->Laundary;?>" />
			<dt>
				요금
			<dd>
				<input type="text" name="fee" size="20" value="<?php echo $room_selected->Fee;?>" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" />
			<dt>
				방인원수
			<dd>
				<input type="text" name="limit" size="20" value="<?php echo $room_selected->Limit;?>" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" />
			<dt>
				이미지 1
			<dd>
				<div id="showimage1" style="position:absolute;visibility:hidden;border:1px solid black"></div>
				<input type="button" name="imgUpload" id="imgUpload" value="이미지 업로드" onclick="uploadImage(event, 'RoomImage1', 'room')" style="cursor:pointer" /> (320x220)<br />
				<input type="hidden" name="idRoomImage1" id="idRoomImage1" value="<?php echo $room_selected->ImageID1;?>" />
				<img src="<?php echo $room_selected->Image1;?>" id="imgRoomImage1" width="320" height="220" onclick="showImage(1, this, event)" alt="크게보기" style="cursor:pointer" />
			<dt>
				이미지 2
			<dd>
				<div id="showimage2" style="position:absolute;visibility:hidden;border:1px solid black"></div>
				<input type="button" name="imgUpload" id="imgUpload" value="이미지 업로드" onclick="uploadImage(event, 'RoomImage2', 'room')" style="cursor:pointer" /> (320x220)<br />
				<input type="hidden" name="idRoomImage2" id="idRoomImage2" value="<?php echo $room_selected->ImageID2;?>" />
				<img src="<?php echo $room_selected->Image2;?>" id="imgRoomImage2" width="320" height="220" onclick="showImage(2, this, event)" alt="크게보기" style="cursor:pointer" />
			<dt>
				이미지 3
			<dd>
				<div id="showimage3" style="position:absolute;visibility:hidden;border:1px solid black"></div>
				<input type="button" name="imgUpload" id="imgUpload" value="이미지 업로드" onclick="uploadImage(event, 'RoomImage3', 'room')" style="cursor:pointer" /> (320x220)<br />
				<input type="hidden" name="idRoomImage3" id="idRoomImage3" value="<?php echo $room_selected->ImageID3;?>" />
				<img src="<?php echo $room_selected->Image3;?>" id="imgRoomImage3" width="320" height="220" onclick="showImage(3, this, event)" alt="크게보기" style="cursor:pointer" />
			<dt> 
				이미지 4
			<dd>
				<div id="showimage4" style="position:absolute;visibility:hidden;border:1px solid black"></div>
				<input type="button" name="imgUpload" id="imgUpload" value="이미지 업로드" onclick="uploadImage(event, 'RoomImage4', 'room')" style="cursor:pointer" /> (320x220)<br />
				<input type="hidden" name="idRoomImage4" id="idRoomImage4" value="<?php echo $room->ImageID4;?>" />
				<img src="<?php echo $room_selected->Image4;?>" id="imgRoomImage4" width="320" height="220" onclick="showImage(4, this, event)" alt="크게보기" style="cursor:pointer" />
			<dt>&nbsp;
			<dd>
				<img src="/images/board/btn_ok.gif" border="0" onclick="check();" style="cursor:hand;">&nbsp;&nbsp;&nbsp;
				<img src="/images/board/btn_cancel.gif" border="0" onclick="history.back(-1);" style="cursor:hand"></a>
		</form>
		</dl>
	</div>
	</div>
	 
<?php 
} 
?>

<script type="text/javascript">
//<![CDATA[
	function check() {
		document.getElementById("dataForm").action="process.php";
		document.getElementById("dataForm").submit();
	}
	
	function showImage(id, obj, e) {
		crossobj = document.getElementById("showimage" + id);
		
		if (crossobj.style.visibility == "hidden") {
			crossobj.style.left = e.clientX;
			crossobj.style.top = e.clientY;
			crossobj.innerHTML = '<img src="' + obj.src + '" style="cursor:pointer" onClick="closepreview()" />';
			crossobj.style.visibility = "visible";
		} else {
			crossobj.style.visibility = "hidden";
		}
	}

	function closepreview(){
		crossobj.style.visibility="hidden"
	}
//]]>
</script>


