<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
$c_Helper = new CodeHelper();
$codes = $c_Helper->getNationCodeList();

showHeader("HOME > 멤버쉽 > 회원가입","member","tit_0701.gif");
body();
showFooter();

function body() {
?>
		<!-- //content -->
		<div id="content">
			<p class="b5">* <img src="../images/sub/member_txt_01.gif"></p>
			<!-- //write -->
	<form name="dataForm" id="dataForm" method="post">
	<input type="hidden" id="field" name="field" value="<?php echo $field;?>" />
	<input type="hidden" id="keyword" name="keyword" value="<?php echo $keyword;?>" />
	<input type="hidden" id="gotoPage" name="gotoPage" value="<?php echo $gotoPage;?>" />
	<input type="hidden" id="mode" name="mode" value="editUser" />
	<input type="hidden" id="level" name="level" value="1" />
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_write">
				<col width="26%" />
				<col />
				<tr>
			<td class="td01">아이디<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<input type="text" name="userId" id="userId" style="ime-mode:disabled" maxlength="30" tabindex="1" onkeyup="checkUserId(this.value);" />
			<label class="fs11" type="text" name='resultMessage1' id='resultMessage1'></label>
					</td>
				</tr>
				<tr>
					<td class="td01">닉네임<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<input type="text" name="nickName" id="nickName" maxlength="30" tabindex="2" onkeyup="checkNick(this.value);" />
			<label class="fs11" type="text" name='resultMessage2' id='resultMessage2'></label>
					</td>
				</tr>
				<tr>
					<td class="td01">이름<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<input type="text" name="name" id="name" maxlength="30" tabindex="2" />
			<label class="fs11" type="text" name='resultMessage3' id='resultMessage3'>(공백없이 한글만 입력가능)</label>
					</td>
				</tr>
				<!--tr>
					<td class="td01">주민등록번호<span class="form-required" title="이 항목은 반드시 입력해야 합니다."></span></td>
					<td>
						<input type="text" name="jumin1" id="jumin1" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="6" style="width:100px" tabindex="3" />
						-
						<input type="password" name="jumin2" id="jumin2" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="7" style="width:100px" tabindex="4" />
			<label class="fs11" type="text" name='resultMessage4' id='resultMessage4'>(소득공제필요시 전체입력)</label>
					</td>
				</tr-->
				<tr>
					<td class="td01">비밀번호<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<input type="password" id="password" name="password" style="ime-mode:disabled" maxlength="20" tabindex="5" />
					</td>
				</tr>
				<tr>
					<td class="td01">비밀번호확인<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<input type="password" id="password_confirm" name="password_confirm" style="ime-mode:disabled" maxlength="20" tabindex="6" onkeyup="checkPassword();" />
			<label class="fs11" type="text" name='resultMessage5' id='resultMessage5'></label>
					</td>
				</tr>
				<tr>
					<td class="td01">비밀번호 분실시 질문<span class="form-required" title=""></span> </td>
					<td>
						<select id="password_quest" name="password_quest" tabindex="7">
							<option value="">질문을 선택해 주세요. </option>
				<option value="1" >내 초등학교때 짝꿍은?</option>
				<option value="2">내가 졸업한 초등학교는?</option>
				<option value="3">내가 졸업한 중학교는?</option>
				<option value="4">내가 졸업한 고등학교는?</option>
				<option value="5">가장 친한 친구의 이름은?</option>
				<option value="6">가장 친한 친구의 별명은?</option>
				<option value="7">내 아버지 성함은?</option>
				<option value="8">내 어머니 성함은?</option>
				<option value="9">내 외삼촌 성함은?</option>
				<option value="10">내 외할아버님 성함은?</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="td01">비밀번호 분실시 답변<span class="form-required" title=""></span> </td>
					<td>
						<input type="text" id="password_answer" name="password_answer" style="width:80%" maxlength="50" tabindex="8" />
					</td>
				</tr>
				<tr>
					<td class="td01"> E-mail<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span> </td>
					<td>
						<input type="text" id="email1" name="email1" style="ime-mode:disabled" maxlength="30" tabindex="9" />
						@
						<input type="text" id="email2" name="email2" style="ime-mode:disabled" maxlength="50" tabindex="10" />
					</td>
				</tr>
				<tr>
					<td class="td01">우편번호<span class="form-required" title=""></span></td>
					<td>
						<input type="text" id="post1" name="post1" style="width:50px" readonly onclick="PostPopup();" tabindex="11" />
						-
						<input type="text" id="post2" name="post2" style="width:50px" readonly onclick="PostPopup();" tabindex="12" />
						<img src="../images/board/btn_zipcode.gif" border="0" align="absmiddle" class="m2" onclick="PostPopup();" />
			</td>
				</tr>
				<tr>
					<td class="td01">주소<span class="form-required" title=""></span></td>
					<td>
						<input type="text" name="addr1" id="addr1" style="width:80%" tabindex="13" readonly onclick="PostPopup();" />
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input type="text" name="addr2" id="addr2" style="width:50%" tabindex="14" />
					</td>
				</tr>
				<tr>
					<td class="td01">전화번호</td>
					<td>
						<select id="tel1" name="tel1" tabindex="15">
				<option value="02">02</option>
				<option value="031">031</option>
				<option value="032">032</option>
				<option value="033">033</option>
				<option value="041">041</option>
				<option value="042">042</option>
				<option value="043">043</option>
				<option value="051">051</option>
				<option value="052">052</option>
				<option value="053">053</option>
				<option value="054">054</option>
				<option value="055">055</option>
				<option value="061">061</option>
				<option value="062">062</option>
				<option value="063">063</option>
				<option value="064">064</option>
				<option value="070">070</option>
						</select>
						-
						<input type="text" id="tel2" name="tel2" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" tabindex="16" maxlength="4" />
						-
						<input type="text" id="tel3" name="tel3" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" tabindex="17" maxlength="4" />
					</td>
				</tr>
				<tr>
					<td class="td01">휴대폰번호</td>
					<td>
						<select id="hp1" name="hp1" tabindex="18">
				<option value="010">010</option>
				<option value="011">011</option>
				<option value="016">016</option>
				<option value="017">017</option>
				<option value="018">018</option>
				<option value="019">019</option>
						</select>
						-
						<input type="text" id="hp2" name="hp2" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" tabindex="19" maxlength="4" />
						-
						<input type="text" id="hp3" name="hp3" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" tabindex="20" maxlength="4" />
						<input type="checkbox" id="smsOk" name="smsOk" value="1" class="chk" tabindex="21" />
						SMS 수신동의 </td>
				</tr>
			</table>
			<!-- write// -->
			<p class="right b10">
				<input name="missionary" id="missionary" type="checkbox" value="1" class="chk" onclick="checkMission()" tabindex="22" />
				<span class="fc_01 b">선교사일 경우 추가입력</span></p>
			<!-- //write -->
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_write" id="frmMission" style="visibility:hidden;display: none;">
				<col width="25%" />
				<col />
				<tr><td colspan="2"><p class="b5"><img src="../images/sub/member_txt_02.gif"></p></td></tr>
				<tr>
					<td class="td01">선교사명<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<input type="text" name="missionName" id="missionName" maxlength="30" tabindex="27" />
					</td>
				</tr>
				<tr>
					<td class="td01">파송교회<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<input type="text" name="church" id="church" maxlength="30" tabindex="23" /> (* 파송교회 혹은 파송선교단체를 꼭 입력해야 합니다.)
					</td>
				</tr>
				<tr>
					<td class="td01">파송교회 연락처<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<select name="churchContact1" id="churchContact1" tabindex="24">
				<option value="02">02</option>
				<option value="031">031</option>
				<option value="032">032</option>
				<option value="033">033</option>
				<option value="041">041</option>
				<option value="042">042</option>
				<option value="043">043</option>
				<option value="051">051</option>
				<option value="052">052</option>
				<option value="053">053</option>
				<option value="054">054</option>
				<option value="055">055</option>
				<option value="061">061</option>
				<option value="062">062</option>
				<option value="063">063</option>
				<option value="064">064</option>
				<option value="070">070</option>
						</select>
						-
						<input type="text" name="churchContact2" id="churchContact2" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="4" tabindex="25" />
						-
						<input type="text" name="churchContact3" id="churchContact3" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="4" tabindex="26" />
					</td>
				</tr>
				<tr>
					<td class="td01">파송선교단체<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<input type="text" name="ngo" id="ngo" maxlength="30" tabindex="28" /> (* 파송교회 혹은 파송선교단체를 꼭 입력해야 합니다.)
					</td>
				</tr>
				<tr>
					<td class="td01">파송선교단체 연락처<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<select name="ngoContact1" id="ngoContact1" tabindex="29">
				<option value="02">02</option>
				<option value="031">031</option>
				<option value="032">032</option>
				<option value="033">033</option>
				<option value="041">041</option>
				<option value="042">042</option>
				<option value="043">043</option>
				<option value="051">051</option>
				<option value="052">052</option>
				<option value="053">053</option>
				<option value="054">054</option>
				<option value="055">055</option>
				<option value="061">061</option>
				<option value="062">062</option>
				<option value="063">063</option>
				<option value="064">064</option>
				<option value="070">070</option>
						</select>
						-
						<input type="text" name="ngoContact2" id="ngoContact2" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="4" tabindex="30" />
						-
						<input type="text" name="ngoContact3" id="ngoContact3" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="4" tabindex="31" />
					</td>
				</tr>
				<tr>
					<td class="td01">선교지</td>
					<td>
						<select name="nation" id="nation" tabindex="32">
<?php 
	foreach ($codes as $codeObj) {
		print "<option value=\"".$codeObj->Code."\">".$codeObj->Name."</option>";
	}
?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="td01">홈페이지 주소 </td>
					<td>
						<input type="text" id="homepage" name="homepage" style="width:50%" maxlength="100" tabindex="33" />
					</td>
				</tr>
				<tr>
					<td class="td01">파송관리자 이름 </td>
					<td>
						<input type="text" id="manager" name="manager" maxlength="30" tabindex="34" />
					</td>
				</tr>
				<tr>
					<td class="td01">파송관리자 연락처 </td>
					<td>
						<select name="managerContact1" id="managerContact1" tabindex="35">
				<option value="010">010</option>
				<option value="011">011</option>
				<option value="016">016</option>
				<option value="017">017</option>
				<option value="018">018</option>
				<option value="019">019</option>
				<option value="02">02</option>
				<option value="031">031</option>
				<option value="032">032</option>
				<option value="033">033</option>
				<option value="041">041</option>
				<option value="042">042</option>
				<option value="043">043</option>
				<option value="051">051</option>
				<option value="052">052</option>
				<option value="053">053</option>
				<option value="054">054</option>
				<option value="055">055</option>
				<option value="061">061</option>
				<option value="062">062</option>
				<option value="063">063</option>
				<option value="064">064</option>
				<option value="070">070</option>
						</select>
						-
						<input type="text" name="managerContact2" id="managerContact2" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="4" tabindex="36" />
						-
						<input type="text" name="managerContact3" id="managerContact3" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="4" tabindex="37" />
					</td>
				</tr>
				<tr>
					<td class="td01"> 파송관리자 E-mail </td>
					<td>
						<input type="text" id="managerEmail1" name="managerEmail1" maxlength="30" tabindex="38" />
						@
						<input type="text" id="managerEmail2" name="managerEmail2" maxlength="50" tabindex="39" />
					</td>
				</tr>
				<tr>
					<td class="td01">후원 계좌번호</td>
					<td>
						<input type="text" id="accountNo" name="accountNo" style="width:50%" maxlength="20" tabindex="40" />
					</td>
				</tr>
				<tr>
					<td class="td01">은행명</td>
					<td>
						<input type="text" id="bank" name="bank" style="width:50%" maxlength="10" tabindex="41" />
					</td>
				</tr>
				<tr>
					<td class="td01">예금주</td>
					<td>
						<input type="type" id="accountName" name="accountName" style="width:50%" maxlength="10" tabindex="42" />
					</td>
				</tr>
				<tr>
					<td class="td01">기타메모</td>
					<td>
						<textarea name="memo" id="memo" class="b10">
간단한 소개 :


사역소개 :
			</textarea>
					</td>
				</tr>
				<tr>
					<td class="td01">기도제목</td>
					<td>
						<textarea name="prayList" id="prayList">
1.

2.

3.


			</textarea>
					</td>
				</tr>
				<tr>
					<td class="td01">가족사항</td>
					<td>			
			<table id="tblFamily" name="tbmFamily" width="100%" border="0" cellspacing="0" cellpadding="0" class="board_con">
			<tr><th>이름</th><th>나이</th><th>성별</th><th>관계</th></tr>
			<tr id="trFamily">
				<td><input type="text" name="familyName" id="familyName" style="width:130px"></td>
				<td><select name="familyAge" id="familyAge">
				<?php 
	$year=substr(time(),0,4);
	for ($i=0; $i<=99; $i = $i+1) {
		print "<option value='".(strftime("%Y",-$i))."'>".($i+1)."세, ".(strftime("%Y",-$i))." </option>";

	}

?>
				</select></td>
				<td><select name="familySex" id="familySex"><option value="0">남자</option><option value="1">여자</option></select></td>
				<td>
					<select name="familyRelation" id="familyRelation">
					<option value="0">부모</option><option value="1">배우자</option>
					<option value="2">자녀</option><option value="3">형제</option>
					<option value="4">기타</option>
					</select>
				</td>
			</tr>
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
			<p class="btn_right"><img src="../images/board/btn_join.gif" border="0" class="m2" onclick="check()" /></p>
		</form>
			<!-- write// -->
		</div>
		<!-- content// -->
<?php } ?>

