<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
//***************************************************************// member edit page//// last update date : 2009.12.28// updated by blackdew// To do List//	 - 비밀번호 변경하는 페이지는 따로 추가해야 함//	 - 자바 스크립트 추가 & update process 진행//***************************************************************
checkUserLogin();

$m_Helper = new MemberHelper();
$member = $m_Helper->getMemberByUserId($_SESSION["userid"]);
$account = $m_Helper->getAccountInfoByUserId($_SESSION["userid"]);
$mission = $m_Helper->getMissionInfoByUserId($_SESSION["userid"]);

$c_Helper = new CodeHelper();
$codes = $c_Helper->getNationCodeList();

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
	global $member, $account;
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
		<!-- //개인정보 -->
			<p class="b5"><img src="../images/sub/stit_080101.gif"></p>
	<form name="dataForm" id="dataForm" method="post">
	<input type="hidden" id="mode" name="mode" value="editUser" />
	<input type="hidden" id="userId" name="userId" value="<?php echo $member->UserID;?>" />
	<input type="hidden" id="level" name="level" value="<?php echo $member->UserLevel;?>" />
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_write">
				<col width="25%" />
				<col />
				<tr>
					<td class="td01">아이디</td>
					<td><?php echo $member->UserID;?></td>
				</tr>
				<tr>
					<td class="td01">닉네임<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<input type="text" name="nickName" id="nickName" maxlength="30" tabindex="1"	value="<?php echo $member->Nick;?>" onkeyup="checkNick(this.value);" />
			<label class="fs11" type="text" name='resultMessage2' id='resultMessage2'></label>
			</td>
				</tr>
				<tr>
					<td class="td01">이름<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<input type="text" name="name" id="name" maxlength="30" tabindex="2" value="<?php echo $member->Name;?>" />
			<label class="fs11" type="text" name='resultMessage3' id='resultMessage3'>(공백없이 한글만 입력가능)</label>
			</td>
				</tr>
				<!--tr>
					<td class="td01">주민등록번호<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
				<?	 $jumin = $member->Jumin;?>
						<input type="text" name="jumin1" id="jumin1" onkeyup="checkNID();" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="6" style="width:100px" tabindex="3" value="<?php echo $jumin[0];?>" />
						-
						<input type="password" name="jumin2" id="jumin2" onkeyup="checkNID();" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="7" style="width:100px" tabindex="4" value="<?php echo $jumin[1];?>" />
			<label class="fs11" type="text" name='resultMessage4' id='resultMessage4'></label>
					</td>
				</tr-->
				<tr>
					<td class="td01"> E-mail<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
			<? $email = $member->Email;?>
					<td>
				<input type="text" name="email1" id="email1" style="ime-mode:disabled" tabindex="5" maxlength="30" value="<?php echo $email[0];?>" />
			@
			<input type="text" name="email2" id="email2" style="ime-mode:disabled" tabindex="6" maxlength="50" value="<?php echo $email[1];?>" />
			</td>
				</tr>
				<tr>
					<td class="td01">우편번호<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
				<?	 $zipcode = $member->Zipcode;?>
						<input type="text" id="post1" name="post1" style="width:50px" readonly onclick="PostPopup();" tabindex="7" value="<?php echo $zipcode[0];?>" />
						-
						<input type="text" id="post2" name="post2" style="width:50px" readonly onclick="PostPopup();" tabindex="8" value="<?php echo $zipcode[1];?>" />
						<img src="../images/board/btn_zipcode.gif" border="0" align="absmiddle" class="m2" onclick="PostPopup();" style="cursor:hand;">
			</td>
				</tr>
				<tr>
					<td class="td01">주소<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<input type="text" name="addr1" id="addr1" style="width:80%" tabindex="9" readonly onclick="PostPopup();" value="<?php echo $member->Address1;?>" />
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input type="text" name="addr2" id="addr2" style="width:50%" tabindex="10" value="<?php echo $member->Address2;?>" />
					</td>
				</tr>
				<tr>
					<td class="td01">전화번호</td>
					<td>
				<?	 $phone = $member->Phone;?>
						<select id="tel1" name="tel1" tabindex="11">
				<option value="02"<?php if (($phone[0]=="02")) { ?> selected<?php } ?>>02</option>
				<option value="031"<?php if (($phone[0]=="031")) { ?> selected<?php } ?>>031</option>
				<option value="032"<?php if (($phone[0]=="032")) { ?> selected<?php } ?>>032</option>
				<option value="033"<?php if (($phone[0]=="033")) { ?> selected<?php } ?>>033</option>
				<option value="041"<?php if (($phone[0]=="041")) { ?> selected<?php } ?>>041</option>
				<option value="042"<?php if (($phone[0]=="042")) { ?> selected<?php } ?>>042</option>
				<option value="043"<?php if (($phone[0]=="043")) { ?> selected<?php } ?>>043</option>
				<option value="051"<?php if (($phone[0]=="051")) { ?> selected<?php } ?>>051</option>
				<option value="052"<?php if (($phone[0]=="052")) { ?> selected<?php } ?>>052</option>
				<option value="053"<?php if (($phone[0]=="053")) { ?> selected<?php } ?>>053</option>
				<option value="054"<?php if (($phone[0]=="054")) { ?> selected<?php } ?>>054</option>
				<option value="055"<?php if (($phone[0]=="055")) { ?> selected<?php } ?>>055</option>
				<option value="061"<?php if (($phone[0]=="061")) { ?> selected<?php } ?>>061</option>
				<option value="062"<?php if (($phone[0]=="062")) { ?> selected<?php } ?>>062</option>
				<option value="063"<?php if (($phone[0]=="063")) { ?> selected<?php } ?>>063</option>
				<option value="064"<?php if (($phone[0]=="064")) { ?> selected<?php } ?>>064</option>
				<option value="070"<?php if (($phone[0]=="070")) { ?> selected<?php } ?>>070</option>
						</select>
						-
						<input type="text" id="tel2" name="tel2" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" tabindex="12" maxlength="4" value="<?php echo $phone[1];?>" />
						-
						<input type="text" id="tel3" name="tel3" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" tabindex="13" maxlength="4" value="<?php echo $phone[2];?>" />
					</td>
				</tr>
				<tr>
					<td class="td01">휴대폰번호</td>
					<td>
				<?	 $mobile = $member->Mobile;?>
						<select id="hp1" name="hp1" tabindex="14">
				<option value="010"<?php if (($mobile[0]=="070")) { ?> selected<?php } ?>>010</option>
				<option value="011"<?php if (($mobile[0]=="070")) { ?> selected<?php } ?>>011</option>
				<option value="016"<?php if (($mobile[0]=="070")) { ?> selected<?php } ?>>016</option>
				<option value="017"<?php if (($mobile[0]=="070")) { ?> selected<?php } ?>>017</option>
				<option value="018"<?php if (($mobile[0]=="070")) { ?> selected<?php } ?>>018</option>
				<option value="019"<?php if (($mobile[0]=="070")) { ?> selected<?php } ?>>019</option>
						</select>
						-
						<input type="text" id="hp2" name="hp2" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" tabindex="15" maxlength="4" value="<?php echo $mobile[1];?>" />
						-
						<input type="text" id="hp3" name="hp3" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" tabindex="16" maxlength="4" value="<?php echo $mobile[2];?>" />
						<input type="checkbox" id="smsOk" name="smsOk" value="1" class="chk" tabindex="21"<?php if (($member->CheckMessageOption==1)) {
?> checked<?php } ?> />
						SMS 수신동의 </td>
				</tr>
			</table>
			<p class="right b10">
				<input name="missionary" id="missionary" type="checkbox" value="1" class="chk" onclick="checkMission()" tabindex=22"<?php if ($member->Level=="3") {
