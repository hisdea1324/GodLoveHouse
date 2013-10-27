<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

showHouseManagerHeader();
showHouseManagerLeft();
body();
showHouseManagerFooter();

function body() {
	$houseId = (isset($_REQUEST["houseId"])) ? trim($_REQUEST["houseId"]) : "";
	$h_helper = new HouseHelper();
	$house = $h_helper->getHouseInfoById($houseId);

	setTestValue($house);
?>
	<!-- rightSec -->
	<div id="rightSec">
		<div class="lnb">
			<strong>Home</strong> &gt; 선교관 관리 &gt; 등록하기
		</div>
		<div id="content">
			<!-- content -->
			<h1>선교관 관리</h1>
			<table class="write mt30">
				<colgroup>
					<col width="20%" />
					<col width="80%" />
				</colgroup>
				<tbody>
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
							<select name="contact1" id="contact1">
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
							<input type="text" name="contact2" id="contact2" style="ime-mode:disabled;" onKeyPress="CheckNumber(event);" class="inputTxt" maxlength="4" size="10" value="<?=$house->Contact1[1]?>" /> - 
							<input type="text" name="contact3" id="contact3" style="ime-mode:disabled;" onKeyPress="CheckNumber(event);" class="inputTxt" maxlength="4" size="10" value="<?=$house->Contact1[2]?>" />
						</td>
					</tr>
					<tr>
						<th>담당자연락처2</th>
						<td><select><option>031</option></select> - 
							<input type="text" class="inputTxt" size="10" value="" /> - 
							<input type="text" class="inputTxt" size="10" value="" />
						</td>
					</tr>
					<tr>
						<th>주거형태</th>
						<td>
							<input type="radio" checked="checked" />아파트 
							<input type="radio" class="ml20"/> 원룸 
							<input type="radio" class="ml20"/> 빌라 
							<input type="radio" class="ml20"/> 기타
						</td>
					</tr>
					<tr>
						<th>지역코드</th>
						<td><select><option>지역선택</option></select></td>
					</tr>
					<tr>
						<th>우편번호</th>
						<td><input type="text" class="inputTxt" size="10" value="" /> - <input type="text" class="inputTxt" size="10" value="" /> <span class="btn1"><a href="#">우편번호찾기</a></span></td>
					</tr>
					<tr>
						<th>주소</th>
						<td><input type="text" class="inputTxt" size="100" value="" /></td>
					</tr>
					<tr>
						<th>가격(1일기준)</th>
						<td><input type="text" class="inputTxt" size="10" value="" /> 원 ~ <input type="text" class="inputTxt" size="10" value="" /> 원</td>
					</tr>
					<tr>
						<th>최대인원</th>
						<td><input type="text" class="inputTxt" size="10" value="" /> 명 ~ <input type="text" class="inputTxt" size="10" value="" /> 명</td>
					</tr>
					<tr>
						<th>방갯수</th>
						<td><input type="text" class="inputTxt" size="10" value="" /> 개</td>
					</tr>
					<tr>
						<th>선교관설명</th>
						<td>
							<p><strong>운영자 소개</strong></p>
							<textarea cols="100" class="inputTxt"></textarea> 
							<p><strong>방정보 소개</strong></p>
							<textarea cols="100" class="inputTxt"></textarea> 
						</td>
					</tr>
					<tr>
						<th>홈페이지</th>
						<td><input type="text" class="inputTxt" size="100" value="" /></td>
					</tr>
					<tr>
						<th>제출서류</th>
						<td><input type="text" class="inputTxt" size="100" value="" /> <span class="btn1"><a href="#">파일업로드</a></span></td>
					</tr>
					<tr>
						<th>상태코드</th>
						<td>
							<input type="radio" checked="checked" />공개 
							<input type="radio" class="ml20"/> 비공개
						</td>
					</tr>
				</tbody>
			</table>
			<div class="aRight mt20">
				<span class="btn2"><a href="#">취소</a></span>
				<span class="btn2"><a href="#">확인</a></span>
			</div>
			<!-- // content -->
		</div>
	</div>
	<!-- // rightSec -->
<?php } ?>
