<?
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
checkUserLogin(7);

showHouseManagerHeader();
showHouseManagerLeft();
body();
showHouseManagerFooter();

function body() {
	$c_Helper = new CodeHelper();
	$codeRegions = $c_Helper->getLocalCodeList();
	$codeStatus = $c_Helper->getHouseStatusCodeList();

	$houseId = (isset($_REQUEST["houseId"])) ? trim($_REQUEST["houseId"]) : "";
	$h_helper = new HouseHelper();
	$house = $h_helper->getHouseInfoById($houseId);

	setTestValue($house);
?>
	<script type="text/javascript" src="/community/editor/js/HuskyEZCreator.js" charset="utf-8"></script>
	
	<!-- rightSec -->
	<div id="rightSec">
		<div class="lnb">
			<strong>Home</strong> &gt; 예약관리 <? 
			echo "&gt; {$house->houseName} ";
			echo "&gt; 정보수정";
			?>
		</div>
		<div id="content">
			<!-- content -->
			<!--h1>선교관 정보수정 - <?=$house->houseName?></h1-->
			<div class="list_year"> 
				<ul class="mr1">
					<li class="txt"><?=$house->houseName?></li>
				</ul>
				<ul class="tabs mt30">
						<li><a href="reserve_2.php?houseId=<?=$houseId?>">예약 현황 보기</a></li>
						<li class="on"><a href="mission_write.php?houseId=<?=$houseId?>">정보수정</a></li>
						<li><a href="mission_write2.php?houseId=<?=$houseId?>">방 추가하기</a></li>
				</ul>
			</div>
			<table class="write mt30">
				<colgroup>
					<col width="20%" />
					<col width="80%" />
				</colgroup>
				<tbody>
					<form name="dataForm" id="dataForm" method="post">
					<input type="hidden" name="mode" id="mode" value="regist" />
					<input type="hidden" name="userid" id="userid" value="<?=$_SESSION["userid"]?>" />
					<input type="hidden" name="houseId" id="houseId" value="<?=$house->HouseID?>" />
					<tr>
						<th>선교관이름</th>
						<td><input type="text" name="houseName" id="houseName" class="inputTxt" maxlength="40" size="50" value="<?=$house->houseName?>" /></td>
					</tr>
					<tr>
						<th>운영단체</th>
						<td><input type="text" name="assocName" id="assocName" class="inputTxt" maxlength="50" size="50" value="<?=$house->AssocName?>" /></td>
					</tr>
					<tr>
						<th>담당자이름1</th>
						<td><input type="text" name="manager" id="manager" class="inputTxt" maxlength="30" size="50" value="<?=$house->Manager1?>" /></td>
					</tr>
					<tr>
						<th>담당자연락처1</th>
						<td>
							<select name="contact11" id="contact11">
								<option>031</option>
								<option value="010" <?php if ($house->Contact1[0] == "010") { print "selected"; } ?>>010</option>
								<option value="011" <?php if ($house->Contact1[0] == "011") { print "selected"; } ?>>011</option>
								<option value="016" <?php if ($house->Contact1[0] == "016") { print "selected"; } ?>>016</option>
								<option value="017" <?php if ($house->Contact1[0] == "017") { print "selected"; } ?>>017</option>
								<option value="018" <?php if ($house->Contact1[0] == "018") { print "selected"; } ?>>018</option>
								<option value="019" <?php if ($house->Contact1[0] == "019") { print "selected"; } ?>>019</option>
								<option value="02" <?php if ($house->Contact1[0] == "02") { print "selected"; } ?>>02</option>
								<option value="031" <?php if ($house->Contact1[0] == "031") { print "selected"; } ?>>031</option>
								<option value="032" <?php if ($house->Contact1[0] == "032") { print "selected"; } ?>>032</option>
								<option value="033" <?php if ($house->Contact1[0] == "033") { print "selected"; } ?>>033</option>
								<option value="041" <?php if ($house->Contact1[0] == "041") { print "selected"; } ?>>041</option>
								<option value="042" <?php if ($house->Contact1[0] == "042") { print "selected"; } ?>>042</option>
								<option value="043" <?php if ($house->Contact1[0] == "043") { print "selected"; } ?>>043</option>
								<option value="051" <?php if ($house->Contact1[0] == "051") { print "selected"; } ?>>051</option>
								<option value="052" <?php if ($house->Contact1[0] == "052") { print "selected"; } ?>>052</option>
								<option value="053" <?php if ($house->Contact1[0] == "053") { print "selected"; } ?>>053</option>
								<option value="054" <?php if ($house->Contact1[0] == "054") { print "selected"; } ?>>054</option>
								<option value="055" <?php if ($house->Contact1[0] == "055") { print "selected"; } ?>>055</option>
								<option value="061" <?php if ($house->Contact1[0] == "061") { print "selected"; } ?>>061</option>
								<option value="062" <?php if ($house->Contact1[0] == "062") { print "selected"; } ?>>062</option>
								<option value="063" <?php if ($house->Contact1[0] == "063") { print "selected"; } ?>>063</option>
								<option value="064" <?php if ($house->Contact1[0] == "064") { print "selected"; } ?>>064</option>
								<option value="070" <?php if ($house->Contact1[0] == "070") { print "selected"; } ?>>070</option>
							</select> - 
							<input type="text" name="contact12" id="contact12" style="ime-mode:disabled;" onKeyPress="CheckNumber(event);" class="inputTxt" maxlength="4" size="10" value="<?=$house->Contact1[1]?>" /> - 
							<input type="text" name="contact13" id="contact13" style="ime-mode:disabled;" onKeyPress="CheckNumber(event);" class="inputTxt" maxlength="4" size="10" value="<?=$house->Contact1[2]?>" />
						</td>
					</tr>
					<tr>
						<th>담당자연락처2</th>
						<td>
							<select name="contact21" id="contact21">
								<option>031</option>
								<option value="010" <?php if ($house->Contact2[0] == "010") { print "selected"; } ?>>010</option>
								<option value="011" <?php if ($house->Contact2[0] == "011") { print "selected"; } ?>>011</option>
								<option value="016" <?php if ($house->Contact2[0] == "016") { print "selected"; } ?>>016</option>
								<option value="017" <?php if ($house->Contact2[0] == "017") { print "selected"; } ?>>017</option>
								<option value="018" <?php if ($house->Contact2[0] == "018") { print "selected"; } ?>>018</option>
								<option value="019" <?php if ($house->Contact2[0] == "019") { print "selected"; } ?>>019</option>
								<option value="02" <?php if ($house->Contact2[0] == "02") { print "selected"; } ?>>02</option>
								<option value="031" <?php if ($house->Contact2[0] == "031") { print "selected"; } ?>>031</option>
								<option value="032" <?php if ($house->Contact2[0] == "032") { print "selected"; } ?>>032</option>
								<option value="033" <?php if ($house->Contact2[0] == "033") { print "selected"; } ?>>033</option>
								<option value="041" <?php if ($house->Contact2[0] == "041") { print "selected"; } ?>>041</option>
								<option value="042" <?php if ($house->Contact2[0] == "042") { print "selected"; } ?>>042</option>
								<option value="043" <?php if ($house->Contact2[0] == "043") { print "selected"; } ?>>043</option>
								<option value="051" <?php if ($house->Contact2[0] == "051") { print "selected"; } ?>>051</option>
								<option value="052" <?php if ($house->Contact2[0] == "052") { print "selected"; } ?>>052</option>
								<option value="053" <?php if ($house->Contact2[0] == "053") { print "selected"; } ?>>053</option>
								<option value="054" <?php if ($house->Contact2[0] == "054") { print "selected"; } ?>>054</option>
								<option value="055" <?php if ($house->Contact2[0] == "055") { print "selected"; } ?>>055</option>
								<option value="061" <?php if ($house->Contact2[0] == "061") { print "selected"; } ?>>061</option>
								<option value="062" <?php if ($house->Contact2[0] == "062") { print "selected"; } ?>>062</option>
								<option value="063" <?php if ($house->Contact1[0] == "063") { print "selected"; } ?>>063</option>
								<option value="064" <?php if ($house->Contact2[0] == "064") { print "selected"; } ?>>064</option>
								<option value="070" <?php if ($house->Contact2[0] == "070") { print "selected"; } ?>>070</option>
							</select> - 
							<input type="text" name="contact22" id="contact22" style="ime-mode:disabled;" onKeyPress="CheckNumber(event);" class="inputTxt" maxlength="4" size="10" value="<?=$house->Contact2[1]?>" /> - 
							<input type="text" name="contact33" id="contact33" style="ime-mode:disabled;" onKeyPress="CheckNumber(event);" class="inputTxt" maxlength="4" size="10" value="<?=$house->Contact2[2]?>" />
						</td>
					</tr>
					<tr>
						<th>주거형태</th>
						<td>
							<input type="radio" name="buildType" id="buildType" value="1" class="ml20" <?php if ($house->buildingType == 1) { print "checked"; } ?> />아파트 
							<input type="radio" name="buildType" id="buildType" value="2" class="ml20" <?php if ($house->buildingType == 2) { print "checked"; } ?> /> 원룸 
							<input type="radio" name="buildType" id="buildType" value="3" class="ml20" <?php if ($house->buildingType == 3) { print "checked"; } ?> /> 빌라 
							<input type="radio" name="buildType" id="buildType" value="4" class="ml20" <?php if ($house->buildingType == 4) { print "checked"; } ?> /> 기타
						</td>
					</tr>
					<tr>
						<th>지역코드</th>
						<td>
							<select name="region" id="region">
								<option value=''>-- 지역선택 --</option>
							<? 
								foreach ($codeRegions as $region) {
									if ($house->regionCode == $region->code) {
										print "<option value='".$region->code."' selected>".$region->name."</option>";
									} else {
										print "<option value='".$region->code."'>".$region->name."</option>";
									} 


								}
							?>
						</td>
					</tr>
					<tr>
						<th>우편번호</th>
						<td>
							<input type="text" name="post1" id="post1" class="inputTxt" size="10" readonly onclick="PostPopup();" value="<?=$house->zipcode[0]?>" />
							-
							<input type="text" name="post2" id="post2" class="inputTxt" size="10" readonly onclick="PostPopup();" value="<?=$house->zipcode[1]?>" />
							<span class="btn1"><a href="javascript:void(0)" onclick="PostPopup();" style="cursor:pointer;">우편번호찾기</a></span>
						</td>
					</tr>
					<tr>
						<th>주소</th>
						<td>
							<input type="text" name="addr1" id="addr1" class="inputTxt" size="100" readonly onclick="PostPopup();" value="<?=$house->address1?>" />
							<input type="text" name="addr2" id="addr2" class="inputTxt" size="100" value="<?=$house->address2?>" />
						</td>
					</tr>
					<tr>
						<th>가격(1일기준)</th>
						<td>
							<input type="text" name="price" id="price" class="inputTxt" size="10" style="ime-mode:disabled;" value="<?=$house->price?>" /> 원 ~
							<input type="text" name="price1" id="price1" class="inputTxt" size="10" style="ime-mode:disabled;" value="<?=$house->price1?>" /> 원
						</td>
					</tr>
					<tr>
						<th>최대인원</th>
						<td>
							<input type="text" name="personLimit" id="personLimit" class="inputTxt" size="10" style="ime-mode:disabled;" onKeyPress="CheckNumber(event);" value="<?=$house->personLimit?>" /> 명 ~
							<input type="text" name="personLimit1" id="personLimit1" class="inputTxt" size="10" style="ime-mode:disabled;" onKeyPress="CheckNumber(event);" value="<?=$house->personLimit1?>" />
						</td>
					</tr>
					<tr>
						<th>방갯수</th>
						<td>
							<input type="text" name="roomLimit" id="roomLimit" class="inputTxt" size="10" style="ime-mode:disabled;" onKeyPress="CheckNumber(event);" value="<?=$house->roomLimit?>" /> 개
						</td>
					</tr>
					<tr>
						<th>선교관설명</th>
						<td>
							<textarea name="explain" id="explain" style="width:600px; height:300px;">
<? 
	if ($house->explain) {
		echo $house->explain;
	} else {
		echo "운영자 소개: <br><br>방정보 소개:<br><br>";
	}
?></textarea>
						</td>
					</tr>
					<tr>
						<th>홈페이지</th>
						<td>
							<input type="text" name="homepage" id="homepage" class="inputTxt" size="100" value="<?=$house->homepageNoLink?>" />
						</td>
					</tr>
					<tr>
						<th>제출서류</th>
						<td>
							<input type="hidden" name="idDocument" id="idDocument" value="<?=$house->documentID?>" />
							<input type="text" id="txtDocument" name="txtDocument" class="inputTxt" size="80" value="<?=$house->document?>" />
							<span class="btn1"><a href="javascript:void(0)" onclick="uploadImage(event, 'Document', 'room');" style="cursor:pointer;">파일 업로드</a></span>
							<!--input type="hidden" name="idDocument2" id="idDocument2" value="<?=$house->documentID2?>" />
							<input type="text" id="txtDocument2" name="txtDocument2" class="inputTxt" size="80" value="<?=$house->document2?>" /> 
							<span class="btn1"><a href="javascript:void(0)" onclick="uploadImage(event, 'Document2', 'room');" style="cursor:pointer;">파일 업로드</a></span-->
					</tr>
					<tr>
						<th>상태코드</th>
						<td>
							<select name="status" id="status">
							<? 
								foreach ($codeStatus as $status) {
									if ($status->code == "S2001" || $status->code == "S2003") continue;
									if ($house->statusCode == $status->code) {
										print "<option value='".$status->code."' selected>".$status->name."</option>";
									} else {
										print "<option value='".$status->code."'>".$status->name."</option>";
									} 
								}
							?>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="aRight mt20">
				<span class="btn2"><a href="javascript:void(0)" onclick="check();">확인</a></span>
				<!--span class="btn2"><a href="javascript:void(0)" onclick="history.back(-1);">취</a></span-->
			</div>
			<!-- // content -->
		</div>
	</div>
	<!-- // rightSec -->
<? } ?>

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
		oEditors.getById["explain"].exec("UPDATE_CONTENTS_FIELD", []);
		<?
		if (!isset($_SESSION['userid']) || strlen($_SESSION['userid'])==0) {
			$backURL = get_path_info();
		?>
			alert("선교관등록은 로그인을 하신후에 할 수 있습니다.");
			location.href = "../member/login.php?backURL=<?=$backURL?>";
		<? } else { ?>
			document.getElementById("dataForm").action="process.php";
			document.getElementById("dataForm").submit();
		<? } ?>
	}
//]]>
</script>

