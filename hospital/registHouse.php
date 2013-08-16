<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

$hospitalId = trim($_REQUEST["hospitalId"]);
if ((strlen($hospitalId)>0)) {
	$needUserLv[1];
} 

$c_Helper = new CodeHelper();
$codes = $c_Helper->getLocalCodeList();
$codeStatus = $c_Helper->getHouseStatusCodeList();

$h_helper = new HospitalHelper();
$hospitalObj = $h_helper->getHospitalInfoById($hospitalId);

# if (sessions.userId <> hospitalObj.UserID) then 
#	Call alertBack("본인 소유의 병원이 아닙니다.")
# end if
showHeader("HOME > 병원 > 병원등록요청","hospital","tit_0903.gif");
body();
showFooter();

function body() {
?>
		<!-- //content -->
		<div id="content">
			<!-- //write -->
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_write">
		<form name="dataForm" id="dataForm" method="post">
		<input type="hidden" name="mode" id="mode" value="registHospital" />
		<input type="hidden" name="userId" id="userId" value="<?php echo $hospitalObj->UserID;?>" />
		<input type="hidden" name="hospitalId" id="hospitalId" value="<?php echo $hospitalObj->HospitalID;?>" />
				<col width="20%" />
				<col />
				<tr>
					<td class="td01">병원 이름 </td>
					<td>
						<input type="text" name="hospitalName" id="hospitalName" maxlength="40" tabindex="1" value="<?php echo $hospitalObj->HospitalName;?>" />
					</td>
				</tr>
				<tr>
					<td class="td01">운영단체</td>
					<td>
						<input type="text" name="assocName" id="assocName" maxlength="50" tabindex="2"	value="<?php echo $hospitalObj->AssocName;?>" />
					</td>
				</tr>
				<tr>
					<td class="td01">담당자 이름 </td>
					<td>
						<input type="text" name="manager" id="manager" maxlength="30" tabindex="3" value="<?php echo $hospitalObj->Manager1;?>" />
					</td>
				</tr>			 
				<tr>
					<td class="td01">담당자 연락처 </td>
					<td>
			<?	 $contact1 = $hospitalObj->Contact1;?>
						<select name="contact1" id="contact1" tabindex="4">
				<option value="010" <?php if (($contact1[0]=="010")) { print "selected"; }?>>010</option>
				<option value="011" <?php if (($contact1[0]=="011")) { print "selected"; }?>>011</option>
				<option value="016" <?php if (($contact1[0]=="016")) { print "selected"; }?>>016</option>
				<option value="017" <?php if (($contact1[0]=="017")) { print "selected"; }?>>017</option>
				<option value="018" <?php if (($contact1[0]=="018")) { print "selected"; }?>>018</option>
				<option value="019" <?php if (($contact1[0]=="019")) { print "selected"; }?>>019</option>
				<option value="02" <?php if (($contact1[0]=="02")) { print "selected"; }?>>02</option>
				<option value="031" <?php if (($contact1[0]=="031")) { print "selected"; }?>>031</option>
				<option value="032" <?php if (($contact1[0]=="032")) { print "selected"; }?>>032</option>
				<option value="033" <?php if (($contact1[0]=="033")) { print "selected"; }?>>033</option>
				<option value="041" <?php if (($contact1[0]=="041")) { print "selected"; }?>>041</option>
				<option value="042" <?php if (($contact1[0]=="042")) { print "selected"; }?>>042</option>
				<option value="043" <?php if (($contact1[0]=="043")) { print "selected"; }?>>043</option>
				<option value="051" <?php if (($contact1[0]=="051")) { print "selected"; }?>>051</option>
				<option value="052" <?php if (($contact1[0]=="052")) { print "selected"; }?>>052</option>
				<option value="053" <?php if (($contact1[0]=="053")) { print "selected"; }?>>053</option>
				<option value="054" <?php if (($contact1[0]=="054")) { print "selected"; }?>>054</option>
				<option value="055" <?php if (($contact1[0]=="055")) { print "selected"; }?>>055</option>
				<option value="061" <?php if (($contact1[0]=="061")) { print "selected"; }?>>061</option>
				<option value="062" <?php if (($contact1[0]=="062")) { print "selected"; }?>>062</option>
				<option value="063" <?php if (($contact1[0]=="063")) { print "selected"; }?>>063</option>
				<option value="064" <?php if (($contact1[0]=="064")) { print "selected"; }?>>064</option>
				<option value="070" <?php if (($contact1[0]=="070")) { print "selected"; }?>>070</option>
						</select>
						-
						<input type="text" name="contact2" id="contact2" style="width:50px;ime-mode:disabled;" onKeyPress="CheckNumber(event);" maxlength="4" tabindex="5" value="<?php echo $contact1[1];?>" />
						-
						<input type="text" name="contact3" id="contact3" style="width:50px;ime-mode:disabled;" onKeyPress="CheckNumber(event);" maxlength="4" tabindex="6" value="<?php echo $contact1[2];?>" />
					</td>
				</tr>
				<tr>
					<td class="td01">지역코드 </td>
					<td>
			<select name="regionCode" id="regionCode" tabindex="8">
						<option value=''>-- 지역선택 --</option>
<?php 
	for ($i=0; $i<=count($codes)-1; $i = $i+1) {
		$region = $codes[$i];
		if (($hospitalObj->regionCode == $region->Code)) {
			print "<option value='".$region->Code."' selected>".$region->Name."</option>";
		} else {
			print "<option value='".$region->Code."'>".$region->Name."</option>";
		} 
	}

?>
					</select>
					</td>
				</tr>				
				<tr>
					<td class="td01">우편번호</td>
					<td>
						<?php $zipcode = $hospitalObj->Zipcode;?>
						<input type="text" name="post1" id="post1" style="width:50px" readonly onclick="PostPopup();" tabindex="9" value="<?php echo $zipcode[0];?>" />
						-
						<input type="text" name="post2" id="post2" style="width:50px" readonly onclick="PostPopup();" value="<?php echo $zipcode[1];?>" />
						<img src="../images/board/btn_zipcode.gif" border="0" align="absmiddle" class="m2" onclick="PostPopup();"></td>
				</tr>
				<tr>
					<td class="td01">주소</td>
					<td>
						<input type="text" name="addr1" id="addr1" style="width:80%" readonly onclick="PostPopup();" tabindex="10" value="<?php echo $hospitalObj->Address1;?>" />
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<input type="text" name="addr2" id="addr2" style="width:50%" tabindex="11" value="<?php echo $hospitalObj->Address2;?>" />
					</td>
				</tr>
				<tr>
					<td class="td01">가격(1일 기준) </td>
					<td>
						<input type="text" name="price" id="price" style="width:100px;ime-mode:disabled;" onKeyPress="CheckNumber(event);" tabindex="12" value="<?php echo $hospitalObj->Price;?>" /> 원
			</td>
				</tr>
				<tr>
					<td class="td01">인일 진료 인원</td>
					<td>
						<input type="text" name="personLimit" id="personLimit" style="width:50px;ime-mode:disabled;" onKeyPress="CheckNumber(event);" tabindex="13" value="<?php echo $hospitalObj->PersonLimit;?>" /> 명
					</td>
				</tr>
				<tr>
					<td class="td01"> 병원 설명 </td>
					<td>
						<textarea name="explain" id="roomLimit" tabindex="15"><?php echo $textFormat[$hospitalObj->Explain][2];?></textarea>
					</td>
				</tr>
		</form>
			</table>
			<!-- write// -->		
		<p class="btn_right"><img src="../images/board/btn_regist.gif" border="0" class="m2" onclick="frmSubmit()" /></p>
		</div>
		<!-- content// -->
<?php } ?>

