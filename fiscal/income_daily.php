<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/class/CalendarBuilder.php");

$StartYear=2012;

$calendar = new CalendarBuilder();
$calendar->CYear = trim($_REQUEST["y"]);
$calendar->CMonth = trim($_REQUEST["m"]);

$fromDate = $calendar->CurrentMonth.$calendar->DataFormat(1);
$toDate = $calendar->NextMonth.$calendar->DataFormat(1);
$s_Helper = new supportHelper();
$dailySupport = $s_Helper->getDailySupport($fromDate, $toDate);
$senders = $s_Helper->getSender($fromDate, $toDate);

showHeader("HOME > 재정보고 > 수입보고","fiscal","tit_0504.gif");
body();
showFooter();

function body() {
?>
		<!-- //content -->
		<div id="content">
			<!-- //search -->
			<div id="search">
				<form name="schForm" action="income_daily.php">
			<span class="fc_01"><strong>년도</strong></span>
				<select name="y">
					<option value="">-- 년도를 선택하세요 --</option>
			<?	 for ($i = $StartYear; $i<=strftime("%Y",time()); $i = $i+1) { ?>
					<option value="<?php echo $i;?>"<?php if ((intval($calendar->CYear)==intval($i))) { ?> selected <?php } ?>><?php echo $i;?></option>
			<?php } ?>
				</select>
		
			<span class="fc_01"><strong>월</strong></span>
				<select name="m">
					<option value="">-- 월을 선택하세요 --</option>
			<?	 for ($i=1; $i<=12; $i = $i+1) { ?>
					<option value="<?php echo $i;?>"<?php if ((intval($calendar->CMonth)==intval($i))) { ?> selected <?php } ?>><?php echo $i;?>월</option>
			<?php } ?>
				</select>		
		<input type="submit" value="검색" style="cursor:pointer" />
		<input type="button" name="월별보기" value="월별보기" onclick="location.href='income_monthly.php'" style="cursor:pointer" />
				</form>
		</div>
			<!-- search// -->
			<!-- //list -->		
			<div class="bg_list">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_list">
					<tr>
						<th colspan="7"><?php echo $y;?>년 <?php echo $m;?>월 (단위 : 천원)</th>
					</tr>			
					<tr>
						<td class="fc_01 b bg_gray" width="14%">일</td>
						<td class="fc_01 b bg_gray" width="14%">월</td>
						<td class="fc_01 b bg_gray" width="14%">화</td>
						<td class="fc_01 b bg_gray" width="14%">수</td>
						<td class="fc_01 b bg_gray" width="14%">목</td>
						<td class="fc_01 b bg_gray" width="14%">금</td>
						<td class="fc_01 b bg_gray" width="14%">토</td>
					</tr>
					<tr>
<?php 
# --------------
# 달력만드는 순서
# 1. 이번달 1일의 요일을 계산한다.
# 2. 이번달의 마지막 날짜를 계산한다. (이번달 날짜수 = 다음달 1일 - 이번달 1일)
# 3. 이번 달 1일의 요일을 계산해서 해당셀에 1부터 차례대로 날짜수 만큼 뿌린다.
# ---------------
# 1일이 시작하기 전 빈칸 생성
	for ($i=1; $i<=($calendar->FirstDay-1); $i = $i+1) {
		print "	<td> </td>".chr(13);
	}


# 1일부터 마지막날까지 달력생성
# ------------------------------
	for ($i=1; $i <= $calendar->LastDate; $i = $i+1) {
		$sumPrice="<br />";

		$ymd = $calendar->CYear.$calendar->CMonth.$calendar->DataFormat($i);

		if (($dailySupport->Exists($ymd))) {
			$sumPrice = PriceFormat($dailySupport->Item($ymd), 2)."<br />";
		} 

		print "<td>".$i."<br />".$sumPrice."</td>";

		# 토요일이면 줄을 바꾼다.
		if (($calendar->IsWeekEnd($i))) {
			print " </tr>".chr(13);
			print " <tr>";
		} 
	}


	#마지막날 이후 달력 끝날떄까지 빈칸 생성(토요일로 끝나면 출력안됨)
	# ------------------------------------------
	if ($calendar->LastDay!=7) {
		for ($i = $calendar->LastDay; $i<=7; $i = $i+1) {
			print "	<td>	</td>";
		}
	} 

	#테이블 닫기
	print " <tr>".chr(13);
?>
					</tr>
			<tr>
						<th colspan="7">이달의 후원자 명단</th>
			</tr>
			<tr>
						<td colspan="7" class="bg_gray left" style="padding-left:15px">
				<ul class="list">
			<?php 
	for ($i=0; $i<=count($senders)-1; $i = $i+1) {
		$sender = $senders[$i];
?>
					<li><?php echo $sender->Name;?></li>
			<?php } ?>
				</ul>
			</td>
					</tr>
				</table>
			</div>			
			<!-- list// -->					
		</div>
		<!-- content// -->
<?php } ?>

