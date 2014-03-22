<?
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
//***************************************************************
// member edit page
//
// last update date : 2009.12.28
// updated by blackdew
// To do List
//	 - 비밀번호 변경하는 페이지는 따로 추가해야 함
//	 - 자바 스크립트 추가 & update process 진행
//***************************************************************
checkUserLogin();

$m_Helper = new MemberHelper();
$member = $m_Helper->getMemberByuserid($_SESSION["userid"]);
$account = $m_Helper->getAccountInfoByuserid($_SESSION["userid"]);
$mission = $m_Helper->getMissionInfoByuserid($_SESSION["userid"]);

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
	global $member, $account, $mission;
	global $codes;
?>
		<!-- //content -->
	<!-- //정보 -->
		<div id="content">
		<div class="mypage b20">
			<p class="hi"><strong><?=$member->Name?></strong>님, 안녕하세요</p>
		<ul class="txt01">
			<li><strong>회원ID</strong> <?=$member->userid?></li>
			<li class="btn">
			<img src="../images/sub/btn_out.gif" onclick="clickTopNavi(10)" class="r5">
			<img src="../images/sub/btn_logout.gif" onclick="clickTopNavi(4)" class="r5">
			</li>
		</ul>
		<ul class="txt02">
			<li class="tit"><?=$account->Method?></li>
			<li>은행 : <?=$account->Bank?></li>
			<li>예금주 : <?=$account->Name?></li>
			<li>계좌번호 : <?=$account->Number?></li>
			<li>이체일 : <?=$account->SendDate?> 일</li>
		</ul> 
		</div>
		<!-- 정보// -->
		<!-- //개인정보 -->
			<p class="b5"><img src="../images/sub/stit_080101.gif"></p>
			<form name="dataForm" id="dataForm" method="post">
			<input type="hidden" id="mode" name="mode" value="editUser" />
			<input type="hidden" id="userid" name="userid" value="<?=$member->userid?>" />
			<input type="hidden" id="level" name="level" value="<?=$member->UserLevel?>" />
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_write">
				<col width="25%" />
				<col />
				<tr>
					<td class="td01">아이디</td>
					<td><?=$member->userid?></td>
				</tr>
				<tr id="trpw_prev">
					<td class="td01">비밀번호</td>
					<td>
						<span class="btn1g"><a href="javascript:void(0)" onclick="change_passwd(true)">비밀번호 변경</a></span>
					</td>
				</tr>
				<tr id="trpw" style="display:none;">
					<td class="td01">비밀번호<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<input type="password" id="password" name="password" style="ime-mode:disabled" maxlength="20" tabindex="19" />&nbsp;&nbsp;
						<span class="btn1g"><a href="javascript:void(0)" onclick="change_passwd(false)">변경하지 않기</a></span>
					</td>
				</tr>
				<tr id="trpw_conf" style="display:none;">
					<td class="td01">비밀번호확인<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<input type="password" id="password_confirm" name="password_confirm" style="ime-mode:disabled" maxlength="20" tabindex="20" onkeyup="checkPassword();" />
						<label class="fs11" type="text" name='resultMessage5' id='resultMessage5'></label>
					</td>
				</tr>
				<tr>
					<td class="td01">닉네임<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<input type="text" name="nickName" id="nickName" maxlength="30" tabindex="1" value="<?=$member->Nick?>" onkeyup="checkNick(this.value);" />
						<label class="fs11" type="text" name='resultMessage2' id='resultMessage2'></label>
					</td>
				</tr>
				<tr>
					<td class="td01">이름<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<input type="text" name="name" id="name" maxlength="30" tabindex="2" value="<?=$member->Name?>" />
						<label class="fs11" type="text" name='resultMessage3' id='resultMessage3'>(공백없이 한글만 입력가능)</label>
					</td>
				</tr>
				<tr>
					<td class="td01"> E-mail<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<input type="text" name="email1" id="email1" style="ime-mode:disabled" tabindex="5" maxlength="30" value="<?=$member->Email[0]?>" />
						@
						<input type="text" name="email2" id="email2" style="ime-mode:disabled" tabindex="6" maxlength="50" value="<?=$member->Email[1]?>" />
					</td>
				</tr>
				<tr>
					<td class="td01">우편번호<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<input type="text" id="post1" name="post1" style="width:50px" readonly onclick="PostPopup();" tabindex="7" value="<?=$member->Zipcode[0]?>" />
						-
						<input type="text" id="post2" name="post2" style="width:50px" readonly onclick="PostPopup();" tabindex="8" value="<?=$member->Zipcode[1]?>" />
						<img src="../images/board/btn_zipcode.gif" border="0" align="absmiddle" class="m2" onclick="PostPopup();" style="cursor:hand;">
					</td>
				</tr>
				<tr>
					<td class="td01">주소<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<input type="text" name="addr1" id="addr1" style="width:80%" tabindex="9" readonly onclick="PostPopup();" value="<?=$member->Address1?>" />
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input type="text" name="addr2" id="addr2" style="width:50%" tabindex="10" value="<?=$member->Address2?>" />
					</td>
				</tr>
				<tr>
					<td class="td01">전화번호</td>
					<td>
						<select id="tel1" name="tel1" tabindex="11">
							<option value="02"<? if ($member->Phone[0] == "02") { ?> selected<? } ?>>02</option>
							<option value="031"<? if ($member->Phone[0] == "031") { ?> selected<? } ?>>031</option>
							<option value="032"<? if ($member->Phone[0] == "032") { ?> selected<? } ?>>032</option>
							<option value="033"<? if ($member->Phone[0] == "033") { ?> selected<? } ?>>033</option>
							<option value="041"<? if ($member->Phone[0] == "041") { ?> selected<? } ?>>041</option>
							<option value="042"<? if ($member->Phone[0] == "042") { ?> selected<? } ?>>042</option>
							<option value="043"<? if ($member->Phone[0] == "043") { ?> selected<? } ?>>043</option>
							<option value="051"<? if ($member->Phone[0] == "051") { ?> selected<? } ?>>051</option>
							<option value="052"<? if ($member->Phone[0] == "052") { ?> selected<? } ?>>052</option>
							<option value="053"<? if ($member->Phone[0] == "053") { ?> selected<? } ?>>053</option>
							<option value="054"<? if ($member->Phone[0] == "054") { ?> selected<? } ?>>054</option>
							<option value="055"<? if ($member->Phone[0] == "055") { ?> selected<? } ?>>055</option>
							<option value="061"<? if ($member->Phone[0] == "061") { ?> selected<? } ?>>061</option>
							<option value="062"<? if ($member->Phone[0] == "062") { ?> selected<? } ?>>062</option>
							<option value="063"<? if ($member->Phone[0] == "063") { ?> selected<? } ?>>063</option>
							<option value="064"<? if ($member->Phone[0] == "064") { ?> selected<? } ?>>064</option>
							<option value="070"<? if ($member->Phone[0] == "070") { ?> selected<? } ?>>070</option>
						</select>
						-
						<input type="text" id="tel2" name="tel2" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" tabindex="12" maxlength="4" value="<?=$member->Phone[1]?>" />
						-
						<input type="text" id="tel3" name="tel3" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" tabindex="13" maxlength="4" value="<?=$member->Phone[2]?>" />
					</td>
				</tr>
				<tr>
					<td class="td01">휴대폰번호</td>
					<td>
						<select id="hp1" name="hp1" tabindex="14">
							<option value="010"<? if ($member->Mobile[0] == "070") { ?> selected<? } ?>>010</option>
							<option value="011"<? if ($member->Mobile[0] == "070") { ?> selected<? } ?>>011</option>
							<option value="016"<? if ($member->Mobile[0] == "070") { ?> selected<? } ?>>016</option>
							<option value="017"<? if ($member->Mobile[0] == "070") { ?> selected<? } ?>>017</option>
							<option value="018"<? if ($member->Mobile[0] == "070") { ?> selected<? } ?>>018</option>
							<option value="019"<? if ($member->Mobile[0] == "070") { ?> selected<? } ?>>019</option>
						</select>
						-
						<input type="text" id="hp2" name="hp2" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" tabindex="15" maxlength="4" value="<?=$member->Mobile[1]?>" />
						-
						<input type="text" id="hp3" name="hp3" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" tabindex="16" maxlength="4" value="<?=$member->Mobile[2]?>" />
						<input type="checkbox" id="smsOk" name="smsOk" value="1" class="chk" tabindex="17"<? if ($member->CheckMessageOption == 1) {?> checked<? } ?> />
						SMS 수신동의 
					</td>
				</tr>
			</table>
			<p class="right b10">
				<input name="missionary" id="missionary" type="checkbox" value="1" class="chk" onclick="checkMission()" tabindex="18"<? if ($member->Level=="3") {
?> checked<? } ?> />
				<span class="fc_01 b">선교사일 경우 추가입력</span></p>
			<!-- //write -->
			<!-- 개인정보// -->

		<? 
	if ($member->userLv!="3") {
		$display="none";
		$churchContact = array("","","");
		$managerContact = array("","","");
		$memo="간단한 소개 :".chr(13).chr(13).chr(13)."사역소개 :".chr(13).chr(13).chr(13);
		$prayList="1.".chr(13).chr(13)."2.".chr(13).chr(13)."3.".chr(13).chr(13);
	} else {
		$display="";
		$churchContact=explode("-",$mission->ChurchContact);
		$managerContact=explode("-",$mission->ManagerContact);
		$memo = $mission->Memo;
		$prayList = $mission->PrayList;
		$familyList = $mission->Family;
	}
