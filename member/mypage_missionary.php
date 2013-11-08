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
$member = $m_Helper->getMemberByuserid($_SESSION["userid"]);

$h_Helper = new HouseHelper();
$reservList = $h_Helper->getReservationListByUser(1);

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
	global $search;
	global $reservList, $member;
?>
		<!-- //content -->
		<!-- //정보 -->
		<div id="content">

			<h2 style="margin-top:30px"><img src="../images/board/stit_ok.gif"></h2>
				<!-- //search -->
		<div id="search">
			<img src="../images/board/img_search.gif" class="r5" align="absmiddle" /><span class="fc_01"><strong>예약 처리 상황</strong></span>
			<select name="status" id="status" onchange="search(this.value)">
				<option value="0">전체</option>
				<option value="1" <?php if ($search == "1") { ?> selected <?php } ?>>신규예약</option>
				<option value="2" <?php if ($search == "2") { ?> selected <?php } ?>>승인</option>
				<option value="3" <?php if ($search == "3") { ?> selected <?php } ?>>완료</option>
				<option value="4" <?php if ($search == "4") { ?> selected <?php } ?>>거절</option>
			</select>
		</div>
		<!-- search// -->
				
		<!-- //list -->
		<div class="bg_list">
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_list">
			<tr>
				<th>예약날짜</th>
				<th class="th01">별명</th>
				<th class="th01">선교관 / 선교관 방이름</th>
				<th class="th01">일정</th>
				<th class="th01" width="20%">승인여부</th>
			</tr>
<?php 
	if (count($reservList) == 0) {
?>
			<tr>
				<td colspan="4">리스트가 없습니다</td>
			</tr>
<?php 
	} else {
		foreach ($reservList as $reservObj) {
?>
			<tr>
				<td><?php echo $reservObj->RegDate;?></td>
				<td class="ltd">
					<label id="profileId<?php echo $i;?>" onmouseover="showProfile('<?php echo $i;?>', event)" onmouseout="unshowProfile('<?php echo $i;?>')" style="cursor:prointer"><?php echo $member->Nick;?><?php //=member.Name ?><?php //=reservObj.userid ?></label>
					<div id="profile<?php echo $i;?>" style="position:absolute;visibility:hidden;border:1px solid black;color:#FFF;"></div>
				</td>
				<td><?php echo $reservObj->HouseName;?> / <?php echo $reservObj->RoomName;?></td>
				<td><?php echo $reservObj->StartDate;?> ~ <?php echo $reservObj->EndDate;?> <!--a href="#"><img src="../images/board/btn_modify_date.gif" align="absmiddle"></a--></td>
				<td><?php echo $reservObj->Status;?> <?	if ($reservObj->Status=="신규예약") { ?><a href="#" onclick="deny(<?php echo $reservObj->BookNo;?>)" >[취소]</a><? } ?>
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

