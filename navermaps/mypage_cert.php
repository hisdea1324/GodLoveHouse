<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
checkUserLogin();
$toDate = trim($_REQUEST["toDate"]);
$fromDate = trim($_REQUEST["fromDate"]);

$sessions = new __construct();
$m_Helper = new MemberHelper();
$member = $m_Helper->getMemberByUserId($sessions->UserID);
$account = $m_Helper->getAccountInfoByUserId($sessions->UserID);

$s_Helper = new SupportHelper();
$supporter = $s_Helper->getServiceSupportByUserId($sessions->UserId);

if (($sessions->authority(7))) {
showHeader("HOME > 멤버쉽 > 온라인 증명서 발급","mypage_manager","tit_0803.gif");
} else if (($sessions->authority(3))) {
showHeader("HOME > 멤버쉽 > 온라인 증명서 발급","mypage_missionary","tit_0803.gif");
} else {
showHeader("HOME > 멤버쉽 > 온라인 증명서 발급","mypage_normal","tit_0803.gif");
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
			<!-- //search -->
		<form name="frmSearch" id="frmSearch" method="post" action="mypage_cert.php">
			<div id="search"> <img src="../images/board/img_search.gif" class="r5" align="absmiddle"><span class="fc_01"><strong>기부금 영수증 검색</strong></span>
				<input type="text" name="startDate" id="startDate" value="<?php echo $fromDate;?>" class="input" readonly onclick="calendar('startDate')">
				<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('startDate')"> ~
				<input type="text" name="endDate" id="endDate" class="input" value="<?php echo $toDate;?>" readonly onclick="calendar('endDate')">
		<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('endDate')">
		<img src="../images/board/btn_search.gif" border="0" align="absmiddle" class="m5" onclick="searchSubmit()">
		</div>
		</form>
			<!-- search// -->
			<!-- //검색결과 -->
			<p>검색결과</p>
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

		document.getElementById("frmSearch").submit();
	}
//]]>
</script>
