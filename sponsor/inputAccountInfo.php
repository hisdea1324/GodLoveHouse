<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
$needUserLv[1];

$IdList = $_REQUEST["IdList"];
$priceList = $_REQUEST["priceList"];
$sumPrice = $_REQUEST["sumPrice"];

$name = $_REQUEST["name"];
$nid = $_REQUEST["nid1"].$_REQUEST["nid2"];
$phone = $_REQUEST["tel1"]."-".$_REQUEST["tel2"]."-".$_REQUEST["tel3"];
$mobile = $_REQUEST["hp1"]."-".$_REQUEST["hp2"]."-".$_REQUEST["hp3"];
$zipcode = $_REQUEST["post1"].$_REQUEST["post2"];
$address1 = $_REQUEST["addr1"];
$address2 = $_REQUEST["addr2"];
$email = $_REQUEST["email1"]."@".$_REQUEST["email2"];

$m_Helper = new MemberHelper();
$s_Helper = new SupportHelper();
$account = $m_Helper->getAccountInfoByUserId($_SESSION["userId"]);
$member = $m_Helper->getMemberByUserId($_SESSION["userId"]);
$supporter = $s_Helper->getCenterSupportByUserId($_SESSION["userId"]);

showHeader("HOME > 선교사후원 > 납입 방법 선택","sponsor","tit_0302.gif");
body();
showFooter();

function body() {
?>
	<!-- //content -->
	<div id="content">
		<!-- //list -->
		<div class="bg_list">

		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_list">
		<form action="process.php" name="dataFrm" id="dataFrm" method="post" />
			<input type="hidden" name="mode" id="mode" value="addCenterSupport" />
			<input type="hidden" name="idList" id="idList" value="<?php echo $IdList;?>" />
			<input type="hidden" name="priceList" id="priceList" value="<?php echo $priceList;?>" />
			<input type="hidden" name="supName" id="supName" value="<?php echo $name;?>" />
			<input type="hidden" name="supNID" id="supNID" value="<?php echo $nid;?>" />
			<input type="hidden" name="phone" id="phone" value="<?php echo $phone;?>" />
			<input type="hidden" name="mobile" id="mobile" value="<?php echo $mobile;?>" />
			<input type="hidden" name="email" id="email" value="<?php echo $email;?>" />
			<input type="hidden" name="zipcode" id="zipcode" value="<?php echo $zipcode;?>" />
			<input type="hidden" name="address1" id="address1" value="<?php echo $address1;?>" />
			<input type="hidden" name="address2" id="address2" value="<?php echo $address2;?>" />
			<col width="20%" />
			<col />
			<tr>
				<th>항목</th>
				<th class="th01">내용</th>
			</tr>
			<tr>
				<td>현재 후원내역</td>
				<td>

				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_write">
				<col width="25%" />
				<col />
<?php 
//현재 후원 내역	$items = $supporter->SupportItem;
	for ($i=0; $i<=count($items); $i = $i+1) {
		$reqInfo = $s_Helper->getRequestInfoByReqID($items[$i]->RequestID);
?>
				<tr>
					<td><strong><?php echo $reqInfo->Title;?></strong></td>
					<td><?php echo $items[$i]->$showPrice;?></td>
				</tr>
<?php 

	}

?>
				</table>

				</td>
			</tr>
			<tr>
				<td>변경 후 후원내역</td>
				<td>

				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_write">
				<col width="25%" />
				<col />
<?php 
//변경 후	$newSupportList=explode(" ",$idList);
	$newPriceList=explode(" ",$priceList);
	$items = $supporter->SupportItem;
	for ($j=0; $j<=count($newSupportList); $j = $j+1) {
		$reqInfo = $s_Helper->getRequestInfoByReqID($newSupportList[$j]);
?>
				<tr>
					<td><strong><?php echo $reqInfo->Title;?></strong></td>
					<td><?php echo priceFormat($newPriceList[$j], 1);?> / 월</td>
				</tr>
<?php 

	}

?>
				</table>

				</td>
			</tr>
			<tr id="trTotalPrice">
				<td>총후원금액</td>
				<td class="ltd">
					<input type="text" name="sumPrice" id="sumPrice" value="<?php echo $sumPrice;?>" readonly />
				</td>
			</tr>
			<tr id="trMethod">
				<td>입금방법</td>
				<td class="ltd">
					<input type="radio" name="method" id="method" onclick="checkSendMethod();" value="CMS"<?php if (($account->Method=="CMS")) {
?> checked<?php } ?> /> CMS 자동이체 &nbsp;&nbsp;&nbsp;
					<input type="radio" name="method" id="method" onclick="checkSendMethod();" value="DIRECT"<?php if (($account->Method=="DIRECT")) {
?> checked<?php } ?> /> 직접입금 &nbsp;&nbsp;&nbsp;
					<input type="radio" name="method" id="method" onclick="checkSendMethod();" value="GIRO"<?php if (($account->Method=="GIRO")) {
?> checked<?php } ?> /> 지로
				</td>
			</tr>
			<tr id="trBank">
				<td>은행명</td>
				<td class="ltd">
					<input type="text" name="bank" id="bank" value="<?php echo $account->Bank;?>" />
				</td>
			</tr>
			<tr id="trNumber">
				<td> 계좌번호</td>
				<td class="ltd">
					<input type="text" name="number" id="number" value="<?php echo $account->Number;?>" />
				</td>
			</tr>
			<tr id="trName">
				<td id="tdName">예금주</td>
				<td class="ltd">
<?php 
	$name = $account->Name;
	if ((strlen($name)==0)) {
		$name = $member->Name;
	} 

?>
					<input type="text" name="accName" id="accName" value="<?php echo $name;?>" />
				</td>
			</tr>
			<tr id="trJumin">
				<td>예금주 주민등록번호</td>
				<td class="ltd">
<?php 
	$jumin = $account->Jumin;
	if ((strlen($jumin[0])+strlen($jumin[1])!=13)) {
		$jumin = $member->Jumin;
	} 

?>
					<input type="text" name="jumin1" id="jumin1" value="<?php echo $jumin[0];?>" maxlength="6" /> -
					<input type="text" name="jumin2" id="jumin2" value="<?php echo $jumin[1];?>" maxlength="7" />
				</td>
			</tr>
			<tr id="trSendDate">
				<td>이체일</td>
				<td class="ltd">
					<input type="radio" name="sendDate" id="sendDate" value="5"<?php if (($account->SendDate==5)) {
?> checked<?php } ?> /> 5일
					<input type="radio" name="sendDate" id="sendDate" value="20"<?php if (($account->SendDate==20)) {
?> checked<?php } ?> /> 20일 
					<input type="radio" name="sendDate" id="sendDate" value="25"<?php if (($account->SendDate==25)) {
?> checked<?php } ?> /> 25일
				</td>
			</tr>
			<tr id="trExpectDate">
				<td> 입금 예정일</td>
				<td class="ltd">
					<input type="radio" name="expectDate" id="expectDate" value="1"<?php if (($account->ExpectDate==1)) {
?> checked<?php } ?> /> 매월 10일 이전 <br />
					<input type="radio" name="expectDate" id="expectDate" value="2"<?php if (($account->ExpectDate==2)) {
?> checked<?php } ?> /> 매월 11일 ~ 20일 <br />
					<input type="radio" name="expectDate" id="expectDate" value="3"<?php if (($account->ExpectDate==3)) {
?> checked<?php } ?> /> 매월 21일 이후 <br />
				</td>
			</tr>
		</form>
		</table>

		</div>
		<!-- list// -->
		<p class="btn_right"><img src="../images/board/btn_ok.gif" border="0" onclick="frmSubmit()" style="cursor:pointer;" /></p>
		<!--p class="btn_right">곧 지원될 예정입니다.</p-->
	</div>
	<!-- content// -->
<?php } ?>

