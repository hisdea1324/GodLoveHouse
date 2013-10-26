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
											<tr>
												<th>아이디</th>
												<td>구월동 고시텔</td>
											</tr>
											<tr>
												<th>닉네임</th>
												<td><input type="text" class="inputTxt" size="30" value="" /> 공백없이 한글만 입력가능</td>
											</tr>
											<tr>
												<th>이름</th>
												<td><input type="text" class="inputTxt" size="30" value="" /></td>
											</tr>
											<tr>
												<th>E-Mail</th>
												<td><input type="text" class="inputTxt" size="10" value="" /> @ <input type="text" class="inputTxt" size="10" value="" /></td>
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
												<th>전화번호</th>
												<td><select><option>031</option></select> - <input type="text" class="inputTxt" size="10" value="" /> - <input type="text" class="inputTxt" size="10" value="" /></td>
											</tr>
											<tr>
												<th>휴대전화</th>
												<td><select><option>010</option></select> - <input type="text" class="inputTxt" size="10" value="" /> - <input type="text" class="inputTxt" size="10" value="" /></td>
											</tr>
										</tbody>
									</table>
									<div class="aRight mt20">
										<span class="btn2"><a href="#">수정</a></span></span>
										<span class="btn2"><a href="#">삭제</a></span></span>
										<span class="btn2"><a href="#">저장</a></span></span>
									</div>
									<!-- // content -->
								</div>
							</div>
							<!-- // rightSec -->

<?php } ?>

