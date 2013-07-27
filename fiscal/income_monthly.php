<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/class/CalendarBuilder.php");
$StartYear=2008;

$calendar = new CalendarBuilder();
$s_Helper = new supportHelper();

$calendar->CYear = trim($_REQUEST["y"]);
$monthlySupport = $s_Helper->getMonthlySupport($calendar->CYear);

showHeader("HOME > 재정보고 > 수입보고","fiscal","tit_0501.gif");
body();
showFooter();

function body() {
?>
		<!-- //content -->
		<div id="content">
	
			<!-- //search -->
			<div id="search">
				<form name="schForm" action="income_monthly.php">
			<span class="fc_01"><strong>년도선택</strong></span>
				<select name="y">
					<option>-- 년도를 선택하세요 --</option>
			<?	 for ($i = $StartYear; $i<=strftime("%Y",time()); $i = $i+1) { ?>
					<option value="<?php echo $i;?>"<?php if ((intval($calendar->CYear)==intval($i))) { ?> selected <?php } ?>><?php echo $i;?></option>
			<?php } ?>
				</select>
				<input type="submit" value="검색" style="cursor:pointer" />
				</form>
		</div>
			<!-- search// -->	
		
			<!-- //list -->
		<label>(단위:천원)</label>
			<div class="bg_list">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_list">
					<tr>
						<th>월</th>
						<th class="th01">금액</th>
					</tr>
	<?php 
	$intSum=0;
	for ($i=1; $i<=12; $i = $i+1) {
		$yearMonth = $calendar->CYear.$calendar->DataFormat($i);
?>
			<tr><td><a href="income_daily.php?y=<?php echo $calendar->CYear;?>&m=<?php echo $i;?>"><?php echo $i;?>월</a></td>
<?php 
		if (($monthlySupport->Exists($yearMonth))) {
			$intSum = $intSum+$monthlySupport->Item($yearMonth);
?>
			<td class="right"><a href="income_daily.php?y=<?php echo $calendar->CYear;?>&m=<?php echo $i;?>"><?php echo $PriceFormat[$monthlySupport->Item($yearMonth)][2];?></a></td>
<?php 
		} else { 
?>
			<td class="right"><a href="income_daily.php?y=<?php echo $calendar->CYear;?>&m=<?php echo $i;?>">0</a></td></tr>
<?php
	 	}
	} 
?>
			<tr>
						<td class="fc_01 b bg_gray">합계</td>
						<td class="fc_01 b bg_gray"><?php echo $PriceFormat[$intSum][2];?></td>
					</tr>
				</table>
			</div>			
			<!-- list// -->
		</div>
		<!-- content// -->
<?php } ?>
