<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

showHouseManagerHeader();
body();
showHouseManagerFooter();

function body() {
?>
							<!-- rightSec -->
							<div id="rightSec">
								<div class="lnb">
									<strong>Home</strong> &gt; 예약관리 &gt; 선교관1 &gt; 미스바관 &gt; 방전체보기
								</div>
								<div id="content">
									<!-- content -->
									<h1>선교관 관리</h1>
									<ul class="tabs mt30">
										<li><a href="reserve_1.php">달력보기</a></li>
										<li class="on"><a href="reserve_2.php">방전체보기</a></li>
									</ul>
									<div class="list_year"> <!-- list_year -->
										<ul class="mr1">
											<li><a href="#"><img src="images/btn_yprev.gif" alt="이전년도" /></a></li>
											<li class="txt">2013</li>
											<li><a href="#"><img src="images/btn_ynext.gif" alt="다음년도" /></a></li>
										</ul>
										<ul>
											<li><a href="#"><img src="images/btn_yprev.gif" alt="이전달" /></a></li>
											<li class="txt">03</li>
											<li><a href="#"><img src="images/btn_ynext.gif" alt="다음달" /></a></li>
										</ul>
									</div> <!-- // list_year -->
									<div class="cal_month2 mt20"> <!-- cal_month -->
										<table>
											<colgroup>
												<col width="7%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
												<col width="3%" />
											</colgroup>
											<thead>
												<tr>
													<th scope="col" rowspan="2" class="fs2">선교관1</th>
													<th scope="col">월</th>
													<th scope="col">화</th>
													<th scope="col">수</th>
													<th scope="col">목</th>
													<th scope="col">금</th>
													<th scope="col"><p class="blue">토</p></th>
													<th scope="col"><p class="red">일</p></th>
													<th scope="col">월</th>
													<th scope="col">화</th>
													<th scope="col">수</th>
													<th scope="col">목</th>
													<th scope="col">금</th>
													<th scope="col"><p class="blue">토</p></th>
													<th scope="col"><p class="red">일</p></th>
													<th scope="col">월</th>
													<th scope="col">화</th>
													<th scope="col">수</th>
													<th scope="col">목</th>
													<th scope="col">금</th>
													<th scope="col"><p class="blue">토</p></th>
													<th scope="col"><p class="red">일</p></th>
													<th scope="col">월</th>
													<th scope="col">화</th>
													<th scope="col">수</th>
													<th scope="col">목</th>
													<th scope="col">금</th>
													<th scope="col"><p class="blue">토</p></th>
													<th scope="col"><p class="red">일</p></th>
													<th scope="col">월</th>
													<th scope="col">화</th>
													<th scope="col">수</th>
												</tr>
												<tr>
													<th scope="col">1</th>
													<th scope="col">2</th>
													<th scope="col">3</th>
													<th scope="col">4</th>
													<th scope="col">5</th>
													<th scope="col"><p class="blue">6</p></th>
													<th scope="col"><p class="red">7</p></th>
													<th scope="col">8</th>
													<th scope="col">9</th>
													<th scope="col">10</th>
													<th scope="col">11</th>
													<th scope="col">12</th>
													<th scope="col"><p class="blue">13</p></th>
													<th scope="col"><p class="red">14</p></th>
													<th scope="col">15</th>
													<th scope="col">16</th>
													<th scope="col">17</th>
													<th scope="col">18</th>
													<th scope="col">19</th>
													<th scope="col"><p class="blue">20</p></th>
													<th scope="col"><p class="red">21</p></th>
													<th scope="col">22</th>
													<th scope="col">23</th>
													<th scope="col">24</th>
													<th scope="col">25</th>
													<th scope="col">26</th>
													<th scope="col"><p class="blue">27</p></th>
													<th scope="col"><p class="red">28</p></th>
													<th scope="col">29</th>
													<th scope="col">30</th>
													<th scope="col">31</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<th><strong>샬롬관</strong></th>
													<td colspan="30"><div class="check cb1" style="width:50%; margin-left:20%">정진화</div></td>
													<td></td>
												</tr>
												<tr>
													<th><strong>화평관</strong></th>
													<td colspan="30"><div class="check cb2" style="width:50%; margin-left:10%">정진화</div></td>
													<td></td>
												</tr>
												<tr>
													<th><strong>미스바관</strong></th>
													<td colspan="30"><div class="check cb3" style="width:50%; margin-left:20%">정진화</div></td>
													<td></td>
												</tr>
												<tr>
													<th><strong>샬롬관</strong></th>
													<td colspan="30"><div class="check cb4" style="width:50%; margin-left:20%">정진화</div></td>
													<td></td>
												</tr>
												<tr>
													<th><strong>샬롬관</strong></th>
													<td colspan="30"><div class="check cb5" style="width:50%; margin-left:10%">정진화</div></td>
													<td></td>
												</tr>
												<tr>
													<th><strong>샬롬관</strong></th>
													<td colspan="30"><div class="check cb6" style="width:50%; margin-left:20%">정진화</div></td>
													<td></td>
												</tr>
											</tbody>
										</table>
									</div> <!-- // cal_month -->
									<div class="search">
										<span class="mr20"><strong>SEARCH ></strong></span>
										<select><option>지역선택</option></select>
										<select><option>선교관선택</option></select>
										<input type="text" class="inputTxt" size="16" />
										<a href="#"><img src="images/btn_calendar.gif" alt="날짜선택" /></a> - 
										<input type="text" class="inputTxt" size="16" />
										<a href="#"><img src="images/btn_calendar.gif" alt="날짜선택" /></a>
										<span class="btn1"><a href="#">검색</a></span>
									</div>
									<div class="list mt10">
										<div class="view" style="margin-left:18%; top:38px; display:none">
											<div class="tit">
												신청자 정보
												<span class="btn1w" style="position:absolute; right:0px; top:-3px"><a href="#">상세보기</a></span>
											</div>
											<ul>
												<li><p>성명</p> 오재호</li>
												<li><p>생년월일</p> 2001.09.09</li>
												<li><p>성별</p> 남</li>
												<li><p>파송단체</p> 나눔교회</li>
												<li><p>선교지</p> 대한민국</li>
												<li><p>사역기간</p> 20년</li>
												<li><p>가족</p> 성인 2명 / 청소년 1명 / 영유아 2명</li>
												<li><p>연락처</p> 010-000-0000</li>
												<li><p>이메일</p> asdf@naver.com</li>
												<li><p>취소횟수</p> 3회</li>
											</ul>
										</div>
										<table>
											<colgroup>
												<col width="10%" />
												<col width="10%" />
												<col width="20%" />
												<col width="30%" />
												<col width="30%" />
											</colgroup>
											<thead>
												<tr>
													<th>예약번호</th>
													<th>이름</th>
													<th>선교관/선교관 방이름</th>
													<th>일정</th>
													<th>상태</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>123456</td>
													<td>문지기</td>
													<td>갓러브하우스/큰방</td>
													<td>2013.08.08~2013.09.09</td>
													<td>
														<span class="btn1g"><a href="#">승인</a></span> 
														<span class="btn1g"><a href="#">거절</a></span> 
														<span class="btn1g"><a href="#">수정</a></span>
													</td>
												</tr>
												<tr>
													<td>123456</td>
													<td>문지기</td>
													<td>갓러브하우스/큰방</td>
													<td>2013.08.08~2013.09.09</td>
													<td>
														<span class="btn1g"><a href="#">승인</a></span> 
														<span class="btn1g"><a href="#">거절</a></span> 
														<span class="btn1g"><a href="#">수정</a></span>
													</td>
												</tr>
												<tr>
													<td>123456</td>
													<td>문지기</td>
													<td>갓러브하우스/큰방</td>
													<td>2013.08.08~2013.09.09</td>
													<td>
														<span class="btn1g"><a href="#">승인</a></span> 
														<span class="btn1g"><a href="#">거절</a></span> 
														<span class="btn1g"><a href="#">수정</a></span>
													</td>
												</tr>
												<tr>
													<td>123456</td>
													<td>문지기</td>
													<td>갓러브하우스/큰방</td>
													<td>2013.08.08~2013.09.09</td>
													<td>
														<span class="btn1g"><a href="#">승인</a></span> 
														<span class="btn1g"><a href="#">거절</a></span> 
														<span class="btn1g"><a href="#">수정</a></span>
													</td>
												</tr>
												<tr>
													<td>123456</td>
													<td>문지기</td>
													<td>갓러브하우스/큰방</td>
													<td>2013.08.08~2013.09.09</td>
													<td>
														<span class="btn1g"><a href="#">승인</a></span> 
														<span class="btn1g"><a href="#">거절</a></span> 
														<span class="btn1g"><a href="#">수정</a></span>
													</td>
												</tr>
												<tr>
													<td>123456</td>
													<td>문지기</td>
													<td>갓러브하우스/큰방</td>
													<td>2013.08.08~2013.09.09</td>
													<td>
														<span class="btn1g"><a href="#">승인</a></span> 
														<span class="btn1g"><a href="#">거절</a></span> 
														<span class="btn1g"><a href="#">수정</a></span>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="paging">
										<a href="#"><img src="images/btn_page_first.gif" alt="처음" /></a>
										<a href="#"><img src="images/btn_page_prev.gif" alt="이전" /></a>
										<a href="#"><strong>1</strong></a>
										<a href="#">2</a>
										<a href="#">3</a>
										<a href="#">4</a>
										<a href="#">5</a>
										<a href="#">6</a>
										<a href="#">7</a>
										<a href="#">8</a>
										<a href="#">9</a>
										<a href="#">10</a>
										<a href="#"><img src="images/btn_page_next.gif" alt="다음" /></a>
										<a href="#"><img src="images/btn_page_last.gif" alt="마지막" /></a>
									</div>
									<!-- // content -->
								</div>
							</div>
							<!-- // rightSec -->
							<!-- // rightSec -->
<?php } ?>