?>
		<!-- //선교사일 경우 -->
			<p class="b5"	id="imgMission" style="display: <?=$display?>;" /><img src="../images/sub/stit_080102.gif"></p>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_write" id="frmMission" style="display: <?=$display?>;">
				<col width="25%" />
				<col />
				<tr>
					<td class="td01">선교사명</td>
					<td><input type="text" name="missionName" id="missionName" maxlength="30" tabindex="27" value="<?=$mission->MissionName?>" /></td>
				</tr>
				<tr>
					<td class="td01">출생년도</span></td>
					<td>
						<select name="birth_year" id="birth_year" tabindex="25">
<?
	for ($i = 0; $i <= 99; $i++) {
		if ($mission->BirthYear == (date('Y') - $i)) {
			print "<option value='".(date('Y') - $i)."' selected>".(date('Y') - $i)." </option>";			
		} else {
			print "<option value='".(date('Y') - $i)."'>".(date('Y') - $i)." </option>";
		}
	}
?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="td01">파송년도</span></td>
					<td>
						<select name="sent_year" id="sent_year" tabindex="25">
<?
	for ($i = 0; $i <= 99; $i++) {
		if ($mission->SentYear == (date('Y') - $i)) {
			print "<option value='".(date('Y') - $i)."' selected>".(date('Y') - $i)." </option>";			
		} else {
			print "<option value='".(date('Y') - $i)."'>".(date('Y') - $i)." </option>";
		}
	}
