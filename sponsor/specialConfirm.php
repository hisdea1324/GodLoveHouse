<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
checkUserLogin();

$detailIdList = trim($_REQUEST["check"]);
if (strlen($detailIdList) == 0) {
	alertBack("잘못된 접근입니다.");
} 

$itemidList=explode(",",$detailIdList);
$reqId = trim($_REQUEST["reqId"]);

$sessions = new __construct();
$m_Helper = new MemberHelper();
$member = $m_Helper->getMemberByUserId($sessions->UserId);
$s_Helper = new SupportHelper();
$reqInfo = $s_Helper->getRequestInfoByReqId($reqId);
$reqAddInfo = $s_Helper->getRequestAddInfoByReqId($reqId);
$reqItems = $reqAddInfo->RequestItem;

$arrayNum=0;
$strPrice=0;
$strDetail="";
for ($i=0; $i<=count($reqItems); $i = $i+1) {
	$reqItem = $reqItems[$i];
	if ((intval($reqItem->RequestItemID)==intval($itemidList[$arrayNum]))) {
		$strDetail="<li>".$reqItem->RequestItem." : ".priceFormat($reqItem->Cost, 1)." </li>".$strDetail;
		$strPrice = $strPrice+$reqItem->Cost;

		if (($arrayNum==count($itemidList))) {
			break;

		} else {
			$arrayNum = $arrayNum+1;
		} 

	} 


}

$strDetail="<ul>".substr($strDetail,0,strlen($strDetail)-2)."</ul>";

showHeader("HOME > 선교사후원 > 특별후원","sponsor","tit_0301.gif");
body();
showFooter();

$sessions = null;

$m_Helper = null;

$member = null;

$s_Helper = null;

$reqInfo = null;

$reqAddInfo = null;


