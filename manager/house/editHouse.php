<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

$houseId = (isset($_REQUEST["houseId"])) ? trim($_REQUEST["houseId"]) : "";
$field = (isset($_REQUEST["field"])) ? trim($_REQUEST["field"]) : "";
$keyword = (isset($_REQUEST["keyword"])) ? trim($_REQUEST["keyword"]) : "";
$gotoPage = (isset($_REQUEST["gotoPage"])) ? trim($_REQUEST["gotoPage"]) : "";

$c_Helper = new CodeHelper();
$codeRegions = $c_Helper->getLocalCodeList();
$codeStatus = $c_Helper->getHouseStatusCodeList();

$h_helper = new HouseHelper();
$houseObj = $h_helper->getHouseInfoById($houseId);

checkAuth();
showAdminHeader("관리툴 - 선교관 등록","","","");
body();
showAdminFooter();

function body() {
	global $keyword, $field, $gotoPage;
	global $houseObj, $codeRegions, $codeStatus;
	
?>
	<script type="text/javascript" src="/community/editor/js/HuskyEZCreator.js" charset="utf-8"></script>
	
	<div class="sub">
	<a href="editHouse.php?mode=addHouse&keyword=<?=$keyword?>&field=<?=$field?>">선교관추가</a> | 
	<a href="index.php">등록된 선교관</a> | 
	<a href="index.php?status=S2001">대기중 선교관</a> | 
	<a href="editHospital.php??mode=addHospital&keyword=<?=$keyword?>&field=<?=$field?>">병원 추가</a> | 
	<a href="hospital.php">등록된 병원</a> | 
	<a href="hospital.php?status=S2001">대기중 병원</a>
	</div>
	</div>
	<div id="wrap">
		<div class="lSec">
		<ul>
			<li><img src="/images/manager/lm_0200.gif"></li>
		<li><a href="editHouse.php?mode=addHouse&keyword=<?=$keyword?>&field=<?=$field?>"><img src="/images/manager/lm_0201.gif"></a></li>
		<li><a href="index.php"><img src="/images/manager/lm_0202.gif"></a></li>
		<li><a href="index.php?status=S2001"><img src="/images/manager/lm_0203.gif"></a></li>
		<li><img src="/images/manager/lm_bot.gif"></li>
		</ul>
	</div>
	
	<div class="rSec">
		<dl>
		<form name="dataForm" id="dataForm" method="post">
		<input type="hidden" name="field" id="field" value="<?=$field?>" />
		<input type="hidden" name="keyword" id="keyword" value="<?=$keyword?>" />
		<input type="hidden" name="gotoPage" id="gotoPage" value="<?=$gotoPage?>" />
		<input type="hidden" name="mode" id="mode" value="editHouse" />
		<input type="hidden" name="houseId" id="houseId" value="<?=$houseObj->houseId?>" />
			<dt>
				선교관 이름 
			<dd>
				<input type="text" name="houseName" id="houseName" maxlength="40" tabindex="1" value="<?=$houseObj->houseName?>" />
			<dt>
				선교관 관리자
			<dd>
				<input type="text" name="userid" id="userid" maxlength="50" tabindex="2" value="<?=$houseObj->userid?>" /> (관리자의 userid를 입력하세요)
			<dt>
				운영단체
			<dd>
				<input type="text" name="assocName" id="assocName" maxlength="50" tabindex="2" value="<?=$houseObj->assocName?>" />
			<dt>
				담당자 이름1
			<dd>
				<input type="text" name="manager1" id="manager1" maxlength="30" tabindex="3" value="<?=$houseObj->manager1?>" //>
			<dt>
				담당자 연락처1
			<dd>
				<select name="contact11" id="contact11" tabindex="4">
					<option value="010" <?php if ($houseObj->contact1[0] == "010") { print "selected"; } ?>>010</option>
					<option value="011" <?php if ($houseObj->contact1[0] == "011") { print "selected"; } ?>>011</option>
					<option value="016" <?php if ($houseObj->contact1[0] == "016") { print "selected"; } ?>>016</option>
					<option value="017" <?php if ($houseObj->contact1[0] == "017") { print "selected"; } ?>>017</option>
					<option value="018" <?php if ($houseObj->contact1[0] == "018") { print "selected"; } ?>>018</option>
					<option value="019" <?php if ($houseObj->contact1[0] == "019") { print "selected"; } ?>>019</option>
					<option value="02" <?php if ($houseObj->contact1[0] == "02") { print "selected"; } ?>>02</option>
					<option value="031" <?php if ($houseObj->contact1[0] == "031") { print "selected"; } ?>>031</option>
					<option value="032" <?php if ($houseObj->contact1[0] == "032") { print "selected"; } ?>>032</option>
					<option value="033" <?php if ($houseObj->contact1[0] == "033") { print "selected"; } ?>>033</option>
					<option value="041" <?php if ($houseObj->contact1[0] == "041") { print "selected"; } ?>>041</option>
					<option value="042" <?php if ($houseObj->contact1[0] == "042") { print "selected"; } ?>>042</option>
					<option value="043" <?php if ($houseObj->contact1[0] == "043") { print "selected"; } ?>>043</option>
					<option value="051" <?php if ($houseObj->contact1[0] == "051") { print "selected"; } ?>>051</option>
					<option value="052" <?php if ($houseObj->contact1[0] == "052") { print "selected"; } ?>>052</option>
					<option value="053" <?php if ($houseObj->contact1[0] == "053") { print "selected"; } ?>>053</option>
					<option value="054" <?php if ($houseObj->contact1[0] == "054") { print "selected"; } ?>>054</option>
					<option value="055" <?php if ($houseObj->contact1[0] == "055") { print "selected"; } ?>>055</option>
					<option value="061" <?php if ($houseObj->contact1[0] == "061") { print "selected"; } ?>>061</option>
					<option value="062" <?php if ($houseObj->contact1[0] == "062") { print "selected"; } ?>>062</option>
					<option value="063" <?php if ($houseObj->contact1[0] == "063") { print "selected"; } ?>>063</option>
					<option value="064" <?php if ($houseObj->contact1[0] == "064") { print "selected"; } ?>>064</option>
					<option value="070" <?php if ($houseObj->contact1[0] == "070") { print "selected"; } ?>>070</option>
				</select>
				-
				<input type="text" name="contact12" id="contact12" style="width:50px;ime-mode:disabled;" onKeyPress="CheckNumber(event);" maxlength="4" tabindex="5" value="<?=$houseObj->contact1[1]?>" />
				-
				<input type="text" name="contact13" id="contact13" style="width:50px;ime-mode:disabled;" onKeyPress="CheckNumber(event);" maxlength="4" tabindex="6" value="<?=$houseObj->contact1[2]?>" />
				
			<!--dt>
				담당자 이름2
			<dd>
				<input type="text" name="manager2" id="manager2" maxlength="30" tabindex="3" value="<?=$houseObj->manager2?>" /-->
			<dt>
				담당자 연락처2
			<dd>
				<select name="contact21" id="contact21" tabindex="4">
					<option value="010" <?php if (($houseObj->contact2[0]=="010")) { print "selected"; } ?>>010</option>
					<option value="011" <?php if (($houseObj->contact2[0]=="011")) { print "selected"; } ?>>011</option>
					<option value="016" <?php if (($houseObj->contact2[0]=="016")) { print "selected"; } ?>>016</option>
					<option value="017" <?php if (($houseObj->contact2[0]=="017")) { print "selected"; } ?>>017</option>
					<option value="018" <?php if (($houseObj->contact2[0]=="018")) { print "selected"; } ?>>018</option>
					<option value="019" <?php if (($houseObj->contact2[0]=="019")) { print "selected"; } ?>>019</option>
					<option value="02" <?php if (($houseObj->contact2[0]=="02")) { print "selected"; } ?>>02</option>
					<option value="031" <?php if (($houseObj->contact2[0]=="031")) { print "selected"; } ?>>031</option>
					<option value="032" <?php if (($houseObj->contact2[0]=="032")) { print "selected"; } ?>>032</option>
					<option value="033" <?php if (($houseObj->contact2[0]=="033")) { print "selected"; } ?>>033</option>
					<option value="041" <?php if (($houseObj->contact2[0]=="041")) { print "selected"; } ?>>041</option>
					<option value="042" <?php if (($houseObj->contact2[0]=="042")) { print "selected"; } ?>>042</option>
					<option value="043" <?php if (($houseObj->contact2[0]=="043")) { print "selected"; } ?>>043</option>
					<option value="051" <?php if (($houseObj->contact2[0]=="051")) { print "selected"; } ?>>051</option>
					<option value="052" <?php if (($houseObj->contact2[0]=="052")) { print "selected"; } ?>>052</option>
					<option value="053" <?php if (($houseObj->contact2[0]=="053")) { print "selected"; } ?>>053</option>
					<option value="054" <?php if (($houseObj->contact2[0]=="054")) { print "selected"; } ?>>054</option>
					<option value="055" <?php if (($houseObj->contact2[0]=="055")) { print "selected"; } ?>>055</option>
					<option value="061" <?php if (($houseObj->contact2[0]=="061")) { print "selected"; } ?>>061</option>
					<option value="062" <?php if (($houseObj->contact2[0]=="062")) { print "selected"; } ?>>062</option>
					<option value="063" <?php if (($houseObj->contact2[0]=="063")) { print "selected"; } ?>>063</option>
					<option value="064" <?php if (($houseObj->contact2[0]=="064")) { print "selected"; } ?>>064</option>
					<option value="070" <?php if (($houseObj->contact2[0]=="070")) { print "selected"; } ?>>070</option>
				</select>
				-
				<input type="text" name="contact22" id="contact22" style="width:50px;ime-mode:disabled;" onKeyPress="CheckNumber(event);" maxlength="4" tabindex="5" value="<?=$houseObj->contact2[1]?>" />
				-
				<input type="text" name="contact23" id="contact23" style="width:50px;ime-mode:disabled;" onKeyPress="CheckNumber(event);" maxlength="4" tabindex="6" value="<?=$houseObj->contact2[2]?>" />
			<dt>
				주거형태
			<dd>
				<input name="buildType" id="buildType" type="radio" value="1" class="chk" <?php if ($houseObj->buildingType == 1) { print "checked"; } ?> tabindex="7" />
				<span class="r10">아파트</span>
				<input name="buildType" id="buildType" type="radio" value="2" class="chk" <?php if ($houseObj->buildingType == 2) { print "checked"; } ?> />
				<span class="r10">빌라</span>
				<input name="buildType" id="buildType" type="radio" value="3" class="chk" <?php if ($houseObj->buildingType == 3) { print "checked"; } ?> />
				<span class="r10">원룸</span>
				<input name="buildType" id="buildType" type="radio" value="4" class="chk" <?php if ($houseObj->buildingType == 4) { print "checked"; } ?> />
				<span class="r10">기타</span>
			<dt>
				지역코드
			<dd>
				<select name="region" id="region">
					<option value=''>-- 지역선택 --</option>
				<?php 
	foreach ($codeRegions as $region) {
		if ($houseObj->regionCode == $region->code) {
			print "<option value='".$region->code."' selected>".$region->name."</option>";
		} else {
			print "<option value='".$region->code."'>".$region->name."</option>";
		} 


	}

?>
				</select>
			<dt>
				우편번호
			<dd>
				<input type="text" name="post1" id="post1" style="width:50px" readonly onclick="PostPopup();" tabindex="8" value="<?=$houseObj->zipcode[0]?>" />
				-
				<input type="text" name="post2" id="post2" style="width:50px" readonly onclick="PostPopup();" value="<?=$houseObj->zipcode[1]?>" />
				<input type="button" value="검색" onclick="PostPopup();" style="cursor:pointer;"></td>
			<dt>
				주소
			<dd>
				<input type="text" name="addr1" id="addr1" style="width:80%" readonly onclick="PostPopup();" value="<?=$houseObj->address1?>" />
				<input type="text" name="addr2" id="addr2" style="width:50%" tabindex="9" value="<?=$houseObj->address2?>" />
			<dt>
				가격(1일 기준)
			<dd>
				<input type="text" name="price" id="price" style="width:80px;ime-mode:disabled;" tabindex="10" value="<?=$houseObj->price?>" />
			<dt>
				최대 인원수
			<dd>
				<input type="text" name="personLimit" id="personLimit" style="width:50px;ime-mode:disabled;" onKeyPress="CheckNumber(event);" tabindex="11" value="<?=$houseObj->personLimit?>" /> 명
			<dt>
				방갯수
			<dd>
				<input type="text" name="roomLimit" id="roomLimit" style="width:50px;ime-mode:disabled;" onKeyPress="CheckNumber(event);" tabindex="12" value="<?=$houseObj->roomLimit?>" /> 개
			<dt>
				홈페이지
			<dd>
				<input type="text" name="homepage" id="homepage" style="width:80%;" tabindex="13" value="<?=$houseObj->homepageNoLink?>" />
			<dt>
				선교관 설명 
			<dd>
				<textarea name="explain" id="explain" tabindex="14" style="width:600px; height:300px;">
<? 
	if ($houseObj->explain) {
		echo $houseObj->explain;
	} else {
		echo "운영자 소개: <br><br>방정보 소개:<br><br>";
	}
?></textarea>
			<dt>
				제출서류
			<dd>
				<input type="hidden" name="idDocument" id="idDocument" value="<?=$houseObj->documentID?>" />
				<input type="text" id="txtDocument" name="txtDocument" size="60" value="<?=$houseObj->document?>" />
				<input type="button" name="imgUpload" id="imgUpload" value="파일 업로드" onclick="uploadImage(event, 'Document', 'room')" style="cursor:pointer" /> 
			<dt>
				상태코드
			<dd>
				<select name="status" id="status">
<?php 
	foreach ($codeStatus as $status) {
		if ($houseObj->status == $status->code) {
			print "<option value='".$status->code."' selected>".$status->name."</option>";
		} else {
			print "<option value='".$status->code."'>".$status->name."</option>";
		} 


	}

?>
				</select>
			<dt>&nbsp;
			<dd>
				<img src="/images/board/btn_ok.gif" border="0" onclick="check();" style="cursor:hand;">&nbsp;&nbsp;&nbsp;
				<img src="/images/board/btn_cancel.gif" border="0" onclick="history.back(-1);" style="cursor:hand"></a>
		</form>
		</dl>
	</div>

<?php } ?>

<script language="javascript">
//<![CDATA[
var oEditors = [];
nhn.husky.EZCreator.createInIFrame({
    oAppRef: oEditors,
	elPlaceHolder: "explain",
	sSkinURI: "/community/editor/SmartEditor2Skin.html",
	fCreator: "createSEditor2"
});

	function showHTML(){
		alert(oEditors.getById["contents"].getIR());
	}

	function check() {
		oEditors.getById["explain"].exec("UPDATE_CONTENTS_FIELD", []);
		document.getElementById("dataForm").action="process.php";
		document.getElementById("dataForm").submit();
	}
	
	function frmSubmit() {
		<? 
if (!isset($_SESSION['userid']) || strlen($_SESSION['userid'])==0) {
	$backURL = get_path_info();
?>
			alert("선교관등록은 로그인을 하신후에 할 수 있습니다.");
			location.href = "../member/login.php?backURL=<?=$backURL?>";
		<? } else { ?>
			oEditors.getById["explain"].exec("UPDATE_CONTENTS_FIELD", []);
			document.getElementById("dataForm").action="process.php";
			document.getElementById("dataForm").submit();
		<? } ?>
	}
//]]>
</script>
