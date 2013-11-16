<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

$regionCode = isset($_REQUEST["region"]) ? trim($_REQUEST["region"]) : "";
$hospitalId = isset($_REQUEST["region"]) ? trim($_REQUEST["hospitalId"]) : "";
$fromDate = isset($_REQUEST["region"]) ? trim($_REQUEST["fromDate"]) : "";
$toDate = isset($_REQUEST["region"]) ? trim($_REQUEST["toDate"]) : "";
$page = isset($_REQUEST["region"]) ? trim($_REQUEST["page"]) : 1;

$c_Helper = new CodeHelper();
$h_Helper = new HospitalHelper();
$h_Helper->PAGE_UNIT=10; # 하단 페이징 단위 
$h_Helper->PAGE_COUNT=7; # 한페이지에 보여줄 리스트 갯수 
$h_Helper->setCondition($hospitalId, $regionCode, $fromDate, $toDate); #  조건문 작성
$strPage = $h_Helper->makePagingHTML($page);
$codes = $c_Helper->getLocalCodeList();
$hospitals = $h_Helper->getHospitalListWithPaging($page);

showHeader("HOME > 병원 > 병원 예약하기","hospital","tit_0901.gif");
body();
showFooter();

function body() {
	global $fromDate, $toDate;
	global $codes, $regionCode;
	global $hospitals, $strPage, $page;
	global $h_Helper;
?>
		<!-- # content -->
		<div id="content">
			<!-- # search -->
		<form name="findFrm" id="findFrm" action="reservation.php" method="get">
			<div id="search"> <img src="../images/board/img_search.gif" class="r10" align="absmiddle">
				<select name="region" id="region" onchange="selectRegion()">
					<option value=''>-- 지역선택 --</option>
<?php 
	foreach ($codes as $codeObj) {
		if ($regionCode == $codeObj->Code) {
			print "<option value='".$codeObj->Code."' selected>".$codeObj->Name."</option>";
		} else {
			print "<option value='".$codeObj->Code."'>".$codeObj->Name."</option>";
		} 
	}
?>
				</select>
				<input type="text" name="fromDate" id="fromDate" value="<?=$fromDate?>" style="width:100px" class="input" readonly onclick="calendar('fromDate')">
				<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('fromDate')" style="cursor:pointer;"> ~
				<input type="text" name="toDate" id="toDate" value="<?=$toDate?>" style="width:100px" class="input" readonly onclick="calendar('toDate')">
				<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('toDate')" style="cursor:pointer;">
				<img src="../images/board/btn_search.gif" border="0" align="absmiddle" style="cursor:pointer;" onclick="frmSubmit()">
		</div>
		 <input type="hidden" name="page" id="page" value="<?=$page?>" />
		</form>
			<!-- search#  -->
			<!-- # list -->
			<div class="bg_list">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_list">
					<col width="7%" />
					<col width="15%" />
					<col width="20%" />
					<col />
					<tr>
						<th>No</th>
						<th>이미지</th>
						<th class="th01">병원(진료과목)</th>
						<th class="th01">내용</th>
					</tr>
<?php
	if (count($hospitals) == 0) {
?>
					<tr>
						<td colspan="4">
							리스트가 없습니다
						</td>
					</tr>
<?php
	} else {
		$searchDateValue = "";
		$i = 0;
		foreach ($hospitals as $hospitalObj) {
?>
			<tr>
			<td><?=(($page-1)*$h_Helper->PAGE_COUNT) + ($i + 1)?></td>
			<td>
<?php 
			if (strlen($toDate) > 0) {
				$searchDateValue = $searchDateValue."&toDate=".$toDate;
			}
			if (strlen($fromDate) > 0) {
				$searchDateValue = $searchDateValue."&fromDate=".$fromDate;
			}
?>
				<a href="reservationDetail.php?hospitalId=<?=$hospitalObj->HospitalID?><?=$searchDateValue?>">
				<img src="<?=$hospitalObj->Image1?>" width="120" height="75" border="0" class="img">
				</a>
			</td>
			<td>
				<a href="reservationDetail.php?hospitalId=<?=$hospitalObj->HospitalID?><?=$searchDateValue?>">
				<?=$hospitalObj->HospitalName?><br />
				</a>
			</td>
						<td class="ltd">
							<ul class="intro">
								<li><b>운영</b> : <?=$hospitalObj->AssocName?></li>
								<li><b>주소</b> : <a href="#" Onclick="javascript:window.open('../navermaps/a5.php?Naddr=<?=rawurlencode($hospitalObj->Address1.$hospitalObj->Address2)?>','win','top=0, left=500,width=550,height=450')"><?=$hospitalObj->Address1?> <?=$hospitalObj->Address2?></a></li>
								<li><b>담당자</b> : <?=$hospitalObj->Manager1?></li>
								<!--li><b>요금</b> : <?=$hospitalObj->showFee()?></li-->
							</ul>
						</td>
					</tr>
<?php 
		}
		$i++;
	}
?>
				</table>
			</div>
			<!-- list#  -->
			<!-- # page -->
			<?=$strPage?>
			<!-- page#  -->
		</div>
	<!-- content#  -->
<?php } ?>

<script type="text/javascript">
//<![CDATA[
	calendar_init();
	
	function selectRegion() {
		var region = document.getElementById("region").value;
		if (region.length == 0) {
			location.href = "reservation.php";
		}
		
		location.href = "reservation.php?region=" + region;
	}
	
	function selectHospital() {
		var hospital = document.getElementById("hospitalId").value;
		if (hospital.length == 0) {
			return;
		}
		location.href = "reservation.php?region=<?=$regionCode?>&hospitalId=" + hospital;
	}
	
	function frmSubmit() {
		var endDate = document.getElementById("toDate").value;
		var startDate = document.getElementById("fromDate").value;

		if (startDate.length == 0 || endDate.length == 0) {
			alert('숙박 기간을 정확히 입력해 주세요');
			return;
		}

		if (startDate.replace(/-/g,'') >= endDate.replace(/-/g,'')) {
			alert('기간이 잘못되었습니다.');
			return;
		}

		var findFrm = document.getElementById("findFrm");
		findFrm.submit();
	}
//]]>
</script>