?> checked<?php } ?> />
				<span class="fc_01 b">선교사일 경우 추가입력</span></p>
			<!-- //write -->
			<!-- 개인정보// -->

		<?php 
	if ($member->Level!="3") {
		$display="none";
		$churchContact = array("","","");
		$ngoContact = array("","","");
		$managerContact = array("","","");
		$managerEmail = array("","");
		$memo="간단한 소개 :".chr(13).chr(13).chr(13)."사역소개 :".chr(13).chr(13).chr(13);
		$prayList="1.".chr(13).chr(13)."2.".chr(13).chr(13)."3.".chr(13).chr(13);
	} else {
		$display="";
		$churchContact=explode("-",$mission->ChurchContact);
		$ngoContact=explode("-",$mission->NgoContact);
		$managerContact=explode("-",$mission->ManagerContact);
		$managerEmail=explode("@",$mission->ManagerEmail);
		$memo = $mission->Memo;
		$prayList = $mission->PrayList;
		$familyList = $mission->Family;
	} 

?>
		<!-- //선교사일 경우 -->
			<p class="b5"	id="imgMission" style="display: <?php echo $display;?>;" /><img src="../images/sub/stit_080102.gif"></p>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_write" id="frmMission" style="display: <?php echo $display;?>;">
				<col width="25%" />
				<col />
				<tr>
					<td class="td01">선교사명</td>
					<td><input type="text" name="missionName" id="missionName" maxlength="30" tabindex="27" value="<?php echo $mission->MissionName;?>" /></td>
				</tr>
				<tr>
					<td class="td01">파송교회</td>
					<td><input type="text" name="church" id="church" maxlength="20" size="30" value="<?php echo $mission->Church;?>" /></td>
				</tr>
				<tr>
					<td class="td01">파송교회 연락처 </td>
					<td>
				<select name="churchContact1" id="churchContact1" tabindex="24">
				<option value="02"<?php if (($churchContact[0]=="02")) { ?> selected<?php } ?>>02</option>
				<option value="031"<?php if (($churchContact[0]=="031")) { ?> selected<?php } ?>>031</option>
				<option value="032"<?php if (($churchContact[0]=="032")) { ?> selected<?php } ?>>032</option>
				<option value="033"<?php if (($churchContact[0]=="033")) { ?> selected<?php } ?>>033</option>
				<option value="041"<?php if (($churchContact[0]=="041")) { ?> selected<?php } ?>>041</option>
				<option value="042"<?php if (($churchContact[0]=="042")) { ?> selected<?php } ?>>042</option>
				<option value="043"<?php if (($churchContact[0]=="043")) { ?> selected<?php } ?>>043</option>
				<option value="051"<?php if (($churchContact[0]=="051")) { ?> selected<?php } ?>>051</option>
				<option value="052"<?php if (($churchContact[0]=="052")) { ?> selected<?php } ?>>052</option>
				<option value="053"<?php if (($churchContact[0]=="053")) { ?> selected<?php } ?>>053</option>
				<option value="054"<?php if (($churchContact[0]=="054")) { ?> selected<?php } ?>>054</option>
				<option value="055"<?php if (($churchContact[0]=="055")) { ?> selected<?php } ?>>055</option>
				<option value="061"<?php if (($churchContact[0]=="061")) { ?> selected<?php } ?>>061</option>
				<option value="062"<?php if (($churchContact[0]=="062")) { ?> selected<?php } ?>>062</option>
				<option value="063"<?php if (($churchContact[0]=="063")) { ?> selected<?php } ?>>063</option>
				<option value="064"<?php if (($churchContact[0]=="064")) { ?> selected<?php } ?>>064</option>
				<option value="070"<?php if (($churchContact[0]=="070")) { ?> selected<?php } ?>>070</option>
						</select>
						-
						<input type="text" name="churchContact2" id="churchContact2" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="4" tabindex="25" value="<?php echo $churchContact[1];?>" />
						-
						<input type="text" name="churchContact3" id="churchContact3" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="4" tabindex="26" value="<?php echo $churchContact[2];?>" />
				</tr>
				<tr>
					<td class="td01">파송선교단체</td>
					<td><input type="text" name="ngo" id="ngo" maxlength="20" size="30" value="<?php echo $mission->Ngo;?>"></td>
				</tr>
				<tr>
					<td class="td01">파송선교단체 연락처 </td>
					<td>
				<select name="ngoContact1" id="ngoContact1" tabindex="24">
				<option value="02"<?php if (($ngoContact[0]=="02")) { ?> selected<?php } ?>>02</option>
				<option value="031"<?php if (($ngoContact[0]=="031")) { ?> selected<?php } ?>>031</option>
				<option value="032"<?php if (($ngoContact[0]=="032")) { ?> selected<?php } ?>>032</option>
				<option value="033"<?php if (($ngoContact[0]=="033")) { ?> selected<?php } ?>>033</option>
				<option value="041"<?php if (($ngoContact[0]=="041")) { ?> selected<?php } ?>>041</option>
				<option value="042"<?php if (($ngoContact[0]=="042")) { ?> selected<?php } ?>>042</option>
				<option value="043"<?php if (($ngoContact[0]=="043")) { ?> selected<?php } ?>>043</option>
				<option value="051"<?php if (($ngoContact[0]=="051")) { ?> selected<?php } ?>>051</option>
				<option value="052"<?php if (($ngoContact[0]=="052")) { ?> selected<?php } ?>>052</option>
				<option value="053"<?php if (($ngoContact[0]=="053")) { ?> selected<?php } ?>>053</option>
				<option value="054"<?php if (($ngoContact[0]=="054")) { ?> selected<?php } ?>>054</option>
				<option value="055"<?php if (($ngoContact[0]=="055")) { ?> selected<?php } ?>>055</option>
				<option value="061"<?php if (($ngoContact[0]=="061")) { ?> selected<?php } ?>>061</option>
				<option value="062"<?php if (($ngoContact[0]=="062")) { ?> selected<?php } ?>>062</option>
				<option value="063"<?php if (($ngoContact[0]=="063")) { ?> selected<?php } ?>>063</option>
				<option value="064"<?php if (($ngoContact[0]=="064")) { ?> selected<?php } ?>>064</option>
				<option value="070"<?php if (($ngoContact[0]=="070")) { ?> selected<?php } ?>>070</option>
						</select>
						-
						<input type="text" name="ngoContact2" id="ngoContact2" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="4" tabindex="25" value="<?php echo $ngoContact[1];?>" />
						-
						<input type="text" name="ngoContact3" id="ngoContact3" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="4" tabindex="26" value="<?php echo $ngoContact[2];?>" />
				</tr>
				<tr>
					<td class="td01">선교지</td>
					<td>
						<select name="nation" id="nation" tabindex="32">
			<?php 
	for ($i=0; $i<=count($codes)-1; $i = $i+1) {
		$codeObj = $codes[$i];
		if (($codeObj->Code == $mission->NationCode)) {
?>
					<option value="<?php echo $codeObj->Code;?>" selected><?php echo $codeObj->Name;?></option>
				<?php 
		} else {
?>
					<option value="<?php echo $codeObj->Code;?>"><?php echo $codeObj->Name;?></option>
				<?php 
		} 


	}