?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="td01">파송기관(교회)</td>
					<td><input type="text" name="church" id="church" maxlength="20" size="30" value="<?=$mission->Church?>" /></td>
				</tr>
				<tr>
					<td class="td01">파송기관(교회) 연락처 </td>
					<td>
						<select name="churchContact1" id="churchContact1" tabindex="24">
						<option value="02"<? if (($churchContact[0]=="02")) { ?> selected<? } ?>>02</option>
						<option value="031"<? if (($churchContact[0]=="031")) { ?> selected<? } ?>>031</option>
						<option value="032"<? if (($churchContact[0]=="032")) { ?> selected<? } ?>>032</option>
						<option value="033"<? if (($churchContact[0]=="033")) { ?> selected<? } ?>>033</option>
						<option value="041"<? if (($churchContact[0]=="041")) { ?> selected<? } ?>>041</option>
						<option value="042"<? if (($churchContact[0]=="042")) { ?> selected<? } ?>>042</option>
						<option value="043"<? if (($churchContact[0]=="043")) { ?> selected<? } ?>>043</option>
						<option value="051"<? if (($churchContact[0]=="051")) { ?> selected<? } ?>>051</option>
						<option value="052"<? if (($churchContact[0]=="052")) { ?> selected<? } ?>>052</option>
						<option value="053"<? if (($churchContact[0]=="053")) { ?> selected<? } ?>>053</option>
						<option value="054"<? if (($churchContact[0]=="054")) { ?> selected<? } ?>>054</option>
						<option value="055"<? if (($churchContact[0]=="055")) { ?> selected<? } ?>>055</option>
						<option value="061"<? if (($churchContact[0]=="061")) { ?> selected<? } ?>>061</option>
						<option value="062"<? if (($churchContact[0]=="062")) { ?> selected<? } ?>>062</option>
						<option value="063"<? if (($churchContact[0]=="063")) { ?> selected<? } ?>>063</option>
						<option value="064"<? if (($churchContact[0]=="064")) { ?> selected<? } ?>>064</option>
						<option value="070"<? if (($churchContact[0]=="070")) { ?> selected<? } ?>>070</option>
						</select>
						-
						<input type="text" name="churchContact2" id="churchContact2" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="4" tabindex="25" value="<?=$churchContact[1]?>" />
						-
						<input type="text" name="churchContact3" id="churchContact3" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="4" tabindex="26" value="<?=$churchContact[2]?>" />
				</tr>
				<tr>
					<td class="td01">선교지</td>
					<td>
						<select name="nation" id="nation" tabindex="32">
