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
			<li><u>후원 정보</u></li>
			<li><a href="edit_support2.php">후원 내역 확인</a></li>
			<li><a href="edit_support3.php">후원 변경</a></li>
		</ol>

		<h2>후원정보 <input type="button" value="변경" style="cursor:pointer" /> </h2>
		<table border="1">
		<tr>
			<td> 선교사 후원 리스트 </td> 
			<td>
<?php 
	for ($i=0; $i<=count($missionList2)-1; $i = $i+1) {
		$mission = $missionList2[$i];
?>
					<p><?php echo $mission->MissionName;?> (<?php echo $mission->Nation;?>) : <?php echo $PriceFormat[30000][1];?> / 월</p>
<?php 

	}

?>
			</td>
		</tr><tr>
			<td> 선교사 후원금 합계 </td> 
			<?	 $mission_donation=count($missionList2)*30000;?>
			<td><?php echo $PriceFormat[$mission_donation][1];?></td>
		</tr><tr>
			<td>정기 후원 리스트 보기</td>
			<td>
<?php 
	$centerItem = $centerSupportInfo->SupportItem;
	$regular_donation=0;
	for ($i=0; $i<=count($centerItem); $i = $i+1) {
		$centerInfo = $centerItem[$i];
		$reqObj = $s_Helper->getRequestInfoByReqID($centerInfo->RequestId);
		$regular_donation = $regular_donation+$centerInfo->Cost;
?>
		 <p><?php echo $reqObj->Title;?> : <?php echo $centerInfo->showPrice;?></p>
<?php 

	}

?>
			</td>
		</tr><tr>
			<td> 정기 후원금 합계</td>
			<td><?php echo $PriceFormat[$regular_donation][1];?></td>
		</tr><tr>
			<td> 자원봉사 리스트 보기</td>
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
		</table> <br />

		<h2>입금정보 <input type="button" value="변경" style="cursor:pointer" /></h2>
		<table border="1">
		<tr>
			<td>후원금 입금방법</td>
			<td><?php echo $account->Method;?></td>
		</tr><tr>
			<td>총 정기후원금액</td>
			<td><?php echo $PriceFormat[$regular_donation+$mission_donation][1];?> / 월</td>
		</tr><tr>
			<td>이체일</td>
			<td><?php echo $account->SendDate;?></td>
		</tr><tr>
			<td>예금주</td>
			<td><?php echo $account->Name;?></td>
		</tr><tr>
			<td>예금주 주민번호</td>
			<?	 $jumin = $account->Jumin;?>
			<td><?php echo $jumin[0];?>-<?php echo $jumin[1];?></td>
		</tr><tr>
			<td>은행명</td>
			<td><?php echo $account->Bank;?></td>
		</tr><tr>
			<td>계좌번호</td>
			<td><?php echo $account->Number;?></td>
		</tr>
		</table>
		
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
