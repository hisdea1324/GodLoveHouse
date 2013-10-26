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
												<td>구월동 고시텔</td>
											</tr>
											<tr>
												<th>방이름</th>
												<td><input type="text" class="inputTxt" size="50" value="" /></td>
											</tr>
											<tr>
												<th>인터넷 유무</th>
												<td>
													<input type="radio" /> 있음 
													<input type="radio" class="ml20"/> 없음
												</td>
											</tr>
											<tr>
												<th>취사여부</th>
												<td>
													<input type="radio" /> 가능 
													<input type="radio" class="ml20"/> 불가능
												</td>
											</tr>
											<tr>
												<th>세탁여부</th>
												<td>
													<input type="radio" /> 가능 
													<input type="radio" class="ml20"/> 불가능
												</td>
											</tr>
											<tr>
												<th>요금</th>
												<td><input type="text" class="inputTxt" size="30" value="" /> 원</td>
											</tr>
											<tr>
												<th>방인원수</th>
												<td><input type="text" class="inputTxt" size="30" value="" /> 명</td>
											</tr>
											<tr>
												<th>색상선택</th>
												<td>
													<ul class="chart_c">
														<li><input type="radio" value="1"/><div class="color c1"></div></li>
														<li><input type="radio" value="2"/><div class="color c2"></div></li>
														<li><input type="radio" value="3"/><div class="color c3"></div></li>
														<li><input type="radio" value="4"/><div class="color c4"></div></li>
														<li><input type="radio" value="5"/><div class="color c5"></div></li>
														<li><input type="radio" value="6"/><div class="color c6"></div></li>
														<li><input type="radio" value="7"/><div class="color c7"></div></li>
														<li><input type="radio" value="8"/><div class="color c8"></div></li>
														<li><input type="radio" value="9"/><div class="color c9"></div></li>
														<li><input type="radio" value="10"/><div class="color c10"></div></li>
													</ul>
												</td>
											</tr>
											<tr>
												<th>이미지</th>
												<td>
													<span class="btn1"><a href="#">이미지등록</a></span> <span class="btn1g"><a href="#">+ 이미지추가</a></span><br />
													<div class="img"></div>
													<div class="img"></div>
													<div class="img"></div>
													<div class="img"></div>
												</td>
											</tr>
										</tbody>
									</table>
									<div class="aRight mt20">
										<span class="btn2"><a href="#">수정</a></span>
										<span class="btn2"><a href="#">삭제</a></span>
									</div>
									<!-- // content -->
								</div>
							</div>
							<!-- // rightSec -->
<?php } ?>
