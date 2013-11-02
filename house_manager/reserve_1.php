<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

showHouseManagerHeader();
showHouseManagerLeft();
body();
showHouseManagerFooter();

function body() {
	$roomId = (isset($_REQUEST["roomId"])) ? trim($_REQUEST["roomId"]) : "";
	$houseId = (isset($_REQUEST["houseId"])) ? trim($_REQUEST["houseId"]) : "";

	$h_helper = new HouseHelper();
	$house = $h_helper->getHouseInfoById($houseId);

	//******************************************************************
	// 달력 세팅
	$StartYear = 2012;

	$calendar = new CalendarBuilder();
	$calendar->CYear = isset($_REQUEST["y"]) ? trim($_REQUEST["y"]) : "";
	$calendar->CMonth = isset($_REQUEST["m"]) ? trim($_REQUEST["m"]) : "";

	$fromDate = $calendar->CurrentMonth.$calendar->DataFormat(1);
	$toDate = $calendar->NextMonth.$calendar->DataFormat(1);
	$s_Helper = new supportHelper();
	$dailySupport = $s_Helper->getDailySupport($fromDate, $toDate);
	$senders = $s_Helper->getSender($fromDate, $toDate);
	//******************************************************************

?>
							<!-- rightSec -->
							<div id="rightSec">
								<div class="lnb">
									<strong>Home</strong> &gt; 예약관리 &gt; <?=$house->houseName?>
								</div>
								<div id="content">
									<!-- content -->
									<h1><?=$house->houseName?> <span class="btn1"><a href="mission_write.php?houseId=<?=$houseId?>">선교관 정보수정</a></span></h1>
									<ul class="tabs mt30">
										<li class="on"><a href="reserve_1.php">달력보기</a></li>
										<li><a href="reserve_2.php">방전체보기</a></li>
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
									<div class="cal_month mt20"> <!-- cal_month -->
										<table>
											<colgroup>
												<col width="14%" />
												<col width="14%" />
												<col width="14%" />
												<col width="14%" />
												<col width="14%" />
												<col width="14%" />
												<col width="14%" />
											</colgroup>
											<thead>
												<tr>
													<th scope="col">Sun.</th>
													<th scope="col">Mon.</th>
													<th scope="col">Tue.</th>
													<th scope="col">Wed.</th>
													<th scope="col">Thu.</th>
													<th scope="col">Fri.</th>
													<th scope="col">Sat.</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>
														<div class="cal_day">
															<p class="red"></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">1</a></p>
															<div class="view" style="display:none">
																<div class="tit">2013.05.05 예약현황</div>
																<ul>
																	<li>[샬롬관] 정진화집사 (4인)</li>
																	<li>오재호 선교사 / 샬롬관</li>
																	<li>오재호 선교사 / 샬롬관</li>
																	<li>오재호 선교사 / 샬롬관</li>
																	<li>오재호 선교사 / 샬롬관</li>
																	<li>오재호 선교사 / 샬롬관</li>
																	<li>오재호 선교사 / 샬롬관</li>
																</ul>
															</div>
															<ul>
																<li class="cb1" style="width:199%;"><a href="#">오재호선교1/샬롬관오재호선교1/샬롬관오재호선교1</a></li>
																<li class="cb2"><a href="#">[샬롬관] 정진화집사 (4인)</a></li>
																<li class="cb3"><a href="#">오재호선교1/샬롬관</a></li>
																<li class="cb4"><a href="#">오재호선교1/샬롬관</a></li>
																<li class="cb5"><a href="#">오재호선교1/샬롬관</a></li>
															</ul>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p class="blue"><a href="#">2</a></p>
														</div>									
													</td>
												</tr>
												<tr>
													<td>
														<div class="cal_day">
															<p class="red"><a href="#">3</a></p>
															<ul>
																<li class="cb6" style="width:299%;"><a href="#">오재호선교1/샬롬관 오재호선교1/샬롬관 오재호선교1/샬롬관</a></li>
																<li class="cb7"><a href="#">오재호선교1/샬롬관</a></li>
																<li class="cb8"><a href="#">오재호선교1/샬롬관</a></li>
																<li class="cb9"><a href="#">오재호선교1/샬롬관</a></li>
																<li class="cb10"><a href="#">오재호선교1/샬롬관</a></li>
															</ul>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">4</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">5</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">6</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">7</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">8</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p class="blue"><a href="#">9</a></p>
														</div>									
													</td>
												</tr>
												<tr>
													<td>
														<div class="cal_day">
															<p class="red"><a href="#">10</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">11</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">12</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">13</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">14</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">15</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p class="blue"><a href="#">16</a></p>
														</div>									
													</td>
												</tr>
												<tr>
													<td>
														<div class="cal_day">
															<p class="red"><a href="#">17</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">18</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">19</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">20</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">21</p></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">22</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p class="blue"><a href="#">23</a></p>
														</div>									
													</td>
												</tr>
												<tr>
													<td>
														<div class="cal_day">
															<p class="red"><a href="#">24</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">25</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">26</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">27</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">28</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p><a href="#">29</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p class="blue"><a href="#">30</a></p>
														</div>									
													</td>
												</tr>
												<tr>
													<td>
														<div class="cal_day">
															<p class="red"><a href="#">31</a></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p></p>
														</div>									
													</td>
													<td>
														<div class="cal_day">
															<p class="blue"></p>
														</div>									
													</td>
												</tr>
											</tbody>
										</table>
									</div> <!-- // cal_month -->
									<!-- // content -->
								</div>
							</div>
							<!-- // rightSec -->
<?php } ?>