?>
						</select>
			</td>
				</tr>
				<tr>
					<td class="td01">홈페이지 주소 </td>
					<td><input type="text" name="homepage" id="homepage" maxlength="200" size="80" value="<?php echo $mission->Homepage;?>"></td>
				</tr>
				<tr>
					<td class="td01">파송관리자 이름 </td>
					<td><input type="text" name="manager" id="manager" maxlength="20" value="<?php echo $mission->Manager;?>"></td>
				</tr>
				<tr>
					<td class="td01">파송관리자 연락처 </td>
					<td>
				<select name="managerContact1" id="managerContact1" tabindex="24">
				<option value="010"<?php if (($managerContact[0]=="010")) { ?> selected<?php } ?>>010</option>
				<option value="011"<?php if (($managerContact[0]=="011")) { ?> selected<?php } ?>>011</option>
				<option value="016"<?php if (($managerContact[0]=="016")) { ?> selected<?php } ?>>016</option>
				<option value="017"<?php if (($managerContact[0]=="017")) { ?> selected<?php } ?>>017</option>
				<option value="018"<?php if (($managerContact[0]=="018")) { ?> selected<?php } ?>>018</option>
				<option value="019"<?php if (($managerContact[0]=="019")) { ?> selected<?php } ?>>019</option>
				<option value="02"<?php if (($managerContact[0]=="02")) { ?> selected<?php } ?>>02</option>
				<option value="031"<?php if (($managerContact[0]=="031")) { ?> selected<?php } ?>>031</option>
				<option value="032"<?php if (($managerContact[0]=="032")) { ?> selected<?php } ?>>032</option>
				<option value="033"<?php if (($managerContact[0]=="033")) { ?> selected<?php } ?>>033</option>
				<option value="041"<?php if (($managerContact[0]=="041")) { ?> selected<?php } ?>>041</option>
				<option value="042"<?php if (($managerContact[0]=="042")) { ?> selected<?php } ?>>042</option>
				<option value="043"<?php if (($managerContact[0]=="043")) { ?> selected<?php } ?>>043</option>
				<option value="051"<?php if (($managerContact[0]=="051")) { ?> selected<?php } ?>>051</option>
				<option value="052"<?php if (($managerContact[0]=="052")) { ?> selected<?php } ?>>052</option>
				<option value="053"<?php if (($managerContact[0]=="053")) { ?> selected<?php } ?>>053</option>
				<option value="054"<?php if (($managerContact[0]=="054")) { ?> selected<?php } ?>>054</option>
				<option value="055"<?php if (($managerContact[0]=="055")) { ?> selected<?php } ?>>055</option>
				<option value="061"<?php if (($managerContact[0]=="061")) { ?> selected<?php } ?>>061</option>
				<option value="062"<?php if (($managerContact[0]=="062")) { ?> selected<?php } ?>>062</option>
				<option value="063"<?php if (($managerContact[0]=="063")) { ?> selected<?php } ?>>063</option>
				<option value="064"<?php if (($managerContact[0]=="064")) { ?> selected<?php } ?>>064</option>
				<option value="070"<?php if (($managerContact[0]=="070")) { ?> selected<?php } ?>>070</option>
						</select>
						-
						<input type="text" name="managerContact2" id="managerContact2" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="4" tabindex="25" value="<?php echo $managerContact[1];?>" />
						-
						<input type="text" name="managerContact3" id="managerContact3" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="4" tabindex="26" value="<?php echo $managerContact[2];?>" />
			</td>
				</tr>
				<tr>
					<td class="td01"> 파송관리자 E-mail </td>
					<td>
						<input type="text" id="managerEmail1" name="managerEmail1" maxlength="30" tabindex="38" value="<?php echo $managerEmail[0];?>" />
						@
						<input type="text" id="managerEmail2" name="managerEmail2" maxlength="50" tabindex="39" value="<?php echo $managerEmail[1];?>" />
			</td>
				</tr>
				<tr>
					<td class="td01">후원 계좌번호</td>
					<td><input type="text" name="accountNo" id="accountNo" maxlength="30" size="30" value="<?php echo $mission->AccountNo;?>"></td>
				</tr>
				<tr>
					<td class="td01">은행명</td>
					<td><input type="text" name="bank" id="bank" maxlength="30" size="30" value="<?php echo $mission->Bank;?>"></td>
				</tr>
				<tr>
					<td class="td01">예금주</td>
					<td><input type="text" name="accountName" id="accountName" maxlength="20" value="<?php echo $mission->AccountName;?>"></td>
				</tr>
				<tr>
					<td class="td01">기타메모</td>
					<td>
			<textarea name="memo" id="memo" class="b10"><?php echo $memo;?></textarea>
					</td>
				</tr>
				<tr>
					<td class="td01">기도제목</td>
					<td>
			<textarea name="prayList" id="prayList"><?php echo $prayList;?></textarea>
					</td>
				</tr>
				<tr>
					<td class="td01">가족사항</td>
					<td>			
			<table id="tblFamily" name="tbmFamily" width="100%" border="0" cellspacing="0" cellpadding="0" class="board_con">
			<tr><th>이름</th><th>나이</th><th>성별</th><th>관계</th><th>삭제</th></tr>
			<?php 
	$year=substr(time(),0,4);
	if ((strlen($mission->UserId)>0)) {
		for ($num=0; $num<=count($familyList); $num = $num+1) {
			$familyObj = $familyList[$num];
?>
				<tr id="trFamily" align="center">
					<input type="hidden" name="familyId" id="familyId" value="<?php echo $familyObj->familyID;?>" />
					<td><input type="text" name="familyName" id="familyName" style="width:150px" value="<?php echo $familyObj->Name;?>" /></td>
					<td><select name="familyAge" id="familyAge">
					<?php 
			for ($i=0; $i<=99; $i = $i+1) {
				if (((strftime("%Y",-$i)) == $familyObj->Age)) {
?>
							<option value='<?php echo (strftime("%Y",-$i));?>' selected><?php echo ($i+1);?>세, <?php echo (strftime("%Y",-$i));?> </option>
						<?php 
				}
					else
				{
?>
							<option value='<?php echo (strftime("%Y",-$i));?>'><?php echo ($i+1);?>세, <?php echo (strftime("%Y",-$i));?> </option>
						<?php 
				} 


			}

?>
					</select></td>
					<td>
						<select name="familySex" id="familySex">
							<option value="남자"<?			 if (($familyObj->Sex=="남자")) { ?> selected<?			 } ?>>남자</option>
							<option value="여자"<?			 if (($familyObj->Sex=="여자")) { ?> selected<?			 } ?>>여자</option>
						</select>
					</td>
					<td>
						<select name="familyRelation" id="familyRelation">
							<option value="부모"<?			 if (($familyObj->Relation=="부모")) { ?> selected<?			 } ?>>부모</option>
							<option value="배우자"<?			 if (($familyObj->Relation=="배우자")) { ?> selected<?			 } ?>>배우자</option>
							<option value="자녀"<?			 if (($familyObj->Relation=="자녀")) { ?> selected<?			 } ?>>자녀</option>
							<option value="형제"<?			 if (($familyObj->Relation=="형제")) { ?> selected<?			 } ?>>형제</option>
							<option value="기타"<?			 if (($familyObj->Relation=="기타")) { ?> selected<?			 } ?>>기타</option>
						</select>
					</td>
					<td><input type="button" name="btnDelFamily" id="btnDelFamily" value="삭제" style="cursor:pointer;" onclick="deleteFamily(<?php echo $familyObj->familyID;?>)" /></td>
				</tr>
			<?php 

		}

	} else {
?>
				<tr id="trFamily" align="center">
					<input type="hidden" name="familyId" id="familyId" value="-1" />
					<td><input type="text" name="familyName" id="familyName" style="width:150px" /></td>
					<td><select name="familyAge" id="familyAge">
					<?php 
		for ($i=0; $i<=99; $i = $i+1) {
?>
						<option value='<?php echo (strftime("%Y",-$i));?>'><?php echo ($i+1);?>세, <?php echo (strftime("%Y",-$i));?> </option>
					<?php 

		}

?>
					</select></td>
					<td><select name="familySex" id="familySex"><option value="남자">남자</option><option value="여자">여자</option></select></td>
					<td>
						<select name="familyRelation" id="familyRelation">
						<option value="부모">부모</option><option value="배우자">배우자</option>
						<option value="자녀">자녀</option><option value="형제">형제</option>
						<option value="기타">기타</option>
						</select>
					</td>
					<td>&nbsp;</td>
				</tr>
			<?php 
	} 

