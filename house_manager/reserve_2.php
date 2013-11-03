<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

showHouseManagerHeader();
showHouseManagerLeft();
body();
showHouseManagerFooter();

function body() {
	global $mysqli;
	global $room_color;

	$roomId = (isset($_REQUEST["roomId"])) ? trim($_REQUEST["roomId"]) : "";
	$houseId = (isset($_REQUEST["houseId"])) ? trim($_REQUEST["houseId"]) : "";
	$toDate = isset($_REQUEST["toDate"]) ? trim($_REQUEST["toDate"]) : "";
	$fromDate = isset($_REQUEST["fromDate"]) ? trim($_REQUEST["fromDate"]) : "";

	$h_Helper = new HouseHelper();
	$room = $h_Helper->getRoomInfoById($roomId);
	$house = $h_Helper->getHouseInfoById($houseId);

	$m_Helper = new MemberHelper();
	$member = $m_Helper->getMemberByUserId($_SESSION["userid"]);
	$mission = $m_Helper->getMissionInfoByUserId($_SESSION["userid"]);

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

	$s_Helper = new supportHelper();
	$dailySupport = $s_Helper->getDailySupport($fromDate, $toDate);
	$senders = $s_Helper->getSender($fromDate, $toDate);
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
?>
							<!-- rightSec -->
							<div id="rightSec">
								<div class="lnb">
									<strong>Home</strong> &gt; 예약관리 &gt; <?=$house->houseName?> &gt; <?=$room->roomName?>
								</div>
								<div id="content">
									<!-- content -->
									<h1><?=$house->houseName?> :: <?=$room->roomName?> <span class="btn1"><a href="mission_write2.php?houseId=<?=$houseId?>&roomId=<?=$roomId?>">방 정보수정</a></span></h1>
									<ul class="tabs mt30">
										<li><a href="reserve_1.php">달력보기</a></li>
										<li class="on"><a href="reserve_2.php">방전체보기</a></li>
									</ul>
									<div class="list_year"> <!-- list_year -->
										<ul class="mr1">
											<li><a href="reserve_2.php?<? echo "{$q[0]}&{$q[1]}&{$q[2]}&{$q[6]}";?>"><img src="images/btn_yprev.gif" alt="이전년도" /></a></li>
											<li class="txt"><?=$calendar['year']?></li>
											<li><a href="reserve_2.php?<? echo "{$q[0]}&{$q[1]}&{$q[4]}&{$q[6]}";?>"><img src="images/btn_ynext.gif" alt="다음년도" /></a></li>
										</ul>
										<ul>
											<li><a href="reserve_2.php?<? echo "{$q[0]}&{$q[1]}&{$q[3]}&{$q[5]}";?>"><img src="images/btn_yprev.gif" alt="이전달" /></a></li>
											<li class="txt"><?=$calendar['month']?></li>
											<li><a href="reserve_2.php?<? echo "{$q[0]}&{$q[1]}&{$q[3]}&{$q[7]}";?>"><img src="images/btn_ynext.gif" alt="다음달" /></a></li>
										</ul>
									</div> <!-- // list_year -->
									<!-- cal_month -->
									<div class="cal_month2 mt20"> 
										<table>
<?
	echo "											<colgroup>\r\n";
	echo "												<col width=\"7%\" />\r\n";
	for ($i = $fromDate; $i < $toDate; $i += 86400) {
		echo "												<col width=\"3%\" />\r\n";
	}
	echo "											</colgroup>\r\n";
	echo "											<thead>\r\n";

	//--------------
	// 달력만드는 순서
	// 1. 이번달 1일의 요일을 계산한다.
	// 2. 이번달의 마지막 날짜를 계산한다. (이번달 날짜수 = 다음달 1일 - 이번달 1일)
	// 3. 이번 달 1일의 요일을 계산해서 해당셀에 1부터 차례대로 날짜수 만큼 뿌린다.
	//---------------
	//1일부터 마지막날까지 요일정보 
	//------------------------------
	echo "												<tr>\r\n";
	echo "													<th scope=\"col\" rowspan=\"2\" class=\"fs2\">방이름</th>\r\n";
	for ($i = $fromDate; $i < $toDate; $i += 86400) {
		switch (date('w', $i)) {
			case 1:
				print "													<th scope=\"col\">월</th>\r\n";
				break;
			case 2:
				print "													<th scope=\"col\">화</th>\r\n";
				break;
			case 3:
				print "													<th scope=\"col\">수</th>\r\n";
				break;
			case 4:
				print "													<th scope=\"col\">목</th>\r\n";
				break;
			case 5:
				print "													<th scope=\"col\">금</th>\r\n";
				break;
			case 6:
				print "													<th scope=\"col\"><p class=\"blue\">토</p></th>\r\n";
				break;
			default:
				print "													<th scope=\"col\"><p class=\"red\">일</p></th>\r\n";
				break;
		}
	}
	echo "												</tr>\r\n";

	//1일부터 마지막날까지 달력생성
	//------------------------------
	print "												<tr>\r\n";
	for ($i = $fromDate; $i < $toDate; $i += 86400) {
		switch (date('w', $i)) {
			case 6:
				print "													<th scope=\"col\"><p class=\"blue\">".date('d', $i)."</p></th>\r\n";
				break;
			case 0:
				print "													<th scope=\"col\"><p class=\"red\">".date('d', $i)."</p></th>\r\n";
				break;
			default:
				print "													<th scope=\"col\">".date('d', $i)."</th>\r\n";
				break;
		} 
	}
	print "												</tr>\r\n";
	print "											</thead>\r\n";


	//방별 예약 정보
	//------------------------------
	print "											<tbody>\r\n";
	foreach ($house->RoomList as $aRoom) {
		//------------------------
		// 룸 예약 정보 받아오기 
		//------------------------
		$query = "SELECT * FROM room B, reservation C WHERE B.roomId = '".$aRoom->RoomID."' AND B.roomId = C.roomId ";
		$query = $query." AND ((endDate >= {$fromDate} AND startDate < {$toDate}) ";
		$query = $query." OR (startDate < {$fromDate} AND endDate >= {$toDate})) ";
		$query = $query." ORDER BY startDate";

		print "												<tr>\r\n";
		print "												<th><strong>{$aRoom->roomName}</strong></th>\r\n";
		print "												<td colspan=\"30\">\r\n";
		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_assoc()) {
				setTestValue($row);
				if (date('m', $row["startDate"]) == $calendar['month']) {
					$start = date('d', $row["startDate"]);
				} else {
					$start = 1;
				}

				if (date('m', $row["endDate"]) == $calendar['month']) {
					$end = date('d', $row["endDate"]);
				} else {
					$end = date('d', $toDate - 86400);
				}

				$margin_left = ($start - 1) / date('d', $toDate - 86400) * 100;
				$width = ($end - $start + 1) / date('d', $toDate - 86400) * 100;
				if ($row["resv_name"]) {
					print "<div class=\"check cb".$room_color[$aRoom->RoomID]."\" style=\"width:{$width}%; margin-left:{$margin_left}%\">".$row["resv_name"]."</div>";
				} else {
					print "<div class=\"check cb".$room_color[$aRoom->RoomID]."\" style=\"width:{$width}%; margin-left:{$margin_left}%\">".$row["userId"]."</div>";
				}
			}

			$result->close();
		}
		print "												</td>\r\n";
		print "												</tr>\r\n";
	}
	print "											</tbody>\r\n";
