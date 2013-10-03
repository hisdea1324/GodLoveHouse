<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
checkUserLogin();

$toDate = isset($_REQUEST["toDate"]) ? trim($_REQUEST["toDate"]) : "";
$fromDate = isset($_REQUEST["fromDate"]) ? trim($_REQUEST["fromDate"]) : "";

// 회원 정보
$m_Helper = new MemberHelper();
$member = $m_Helper->getMemberByUserId($_SESSION["userid"]);

// 계좌정보
$account = $m_Helper->getAccountInfoByUserId($_SESSION["userid"]);

// 선교사 후원
$missionList1 = $m_Helper->getMemberListByPrayer($_SESSION["userid"]);
$missionList2 = $m_Helper->getMemberListByRegular($_SESSION["userid"]);

// 후원 내역 
$s_Helper = new SupportHelper();
$centerSupportInfo = $s_Helper->getCenterSupportByUserId($_SESSION["userid"]);
$serviceSupportInfo = $s_Helper->getServiceSupportByUserId($_SESSION["userid"]);

if ($_SESSION["userLv"] >= 7) {
	showHeader("HOME > 멤버쉽 > 후원정보","mypage_manager","tit_0802.gif");
} else if ($_SESSION["userLv"] >= 3) {
	showHeader("HOME > 멤버쉽 > 후원정보","mypage_missionary","tit_0802.gif");
} else {
	showHeader("HOME > 멤버쉽 > 후원정보","mypage_normal","tit_0802.gif");
} 

body();
showFooter();

function body() {
	global $member, $account, $missionList1, $missionList2;
?>
	<!-- //content -->
	<!-- //정보 -->
	<div id="content">
		<div class="mypage b20">
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
		</div>
		<!-- 정보// -->

		<!-- //search -->
		<form name="frmSearch" id="frmSearch" method="post" action="mypage_support.php">
			<div id="search">
				<img src="../images/board/img_search.gif" class="r5" align="absmiddle">
				<span class="fc_01"><strong>후원기간검색</strong></span>
				<input type="text" name="startDate" id="startDate" value="<?php echo $fromDate;?>" class="input" readonly onclick="calendar('startDate')">
				<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('startDate')"> ~
				<input type="text" name="endDate" id="endDate" class="input" value="<?php echo $toDate;?>" readonly onclick="calendar('endDate')">
				<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('endDate')">
				<img src="../images/board/btn_search.gif" border="0" align="absmiddle" class="m5" onclick="searchSubmit()">
			</div>
		</form>
		
		<!-- search// -->
		<!-- //검색결과 -->
		<p class="hi"><strong>선교사 후원 </strong></p>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_write">
			<col width="20%" />
			<col />
			<tr>
				<td><strong>기도후원자</strong></td>
				<td>
<?php 
	for ($i=0; $i<=count($missionList1)-1; $i = $i+1) {
		$mission = $missionList1[$i];
?>
					<p><?php echo $mission->MissionName;?> (<?php echo $mission->Nation;?>)</p>
<?php 

	}

?>
				</td>
			</tr>
			<tr>
				<td><strong>정기후원자</strong></td>
				<td>
<?php 
	for ($i=0; $i<=count($missionList2)-1; $i = $i+1) {
		$mission = $missionList2[$i];
?>
					<p><?php echo $mission->MissionName;?> (<?php echo $mission->Nation;?>)</p>
<?php 

	}

?>
				</td>
			</tr>
		</table>
<?php 
	if (($centerSupportInfo->SupportID>0)) {
?>
		<p class="hi"><strong>선교사역 후원 </strong></p>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_write">
			<col width="20%" />
			<col />
<?php 
		$centerItem = $centerSupportInfo->SupportItem;
		for ($i=0; $i<=count($centerItem); $i = $i+1) {
			$centerInfo = $centerItem[$i];
			$reqObj = $s_Helper->getRequestInfoByReqID($centerInfo->RequestId);
?>
			<tr>
				<td><strong><?php echo $reqObj->Title;?></strong></td>
				<td>
					<p><?php echo $centerInfo->showPrice;?> </p>
				</td>
			</tr>
<?php 

		}

?>
		</table>
<?php 
	} 


	$specialList = $s_Helper->getReqListForSpecial($sessions->UserID);
	if ((count($specialList)>0)) {
?>
		<p class="hi"><strong>특별후원 </strong></p>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_write">
			<col width="30%" />
			<col />
<?php 
		for ($i=0; $i<=count($specialList)-1; $i = $i+1) {
			$specialInfo = $specialList[$i];
			$specialAddInfo = $s_Helper->getRequestAddInfoByReqID($specialInfo->RequestID);
			$specialItems = $specialAddInfo->RequestItem;
?>
			<tr>
				<td><strong><?php echo $specialInfo->Title;?> (<?php echo $specialInfo->RequestID;?>)</strong></td>
				<td>
<?php 
			for ($j=0; $j<=count($specialItems); $j = $j+1) {
				$specialItem = $specialItems[$j];
				if (($specialItem->SendUser == $sessions->UserID)) {
?>
					<p><?php echo $specialItem->RequestItem;?> :	<?php echo $specialItem->showPrice();?></p>
<?php 
				} 


			}

?>
				</td>
			</tr>
<?php 

		}

?>
		</table>
<?php 
	} 


	if (($serviceSupportInfo->SupportID>0)) {
?>
		<p class="hi"><strong>자원봉사참여 </strong></p>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_write">
			<col width="20%" />
			<col />
			<tr>
				<td><strong>참여내역</strong></td>
				<td>
<?php 
		$serviceItem = $serviceSupportInfo->SupportItem;
		for ($i=0; $i<=count($serviceItem); $i = $i+1) {
			$serviceInfo = $serviceItem[$i];
			$reqObj = $s_Helper->getRequestInfoByReqID($serviceInfo->RequestId);
?>
					<p><?php echo $reqObj->Title;?> </p>
<?php 

		}

?>
				</td>
			</tr>
		</table>
<?php 
	} 

?>
		<!-- 검색결과// -->

	</div>
	<!-- content// -->
<?php } ?>

<script type="text/javascript">
//<![CDATA[
	calendar_init();

	function searchSubmit() {
		var endDate = document.getElementById("endDate").value;
		var startDate = document.getElementById("startDate").value;

		if (startDate.length == 0 || endDate.length == 0) {
			alert('기간을 정확히 입력해 주세요');
			return;
		}

		if (startDate.replace(/-/g,'') >= endDate.replace(/-/g,'')) {
			alert('기간이 잘못되었습니다.');
			return;
		}

		alert('아직 준비중입니다.');
		//document.getElementById("frmSearch").submit();
	}
//]]>
</script>