<script type="text/javascript">
//<![CDATA[
	function frmSubmit() {
<?php 
	if (!isset($_SESSION['userId']) || strlen($_SESSION['userId'])==0 && false) {
		# 로그인 안해도 등록할 수 있도록 수정함
		$backURL = get_path_info();
?>
			alert("병원등록은 로그인을 하신후에 할 수 있습니다.");
			location.href = "../member/login.php?backURL=<?php echo $backURL;?>";
<?php } else { ?>
			if (document.getElementById("hospitalName").value == "") {
				alert("병원 이름을 입력해주세요.");
				document.getElementById("hospitalName").focus();
				return;
			}
			if (document.getElementById("assocName").value == "") {
				alert("운영단체를 입력해주세요.");
				document.getElementById("assocName").focus();
				return;
			}
			if (document.getElementById("manager").value == "") {
				alert("담당자 이름을 입력해주세요.");
				document.getElementById("manager").focus();
				return;
			}
			if (document.getElementById("contact2").value == "" || document.getElementById("contact3").value == "") {
				alert("담당자 연락처를 확인해주세요.");
				document.getElementById("contact2").select();
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
			if (document.getElementById("personLimit").value == "" || document.getElementById("personLimit").value < 1) {
				alert("최대 인원수를 정확히 입력해주세요.");
				document.getElementById("personLimit").focus();
				return;
			}
			if (document.getElementById("roomLimit").value == "" || document.getElementById("roomLimit").value < 1) {
				alert("방 갯수를 정확히 입력해주세요.");
				document.getElementById("roomLimit").focus();
				return;
			}
			document.getElementById("dataForm").action="process.php";
			document.getElementById("dataForm").submit();
<?php } ?>
	}
//]]>
</script>
