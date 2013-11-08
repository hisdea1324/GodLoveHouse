<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

$hospitalId = trim($_REQUEST["hospitalId"]);
$field = trim($_REQUEST["field"]);
$keyword = trim($_REQUEST["keyword"]);
$gotoPage = trim($_REQUEST["gotoPage"]);

$c_Helper = new CodeHelper();
$codeResions = $c_Helper->getLocalCodeList();
$codeStatus = $c_Helper->getHouseStatusCodeList();

$h_helper = new HospitalHelper();
$hospitalObj = $h_helper->getHospitalInfoById($hospitalId);

checkAuth();
showAdminHeader("관리툴 - 선교관 등록","","","");
body();
showAdminFooter();

function body() {
	global $CurUrl, $strPage, $htmlTable, $gotoPage, $codeResions, $codeStatus;
	global $field, $keyword;
?>
	<div class="sub">
	<a href="editHouse.php?mode=addHouse&keyword=<?php echo $keyword;?>&field=<?php echo $field;?>">선교관추가</a> | 
	<a href="index.php">등록된 선교관</a> | 
	<a href="index.php?status=S2001">대기중 선교관</a> | 
	<a href="editHospital.php??mode=addHospital&keyword=<?php echo $keyword;?>&field=<?php echo $field;?>">병원 추가</a> | 
	<a href="hospital.php">등록된 병원</a> | 
	<a href="hospital.php?status=S2001">대기중 병원</a>
	</div>
	</div>
	<div id="wrap">
		<div class="lSec">
		<ul>
			<li><img src="/images/manager/lm_0200.gif"></li>
		<li><a href="editHouse.php?mode=addHouse&keyword=<?php echo $keyword;?>&field=<?php echo $field;?>"><img src="/images/manager/lm_0201.gif"></a></li>
		<li><a href="index.php"><img src="/images/manager/lm_0202.gif"></a></li>
		<li><a href="index.php?status=S2001"><img src="/images/manager/lm_0203.gif"></a></li>
		<li><img src="/images/manager/lm_bot.gif"></li>
		</ul>
	</div>
	
	<div class="rSec">
		<dl>
		<form name="dataForm" id="dataForm" method="post">
		<input type="hidden" name="field" id="field" value="<?php echo $field;?>" />
		<input type="hidden" name="keyword" id="keyword" value="<?php echo $keyword;?>" />
		<input type="hidden" name="gotoPage" id="gotoPage" value="<?php echo $gotoPage;?>" />
		<input type="hidden" name="mode" id="mode" value="editHospital" />
		<input type="hidden" name="HospitalId" id="hospitalId" value="<?php echo $hospitalObj->HospitalID;?>" />
			<dt>
				병원 이름 
			<dd>
				<input type="text" name="hospitalName" id="hospitalName" maxlength="40" tabindex="1" value="<?php echo $hospitalObj->HospitalName;?>" />
			<dt>
				병원 관리자
			<dd>
				<input type="text" name="userid" id="userid" maxlength="50" tabindex="2" value="<?php echo $hospitalObj->userid;?>" /> (관리자의 userid를 입력하세요)
			<dt>
				운영단체
			<dd>
				<input type="text" name="assocName" id="assocName" maxlength="50" tabindex="2" value="<?php echo $hospitalObj->AssocName;?>" />
			<dt>
				담당자 이름1
			<dd>
				<input type="text" name="manager1" id="manager1" maxlength="30" tabindex="3" value="<?php echo $hospitalObj->Manager1;?>" //>
			<?	 $contact1 = $hospitalObj->Contact1;?>
			<dt>
				담당자 연락처1
			<dd>
				<select name="contact11" id="contact11" tabindex="4">
					<option value="010" <?php if (($contact1[0]=="010")) { print "selected"; } ?>>010</option>
					<option value="011" <?php if (($contact1[0]=="011")) { print "selected"; } ?>>011</option>
					<option value="016" <?php if (($contact1[0]=="016")) { print "selected"; } ?>>016</option>
					<option value="017" <?php if (($contact1[0]=="017")) { print "selected"; } ?>>017</option>
					<option value="018" <?php if (($contact1[0]=="018")) { print "selected"; } ?>>018</option>
					<option value="019" <?php if (($contact1[0]=="019")) { print "selected"; } ?>>019</option>
					<option value="02" <?php if (($contact1[0]=="02")) { print "selected"; } ?>>02</option>
					<option value="031" <?php if (($contact1[0]=="031")) { print "selected"; } ?>>031</option>
					<option value="032" <?php if (($contact1[0]=="032")) { print "selected"; } ?>>032</option>
					<option value="033" <?php if (($contact1[0]=="033")) { print "selected"; } ?>>033</option>
					<option value="041" <?php if (($contact1[0]=="041")) { print "selected"; } ?>>041</option>
					<option value="042" <?php if (($contact1[0]=="042")) { print "selected"; } ?>>042</option>
					<option value="043" <?php if (($contact1[0]=="043")) { print "selected"; } ?>>043</option>
					<option value="051" <?php if (($contact1[0]=="051")) { print "selected"; } ?>>051</option>
					<option value="052" <?php if (($contact1[0]=="052")) { print "selected"; } ?>>052</option>
					<option value="053" <?php if (($contact1[0]=="053")) { print "selected"; } ?>>053</option>
					<option value="054" <?php if (($contact1[0]=="054")) { print "selected"; } ?>>054</option>
					<option value="055" <?php if (($contact1[0]=="055")) { print "selected"; } ?>>055</option>
					<option value="061" <?php if (($contact1[0]=="061")) { print "selected"; } ?>>061</option>
					<option value="062" <?php if (($contact1[0]=="062")) { print "selected"; } ?>>062</option>
					<option value="063" <?php if (($contact1[0]=="063")) { print "selected"; } ?>>063</option>
					<option value="064" <?php if (($contact1[0]=="064")) { print "selected"; } ?>>064</option>
					<option value="070" <?php if (($contact1[0]=="070")) { print "selected"; } ?>>070</option>
				</select>
				-
				<input type="text" name="contact12" id="contact12" style="width:50px;ime-mode:disabled;" onKeyPress="CheckNumber(event);" maxlength="4" tabindex="5" value="<?php echo $contact1[1];?>" />
				-
				<input type="text" name="contact13" id="contact13" style="width:50px;ime-mode:disabled;" onKeyPress="CheckNumber(event);" maxlength="4" tabindex="6" value="<?php echo $contact1[2];?>" />
				
			<!--dt>
				담당자 이름2
			<dd>
				<input type="text" name="manager2" id="manager2" maxlength="30" tabindex="3" value="<?php echo $hospitalObj->Manager2;?>" /-->
			<?	 $contact2 = $hospitalObj->Contact2;?>
			<dt>
				담당자 연락처2
			<dd>
				<select name="contact21" id="contact21" tabindex="4">
					<option value="010" <?php if (($contact2[0]=="010")) { print "selected"; } ?>>010</option>
					<option value="011" <?php if (($contact2[0]=="011")) { print "selected"; } ?>>011</option>
					<option value="016" <?php if (($contact2[0]=="016")) { print "selected"; } ?>>016</option>
					<option value="017" <?php if (($contact2[0]=="017")) { print "selected"; } ?>>017</option>
					<option value="018" <?php if (($contact2[0]=="018")) { print "selected"; } ?>>018</option>
					<option value="019" <?php if (($contact2[0]=="019")) { print "selected"; } ?>>019</option>
					<option value="02" <?php if (($contact2[0]=="02")) { print "selected"; } ?>>02</option>
					<option value="031" <?php if (($contact2[0]=="031")) { print "selected"; } ?>>031</option>
					<option value="032" <?php if (($contact2[0]=="032")) { print "selected"; } ?>>032</option>
					<option value="033" <?php if (($contact2[0]=="033")) { print "selected"; } ?>>033</option>
					<option value="041" <?php if (($contact2[0]=="041")) { print "selected"; } ?>>041</option>
					<option value="042" <?php if (($contact2[0]=="042")) { print "selected"; } ?>>042</option>
					<option value="043" <?php if (($contact2[0]=="043")) { print "selected"; } ?>>043</option>
					<option value="051" <?php if (($contact2[0]=="051")) { print "selected"; } ?>>051</option>
					<option value="052" <?php if (($contact2[0]=="052")) { print "selected"; } ?>>052</option>
					<option value="053" <?php if (($contact2[0]=="053")) { print "selected"; } ?>>053</option>
					<option value="054" <?php if (($contact2[0]=="054")) { print "selected"; } ?>>054</option>
					<option value="055" <?php if (($contact2[0]=="055")) { print "selected"; } ?>>055</option>
					<option value="061" <?php if (($contact2[0]=="061")) { print "selected"; } ?>>061</option>
					<option value="062" <?php if (($contact2[0]=="062")) { print "selected"; } ?>>062</option>
					<option value="063" <?php if (($contact2[0]=="063")) { print "selected"; } ?>>063</option>
					<option value="064" <?php if (($contact2[0]=="064")) { print "selected"; } ?>>064</option>
					<option value="070" <?php if (($contact2[0]=="070")) { print "selected"; } ?>>070</option>
				</select>
				-
				<input type="text" name="contact22" id="contact22" style="width:50px;ime-mode:disabled;" onKeyPress="CheckNumber(event);" maxlength="4" tabindex="5" value="<?php echo $contact2[1];?>" />
				-
				<input type="text" name="contact23" id="contact23" style="width:50px;ime-mode:disabled;" onKeyPress="CheckNumber(event);" maxlength="4" tabindex="6" value="<?php echo $contact2[2];?>" />
			<dt>
				지역코드
			<dd>
				<select name="region" id="region">
					<option value=''>-- 지역선택 --</option>
				<?php 
	for ($i=0; $i<=count($codeResions)-1; $i = $i+1) {
		$region = $codeResions[$i];
		if (($hospitalObj->regionCode == $region->Code)) {
			print "<option value='".$region->Code."' selected>".$region->Name."</option>";
		} else {
			print "<option value='".$region->Code."'>".$region->Name."</option>";
		} 


	}

?>
				</select>
			<?	 $zipcode = $hospitalObj->Zipcode;?>
			<dt>
				우편번호
			<dd>
				<input type="text" name="post1" id="post1" style="width:50px" readonly onclick="PostPopup();" tabindex="8" value="<?php echo $zipcode[0];?>" />
				-
				<input type="text" name="post2" id="post2" style="width:50px" readonly onclick="PostPopup();" value="<?php echo $zipcode[1];?>" />
				<input type="button" value="검색" onclick="PostPopup();" style="cursor:pointer;"></td>
			<dt>
				주소
			<dd>
				<input type="text" name="addr1" id="addr1" style="width:80%" readonly onclick="PostPopup();" value="<?php echo $hospitalObj->Address1;?>" />
				<input type="text" name="addr2" id="addr2" style="width:50%" tabindex="9" value="<?php echo $hospitalObj->Address2;?>" />
			<dt>
				가격(1일 기준)
			<dd>
				<input type="text" name="price" id="price" style="width:80px;ime-mode:disabled;" tabindex="10" value="<?php echo $hospitalObj->Price;?>" />
			<dt>
				일일 진료 인원
			<dd>
				<input type="text" name="personLimit" id="personLimit" style="width:50px;ime-mode:disabled;" onKeyPress="CheckNumber(event);" tabindex="11" value="<?php echo $hospitalObj->PersonLimit;?>" /> 명
			<dt>
				홈페이지
			<dd>
				<input type="text" name="homepage" id="homepage" style="width:80%;" tabindex="13" value="<?php echo $hospitalObj->HomepageNoLink;?>" />
			<dt>
				병원 설명 
			<dd>
				<textarea name="explain" id="explain" tabindex="14" cols=50 rows=5><?php echo textFormat($hospitalObj->Explain, 2);?></textarea>	
			<dt>
				제출서류
			<dd>
				<input type="hidden" name="idDocument" id="idDocument" value="<?php echo $hospitalObj->DocumentID;?>" />
				<input type="text" id="txtDocument" name="txtDocument" size="60" value="<?php echo $hospitalObj->Document;?>" />
				<input type="button" name="imgUpload" id="imgUpload" value="파일 업로드" onclick="uploadImage(event, 'Document', 'room')" style="cursor:pointer" /> 
			<dt>
				상태코드
			<dd>
				<select name="status" id="status">
				<?php 
	for ($i=0; $i<=count($codeStatus)-1; $i = $i+1) {
		$status = $codeStatus[$i];
		if (($hospitalObj->StatusCode == $status->Code)) {
			print "<option value='".$status->Code."' selected>".$status->Name."</option>";
		} else {
			print "<option value='".$status->Code."'>".$status->Name."</option>";
		} 


	}

?>
				</select>
			<dt>
				이미지 1
			<dd>
				<div id="showimage1" style="position:absolute;visibility:hidden;border:1px solid black"></div>
				<input type="button" name="imgUpload" id="imgUpload" value="이미지 업로드" onclick="uploadImage(event, 'HospitalImage1', 'room')" style="cursor:pointer" /> (320x220)<br />
				<input type="hidden" name="idHospitalImage1" id="idHospitalImage1" value="<?php echo $hospitalObj->ImageID1;?>" />
				<img src="<?php echo $hospitalObj->Image1;?>" id="imgHospitalImage1" width="320" height="220" onclick="showImage(1, this, event)" alt="크게보기" style="cursor:pointer" />
			<dt>
				이미지 2
			<dd>
				<div id="showimage2" style="position:absolute;visibility:hidden;border:1px solid black"></div>
				<input type="button" name="imgUpload" id="imgUpload" value="이미지 업로드" onclick="uploadImage(event, 'HospitalImage2', 'room')" style="cursor:pointer" /> (320x220)<br />
				<input type="hidden" name="idHospitalImage2" id="idHospitalImage2" value="<?php echo $hospitalObj->ImageID2;?>" />
				<img src="<?php echo $hospitalObj->Image2;?>" id="imgHospitalImage2" width="320" height="220" onclick="showImage(2, this, event)" alt="크게보기" style="cursor:pointer" />
			<dt>
				이미지 3
			<dd>
				<div id="showimage3" style="position:absolute;visibility:hidden;border:1px solid black"></div>
				<input type="button" name="imgUpload" id="imgUpload" value="이미지 업로드" onclick="uploadImage(event, 'HospitalImage3', 'room')" style="cursor:pointer" /> (320x220)<br />
				<input type="hidden" name="idHospitalImage3" id="idHospitalImage3" value="<?php echo $hospitalObj->ImageID3;?>" />
				<img src="<?php echo $hospitalObj->Image3;?>" id="imgHospitalImage3" width="320" height="220" onclick="showImage(3, this, event)" alt="크게보기" style="cursor:pointer" />
			<dt> 
				이미지 4
			<dd>
				<div id="showimage4" style="position:absolute;visibility:hidden;border:1px solid black"></div>
				<input type="button" name="imgUpload" id="imgUpload" value="이미지 업로드" onclick="uploadImage(event, 'HospitalImage4', 'room')" style="cursor:pointer" /> (320x220)<br />
				<input type="hidden" name="idHospitalImage4" id="idHospitalImage4" value="<?php echo $hospitalObj->ImageID4;?>" />
				<img src="<?php echo $hospitalObj->Image4;?>" id="imgHospitalImage4" width="320" height="220" onclick="showImage(4, this, event)" alt="크게보기" style="cursor:pointer" />
			<dt>&nbsp;
			<dd>
				<img src="/images/board/btn_ok.gif" border="0" onclick="check();" style="cursor:hand;">&nbsp;&nbsp;&nbsp;
				<img src="/images/board/btn_cancel.gif" border="0" onclick="history.back(-1);" style="cursor:hand"></a>
		</form>
		</dl>
	</div>

<?php } ?>

<script type="text/javascript">
//<![CDATA[
	function check() {
		document.getElementById("dataForm").action="process.php";
		document.getElementById("dataForm").submit();
	}
	
	function frmSubmit() {
		<?php 
if (!isset($_SESSION['userid']) || strlen($_SESSION['userid'])==0) {
	$backURL = get_path_info();
?>
			alert("병원등록은 로그인을 하신후에 할 수 있습니다.");
			location.href = "../member/login.php?backURL=<?php echo $backURL;?>";
		<?php } else { ?>
			document.getElementById("dataForm").action="process.php";
			document.getElementById("dataForm").submit();
		<?php } ?>
	}
//]]>
</script>
