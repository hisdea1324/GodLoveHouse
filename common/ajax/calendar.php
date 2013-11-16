<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

$roomId = (isset($_REQUEST["roomId"])) ? trim($_REQUEST["roomId"]) : "";
$hospitalId = (isset($_REQUEST["hospitalId"])) ? trim($_REQUEST["hospitalId"]) : "";
$yValue = trim($_REQUEST["year"]);
$mValue = trim($_REQUEST["month"]);

$prevYear = ($yValue - 1).", ".$mValue;
$nextYear = ($yValue + 1).", ".$mValue;

$prevMon = ($mValue == 1) ? ($yValue - 1).", 12" : $yValue.", ".($mValue - 1);
$nextMon = ($mValue == 12) ? ($yValue + 1).", 1" : $nextMon = $yValue.", ".($mValue + 1);

$start_timestamp = mktime(0, 0, 0, $mValue, 1, $yValue);
$end_timestamp = mktime(0, 0, 0, $mValue + 1, 1, $yValue);

if ($roomId) {
	$query = "SELECT * FROM reservation WHERE roomId = $roomId";
	$query = "$query AND endDate >= $start_timestamp AND startDate <= $end_timestamp AND reservStatus <> 'S0004'";
} else if ($hospitalId) {
	$query = "SELECT * FROM reservation WHERE hospitalId = $hospitalId";
	$query = "$query AND endDate >= $start_timestamp AND startDate <= $end_timestamp AND reservStatus <> 'S0004'";
} else {
	// error
	$query = "";
	exit();
}

global $mysqli;
$date_set = array();

if ($result = $mysqli->query($query)) {
	while ($row = $result->fetch_array()) {
		$start = $row["startDate"];
		$end = $row["endDate"];
		
		while ($start <= $end) {
			$date_set[$start] = true;
			$start += 24 * 60 * 60;
		}
	}
}

//print_r($date_set);
?>
<p>
<img src='/images/board/btn_pre_02.gif' border="0" style="cursor:pointer" onclick="callPage(<?php echo $prevYear;?>);" />
<img src="/images/board/btn_pre_01.gif" border="0" class="r15" style="cursor:pointer" onclick="callPage(<?php echo $prevMon;?>);" />
<?php echo date('Y-m', $start_timestamp + 7 * 86400);?>
<img src="/images/board/btn_next_01.gif" border="0" class="l15" style="cursor:pointer" onclick="callPage(<?php echo $nextMon;?>);" />
<img src='/images/board/btn_next_02.gif' border="0" style="cursor:pointer" onclick="callPage(<?php echo $nextYear;?>);" />
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
	$start_timestamp = $start_timestamp - date("w", $start_timestamp) * 86400;
	while ($start_timestamp < $end_timestamp) {
		$day_of_week = strtolower(date("D", $start_timestamp));
		$select = (isset($date_set[$start_timestamp])) ? "select" : "";
		
		$day = date("j", $start_timestamp);
		$month = date("m", $start_timestamp);
		
		if ($day_of_week == "sun") {
			echo "<tr>\n";
		}

		if ($month != $mValue) {
			echo "<td></td>";
		} else {
			echo "<td class='$day_of_week $select'>$day</td>\n";
		}
		
		if ($day_of_week == "sat") {
			echo "</tr>\n";
		}
		
		$start_timestamp += 86400;
	}
?>
</table>
<span><img src="/images/board/txt_select.gif"></span>