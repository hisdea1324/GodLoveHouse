<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
checkUserLogin();

$sessions = new __construct();

// 회원 정보$m_Helper = new MemberHelper();
$member = $m_Helper->getMemberByUserId($sessions->UserID);

// 계좌정보$account = $m_Helper->getAccountInfoByUserId($sessions->UserID);

// 선교사 후원$missionList2 = $m_Helper->getMemberListByRegular($sessions->UserID);

// 후원 내역 $s_Helper = new SupportHelper();
$centerSupportInfo = $s_Helper->getCenterSupportByUserId($sessions->UserID);
$serviceSupportInfo = $s_Helper->getServiceSupportByUserId($sessions->UserID);

if (($sessions->authority(7))) {
showHeader("HOME > 멤버쉽 > 후원정보","mypage_manager","tit_0802.gif");
} else if (($sessions->authority(3))) {
showHeader("HOME > 멤버쉽 > 후원정보","mypage_missionary","tit_0802.gif");
} else {
showHeader("HOME > 멤버쉽 > 후원정보","mypage_normal","tit_0802.gif");
} 


body();
showFooter();

function body() {
?>
	<!-- //content -->
	<!-- //정보 -->

		<ol>
			<li><a href="edit_support1.php">후원 정보</a></li>
			<li><u>후원 내역 확인</u></li>
			<li><a href="edit_support3.php">후원 변경</a></li>
		</ol>

		<h2>정기후원내역</h2>
		<table border="1">
		<tr>
			<th> 입금날짜 </th> 
			<th> 금액 </th>
		</tr><tr>
			<td> 2010/05/25 </td> 
			<td> 130,000원</td>
		</tr><tr>
			<td> 2010/04/25 </td> 
			<td> 130,000원</td>
		</tr>
		<tr>
			<td colspan="2"> << < 1 2 3 4 5 6 7 8 > >> </td>
		</tr>
		</table> <br />
	 
		<h2>특별후원내역</h2>
<?php 
	$specialList = $s_Helper->getReqListForSpecial($sessions->UserID);
	if ((count($specialList)>0)) {
?>
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

?>
		
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