?>
										</table>
									</div> <!-- // cal_month -->


<?php
	if ($house->status == "승인") {
?>
		<br /><br />
		<form action="process.php" method="post" name="frmReserve" id="frmReserve">
			<input type="hidden" name="mode" id="mode" value="reservation" />
			<input type="hidden" name="roomId" id="roomId" value="<?=$roomId;?>" />
			<input type="hidden" name="houseId" id="roomId" value="<?=$houseId;?>" />
			<h2><img src="../images/board/stit_reserve_03.gif"></h2>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_reserve">
				<col width="15%">
				<col />
				<tr>
					<td class="td01"><p class="reserve"><b>날짜입력</b></td>
					<td>
						<input type="text" name="startDate" id="startDate" value="" class="input" readonly onclick="calendar('startDate')">
						<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('startDate')"> ~
						<input type="text" name="endDate" id="endDate" class="input" value="" readonly onclick="calendar('endDate')">
						<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('endDate')">
						<label class="fs11" type="text" name='resultMessage1' id='resultMessage1'></label>
					</td>
				</tr>
				<tr>
					<td class="td01"><p class="reserve"><b>이름</b></td>
					<td>
						<input type="text" name="resv_name" id="resv_name" value="<?=$member->name?>" class="input">
					</td>
				</tr>
				<tr>
					<td class="td01"><p class="reserve"><b>연락처</b></td>
					<td>
						<input type="text" name="resv_phone" id="resv_phone" value="<?=$member->mobile[0]?>-<?=$member->mobile[1]?>-<?=$member->mobile[2]?>" class="input">
					</td>
				</tr>
				<tr>
					<td class="td01"><p class="reserve"><b>선교지</b></td>
					<td>
						<input type="text" name="resv_nation" id="resv_nation" value="<?=$mission->nation?>" class="input">
					</td>
				</tr>
				<tr>
					<td class="td01"><p class="reserve"><b>지원단체</b></td>
					<td>
						<? if ($mission->church) { ?>
						<input type="text" name="resv_assoc" id="resv_assoc" value="<?=$mission->church?>" class="input">
						<? } else { ?>
						<input type="text" name="resv_assoc" id="resv_assoc" value="<?=$mission->ngo?>" class="input">
						<? } ?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<img src="../images/board/btn_reserve.gif" border="0" align="absmiddle" class="m5" onclick="reserveSubmit()">
					</td>
				</tr>
			</table>
		</form>
<?php 
} 
?>

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


<script type="text/javascript">
//<![CDATA[
	calendar_init();
//]]>
</script>