<? 
	for ($i=0; $i<=count($codes)-1; $i = $i+1) {
		$codeObj = $codes[$i];
		if (($codeObj->Code == $mission->NationCode)) { ?>
					<option value="<?=$codeObj->Code?>" selected><?=$codeObj->Name?></option>
<?
		} else {
?>
					<option value="<?=$codeObj->Code?>"><?=$codeObj->Name?></option>
<? 
		}
	}
?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="td01">국내 연락처 </td>
					<td>
						<select name="managerContact1" id="managerContact1" tabindex="24">
						<option value="010"<? if (($managerContact[0]=="010")) { ?> selected<? } ?>>010</option>
						<option value="011"<? if (($managerContact[0]=="011")) { ?> selected<? } ?>>011</option>
						<option value="016"<? if (($managerContact[0]=="016")) { ?> selected<? } ?>>016</option>
						<option value="017"<? if (($managerContact[0]=="017")) { ?> selected<? } ?>>017</option>
						<option value="018"<? if (($managerContact[0]=="018")) { ?> selected<? } ?>>018</option>
						<option value="019"<? if (($managerContact[0]=="019")) { ?> selected<? } ?>>019</option>
						<option value="02"<? if (($managerContact[0]=="02")) { ?> selected<? } ?>>02</option>
						<option value="031"<? if (($managerContact[0]=="031")) { ?> selected<? } ?>>031</option>
						<option value="032"<? if (($managerContact[0]=="032")) { ?> selected<? } ?>>032</option>
						<option value="033"<? if (($managerContact[0]=="033")) { ?> selected<? } ?>>033</option>
						<option value="041"<? if (($managerContact[0]=="041")) { ?> selected<? } ?>>041</option>
						<option value="042"<? if (($managerContact[0]=="042")) { ?> selected<? } ?>>042</option>
						<option value="043"<? if (($managerContact[0]=="043")) { ?> selected<? } ?>>043</option>
						<option value="051"<? if (($managerContact[0]=="051")) { ?> selected<? } ?>>051</option>
						<option value="052"<? if (($managerContact[0]=="052")) { ?> selected<? } ?>>052</option>
						<option value="053"<? if (($managerContact[0]=="053")) { ?> selected<? } ?>>053</option>
						<option value="054"<? if (($managerContact[0]=="054")) { ?> selected<? } ?>>054</option>
						<option value="055"<? if (($managerContact[0]=="055")) { ?> selected<? } ?>>055</option>
						<option value="061"<? if (($managerContact[0]=="061")) { ?> selected<? } ?>>061</option>
						<option value="062"<? if (($managerContact[0]=="062")) { ?> selected<? } ?>>062</option>
						<option value="063"<? if (($managerContact[0]=="063")) { ?> selected<? } ?>>063</option>
						<option value="064"<? if (($managerContact[0]=="064")) { ?> selected<? } ?>>064</option>
						<option value="070"<? if (($managerContact[0]=="070")) { ?> selected<? } ?>>070</option>
						</select>
						-
						<input type="text" name="managerContact2" id="managerContact2" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="4" tabindex="25" value="<?=$managerContact[1]?>" />
						-
						<input type="text" name="managerContact3" id="managerContact3" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="4" tabindex="26" value="<?=$managerContact[2]?>" />
					</td>
				</tr>
				<tr>
					<td class="td01">후원 계좌번호</td>
					<td><input type="text" name="accountNo" id="accountNo" maxlength="30" size="30" value="<?=$mission->AccountNo?>"></td>
				</tr>
				<tr>
					<td class="td01">은행명</td>
					<td><input type="text" name="bank" id="bank" maxlength="30" size="30" value="<?=$mission->Bank?>"></td>
				</tr>
				<tr>
					<td class="td01">예금주</td>
					<td><input type="text" name="accountName" id="accountName" maxlength="20" value="<?=$mission->AccountName?>"></td>
				</tr>
				<tr>
					<td class="td01">기타메모</td>
					<td>
						<textarea name="memo" id="memo" class="b10"><?=$memo?></textarea>
					</td>
				</tr>
				<tr>
					<td class="td01">기도제목</td>
					<td>
						<textarea name="prayList" id="prayList"><?=$prayList?></textarea>
					</td>
				</tr>
				<!--tr>
					<td class="td01">가족사항</td>
					<td>			
			<table id="tblFamily" name="tbmFamily" width="100%" border="0" cellspacing="0" cellpadding="0" class="board_con">
			<tr><th>이름</th><th>나이</th><th>성별</th><th>관계</th><th>삭제</th></tr>
