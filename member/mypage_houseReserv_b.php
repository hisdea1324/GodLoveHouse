<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
checkUserLogin();
$toDate = trim($_REQUEST["toDate"]);
$fromDate = trim($_REQUEST["fromDate"]);
$search = trim($_REQUEST["search"]);
$page = trim($_REQUEST["page"]);
if ((strlen($page)==0)) {
	$page=1;
} 

$sessions = new __construct();
$m_Helper = new MemberHelper();
$member = $m_Helper->getMemberByUserId($sessions->UserID);
$account = $m_Helper->getAccountInfoByUserId($sessions->UserID);

$h_Helper = new HouseHelper();
$h_Helper->PAGE_UNIT=10; //하단 페이징 단위 $h_Helper->PAGE_COUNT=7; //한페이지에 보여줄 리스트 갯수 $h_Helper->setReservationListCondition($search);
$strPage = $h_Helper->makeReservationListPagingHTML($page);
$reservList = $h_Helper->getReservationListWithPaging($page);

if (($sessions->authority(7))) {
showHeader("HOME > 멤버쉽 > 선교관 예약관리","mypage_manager","tit_0804.gif");
} else if (($sessions->authority(3))) {
showHeader("HOME > 멤버쉽 > 선교관 예약관리","mypage_missionary","tit_0804.gif");
} else {
showHeader("HOME > 멤버쉽 > 선교관 예약관리","mypage_normal","tit_0804.gif");
} 


body();
showFooter();

function body() {
?>
	<!-- //content -->
	<div id="content">
		<div class="mypage b20">
			<p class="hi"><strong><?php echo $member->Name;?></strong>님, 안녕하세요</p>
			<ul class="txt01">
				<li><strong>회원ID</strong><?php echo $member->UserID;?></li>
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
		</div>

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
			<p class="btn_right"><img src="../images/board/btn_add2.gif" border="0" class="r5" onclick="addReserv()" /></p>
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
	function allow(value) {
		if (confirm('예약을 승인합니다.'))
			location.href = 'process.php?mode=changeReservStatus&status=2&bookNo=' + value;
	}

	function deny(value) {
		if (confirm('예약을 거절합니다.'))
			location.href = 'process.php?mode=changeReservStatus&status=4&bookNo=' + value;
	}

	function complete(value) {
		if (confirm('예약을 완료합니다.'))
			location.href = 'process.php?mode=changeReservStatus&status=3&bookNo=' + value;
	}

	function addReserv() {
		location.href = 'mypage_houseInfo.php';
	}
	
	function search(value) {
		location.href = 'mypage_houseReserv.php?search=' + value;
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
