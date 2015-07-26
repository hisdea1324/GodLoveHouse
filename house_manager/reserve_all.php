<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
checkUserLogin(7);

showHouseManagerHeader();
showHouseManagerLeft();
body();
showHouseManagerFooter();

function body() {
	global $mysqli;

	$m_Helper = new MemberHelper();
	$member = $m_Helper->getMemberByuserid($_SESSION["userid"]);
	$mission = $m_Helper->getMissionInfoByuserid($_SESSION["userid"]);
?>
							<!-- rightSec -->
							<div id="rightSec">
								<div class="lnb">
									<strong>Home</strong> <? 
									echo "&gt; 최근 예약 리스트";
								?></div>
								<div id="content">
<?php

	$search = isset($_REQUEST["search"]) ? trim($_REQUEST["search"]) : "";
	$page = isset($_REQUEST["page"]) ? trim($_REQUEST["page"]) : 1;

	$h_Helper = new HouseHelper();
	$h_Helper->PAGE_UNIT = 10; //하단 페이징 단위
	$h_Helper->PAGE_COUNT = 30; //한페이지에 보여줄 리스트 갯수
	$h_Helper->setReservationListCondition($search);
	$strPage = $h_Helper->makeReservationListPagingHTML($page);
	$reservList = $h_Helper->getReservationListWithPaging($page);
?>

		<div class="search">
			<span class="mr20"><strong>SEARCH ></strong></span>
			<select name="status" id="status" onchange="search(this.value)">
				<option value="">전체</option>
				<option value="1" <? if ($search == "1") { ?> selected <? } ?>>신규예약</option>
				<option value="2" <? if ($search == "2") { ?> selected <? } ?>>승인</option>
				<option value="3" <? if ($search == "3") { ?> selected <? } ?>>완료</option>
				<option value="4" <? if ($search == "4") { ?> selected <? } ?>>거절</option>
			</select>
			<!--span class="btn1"><a href="javascript:void(0)" onclick="search(this.value)">검색</a></span-->
		</div>
				
		<!-- //list -->
		<div class="list mt10">
			<table>
				<colgroup>
					<col width="10%" />
					<col width="10%" />
					<col width="20%" />
					<col width="35%" />
					<col width="25%" />
				</colgroup>
				<thead>
					<tr>
						<th>예약번호</th>
						<th>이름</th>
						<th>선교관 / 방이름</th>
						<th>일정</th>
						<th>상태</th>
					</tr>
				</thead>
				<tbody>
<?php 
	if (count($reservList) == 0) {
		echo "			<tr>\r\n";
		echo "				<td colspan=\"5\">리스트가 없습니다</td>\r\n";
		echo "			</tr>\r\n";
	} else {
		foreach ($reservList as $aResv) {
?>
					<tr>
						<td><?=$aResv->BookNo?></td>
						<td>
							<label id="_pf2_<?=$aResv->BookNo?>"><? 
							if ($aResv->resv_name) { 
								echo $aResv->resv_name; 
							} else { 
								echo $member->Nick;
							} 
							?></label>
							<button type="button" class="btn btn-success btn-xs" data-bookno="<?=$aResv->BookNo?>" data-userid="<?=$aResv->userid?>" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
							<div class="view" id="pf2_<?=$aResv->BookNo?>" style="position:absolute;visibility:hidden; top:38px;" ></div>
						</td>
						<td><?=$aResv->HouseName?> / <?=$aResv->RoomName?></td>
						<td style="text-align: left;">
							<? if ($aResv->Status != "거절") {?>
							<form method="post" name="frmEditDate<?=$aResv->BookNo?>" id="frmEditDate<?=$aResv->BookNo?>">
							<input type="hidden" name="mode" id="mode" value="edit_date" />
							<input type="hidden" name="bookNo" id="bookNo" value="<?=$aResv->BookNo?>" />
							<input type="text" size="12" name="startDate<?=$aResv->BookNo?>" id="startDate<?=$aResv->BookNo?>" value="<?=date("Y-m-d", $aResv->StartDate)?>" class="input" readonly onclick="calendar('startDate<?=$aResv->BookNo?>')">
							<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('startDate<?=$aResv->BookNo?>')"> ~
							<input type="text" size="12" name="endDate<?=$aResv->BookNo?>" id="endDate<?=$aResv->BookNo?>" class="input" value="<?=date("Y-m-d", $aResv->EndDate)?>" readonly onclick="calendar('endDate<?=$aResv->BookNo?>')">
							<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('endDate<?=$aResv->BookNo?>')">
							<span class="btn1g" style="padding: 1px 10px; margin-top: 0; font-size: 9px; height: 20px;"><a href="javascript:void(0)" onclick="edit_date(<?=$aResv->BookNo?>)">날짜 변경</a></span>
							</form>
							<? } else { ?>
							<?=date("Y.m.d", $aResv->StartDate)?> ~ <?=date("Y.m.d", $aResv->EndDate)?> <br />
							<? } ?> 
							예약기간: <?=$aResv->duration?>일<br />
							예약날짜: <?=date("Y.m.d", $aResv->RegDate)?>
						</td>
						<td><?
							if ($aResv->Status == "신규예약") {
								echo "<span style=\"border:1px solid #3b4047; padding:2px 10px 2px 10px; border-radius:1px; font-size:10px; font-weight:bold; text-align:center; background:#ffeeee; color:#550000;\">{$aResv->Status}</span><br>";
							 	echo "<span class=\"btn1g\"><a href=\"javascript:void(0)\" onclick=\"allow({$aResv->BookNo})\">승인</a></span>\r\n";
							 	echo "<span class=\"btn1g\"><a href=\"javascript:void(0)\" onclick=\"deny({$aResv->BookNo})\">거절</a></span>\r\n";
							} else if ($aResv->Status == "승인") {
								echo "<span style=\"border:1px solid #3b4047; padding:2px 10px 2px 10px; border-radius:1px; font-size:10px; font-weight:bold; text-align:center; background:#eeffee; color:#005500;\">{$aResv->Status}</span><br>";
							 	echo "<span class=\"btn1g\"><a href=\"javascript:void(0)\" onclick=\"complete({$aResv->BookNo})\">완료</a></span>\r\n";
							 	echo "<span class=\"btn1g\"><a href=\"javascript:void(0)\" onclick=\"deny({$aResv->BookNo})\">거절</a></span>\r\n";
							} else {
							 	echo "<span class=\"btn1g\"><a href=\"javascript:void(0)\" onclick=\"deny({$aResv->BookNo})\">삭제</a></span>\r\n";
							}
						?></td>
					</tr>
<?
		}
	}
?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!-- // rightSec -->
<!-- // rightSec -->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">신청자 정보</h4>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
	    <button type="button" id="btn-print" class="btn btn-primary" target="_print">프린트하기</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
//<![CDATA[
	calendar_init();

	function allow(value) {
		if (confirm('예약을 승인합니다.'))
			location.href = 'process.php?mode=changeReservStatus&status=2&bookNo=' + value;
	}

	function deny(value) {
		if (confirm('예약을 거절합니다.'))
			location.href = 'process.php?mode=changeReservStatus&status=4&bookNo=' + value;
	}

	function complete(value) {
		if (confirm('예약을 완료합니다.'))
			location.href = 'process.php?mode=changeReservStatus&status=3&bookNo=' + value;
	}

	function edit_date(value) {
		document.getElementById('frmEditDate' + value).action = "process.php";
		document.getElementById('frmEditDate' + value).submit();
	}
	
	function search(value) {
		location.href = 'reserve_all.php?search=' + value;
	}

	$(function () {
		$('#myModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var modal = $(this)

		  modal.find('#btn-print').attr('data-userid', button.data('userid'))
		  modal.find('#btn-print').attr('data-bookno', button.data('bookno'))
  		  var url = 'ajax.php?mode=getUserProfile&reservationNo=' + button.data('bookno');
		  $.get(url, function(data, status) {
			modal.find('.modal-body').html(data)
		  });
		})

		$('#btn-print').click(function () {
			location.href='print.php?userid=' + $(this).attr('data-userid') + '&reservationNo=' + $(this).attr('data-bookno');
		})
	});
//]]>
</script>
<?php } ?>
