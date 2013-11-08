<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

$regionCode = (isset($_REQUEST["region"])) ? trim($_REQUEST["region"]) : "";
$houseId = (isset($_REQUEST["houseId"])) ? trim($_REQUEST["houseId"]) : "";
$fromDate = (isset($_REQUEST["fromDate"])) ? trim($_REQUEST["fromDate"]) : "";
$toDate = (isset($_REQUEST["toDate"])) ? trim($_REQUEST["toDate"]) : "";
$page = (isset($_REQUEST["page"])) ? trim($_REQUEST["page"]) : 1;

$c_Helper = new CodeHelper();
$h_Helper = new HouseHelper();
$h_Helper->PAGE_UNIT = 10; #하단 페이징 단위
$h_Helper->PAGE_COUNT = 7; #한페이지에 보여줄 리스트 갯수 
$h_Helper->setCondition($houseId, $regionCode, $fromDate, $toDate); # 조건문 작성
$strPage = $h_Helper->makePagingHTML($page);
$codes = $c_Helper->getLocalCodeList();
$houses = $h_Helper->getHouseListByRegion($regionCode);
$rooms = $h_Helper->getRoomListWithPaging($page);

showHeader("HOME > 선교관 > 선교관 예약하기","living","tit_0202.gif");
body();
showFooter();

function body() {
	global $codes, $regionCode, $page, $strPage;
	global $h_Helper, $houses, $houseId, $rooms;
	global $fromDate, $toDate;
?>
		<!-- //content -->
		<div id="content">
			<!-- //search -->
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
				<select name="houseId" id="houseId" onchange="selectHouse()">
					<option value=''>-- 선교관선택 --</option>
			<?php 
	foreach ($houses as $houseObj) {
		$houseName = StrFormatByLength($houseObj->HouseName, 20);
		if (strlen($houseId) > 0 && $houseId==($houseObj->HouseID)) {
			print "<option value='".$houseObj->HouseID."' selected>".$houseName."</option>";
		} else {
			print "<option value='".$houseObj->HouseID."'>".$houseName."</option>";
		} 
	}
?>
				</select>
				<input type="text" name="fromDate" id="fromDate" value="<?php echo $fromDate;?>" style="width:100px" class="input" readonly onclick="calendar('fromDate')">
				<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('fromDate')" style="cursor:pointer;"> ~
				<input type="text" name="toDate" id="toDate" value="<?php echo $toDate;?>" style="width:100px" class="input" readonly onclick="calendar('toDate')">
				<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('toDate')" style="cursor:pointer;">
		 <img src="../images/board/btn_search.gif" border="0" align="absmiddle" style="cursor:pointer;" onclick="frmSubmit()">
		</div>
		 <input type="hidden" name="page" id="page" value="<?php echo $page;?>" />
		</form>
			<!-- search// -->
			<!-- //list -->
			<div class="bg_list">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_list">
					<col width="7%" />
					<col width="20%" />
					<col width="20%" />
					<col />
					<tr>
						<th>No</th>
						<th>이미지</th>
						<th class="th01">선교관(방이름)</th>
						<th class="th01">내용</th>
					</tr>
<?php
	if (count($rooms) == 0) {
?>
					<tr>
						<td colspan="4">
							리스트가 없습니다
						</td>
					</tr>
<?php
	} else {
		foreach ($rooms as $roomObj) {
			$houseObj = $h_Helper->getHouseInfoById($roomObj->HouseID);
?>
			<tr>
			<td><?php echo $roomObj->roomId;?></td>
			<td>
<?php 
			$searchDateValue = "";
			if (strlen($toDate) > 0) {
				$searchDateValue = $searchDateValue."&toDate=".$toDate;
			}
			if (strlen($fromDate) > 0) {
				$searchDateValue = $searchDateValue."&fromDate=".$fromDate;
			}
?>
				<a href="reservationDetail.php?houseId=<?php echo $roomObj->HouseID;?>&roomId=<?php echo $roomObj->RoomID;?><?php echo $searchDateValue;?>">
				<img src="<?php echo $roomObj->Image1;?>" width="120" height="75" border="0" class="img">
				</a>
			</td>
			<td>
				<a href="reservationDetail.php?houseId=<?php echo $roomObj->HouseID;?>&roomId=<?php echo $roomObj->RoomID;?><?php echo $searchDateValue;?>">
				<?php echo $houseObj->HouseName;?><br />(<?php echo $roomObj->RoomName;?>)
				</a>
			</td>
						<td class="ltd">
							<ul class="intro">
								<li><b>운영</b> : <?php echo $houseObj->AssocName;?></li>
								<li><b>주소</b> : <a href="#" Onclick="javascript:window.open('../navermaps/a5.php?Naddr=<?php echo rawurlencode($houseObj->Address1." ".$houseObj->Address2);?>','win','top=0, left=500,width=550,height=450')"><?php echo $houseObj->Address1;?></a></li>
								<li><b>담당자</b> : <?php echo $houseObj->Manager1;?></li>
								<li><b>요금</b> : <?php echo $roomObj->showFee();?></li>
							</ul>
						</td>
					</tr>
<?php
		}
	} 
?>
				</table>
			</div>
			<!-- list// -->
			<!-- //page -->
			<?php echo $strPage;?>
			<!-- page// -->
		</div>
	<!-- content// -->
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
	
	function selectHouse() {
		var house = document.getElementById("houseId").value;
		if (house.length == 0) {
			return;
		}
		location.href = "reservation.php?region=<?php echo $regionCode;?>&houseId=" + house;
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