function body() {
?>
	<!-- //content -->
	<div id="content">

		<!-- //view -->
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_con2 b20">
		<form action="process.php" method="post" name="dataFrm" id="dataFrm">
			<input type="hidden" name="mode" id="mode" value="addSupport" />
			<input type="hidden" name="reqId" id="reqId" value="<?php echo $reqId;?>" />
			<input type="hidden" name="detailId" id="detailId" value="<?php echo $detailIdList;?>" />
			<input type="hidden" name="sumPrice" id="sumPrice" value="<?php echo $strPrice;?>" />
			<col width="15%" />
			<col />
			<col width="15%" />
			<col />
			<tr>
				<th>이름</th>
				<td colspan="3" class="ltd"><?php echo $reqAddInfo->UserId;?></td>
			</tr>
			<tr>
				<th>선교지</th>
				<td><?php echo $reqAddInfo->Nation;?></td>
				<th>E-mail</th>
				<td><?php echo $reqAddInfo->Email;?></td>
			</tr>
			<tr>
				<th>제목</th>
				<td colspan="3"> <?php echo $reqInfo->Title;?> </td>
			</tr>
			<tr>
				<th>내용</th>
				<td colspan="3"><?php echo $reqInfo->Explain;?></td>
				</tr>
			<tr>
				<th>필요내용</th>
				<td colspan="3"><?php echo $strDetail;?> </td>
			</tr>
			<tr>
				<th>후원금액</th>
				<td><?php echo priceFormat($strPrice, 1);?></td>
				<th>후원마감일</th>
				<td><?php echo dateFormat($reqAddInfo->Due, 1);?></td>
			</tr>
			<tr>
				<th colspan="4">
					(*후원자 정보 수정은 마이페이지에서 하실 수 있습니다.)
					<input type="button" value="개인정보수정" onclick="editMemberInfo()" style="cursor:pointer" />
				</th>
			</tr>
			<tr>
				<th>후원자 이름</th>
				<td class="ltd" colspan="3">
					<input type="text" name="name" id="name" value="<?php echo $member->Name;?>" tabIndex="1" />
				</td>
			</tr>
			<tr>
				<th>주민등록번호</th>
				<td class="ltd" colspan="3">
					<?	 $jumin = $member->Jumin;?>
					<input type="text" name="nid1" id="nid1" value="<?php echo $jumin[0];?>" style="width:50px;ime-mode:disabled" maxlength="6" tabIndex="2" /> -
					<input type="text" name="nid2" id="nid2" value="<?php echo $jumin[1];?>" style="width:50px;ime-mode:disabled" maxlength="7" tabIndex="3" />
				</td>
			</tr>
			<tr>
				<th>전화번호</th>
				<td class="ltd" colspan="3">
					<?	 $phone = $member->Phone;?>
					<input type="text" name="tel1" id="tel1" style="width:50px;ime-mode:disabled" onKeyPress="CheckNumber(event);" tabindex="4" maxlength="4" value="<?php echo $phone[0];?>" /> -
					<input type="text" id="tel2" name="tel2" style="width:50px;ime-mode:disabled" onKeyPress="CheckNumber(event);" tabindex="5" maxlength="4" value="<?php echo $phone[1];?>" /> -
					<input type="text" id="tel3" name="tel3" style="width:50px;ime-mode:disabled" onKeyPress="CheckNumber(event);" tabindex="6" maxlength="4" value="<?php echo $phone[2];?>" />
				</td>
			</tr>
			<tr>
				<th>핸드폰</th>
				<td class="ltd" colspan="3">
					<?	 $mobile = $member->Mobile;?>
					<input type="text" name="hp1" id="hp1" style="width:50px;ime-mode:disabled" onKeyPress="CheckNumber(event);" tabindex="7" maxlength="4" value="<?php echo $mobile[0];?>" /> -
					<input type="text" id="hp2" name="hp2" style="width:50px;ime-mode:disabled" onKeyPress="CheckNumber(event);" tabindex="8" maxlength="4" value="<?php echo $mobile[1];?>" /> -
					<input type="text" id="hp3" name="hp3" style="width:50px;ime-mode:disabled" onKeyPress="CheckNumber(event);" tabindex="9" maxlength="4" value="<?php echo $mobile[2];?>" />
				</td>
			</tr>
			<tr>
				<th>이메일</th>
				<td class="ltd" colspan="3">
					<?	 $email = $member->Email;?>						
					<input type="text" id="email1" name="email1" maxlength="30" tabindex="10" value="<?php echo $email[0];?>" /> @
					<input type="text" id="email2" name="email2" maxlength="50" tabindex="11" value="<?php echo $email[1];?>" />
				</td>
			</tr>
			<tr>
				<th>주소</th>
				<td class="ltd" colspan="3">
					<?	 $zipcode = $member->Zipcode;?>
					<input type="text" id="post1" name="post1" style="width:50px" readonly onclick="PostPopup();" tabindex="12" value="<?php echo $zipcode[0];?>" /> -
					<input type="text" id="post2" name="post2" style="width:50px" readonly onclick="PostPopup();" tabindex="13" value="<?php echo $zipcode[1];?>" />
					<img src="../images/board/btn_zipcode.gif" border="0" align="absmiddle" class="m2" onclick="PostPopup();" /><br>
					<input type="text" name="addr1" id="addr1" style="width:80%" tabindex="14" readonly onclick="PostPopup();" value="<?php echo $member->Address1;?>" /><br>
					<input type="text" name="addr2" id="addr2" style="width:50%" tabindex="15" value="<?php echo $member->Address2;?>" />
				</td>
			</tr>
		</form>
		</table>

		<!-- view// -->
		<p class="btn_right"><img src="../images/board/btn_ok.gif" border="0" onclick="frmSubmit()" style="cursor:pointer" /></p>
	</div>
	<!-- content// -->
<?php } ?>

<script type="text/javascript">
//<![CDATA[
	function editMemberInfo() {
		location.href = "/member/mypage_member.php";
	}

	function frmSubmit() {
		document.getElementById("dataFrm").submit();
	}
//]]>
</script>
