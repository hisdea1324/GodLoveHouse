<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
$needUserLv[1];

$sumPrice = $_REQUEST["sumPrice"];
$IdList=explode(",",$_REQUEST["IdList"]);
for ($i=0; $i<=count($IdList); $i = $i+1) {
	$price[$i] = $_REQUEST["price".($i+1)];

}


$sessions = new __construct();
$s_Helper = new SupportHelper();
$member = $s_Helper->getCenterSupportByUserId($sessions->UserId);
if (($member->IsNew())) {
	$m_Helper = new MemberHelper();
	$member = $m_Helper->getMemberByUserId($sessions->UserId);
} 


showHeader("HOME > 선교사후원 > 후원자 정보입력","sponsor","tit_0302.gif");
body();
showFooter();

$sessions = null;

$m_Helper = null;

$member = null;


function body() {
?>
	<!-- //content -->
	<div id="content">
		<!-- //list -->
		<div class="bg_list">

		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_list">
		<form action="inputAccountInfo.php" name="dataFrm" id="dataFrm" method="post" />
			<input type="hidden" name="idList" id="idList" value="<?php echo $join[$IdList];?>" />
			<input type="hidden" name="priceList" id="priceList" value="<?php echo $join[$price];?>" />
			<input type="hidden" name="sumPrice" id="sumPrice" value="<?php echo $sumPrice;?>" />
			<col width="20%" />
			<col />
			<tr>
				<th>항목</th>
				<th class="th01">내용</th>
			</tr>
			<tr>
				<td colspan="2">
					(*후원자 정보 수정은 마이페이지에서 하실 수 있습니다.)
					<input type="button" value="개인정보수정" onclick="editMemberInfo()" style="cursor:pointer" />
				</td>
			</tr>
			<tr>
				<td>이름</td>
				<td class="ltd">
					<input type="text" name="name" id="name" value="<?php echo $member->Name;?>" tabIndex="1" />
				</td>
			</tr>
			<tr>
				<td>주민등록번호</td>
				<td class="ltd">
					<?	 $jumin = $member->Jumin;?>
					<input type="text" name="nid1" id="nid1" value="<?php echo $jumin[0];?>" style="width:50px;ime-mode:disabled" maxlength="6" tabIndex="2" /> -
					<input type="text" name="nid2" id="nid2" value="<?php echo $jumin[1];?>" style="width:50px;ime-mode:disabled" maxlength="7" tabIndex="3" />
				</td>
			</tr>
			<tr>
				<td>전화번호</td>
				<td class="ltd">
					<?	 $phone = $member->Phone;?>
					<input type="text" name="tel1" id="tel1" style="width:50px;ime-mode:disabled" onKeyPress="CheckNumber(event);" tabindex="4" maxlength="4" value="<?php echo $phone[0];?>" /> -
					<input type="text" id="tel2" name="tel2" style="width:50px;ime-mode:disabled" onKeyPress="CheckNumber(event);" tabindex="5" maxlength="4" value="<?php echo $phone[1];?>" /> -
					<input type="text" id="tel3" name="tel3" style="width:50px;ime-mode:disabled" onKeyPress="CheckNumber(event);" tabindex="6" maxlength="4" value="<?php echo $phone[2];?>" />
				</td>
			</tr>
			<tr>
				<td>핸드폰</td>
				<td class="ltd">
					<?	 $mobile = $member->Mobile;?>
					<input type="text" name="hp1" id="hp1" style="width:50px;ime-mode:disabled" onKeyPress="CheckNumber(event);" tabindex="7" maxlength="4" value="<?php echo $mobile[0];?>" /> -
					<input type="text" id="hp2" name="hp2" style="width:50px;ime-mode:disabled" onKeyPress="CheckNumber(event);" tabindex="8" maxlength="4" value="<?php echo $mobile[1];?>" /> -
					<input type="text" id="hp3" name="hp3" style="width:50px;ime-mode:disabled" onKeyPress="CheckNumber(event);" tabindex="9" maxlength="4" value="<?php echo $mobile[2];?>" />
				</td>
			</tr>
			<tr>
				<td>이메일</td>
				<td class="ltd">
					<?	 $email = $member->Email;?>						
					<input type="text" id="email1" name="email1" maxlength="30" tabindex="10" value="<?php echo $email[0];?>" /> @
					<input type="text" id="email2" name="email2" maxlength="50" tabindex="11" value="<?php echo $email[1];?>" />
				</td>
			</tr>
			<tr>
				<td>주소</td>
				<td class="ltd">
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

		</div>
		<!-- list// -->
		<p class="btn_right"><img src="../images/board/btn_ok.gif" border="0" onclick="frmSubmit()" style="cursor:pointer;" tabindex="16" /></p>
		<!--p class="btn_right">곧 지원될 예정입니다.</p-->
	</div>
	<!-- content// -->
<?php } ?>

<script type="text/javascript">
//<![CDATA[
	function editMemberInfo() {
		location.href = "/member/mypage_member.php";
	}
	
	function frmSubmit() {
		document.getElementById('dataFrm').submit();
	}
//]]>
</script>