?>
			</table>
			<input type="hidden" id="familyNum" name="familyNum" value="0">
			<p class="right" valign="absmiddle">
				<input type="button" value=" 추가 " border="0" style="cursor:pointer" onclick='addRelation()' onfocus='this.blur();'>
				<input type="button" value=" 삭제 " border="0" style="cursor:pointer" onclick='DelRelation()' onfocus='this.blur();'>
			</p>
					</td>
				</tr>
				<tr>
					<td class="td01">가족사항 공개여부 </td>
					<td>
			<input name="flagFalily" id="flagFalily" type="radio" value="1" class="chk" checked><span style="padding-right:50px">공개</span>
			<input name="flagFalily" id="flagFalily" type="radio" value="0" class="chk">비공개
					</td>
				</tr>
		<!--tr>
			<td class="td01">사진</td>
			<td height="30">
			<input type="file" name="fileImage" id="fileImage" size="40" tabindex="18" /><br />
			</td>
		</tr-->
			</table>
			<!-- 선교사일경우// -->
			
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td align="center"><input type="button" value="저장" onclick="check()" /></td>
				</tr>
			</table>
		</div>
		<!-- content// -->
<?php } ?>


<script type="text/javascript">
//<![CDATA[
	var familyNum = 1;
	
		function checkNick(nick) {
		var url = 'ajax.php?mode=checkNick&nick='+nick;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: '', onComplete: resultNick]);
	}
	
	function resultNick(reqResult) {
		var addHtml = reqResult.responseText;
		document.getElementById("resultMessage2").innerHTML = addHtml;
	}
	
	function checkNID()	{
		var nid1 = document.getElementById("jumin1").value;
		var nid2 = document.getElementById("jumin2").value;
		var url = 'ajax.php?mode=checkNID&nid1='+nid1+'&nid2='+nid2;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: '', onComplete: resultNID]);
	}
	
	function resultNID(reqResult) {
		var addHtml = reqResult.responseText;
		document.getElementById("resultMessage4").innerHTML = addHtml;
	}

	function checkMission() {
		if (document.getElementById("missionary").checked) {
			document.getElementById("frmMission").style.display = "";
			document.getElementById("imgMission").style.display = "";
		} else {
			document.getElementById("frmMission").style.display = "none";
			document.getElementById("imgMission").style.display = "none";
		}
	}
	
	function check() {
		if (document.getElementById("missionary").checked && !checkMissionary()) {
			return;
		}
		
		if (document.getElementById("nickName").value == "") {
			alert("닉네임을 입력해주세요.");
			document.getElementById("nickName").focus();
			return;
		}
		if (document.getElementById("name").value == "") {
			alert("이름을 입력해주세요.");
			document.getElementById("name").focus();
			return;
		}
		/*
		if (document.getElementById("password").value == "" || 
			document.getElementById("password").value != document.getElementById("password_confirm").value) {
			alert("비밀번호를 확인해주세요.");
			document.getElementById("password").focus();
			return;
		}
		*/
// 		if (document.getElementById("jumin1").value == "" || document.getElementById("jumin1").value.length < 6 || 
// 			document.getElementById("jumin2").value == "" || document.getElementById("jumin2").value.length < 7) {
// 			alert("주민등록번호를 확인해주세요.");
// 			document.getElementById("jumin1").select();
// 			return;
// 		}
		if (document.getElementById("email1").value == "" || document.getElementById("email2").value == "") {
			alert("이메일 주소를 확인해주세요.");
			document.getElementById("email1").select();
			return;
		}

		if (document.getElementById("post1").value == "" || 
			document.getElementById("post2").value == "" || 
			document.getElementById("addr1").value == "") {
			alert("우편번호 찾기를 이용해주세요.");
			document.getElementById("post1").focus();
			return;
		}
		
		if (document.getElementById("addr2").value == "") {
			alert("상세주소를 입력해주세요.");
			document.getElementById("addr2").focus();
			return;
		}
		
		if (document.getElementById("missionary").checked) {
			if (document.getElementById("missionName").value == "") {
				alert("선교사명을 입력해주세요.");
				document.getElementById("missionName").focus();
				return;
			}
			if (document.getElementById("nation").value == "") {
				alert("선교지를 입력해주세요.");
				document.getElementById("nation").focus();
				return;
			}
		}
		
		document.getElementById("dataForm").action="process.php";
		document.getElementById("dataForm").submit();
	}
	
	function checkMissionary() {
		return true;
	}
		
	function addRelation() {
		var tblObj = document.getElementById("tblFamily");
		var index = (tblObj.rows.length - 1)
 
		var indexLimit = 10;
		var limitMessage = "가족은";
		
		if(index >= indexLimit) {
			alert(limitMessage + " 최대 " + indexLimit + "항목입니다.");
			return;
		}
		
		// explorer 에서만 사용되는 코드 tBodies
		var trObj = document.getElementById("trFamily");
		var newTr = trObj.cloneNode(true);
		var objs = newTr.getElementsByTagName("input");
		var obj = objs[0];
		obj.value = '';
		var bodyObj = tblObj.tBodies[0];
		bodyObj.appendChild(newTr);
	}
	
	function ReplaceIndex(obj, index) {
		var objs = obj.getElementsByTagName("input");

		for(var i = objs.length - 1; i >= 0; i--) {
			var obj = objs[i];
			var newObj = document.createElement(obj.outerHTML);
			newObj.value = '';
			obj.replaceNode(newObj);
		}
 
		var objs = obj.getElementsByTagName("textarea");
		for(var i = objs.length - 1; i >= 0; i--) {
			var obj = objs[i];
			var newObj = document.createElement(obj.outerHTML);
			obj.replaceNode(newObj);
		}
 
		var objs = obj.getElementsByTagName("select");
		for(var i = objs.length - 1; i >= 0; i--) {
			var obj = objs[i];
			var newObj = document.createElement(obj.outerHTML);
			var optionLength = parseInt(obj.options.length, 10);
			for(var j = 0; j < optionLength; j++) {
				newObj.appendChild(obj.options[0]);
			}
			newObj.selectedIndex = 0;
			obj.replaceNode(newObj);
		}
	}

	function DelRelation() {
		var tblObj = document.getElementById("tblFamily");
		var deleteLimit = 1;
		
		if((tblObj.rows.length - 1) <= deleteLimit) {
			alert("삭제할 행이 없습니다\r\n기존 항목 삭제는 관계 컬럼의 삭제버튼을 이용해 주세요");
			return;
		}
		
		if(tblObj.rows.length > 1) {
			tblObj.deleteRow(tblObj.rows.length - 1);
		} else {
			alert("삭제할 행이 없습니다");
		}
	}
	
	function deleteFamily(familyId) {
		location.href = "process.php?mode=deleteFamily&userLv=<?php echo $userLv;?>&userId=<?php echo $userId;?>&familyId=" + familyId;
	}
//]]>
</script>

