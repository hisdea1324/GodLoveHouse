<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

$hospitalId = trim($_REQUEST["hospitalId"]);
$toDate = trim($_REQUEST["toDate"]);
$fromDate = trim($_REQUEST["fromDate"]);

$h_Helper = new HospitalHelper();
$hospital = $h_Helper->getHospitalInfoById($hospitalId);

if ($hospital->StatusCode=="S2002") {
	showHeader("HOME > 병원 > 병원 예약하기","hospital","tit_0901.gif");
} else {
	showHeader("HOME > 병원 >	기타병원안내","hospital","tit_0902.gif");
} 

body();
showFooter();

function body() {
?>
	<!-- //content -->
	<div id="content">

		<!-- //search -->
		<div id="search">
			<img src="../images/board/img_search.gif" align="absmiddle">
			<img src="../images/board/txt_reserve.gif" align="absmiddle" class="m5">
		</div>
		<!-- search// -->

		<H2><img src="../images/board/stit_reserve_01.gif"></H2>
		<div id="calendar">
			<!-- //photo -->
			<div class="photo">
				<p class="img01"><img src="<?php echo $hospital->Image1;?>" width="320" id="mainImage" /></p>
				<div class="img02">
				<ul>
					<li><img src="<?php echo $hospital->Image1;?>" width="70" border="0" onclick="changeImage('<?php echo $hospital->Image1;?>')" style="cursor:pointer;"></li>
					<li><img src="<?php echo $hospital->Image2;?>" width="70" border="0" onclick="changeImage('<?php echo $hospital->Image2;?>')" style="cursor:pointer;"></li>
					<li><img src="<?php echo $hospital->Image3;?>" width="70" border="0" onclick="changeImage('<?php echo $hospital->Image3;?>')" style="cursor:pointer;"></li>
					<li><img src="<?php echo $hospital->Image4;?>" width="70" border="0" onclick="changeImage('<?php echo $hospital->Image4;?>')" style="cursor:pointer;"></li>
				</ul>
				</div>
			</div>
			<!-- photo// -->
	
			<!-- //calenar -->
			<div class="cal" name='reservationCal' id='reservationCal'>
			</div>
			<!-- calendar// -->
		</div>

<?php
	if ($hospital->StatusCode=="S2002") {
?>
		<form action="process.php" method="post" name="frmReserve" id="frmReserve">
			<input type="hidden" name="mode" id="mode" value="reservation" />
			<input type="hidden" name="hospitalId" id="hospitalId" value="<?php echo $hospitalId;?>" />
			<h2><img src="../images/board/stit_reserve_03.gif"></h2>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_reserve">
				<col width="15%">
				<col />
				<tr>
					<td class="td01"><p class="reserve"><b>날짜입력</b></td>
					<td>
						<input type="text" name="startDate" id="startDate" value="<?php echo $fromDate;?>" class="input" readonly onclick="calendar('startDate')">
						<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('startDate')"> ~
						<input type="text" name="endDate" id="endDate" class="input" value="<?php echo $toDate;?>" readonly onclick="calendar('endDate')">
						<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('endDate')">
						<img src="../images/board/btn_reserve.gif" border="0" align="absmiddle" class="m5" onclick="reserveSubmit()"></p>
						<label class="fs11" type="text" name='resultMessage1' id='resultMessage1'></label>
					</td>
				</tr>
			</table>
		</form>
		<br /><br />
<?php 
	} 
?>
	
		<H2><img src="../images/board/stit_hospital.gif"></H2>
		<!-- //view-->
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_reserve">
			<col width="15%">
			<col />
			<col width="15%">
			<col />
			<col width="20%">
			<col />
			<tr>
				<td class="td01">최대인원</td>
				<td><?php echo $hospital->PersonLimit;?>명</td>
				<td class="td01">가격(1일기준) </td>
				<td><?php echo priceFormat($hospital->Price, 1);?></td>
			</tr>
			<tr>
				<td class="td01">제출서류</td>
				<td colspan="5"><?php echo $hospital->Document;?><br></td>
			</tr>
			<tr>
				<td class="td01">선교관 소개</td>
				<td colspan="5"><?php echo $hospital->Explain;?></td>
			</tr>
			<tr>
				<td class="td01">주소</td>
				<td colspan="5">
					<?php $zipcode = $hospital->Zipcode;?>
					[<?php echo $zipcode[0];?>-<?php echo $zipcode[1];?>]
					<a href="#" Onclick="javascript:window.open('../navermaps/a5.php?Naddr=<?php echo rawurlencode($hospital->Address1.$ospital->Address2);?>','win','top=0, left=500, width=550,height=450')"><?php echo $hospital->Address1;?> <?php echo $hospital->Address2;?></A>
				</td>
			</tr>
			<tr>
				<td class="td01">홈페이지</td>
				<td colspan="5">
					<?php echo $hospital->HomePage;?>
				</td>
			</tr>
			<tr>
				<td class="td01">담당자</td>
				<td colspan="5">
					<?php echo $hospital->showContactInfo();?>
				</td>
			</tr>
		</table>
		<!-- view// -->
		<p class="btn_right"><img src="../images/board/btn_list.gif" border="0" class="m2" onclick="goHospitalList();"></p>	
		</div>
	</div>
	<!-- content// -->
<?php } ?>

<script type="text/javascript">
//<![CDATA[
	calendar_init();
	
	var date = new Date;
	var year = date.getFullYear();
	var month = date.getMonth();
	callPage(year, month + 1);
		
	function goHospitalList() {
		history.back(-1);
		//location.href = "reservation.php?hospitalId=<?php echo $hospitalId;?>";
	}
	
	function changeImage(imgName) {
		document.getElementById("mainImage").src = imgName;
	}
	
	function callPage(y, m) {
		var url = 'ajax.php?mode=getCalendar&hospitalId=<?php echo $hospitalId;?>&year='+y+'&month='+m;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: '', onComplete: insertCalendar]);
	}

	function insertCalendar(reqResult) {
		var addHtml = reqResult.responseText;
		document.getElementById("reservationCal").innerHTML = addHtml;
	}
	
	function reserveSubmit() {
		var endDate = document.getElementById("endDate").value;
		var startDate = document.getElementById("startDate").value;

		if (startDate.length == 0 || endDate.length == 0) {
			alert('숙박 기간을 정확히 입력해 주세요');
			return;
		}

		if (startDate.replace(/-/g,'') >= endDate.replace(/-/g,'')) {
			alert('기간이 잘못되었습니다.');
			return;
		}

		document.getElementById("frmReserve").submit();
	}
//]]>
</script>
