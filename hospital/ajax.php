<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

$mode = trim($_REQUEST["mode"]);
switch (($mode)) {
	case "getCalendar":
		getCalendar();
		break;
	default:
		break;
} 

function getCalendar() {
	$hospitalId = trim($_REQUEST["hospitalId"]);
	$yValue = trim($_REQUEST["year"]);
	$mValue = trim($_REQUEST["month"]);

	$prevYear=($yValue-1).", ".$mValue;
	$nextYear=($yValue+1).", ".$mValue;
	if (($mValue==1)) {
		$prevMon=($yValue-1).", 12";
	} else {
		$prevMon = $yValue.", ".($mValue-1);
	} 

	if (($mValue==12)) {
		$nextMon=($yValue+1).", 1";
	} else {
		$nextMon = $yValue.", ".($mValue+1);
	} 


	$lastDay=getLastDay($yValue,$mValue);
	$start=strftime("%w",$yValue."-".$mValue."-"."1")+1;

	$monthStart = $yValue."-".$mValue."-"."1";
	$monthEnd = $yValue."-".$mValue."-".$lastDay;
	$query = "SELECT * FROM reservation WHERE hospitalId = ".$hospitalId;
	$query = $query." AND endDate >= '".$monthStart."' AND startDate <= '".$monthEnd."' AND reservStatus <> 'S0004'";
	$dateRS = $db->Execute($query);
	for ($i=1; $i<=32; $i = $i+1) {
		$dateSet[$i]=true;

	}

	while(!($dateRS->EOF || $dateRS->BOF)) {
		for ($i=substr($dateRS["startDate"],strlen($dateRS["startDate"])-(2)); $i<=substr($dateRS["endDate"],strlen($dateRS["endDate"])-(2)); $i = $i+1) {
			$dateSet[$i]=false;

		}

		$dateRS->MoveNext;
	} 
	$dateRS = null;

?>
	<p>
	<img src='../images/board/btn_pre_02.gif' border="0" style="cursor:pointer" onclick="callPage(<?php echo $prevYear;?>);" />
	<img src="../images/board/btn_pre_01.gif" border="0" class="r15" style="cursor:pointer" onclick="callPage(<?php echo $prevMon;?>);" />
<?php 
	if ((strlen($mValue)<2)) {
		print $yValue.".0".$mValue;
	} else {
		print $yValue.".".$mValue;
	} 

?>
	<img src="../images/board/btn_next_01.gif" border="0" class="l15" style="cursor:pointer" onclick="callPage(<?php echo $nextMon;?>);" />
	<img src='../images/board/btn_next_02.gif' border="0" style="cursor:pointer" onclick="callPage(<?php echo $nextYear;?>);" />
	</p>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	<th class="sun">일</th>
	<th>월</th>
	<th>화</th>
	<th>수</th>
	<th>목</th>
	<th>금</th>
	<th class="sat th02">토</th>
	</tr>
<?php 
	$vDate=1;
	for ($i=1; $i<=6; $i = $i+1) {
		print "<tr>".chr(13);
		for ($j=1; $j<=7; $j = $j+1) {
			if (($j==1)) {
				$strClass="sun";
			} else if (($j==7)) {
				$strClass="sat";
			} else {
				$strClass="";
			} 


			if (($i==1 && $j<$start)) {
				print "<td>&nbsp;</td>".chr(13);
			} else if (($vDate <= $lastDay)) {
				if (($dateSet[$vDate]==false)) {
					$strClass=" class = '".$strClass." select'";
				} else {
					$strClass=" class = '".$strClass."'";
				} 

				print "<td".$strClass.">".$vDate."</td>".chr(13);
				$vDate = $vDate+1;
			} else {
				print "<td>&nbsp;</td>".chr(13);
			} 
		}

		print "</tr>".chr(13);
	}

?>
	</table>
	<span><img src="../images/board/txt_select.gif"></span>
<?php 
} 

function getLastDay($yValue,$mValue) {
	$last = array(31,28,31,30,31,30,31,31,30,31,30,31);

	if (($yValue%4==0)) {
		$leap=true;
	} 
	if (($yValue%100==0)) {
		$leap=false;
	} 
	if (($yValue%400==0)) {
		$leap=true;
	} 
	if (($leap==true)) {
		$last[2]=29;
	} 

	return $last[$mValue-1];
} 
?>
