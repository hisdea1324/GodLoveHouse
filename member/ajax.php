<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
$mode = trim($_REQUEST["mode"]);
switch (($mode)) {
	case "checkUserId":
confirmUserId();
		break;
	case "checkNick":
confirmNick();
		break;
	case "checkNID":
confirmNID();
		break;
	case "checkPassword":
confirmPassword();
		break;
	case "getUserProfile":
getUserProfile();
		break;
	case "getCalendar":
getCalendar();
		break;
} 

function confirmUserId() {
	$userId = trim($_REQUEST["userId"]);
	$query = "SELECT * FROM users WHERE userId = '".$userId."'";
	$rs = $db->execute($query);

	if ((strlen($userId)<4)) {
		print "<b><font color=red>아이디는 4자 이상만 가능합니다.</font></b>";
	} else if (($rs->eof || $rs->bof)) {
		print "사용 가능한 아이디입니다.";
	} else if ((strlen($userId)>0)) {
		print "<b><font color=red>이 아이디는 사용할 수 없습니다.</font></b>";
	} 


	$rs = null;

} 

function confirmNick() {
	$nick = trim($_REQUEST["nick"]);
	$query = "SELECT * FROM users WHERE nick = '".$nick."'";
	$rs = $db->execute($query);

	if ((strlen($nick)==0)) {
		print "";
	} else if ((strlen($nick)<2)) {
		print "<b><font color=red>닉네임은 2자 이상만 가능합니다.</font></b>";
	} else if (($Rs->EOF || $Rs->BOF)) {
		print "사용 가능한 닉네임입니다.";
	} else {
		print "<b><font color=red>이 닉네임은 사용할 수 없습니다.</font></b>";
	} 

	$Rs = null;

} 

function confirmNID() {
	$nid1 = trim($_REQUEST["nid1"]);
	$nid2 = trim($_REQUEST["nid2"]);
	$query = "SELECT * FROM users WHERE jumin = '".$nid1.$nid2."'";
	$rs = $db->execute($query);

	if ((strlen($nid1)==0 && strlen($nid2)==0)) {
		print "";
	} else if ((strlen($nid1)!=6 || strlen($nid2)!=7)) {
		print "<b><font color=red>정확히 입력해 주세요</font></b>";
	} else if (($Rs->EOF || $Rs->BOF)) {
		print "사용 가능합니다.";
	} else {
		print "<b><font color=red>이미 사용중인 번호입니다.</font></b>";
	} 

	$Rs = null;

} 

function confirmPassword() {
	$pw1 = trim($_REQUEST["pw1"]);
	$pw2 = trim($_REQUEST["pw2"]);

	if ((strlen($pw2)==0 || strlen($pw2)!=strlen($pw1))) {
		print "";
	} else if (($pw2 == $pw1)) {
		print "확인 되었습니다.";
	} else {
		print "<b><font color=red>비밀번호와 일치하지 않습니다.</font></b>";
	} 

	$Rs = null;

} 

function getUserProfile() {
	$m_helper = new MemberHelper();
	$member = $m_helper->getMemberByUserId($trim[$_REQUEST["userid"]]);
	$mission = $m_helper->getMissionInfoByUserId($trim[$_REQUEST["userid"]]);

	print "<table width=200><tr><td>";
	print "<ul>";
	print "<li> 이름 : ".$member->Name."</li>";
	print "<li> 선교사명 : ".$mission->MissionName."</li>";
	print "<li> 지역 : ".$mission->Nation."</li>";
	print "<li> 파송교회 : ".$mission->Church."</li>";
	print "<li> 파송단체 : ".$mission->Ngo."</li>";
	print "</ul>";
	print "</td></tr></table>";

	$mission = null;

	$member = null;

	$m_helper = null;

} 

function getCalendar() {
	$roomId = trim($_REQUEST["roomId"]);
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
	$query = "SELECT * FROM reservation WHERE roomId = ".$roomId;
	$query = $query." AND ((endDate >= '".$monthStart."' AND startDate <= '".$monthEnd."') ";
	$query = $query." OR (startDate < '".$monthStart."' AND endDate > '".$monthEnd."')) ";
	$query = $query." AND reservStatus <> 'S0004'";
	$dateRS = $db->Execute($query);
	for ($i=1; $i<=32; $i = $i+1) {
		$dateSet[$i]=true;

	}


	while(!($dateRS->EOF || $dateRS->BOF)) {
		if ((intval(substr($dateRs["startDate"],5,2))==intval($mValue))) {
			$startDateValue=substr($dateRS["startDate"],strlen($dateRS["startDate"])-(2));
		} else {
			$startDateValue=1;
		} 


		if ((intval(substr($dateRs["endDate"],5,2))==intval($mValue))) {
			$endDateValue=substr($dateRS["endDate"],strlen($dateRS["endDate"])-(2));
		} else {
			$endDateValue = $lastDay;
		} 


		for ($i = $startDateValue; $i <= $endDateValue; $i = $i+1) {
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
			}
				else
			if (($j==7)) {
				$strClass="sat";
			} else {
				$strClass="";
			} 


			if (($i==1 && $j<$start)) {
				print "<td>&nbsp;</td>".chr(13);
			}
				else
			if (($vDate <= $lastDay)) {
				if (($dateSet[$vDate]==false)) {
					$strClass=" class = '".$strClass." select'";
				}
					else
				{
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