<script type="text/javascript">
//<![CDATA[
	checkSendMethod();
	
	function checkSendMethod() {
		var method = document.getElementsByName("method");
		if (method[0].checked) {
			document.getElementById("trBank").style.display = "";
			document.getElementById("trNumber").style.display = "";
			document.getElementById("trName").style.display = "";
			document.getElementById("trJumin").style.display = "";
			document.getElementById("trSendDate").style.display = "";
			document.getElementById("trExpectDate").style.display = "none";
			document.getElementById("tdName").innerHTML = "예금주";
		} else if (method[1].checked) {
			document.getElementById("trBank").style.display = "none";
			document.getElementById("trNumber").style.display = "none";
			document.getElementById("trName").style.display = "";
			document.getElementById("trJumin").style.display = "none";
			document.getElementById("trSendDate").style.display = "none";
			document.getElementById("trExpectDate").style.display = "";
			document.getElementById("tdName").innerHTML = "입금자명";
		} else {
			document.getElementById("trBank").style.display = "none";
			document.getElementById("trNumber").style.display = "none";
			document.getElementById("trName").style.display = "none";
			document.getElementById("trJumin").style.display = "none";
			document.getElementById("trSendDate").style.display = "none";
			document.getElementById("trExpectDate").style.display = "none";
		}
	}
	
	function editMemberInfo() {
		location.href = "/member/mypage_member.php";
	}
	
	function frmSubmit() {
		document.getElementById('dataFrm').submit();
	}
//]]>
</script>
