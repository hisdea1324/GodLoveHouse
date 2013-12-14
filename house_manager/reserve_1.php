<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
checkUserLogin(7);

showHouseManagerHeader();
showHouseManagerLeft();
body();
showHouseManagerFooter();

function body() {
	global $mysqli;
	
	$roomId = (isset($_REQUEST["roomId"])) ? trim($_REQUEST["roomId"]) : "";
	$houseId = (isset($_REQUEST["houseId"])) ? trim($_REQUEST["houseId"]) : "";

	$toDate = isset($_REQUEST["toDate"]) ? trim($_REQUEST["toDate"]) : "";
	$fromDate = isset($_REQUEST["fromDate"]) ? trim($_REQUEST["fromDate"]) : "";

	$h_helper = new HouseHelper();
	$house = $h_helper->getHouseInfoById($houseId);

	//******************************************************************
	// 달력 세팅
	$calendar['year'] = isset($_REQUEST["year"]) ? trim($_REQUEST["year"]) : date("Y");
	$calendar['month'] = isset($_REQUEST["month"]) ? trim($_REQUEST["month"]) : date("m");
	setTestValue($calendar);

	$fromDate = mktime(0, 0, 0, $calendar['month'], 1, $calendar['year']);
	$toDate = mktime(0, 0, 0, $calendar['month'] + 1, 1, $calendar['year']);

	// year month correcting
	$calendar['year'] = date('Y', $fromDate);
	$calendar['month'] = date('m', $fromDate);
	//******************************************************************

	//******************************************************************
	// request query pre setting
	$q[0] = "houseId=".$houseId;
	$q[1] = "roomId=".$roomId;
	$q[2] = "year=".($calendar['year'] - 1);
	$q[3] = "year=".$calendar['year'];
	$q[4] = "year=".($calendar['year'] + 1);
	$q[5] = "month=".($calendar['month'] - 1);
	$q[6] = "month=".($calendar['month']);
	$q[7] = "month=".($calendar['month'] + 1);
	//******************************************************************
	
	$query = "SELECT A.houseId, B.roomName, C.* FROM house A, room B, reservation C";
	$query = $query." WHERE A.userid = '{$_SESSION['userid']}' AND A.houseId = B.houseId AND B.roomId = C.roomId AND C.reservStatus <> 'S0004'";
	$query = $query." AND ((endDate >= {$fromDate} AND startDate < {$toDate})";
	$query = $query." OR (startDate < {$fromDate} AND endDate >= {$toDate}))";
	$query = $query." ORDER BY startDate";

	$reservations = array();
	if ($result = $mysqli->query($query)) {
		while ($row = $result->fetch_assoc()) {
			array_push($reservation, $row);
		}
	}
?>
							<!-- rightSec -->
							<div id="rightSec">
								<div class="lnb">
									<strong>Home</strong> &gt; 예약관리 &gt; <?=$house->houseName?>
								</div>
								<div id="content">
									<!-- content -->
									<!--h1><?=$house->houseName?> <span class="btn1"><a href="mission_write.php?houseId=<?=$houseId?>">선교관 정보수정</a></span></h1-->
									<div class="list_year"> <!-- list_year -->
										<ul class="mr1">
											<li><a href="reserve_1.php?<?="{$q[0]}&{$q[1]}&{$q[2]}&{$q[6]}"?>"><img src="images/btn_yprev.gif" alt="이전년도" /></a></li>
											<li class="txt"><?=$calendar['year']?></li>
											<li><a href="reserve_1.php?<?="{$q[0]}&{$q[1]}&{$q[4]}&{$q[6]}"?>"><img src="images/btn_ynext.gif" alt="다음년도" /></a></li>
										</ul>
										<ul>
											<li><a href="reserve_1.php?<?="{$q[0]}&{$q[1]}&{$q[3]}&{$q[5]}"?>"><img src="images/btn_yprev.gif" alt="이전달" /></a></li>
											<li class="txt"><?=$calendar['month']?></li>
											<li><a href="reserve_1.php?<?="{$q[0]}&{$q[1]}&{$q[3]}&{$q[7]}"?>"><img src="images/btn_ynext.gif" alt="다음달" /></a></li>
										</ul>
										<!--ul class="tabs mt30">
											<li class="on"><a href="reserve_1.php">달력보기</a></li>
											<li><a href="reserve_2.php">방전체보기</a></li>
										</ul-->
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
												<? for ($i = 0; $i < date("w", $fromDate); $i++) { ?>
													<? if ($i == 0) { ?>
													<tr>
													<? } ?>
													<td>
														<div class="cal_day">
															<p> </p>
														</div>									
													</td>
													<? if ($i == 6) { ?>
													</tr>
													<? } ?>
												<? } ?>
												<? for ($i = $fromDate; $i < $toDate; $i += 86400) { ?>
													<? if (date('w', $i) == 0) { ?>
													<tr>
													<? } ?>
													<td>
														<div class="cal_day">
														<? if (date('w', $i) == 0) { ?>
															<p class="red"><a href="#"><?=date('d', $i)?></a></p>
														<? } else if (date('w', $i) == 6) { ?>
															<p class="blue"><a href="#"><?=date('d', $i)?></a></p>
														<? } else { ?>
															<p><a href="#"><?=date('d', $i)?></a></p>
														<? } ?>
														</div>
													</td>
													<? if (date('w', $i) == 6) { ?>
													</tr>
													<? } ?>
												<? } ?>
												<? for ($i = date("w", $toDate); $i <= 6; $i++) { ?>
													<? if ($i == 0) { break; } ?>
													<td>
														<div class="cal_day">
															<p> </p>
														</div>									
													</td>
													<? if ($i == 6) { ?>
													</tr>
													<? } ?>
												<? } ?>
												
															<!--div class="view" style="display:none">
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
															</ul-->
															
															<!--ul>
																<li class="cb6" style="width:299%;"><a href="#">오재호선교1/샬롬관 오재호선교1/샬롬관 오재호선교1/샬롬관</a></li>
																<li class="cb7"><a href="#">오재호선교1/샬롬관</a></li>
																<li class="cb8"><a href="#">오재호선교1/샬롬관</a></li>
																<li class="cb9"><a href="#">오재호선교1/샬롬관</a></li>
																<li class="cb10"><a href="#">오재호선교1/샬롬관</a></li>
															</ul-->
											</tbody>
										</table>
									</div> <!-- // cal_month -->
									<!-- // content -->
								</div>
							</div>
							<!-- // rightSec -->
<?php } ?>