<script type="text/javascript">
//<![CDATA[
	var familyNum = 1;
	
	function checkUserId(userId) {
		var url = 'ajax.php?mode=checkUserId&userId='+userId;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: '', onComplete: resultUserId});
	}
	
	function resultUserId(reqResult) {
		var addHtml = reqResult.responseText;
		document.getElementById("resultMessage1").innerHTML = addHtml;
	}
	
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

	function checkPassword()	{
		var pw1 = document.getElementById("password").value;
		var pw2 = document.getElementById("password_confirm").value;
		var url = 'ajax.php?mode=checkPassword&pw1='+pw1+'&pw2='+pw2;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: '', onComplete: resultPassword});
	}
	
	function resultPassword(reqResult) {
		var addHtml = reqResult.responseText;
		document.getElementById("resultMessage5").innerHTML = addHtml;
	}
	
	function checkMission() {
		if (document.getElementById("missionary").checked) {
			document.getElementById("frmMission").style.visibility = "";
			document.getElementById("frmMission").style.display = "";
		} else {
			document.getElementById("frmMission").style.visibility = "hidden";
			document.getElementById("frmMission").style.display = "none";
		}
	}
	
	function check() {
		if (document.getElementById("missionary").checked && !checkMissionary()) {
			return;
		}
		
		if (document.getElementById("userId").value == "") {
			alert("아이디를 입력해주세요.");
			document.getElementById("userId").focus();
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
		if (document.getElementById("password").value == "" || 
			document.getElementById("password").value != document.getElementById("password_confirm").value) {
			alert("비밀번호를 확인해주세요.");
			document.getElementById("password").focus();
			return;
		}
//		if (document.getElementById("jumin1").value == "" || document.getElementById("jumin1").value.length < 6 || 
//			document.getElementById("jumin2").value == "" || document.getElementById("jumin2").value.length < 7) {
// 		if (document.getElementById("jumin1").value == "" || document.getElementById("jumin1").value.length < 6) {
// 			alert("주민등록번호 앞자리는 입력해야 합니다.");
// 			document.getElementById("jumin1").select();
// 			return;
// 		}
		if (document.getElementById("email1").value == "" || document.getElementById("email2").value == "") {
			alert("이메일 주소를 확인해주세요.");
			document.getElementById("email1").select();
			return;
		}
/*
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
*/		
		if (document.getElementById("missionary").checked) {
			if (document.getElementById("missionName").value == "") {
				alert("선교사명을 입력해주세요.");
				document.getElementById("missionName").focus();
				return;
			}
			if (document.getElementById("church").value == "" && document.getElementById("ngo").value == "") {
				alert("파송교회 혹은 파송선교단체를 입력해주세요.");
				document.getElementById("church").focus();
				return;
			} 
			if (document.getElementById("church").value != "" && (document.getElementById("churchContact2").value == "" || document.getElementById("churchContact3").value == "")) {
				alert("파송교회의 연락처를 정확히 입력해주세요.");
				document.getElementById("churchContact2").focus();
				return;
			}
			if (document.getElementById("ngo").value != "" && (document.getElementById("ngoContact2").value == "" || document.getElementById("ngoContact3").value == "")) {
				alert("파송선교단체의 연락처를 정확히 입력해주세요.");
				document.getElementById("ngoContact2").focus();
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
//]]>
</script>

