<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

showHouseManagerHeader();
showHouseManagerLeft();
body();
showHouseManagerFooter();

function body() {
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
												<td><input type="text" class="inputTxt" size="50" value="" /></td>
											</tr>
											<tr>
												<th>선교관 관리자</th>
												<td><input type="text" class="inputTxt" size="50" value="" /></td>
											</tr>
											<tr>
												<th>운영단체</th>
												<td><input type="text" class="inputTxt" size="50" value="" /></td>
											</tr>
											<tr>
												<th>담당자이름1</th>
												<td><input type="text" class="inputTxt" size="50" value="" /></td>
											</tr>
											<tr>
												<th>담당자연락처1</th>
												<td><select><option>031</option></select> - <input type="text" class="inputTxt" size="10" value="" /> - <input type="text" class="inputTxt" size="10" value="" /></td>
											</tr>
											<tr>
												<th>담당자연락처2</th>
												<td><select><option>031</option></select> - <input type="text" class="inputTxt" size="10" value="" /> - <input type="text" class="inputTxt" size="10" value="" /></td>
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