<? 
	if (strlen($mission->userid) > 0) {
		foreach ($familyList as $familyObj) {
?>
				<tr id="trFamily" align="center">
					<input type="hidden" name="familyId[]" id="familyId[]" value="<?=$familyObj->id?>" />
					<td><input type="text" name="familyName[]" id="familyName[]" style="width:150px" value="<?=$familyObj->Name?>" /></td>
					<td><select name="familyAge[]" id="familyAge[]">
<? 
			for ($i = 0; $i<=99; $i++) {
				if ((date('Y') - $i) == $familyObj->Age) {
?>
							<option value='<?=(date('Y') - $i)?>' selected><?=($i + 1)?>세, <?=(date('Y') - $i)?> </option>
<? 
				} else {
?>
							<option value='<?=(date('Y') - $i)?>'><?=($i + 1)?>세, <?=(date('Y') - $i)?> </option>
<? 
				} 
			}
?>
					</select></td>
					<td>
						<select name="familySex[]" id="familySex[]">
							<option value="남자"<? if (($familyObj->Sex=="남자")) { ?> selected<? } ?>>남자</option>
							<option value="여자"<? if (($familyObj->Sex=="여자")) { ?> selected<? } ?>>여자</option>
						</select>
					</td>
					<td>
						<select name="familyRelation[]" id="familyRelation[]">
							<option value="부모"<? if (($familyObj->Relation=="부모")) { ?> selected<?	 } ?>>부모</option>
							<option value="배우자"<? if (($familyObj->Relation=="배우자")) { ?> selected<? } ?>>배우자</option>
							<option value="자녀"<? if (($familyObj->Relation=="자녀")) { ?> selected<? } ?>>자녀</option>
							<option value="형제"<? if (($familyObj->Relation=="형제")) { ?> selected<? } ?>>형제</option>
							<option value="기타"<? if (($familyObj->Relation=="기타")) { ?> selected<? } ?>>기타</option>
						</select>
					</td>
					<td><input type="button" name="btnDelFamily" id="btnDelFamily" value="삭제" style="cursor:pointer;" onclick="deleteFamily(<?=$familyObj->familyID?>)" /></td>
				</tr>
			<? 

		}

	} else {
?>
				<tr id="trFamily" align="center">
					<input type="hidden" name="familyId" id="familyId" value="-1" />
					<td><input type="text" name="familyName" id="familyName" style="width:150px" /></td>
					<td><select name="familyAge" id="familyAge">
<? 
	for ($i = 0; $i <= 99; $i++) {
		print "<option value='".(date('Y') - $i)."'>".($i + 1)."세, ".(date('Y') - $i)." </option>";

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
			<? 
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
				</tr-->
				<tr>
					<td class="td01">선교사 증명</td>
					<td>
						<input type="button" name="fileUpload" id="fileUpload" value=" 파일 업로드 " onclick="uploadFile(event, 'missionFile', 'mission')" style="cursor:pointer" />
						<input type="hidden" name="idmissionFile" id="idmissionFile" value="" />
						<input type="text" name="txtmissionFile" id="txtmissionFile" value="<?=$mission->attachFileName?>" size="80" readonly /> <br />
						* fax(0505-911-0811), 혹은 email(godlovehouse@nate.com)로 보내주셔도 됩니다.
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
					<td align="center"><span class="btn1g"><a href="javascript:void(0)" onclick="check()">저장</a></span></td>
				</tr>
			</table>
		</div>
		<!-- content// -->
<? } ?>


<script type="text/javascript">
//<![CDATA[
	var familyNum = 1;
	
	function checkNick(nick) {
		var url = 'ajax.php?mode=checkNick&nick='+nick;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: '', onComplete: resultNick});
	}
	
	function resultNick(reqResult) {
		var addHtml = reqResult.responseText;
		document.getElementById("resultMessage2").innerHTML = addHtml;
	}
	
	function checkNID()	{
		var nid1 = document.getElementById("jumin1").value;
		var nid2 = document.getElementById("jumin2").value;
		var url = 'ajax.php?mode=checkNID&nid1='+nid1+'&nid2='+nid2;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: '', onComplete: resultNID});
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
		
		if (document.getElementById("password").value != "" && 
			document.getElementById("password").value != document.getElementById("password_confirm").value) {
			alert("비밀번호를 확인해주세요.");
			document.getElementById("password").focus();
			return;
		}
		
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

	function checkPassword()	{
		var pw1 = document.getElementById("password").value;
		var pw2 = document.getElementById("password_confirm").value;
		var url = 'ajax.php?mode=checkPassword&pw1='+pw1+'&pw2='+pw2;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: '', onComplete: resultPassword});
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
		
		if ((tblObj.rows.length - 1) <= deleteLimit) {
			alert("삭제할 행이 없습니다. 기존 항목 삭제는 관계 컬럼의 삭제버튼을 이용해 주세요");
			return;
		}
		
		if (tblObj.rows.length > 1) {
			tblObj.deleteRow(tblObj.rows.length - 1);
		} else {
			alert("삭제할 행이 없습니다");
		}
	}
	
	function deleteFamily(familyId) {
		location.href = "process.php?mode=deleteFamily&userLv=<?=$userLv?>&userid=<?=$userid?>&familyId=" + familyId;
	}
	
	function change_passwd(flag) {
		if (flag) {
			document.getElementById("trpw_prev").style.display = "none";
			document.getElementById("trpw").style.display = "";
			document.getElementById("trpw_conf").style.display = "";
		} else {
			document.getElementById("trpw_prev").style.display = "";
			document.getElementById("trpw").style.display = "none";
			document.getElementById("trpw_conf").style.display = "none";
			document.getElementById("password").value = "";
			document.getElementById("password_confirm").value = "";
		}
	}
//]]>
</script>

