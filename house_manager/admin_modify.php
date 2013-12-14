<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
checkUserLogin(7);

showHouseManagerHeader();
showHouseManagerLeft();
body();
showHouseManagerFooter();

function body() {
	$m_Helper = new MemberHelper();
	$member = $m_Helper->getMemberByuserid($_SESSION["userid"]);
?>
	<!-- rightSec -->
	<div id="rightSec">
		<div class="lnb">
			<strong>Home</strong> &gt; 관리자정보수정 &gt; 개인정보수정
		</div>
		<div id="content">
			<!-- content -->
			<h1>개인정보수정</h1>
			<table class="write mt30">
				<colgroup>
					<col width="20%" />
					<col width="80%" />
				</colgroup>
				<tbody>
					<form name="dataForm" id="dataForm" method="post">
					<input type="hidden" id="mode" name="mode" value="editUser" />
					<input type="hidden" id="userid" name="userid" value="<?=$member->userid?>" />
					<input type="hidden" id="level" name="level" value="<?=$member->UserLevel?>" />
					<tr>
						<th>아이디</th>
						<td><?=$member->userid?></td>
					</tr>
					<tr>
						<th>닉네임</th>
						<td>
							<input type="text" class="inputTxt" name="nickName" id="nickName" maxlength="30" tabindex="1" value="<?=$member->Nick?>" onkeyup="checkNick(this.value);" />
							<label class="fs11" type="text" name='resultMessage2' id='resultMessage2'></label> 공백없이 한글만 입력가능
						</td>
					</tr>
					<tr>
						<th>이름</th>
						<td>
							<input type="text" class="inputTxt" name="name" id="name" maxlength="30" tabindex="2" value="<?=$member->Name?>" />
							<label class="fs11" type="text" name='resultMessage3' id='resultMessage3'>(공백없이 한글만 입력가능)</label>
						</td>
					</tr>
					<tr>
						<th>E-Mail</th>
						<td>
							<input type="text" class="inputTxt" name="email1" id="email1" style="ime-mode:disabled" tabindex="5" maxlength="30" value="<?=$member->Email[0]?>" />
							@
							<input type="text" class="inputTxt" name="email2" id="email2" style="ime-mode:disabled" tabindex="6" maxlength="50" value="<?=$member->Email[1]?>" />
					</tr>
					<tr>
						<th>우편번호</th>
						<td>
							<input type="text" class="inputTxt" id="post1" name="post1" style="width:50px" readonly onclick="PostPopup();" tabindex="7" value="<?=$member->Zipcode[0]?>" />
							-
							<input type="text" class="inputTxt" id="post2" name="post2" style="width:50px" readonly onclick="PostPopup();" tabindex="8" value="<?=$member->Zipcode[1]?>" />
							<span class="btn1"><a href="javascript:void(0)" onclick="PostPopup();">우편번호찾기</a></span>
						</td>
					</tr>
					<tr>
						<th>주소</th>
						<td>
							<input type="text" class="inputTxt" name="addr1" id="addr1" style="width:80%" tabindex="9" readonly onclick="PostPopup();" value="<?=$member->Address1?>" />
							<input type="text" class="inputTxt" name="addr2" id="addr2" style="width:80%" tabindex="10" value="<?=$member->Address2?>" />
						</td>
					</tr>
					<tr>
						<th>전화번호</th>
						<td>
							<select id="tel1" name="tel1" tabindex="11">
								<option value="02"<?php if ($member->Phone[0] == "02") { ?> selected<?php } ?>>02</option>
								<option value="031"<?php if ($member->Phone[0] == "031") { ?> selected<?php } ?>>031</option>
								<option value="032"<?php if ($member->Phone[0] == "032") { ?> selected<?php } ?>>032</option>
								<option value="033"<?php if ($member->Phone[0] == "033") { ?> selected<?php } ?>>033</option>
								<option value="041"<?php if ($member->Phone[0] == "041") { ?> selected<?php } ?>>041</option>
								<option value="042"<?php if ($member->Phone[0] == "042") { ?> selected<?php } ?>>042</option>
								<option value="043"<?php if ($member->Phone[0] == "043") { ?> selected<?php } ?>>043</option>
								<option value="051"<?php if ($member->Phone[0] == "051") { ?> selected<?php } ?>>051</option>
								<option value="052"<?php if ($member->Phone[0] == "052") { ?> selected<?php } ?>>052</option>
								<option value="053"<?php if ($member->Phone[0] == "053") { ?> selected<?php } ?>>053</option>
								<option value="054"<?php if ($member->Phone[0] == "054") { ?> selected<?php } ?>>054</option>
								<option value="055"<?php if ($member->Phone[0] == "055") { ?> selected<?php } ?>>055</option>
								<option value="061"<?php if ($member->Phone[0] == "061") { ?> selected<?php } ?>>061</option>
								<option value="062"<?php if ($member->Phone[0] == "062") { ?> selected<?php } ?>>062</option>
								<option value="063"<?php if ($member->Phone[0] == "063") { ?> selected<?php } ?>>063</option>
								<option value="064"<?php if ($member->Phone[0] == "064") { ?> selected<?php } ?>>064</option>
								<option value="070"<?php if ($member->Phone[0] == "070") { ?> selected<?php } ?>>070</option>
							</select>
							-
							<input type="text" class="inputTxt" id="tel2" name="tel2" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" tabindex="12" maxlength="4" value="<?=$member->Phone[1]?>" />
							-
							<input type="text" class="inputTxt" id="tel3" name="tel3" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" tabindex="13" maxlength="4" value="<?=$member->Phone[2]?>" />
						</td>
					</tr>
					<tr>
						<th>휴대전화</th>
						<td>
							<select id="hp1" name="hp1" tabindex="14">
								<option value="010"<?php if ($member->Mobile[0] == "070") { ?> selected<?php } ?>>010</option>
								<option value="011"<?php if ($member->Mobile[0] == "070") { ?> selected<?php } ?>>011</option>
								<option value="016"<?php if ($member->Mobile[0] == "070") { ?> selected<?php } ?>>016</option>
								<option value="017"<?php if ($member->Mobile[0] == "070") { ?> selected<?php } ?>>017</option>
								<option value="018"<?php if ($member->Mobile[0] == "070") { ?> selected<?php } ?>>018</option>
								<option value="019"<?php if ($member->Mobile[0] == "070") { ?> selected<?php } ?>>019</option>
							</select>
							-
							<input type="text" class="inputTxt" id="hp2" name="hp2" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" tabindex="15" maxlength="4" value="<?=$member->Mobile[1]?>" />
							-
							<input type="text" class="inputTxt" id="hp3" name="hp3" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" tabindex="16" maxlength="4" value="<?=$member->Mobile[2]?>" />
						</td>
					</tr>
					</form>
				</tbody>
			</table>
			<div class="aRight mt20">
				<span class="btn2"><a href="javascript:void(0)" onclick="check()">수정</a></span></span>
			</div>
			<!-- // content -->
		</div>
	</div>
	<!-- // rightSec -->

<?php } ?>

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


	function check() {		
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
		document.getElementById("dataForm").action="process.php";
		document.getElementById("dataForm").submit();
	}
//]]>
</script>
