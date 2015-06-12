<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

$roomId = (isset($_REQUEST["roomId"])) ? trim($_REQUEST["roomId"]) : "";
$houseId = (isset($_REQUEST["houseId"])) ? trim($_REQUEST["houseId"]) : "";
$toDate = (isset($_REQUEST["toDate"])) ? trim($_REQUEST["toDate"]) : "";
$fromDate = (isset($_REQUEST["fromDate"])) ? trim($_REQUEST["fromDate"]) : "";

$h_Helper = new HouseHelper();
$room = $h_Helper->getRoomInfoById($roomId);
$house = $h_Helper->getHouseInfoById($room->HouseID);

$m_Helper = new MemberHelper();
if (isset($_SESSION["userid"])) {
	$member = $m_Helper->getMemberByuserid($_SESSION["userid"]);
	$mission = $m_Helper->getMissionInfoByuserid($_SESSION["userid"]);
} else {
	$member = $m_Helper->getMemberByuserid();
	$mission = $m_Helper->getMissionInfoByuserid();
}
$c_Helper = new CodeHelper();
$codes = $c_Helper->getNationCodeList();

$content = rawurlencode(str_replace("<br>", "", $house->explain));
$image = str_replace(" ", "%20", $room->Image1);
$fb_string = <<<EOT
	<meta property="og:image" content=http://godlovehouse.net{$image}" />
	<meta property="og:title" content="{$house->HouseName} :: {$room->RoomName}" />
	<meta property="title" content="{$house->HouseName} :: {$room->RoomName}" />
	<meta name="Description" content="{$content}" />
EOT;

if ($house->StatusCode == "S2002") {
	showHeader("HOME > 선교관 > 예약종합안내","living","tit_0202.gif", $fb_string);
} else {
	showHeader("HOME > 선교관 > 기타 선교관 안내","living","tit_0201.gif", $fb_string);
} 

body();
showFooter();

function body() {
	global $roomId, $houseId, $fromDate, $toDate;
	global $room, $house, $member, $mission, $codes;
?>
	<!-- //content -->
	<div id="content">

		<!-- //search -->
		<div id="search">
			<img src="../images/board/img_search.gif" align="absmiddle">
			<img src="../images/board/txt_reserve.gif" align="absmiddle" class="m5">
			<select name="room" id="room" onchange="selectRoom()">
				<option value=''>-- 객실선택 --</option>
<?php 
	foreach ($house->RoomList as $roomInfo) {
		if (trim($roomId) == trim($roomInfo->RoomID)) {
?>
				<option value='<?=$roomInfo->RoomID?>' selected><?=$roomInfo->RoomName?></option>
<?php 
		} else {
?>
				<option value='<?=$roomInfo->RoomID?>'><?=$roomInfo->RoomName?></option>
<?php
		} 
	}

?>
			</select>
		</div>
		<!-- search// -->

		<H2><img src="../images/board/stit_reserve_01.gif"></H2>
		<div id="calendar">
			<!-- //photo -->
			<div class="photo">
				<p class="img01"><img src="<?=$room->Image1?>" width="320" id="mainImage" /></p>
				<div class="img02">
				<ul>
					<li><img src="<?=$room->Image1?>" width="70" border="0" onclick="changeImage('<?=$room->Image1?>')" style="cursor:pointer;"></li>
					<li><img src="<?=$room->Image2?>" width="70" border="0" onclick="changeImage('<?=$room->Image2?>')" style="cursor:pointer;"></li>
					<li><img src="<?=$room->Image3?>" width="70" border="0" onclick="changeImage('<?=$room->Image3?>')" style="cursor:pointer;"></li>
					<li><img src="<?=$room->Image4?>" width="70" border="0" onclick="changeImage('<?=$room->Image4?>')" style="cursor:pointer;"></li>
				</ul>
				</div>
			</div>
			<!-- photo// -->
			<!-- //calenar -->
			<div class="cal" name='reservationCal' id='reservationCal'>
			</div>
			<!-- calendar// -->
		</div>
   		
		<H2><img src="../images/board/stit_reserve_02.gif"></H2>
		<!-- //view-->
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_reserve">
			<col width="15%">
			<col />
			<col width="15%">
			<col />
			<col width="17%">
			<col />
			<tr>
				<td class="td01">주거형태</td>
				<td><?=$house->building?></td>
				<td class="td01">최대인원</td>
				<td><?=$room->Limit?>명</td>
				<td class="td01">가격(1일기준) </td>
				<td colspan="3"><?=$room->showFee()?></td>
			</tr>
			<tr>
				<td class="td01">세탁시설</td>
				<td><?=$room->Laundary?></td>
				<td class="td01">주방시설</td>
				<td><?=$room->Kitchen?></td>
				<td class="td01">인터넷</td>
				<td><?=$room->Network?></td>
				<td class="td01">침대</td>
				<td><?=$room->bed?></td>
			</tr>
			<tr>
				<td class="td01">선교관소개</td>
				<td colspan="7"><?=$house->Explain?></td>
			</tr>
			<tr>
				<td class="td01">객실소개</td>
				<td colspan="7"><?=$room->Explain?></td>
			</tr>
			<tr>
				<td class="td01">주소</td>
				<td colspan="7">
					[<?=implode('-', $house->Zipcode)?>]
					<a href="javascript:void(0)" Onclick="javascript:window.open('../navermaps/a5.php?Naddr=<?=rawurlencode($house->Address1.$house->Address2)?>','win','top=0, left=500, width=550,height=450')"><?=$house->Address1?></A>
					&nbsp;&nbsp;&nbsp;<span class="btn1"><a href="javascript:void(0)" onclick="javascript:window.open('../navermaps/a5.php?Naddr=<?=rawurlencode($house->Address1.$house->Address2)?>','win','top=0, left=500, width=550,height=450')">지도 보기</a></span>
				</td>
			</tr>
			<tr>
				<td class="td01">홈페이지</td>
				<td colspan="7">
					<?
					if (strpos($house->HomePage, '없음') || strpos($house->HomePage, 'http') == false) {
						echo "<a href=\"{$house->HomePage}\" target=\"_blank\">{$house->HomePage}</a>";
					} else {
						echo $house->HomePage;
					}
					?>
				</td>
			</tr>
			<?
			/*
			<tr>
				<td class="td01">담당자</td>
				<td colspan="7">
					<?=$house->showContactInfo()?>
				</td>
			</tr>
			 */
			 ?>
		</table>
<br>
<?
	if ($house->status == "승인") {
?>
			<form action="process.php" method="post" name="frmReserve" id="frmReserve">
			<input type="hidden" name="mode" id="mode" value="reservation" />
			<input type="hidden" name="roomId" id="roomId" value="<?=$roomId?>" />
			<input type="hidden" name="houseId" id="houseId" value="<?=$houseId?>" />
			<h2><img src="../images/board/stit_reserve_03.gif"> &nbsp; * 선교사증명서 제출로 최종예약됩니다. 예약내용은 마이페이지=> 내예약정보에서 확인 가능합니다.</h2>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_reserve">
				<col width="15%">
				<col />
				<col width="14%">
				<col />


<?		
		if (isset($_SESSION['userLv']) && $_SESSION['userLv'] < 1) {
?>

				<tr>
					<td class="td01"><p class="reserve">예약은 회원에게만 제공됩니다.<a href="/member/login.php">[로그인 하기]</a> <a href="/member/join.php">[회원 가입 하기]</a><br /></td>
				</tr>
<?
			#두번째 방법. 선교관 이용 신청서 양식을 보내주시면 이용할 수 있습니다. <a href="javascript:void(0);" onclick="centerWinOpen(860, 600, '/common/usingForm.php', 'usingForm')">[다운로드]</a><br />
		} else {
?>
				<tr>
					<td class="td01"><p class="reserve"><b>이름</b></td>
					<td>
						<input type="text" name="resv_name" id="resv_name" value="<?=$member->name?>" class="input">
					</td>
					<td class="td01">출생년도</span></td>
					<td>
						<select name="birth_year" id="birth_year" tabindex="25">
<?
	for ($i = 0; $i <= 99; $i++) {
		if ($mission->BirthYear == (date('Y') - $i)) {
			print "<option value='".(date('Y') - $i)."' selected>".(date('Y') - $i)." </option>";			
		} else {
			print "<option value='".(date('Y') - $i)."'>".(date('Y') - $i)." </option>";
		}
	}
?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="td01">E-mail<span class="form-required" title="이 항목은 반드시 입력해야 합니다.">*</span></td>
					<td>
						<input type="text" name="email1" id="email1" style="ime-mode:disabled" tabindex="5" maxlength="30" value="<?=$member->Email[0]?>" />
						@
						<input type="text" name="email2" id="email2" style="ime-mode:disabled" tabindex="6" maxlength="50" value="<?=$member->Email[1]?>" />
					</td>
					<td class="td01">휴대폰번호</td>
					<td>
						<select id="hp1" name="hp1" tabindex="14">
							<option value="010"<? if ($member->Mobile[0] == "070") { ?> selected<? } ?>>010</option>
							<option value="011"<? if ($member->Mobile[0] == "070") { ?> selected<? } ?>>011</option>
							<option value="016"<? if ($member->Mobile[0] == "070") { ?> selected<? } ?>>016</option>
							<option value="017"<? if ($member->Mobile[0] == "070") { ?> selected<? } ?>>017</option>
							<option value="018"<? if ($member->Mobile[0] == "070") { ?> selected<? } ?>>018</option>
							<option value="019"<? if ($member->Mobile[0] == "070") { ?> selected<? } ?>>019</option>
						</select>
						-
						<input type="text" id="hp2" name="hp2" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" tabindex="15" maxlength="4" value="<?=$member->Mobile[1]?>" />
						-
						<input type="text" id="hp3" name="hp3" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" tabindex="16" maxlength="4" value="<?=$member->Mobile[2]?>" />
					</td>
				</tr>
				<tr>
					<td class="td01">전화번호</td>
					<td>
						<select id="tel1" name="tel1" tabindex="11">
							<option value="02"<? if ($member->Phone[0] == "02") { ?> selected<? } ?>>02</option>
							<option value="031"<? if ($member->Phone[0] == "031") { ?> selected<? } ?>>031</option>
							<option value="032"<? if ($member->Phone[0] == "032") { ?> selected<? } ?>>032</option>
							<option value="033"<? if ($member->Phone[0] == "033") { ?> selected<? } ?>>033</option>
							<option value="041"<? if ($member->Phone[0] == "041") { ?> selected<? } ?>>041</option>
							<option value="042"<? if ($member->Phone[0] == "042") { ?> selected<? } ?>>042</option>
							<option value="043"<? if ($member->Phone[0] == "043") { ?> selected<? } ?>>043</option>
							<option value="051"<? if ($member->Phone[0] == "051") { ?> selected<? } ?>>051</option>
							<option value="052"<? if ($member->Phone[0] == "052") { ?> selected<? } ?>>052</option>
							<option value="053"<? if ($member->Phone[0] == "053") { ?> selected<? } ?>>053</option>
							<option value="054"<? if ($member->Phone[0] == "054") { ?> selected<? } ?>>054</option>
							<option value="055"<? if ($member->Phone[0] == "055") { ?> selected<? } ?>>055</option>
							<option value="061"<? if ($member->Phone[0] == "061") { ?> selected<? } ?>>061</option>
							<option value="062"<? if ($member->Phone[0] == "062") { ?> selected<? } ?>>062</option>
							<option value="063"<? if ($member->Phone[0] == "063") { ?> selected<? } ?>>063</option>
							<option value="064"<? if ($member->Phone[0] == "064") { ?> selected<? } ?>>064</option>
							<option value="070"<? if ($member->Phone[0] == "070") { ?> selected<? } ?>>070</option>
						</select>
						-
						<input type="text" id="tel2" name="tel2" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" tabindex="12" maxlength="4" value="<?=$member->Phone[1]?>" />
						-
						<input type="text" id="tel3" name="tel3" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" tabindex="13" maxlength="4" value="<?=$member->Phone[2]?>" />
					</td>
					<td class="td01">국내연락처 </td>
					<td>
						<? $managerContact=explode("-",$mission->ManagerContact); ?>
						<select name="managerContact1" id="managerContact1" tabindex="24">
						<option value="010"<? if (($managerContact[0]=="010")) { ?> selected<? } ?>>010</option>
						<option value="011"<? if (($managerContact[0]=="011")) { ?> selected<? } ?>>011</option>
						<option value="016"<? if (($managerContact[0]=="016")) { ?> selected<? } ?>>016</option>
						<option value="017"<? if (($managerContact[0]=="017")) { ?> selected<? } ?>>017</option>
						<option value="018"<? if (($managerContact[0]=="018")) { ?> selected<? } ?>>018</option>
						<option value="019"<? if (($managerContact[0]=="019")) { ?> selected<? } ?>>019</option>
						<option value="02"<? if (($managerContact[0]=="02")) { ?> selected<? } ?>>02</option>
						<option value="031"<? if (($managerContact[0]=="031")) { ?> selected<? } ?>>031</option>
						<option value="032"<? if (($managerContact[0]=="032")) { ?> selected<? } ?>>032</option>
						<option value="033"<? if (($managerContact[0]=="033")) { ?> selected<? } ?>>033</option>
						<option value="041"<? if (($managerContact[0]=="041")) { ?> selected<? } ?>>041</option>
						<option value="042"<? if (($managerContact[0]=="042")) { ?> selected<? } ?>>042</option>
						<option value="043"<? if (($managerContact[0]=="043")) { ?> selected<? } ?>>043</option>
						<option value="051"<? if (($managerContact[0]=="051")) { ?> selected<? } ?>>051</option>
						<option value="052"<? if (($managerContact[0]=="052")) { ?> selected<? } ?>>052</option>
						<option value="053"<? if (($managerContact[0]=="053")) { ?> selected<? } ?>>053</option>
						<option value="054"<? if (($managerContact[0]=="054")) { ?> selected<? } ?>>054</option>
						<option value="055"<? if (($managerContact[0]=="055")) { ?> selected<? } ?>>055</option>
						<option value="061"<? if (($managerContact[0]=="061")) { ?> selected<? } ?>>061</option>
						<option value="062"<? if (($managerContact[0]=="062")) { ?> selected<? } ?>>062</option>
						<option value="063"<? if (($managerContact[0]=="063")) { ?> selected<? } ?>>063</option>
						<option value="064"<? if (($managerContact[0]=="064")) { ?> selected<? } ?>>064</option>
						<option value="070"<? if (($managerContact[0]=="070")) { ?> selected<? } ?>>070</option>
						</select>
						-
						<input type="text" name="managerContact2" id="managerContact2" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="4" tabindex="25" value="<?=$managerContact[1]?>" />
						-
						<input type="text" name="managerContact3" id="managerContact3" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="4" tabindex="26" value="<?=$managerContact[2]?>" />
					</td>
				</tr>		
				<tr>
					<td class="td01">선교지</td>
					<td>
						<select name="nation" id="nation" tabindex="32">
<? 
	for ($i=0; $i<=count($codes)-1; $i = $i+1) {
		$codeObj = $codes[$i];
		if (($codeObj->Code == $mission->NationCode)) { ?>
					<option value="<?=$codeObj->Code?>" selected><?=$codeObj->Name?></option>
<?
		} else {
?>
					<option value="<?=$codeObj->Code?>"><?=$codeObj->Name?></option>
<? 
		}
	}
?>
						</select>
					</td>
					<td class="td01">파송년도</span></td>
					<td>
						<select name="sent_year" id="sent_year" tabindex="25">
<?
	for ($i = 0; $i <= 99; $i++) {
		if ($mission->SentYear == (date('Y') - $i)) {
			print "<option value='".(date('Y') - $i)."' selected>".(date('Y') - $i)." </option>";			
		} else {
			print "<option value='".(date('Y') - $i)."'>".(date('Y') - $i)." </option>";
		}
	}
?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="td01">파송단체</td>
					<td><input type="text" name="church" id="church" maxlength="20" size="30" value="<?=$mission->Church?>" /></td>
					<td class="td01">단체연락처 </td>
					<? $churchContact=explode("-",$mission->ChurchContact); ?>
					<td>
						<select name="churchContact1" id="churchContact1" tabindex="24">
						<option value="02"<? if (($churchContact[0]=="02")) { ?> selected<? } ?>>02</option>
						<option value="031"<? if (($churchContact[0]=="031")) { ?> selected<? } ?>>031</option>
						<option value="032"<? if (($churchContact[0]=="032")) { ?> selected<? } ?>>032</option>
						<option value="033"<? if (($churchContact[0]=="033")) { ?> selected<? } ?>>033</option>
						<option value="041"<? if (($churchContact[0]=="041")) { ?> selected<? } ?>>041</option>
						<option value="042"<? if (($churchContact[0]=="042")) { ?> selected<? } ?>>042</option>
						<option value="043"<? if (($churchContact[0]=="043")) { ?> selected<? } ?>>043</option>
						<option value="051"<? if (($churchContact[0]=="051")) { ?> selected<? } ?>>051</option>
						<option value="052"<? if (($churchContact[0]=="052")) { ?> selected<? } ?>>052</option>
						<option value="053"<? if (($churchContact[0]=="053")) { ?> selected<? } ?>>053</option>
						<option value="054"<? if (($churchContact[0]=="054")) { ?> selected<? } ?>>054</option>
						<option value="055"<? if (($churchContact[0]=="055")) { ?> selected<? } ?>>055</option>
						<option value="061"<? if (($churchContact[0]=="061")) { ?> selected<? } ?>>061</option>
						<option value="062"<? if (($churchContact[0]=="062")) { ?> selected<? } ?>>062</option>
						<option value="063"<? if (($churchContact[0]=="063")) { ?> selected<? } ?>>063</option>
						<option value="064"<? if (($churchContact[0]=="064")) { ?> selected<? } ?>>064</option>
						<option value="070"<? if (($churchContact[0]=="070")) { ?> selected<? } ?>>070</option>
						</select>
						-
						<input type="text" name="churchContact2" id="churchContact2" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="4" tabindex="25" value="<?=$churchContact[1]?>" />
						-
						<input type="text" name="churchContact3" id="churchContact3" style="width:50px" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" maxlength="4" tabindex="26" value="<?=$churchContact[2]?>" />
				</tr>
			
				<tr>
					<td class="td01">선교사증명서</td>
					<td colspan="3">
						<input type="button" name="fileUpload" id="fileUpload" value=" 파일 업로드 " onclick="uploadFile(event, 'missionFile', 'mission')" style="cursor:pointer" />
						<input type="hidden" name="idmissionFile" id="idmissionFile" value="" />
						<input type="text" name="txtmissionFile" id="txtmissionFile" value="<?=$mission->attachFileName?>" size="80" readonly /> <br />
						* 미제출시 팩스(0505-911-0811) 혹은 이메일(godlovehouse@nate.com)로 보내주세요.
					</td>
				</tr>
		
				<tr>
					<td class="td01"><p class="reserve"><b>예약일정</b></td>
					<td colspan="3">
						<input type="text" name="startDate" id="startDate" value="<?=$fromDate?>" class="input" readonly onclick="calendar('startDate')">
						<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('startDate')"> ~
						<input type="text" name="endDate" id="endDate" class="input" value="<?=$toDate?>" readonly onclick="calendar('endDate')">
						<img src="../images/board/icon_calendar.gif" border="0" class="m2" align="absmiddle" onclick="calendar('endDate')">
						<label class="fs11" type="text" name='resultMessage1' id='resultMessage1'></label>
					</td>
				</tr>
				<tr>
					<td class="td01"><p class="reserve"><b>방문목적</b></td>
					<td colspan="3">
						
						<input type="checkbox" name="purpose[]" id="purpose[]" value="병원" class="input"> 병원
						<input type="checkbox" name="purpose[]" id="purpose[]" value="단체행사" class="input"> 단체행사 
						<input type="checkbox" name="purpose[]" id="purpose[]" value="안식년" class="input"> 안식년 
						<input type="checkbox" name="purpose[]" id="purpose[]" value="자녀교육" class="input"> 자녀교육 
						<input type="checkbox" name="purpose[]" id="purpose[]" value="기타" class="input"> 기타 
					</td>
				</tr>
		
				<tr>
					<td class="td01"><p class="reserve"><b>희망입주시간</b></td>
					<td>
						<select type="text" name="arrival_time" id="arrival_time">
							<? 
							for ($i = 6; $i <= 22; $i++) {
								echo "<option value='{$i}시'>{$i}시</option>";
							}
							?>
						</select>
					</td>
					<td class="td01"><p class="reserve"><b>입주인원수</b></td>
					<td>
						<select type="text" name="people_number" id="people_number">
							<? 
							for ($i = 1; $i <= 10; $i++) {
								echo "<option value='{$i}명'>{$i}명</option>";
							}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="td01"><p class="reserve"><b>메모</b></td>
					<td colspan="3">
						<textarea type="text" name="memo" id="memo">
* 입주 예정자 상세 (이름/나이/성별/관계):

* 소개 및 요청사항:

						</textarea>
					</td>
				</tr>
		<tr>
					<td colspan="2">
						<img id="btn_reserv" src="../images/board/btn_reserve.gif" border="0" align="right" align="absmiddle" class="m5" onclick="reserveSubmit()">
					</td>
				</tr>
<? 
			}
		} 
?>
			</table>
		</form>

		<div class="fb-comments" data-href="http://www.godlovehouse.net/living/reservationDetail.php?houseId=<?=$houseId?>&roomId=<?=$roomId?>" data-width="700" data-numposts="10" data-colorscheme="light"></div>

		<!-- view// -->
		<p class="btn_right"><img src="../images/board/btn_list.gif" border="0" class="m2" onclick="goRoomList();"></p>	
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
	
	function selectRoom() {
		var room = document.getElementById("room").value;
		if (room.length == 0) {
			return;
		}
		
		location.href = "reservationDetail.php?houseId=<?=$houseId?>&roomId=" + room;
	}
	
	function goRoomList() {
		history.back(-1);
		//location.href = "reservation.php?houseId=<?=$houseId?>";
	}
	
	function changeImage(imgName) {
		document.getElementById("mainImage").src = imgName;
	}
	
	function callPage(y, m) {
		var url = '/common/ajax/calendar.php?roomId=<?=$roomId?>&year='+y+'&month='+m;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: '', onComplete: insertCalendar});
	}

	function insertCalendar(reqResult) {
		var addHtml = reqResult.responseText;
		document.getElementById("reservationCal").innerHTML = addHtml;
	}
	
	var submited = false;
	function reserveSubmit() {
<? 
if (isset($_SESSION['userLv']) && $_SESSION['userLv'] < 1) {
?>
			alert('선교사 혹은 선교관리자 회원이 되어야 예약이 가능합니다.');
			return;
<? 
} else {
?>

		if (document.getElementById("resv_name").value == "") {
			alert("예약자 이름을 입력해주세요.");
			document.getElementById("resv_name").focus();
			return;
		}
		if (document.getElementById("church").value == "") {
			alert("파송기관을 입력해주세요.");
			document.getElementById("church").focus();
			return;
		}
		if (document.getElementById("churchContact2").value == "" || document.getElementById("churchContact3").value == "") {
			alert("파송기관 연락처를 입력해주세요.");
			document.getElementById("churchContact2").focus();
			return;
		}
		if (document.getElementById("email1").value == "" || document.getElementById("email2").value == "") {
			alert("이메일 주소를 확인해주세요.");
			document.getElementById("email1").select();
			return;
		}
		if (document.getElementById("tel2").value == "" || document.getElementById("tel3").value == "") {
			alert("전화번호를 확인해주세요.");
			document.getElementById("tel2").select();
			return;
		}
		if (document.getElementById("hp2").value == "" || document.getElementById("hp3").value == "") {
			alert("휴대폰 번호를 확인해주세요.");
			document.getElementById("hp2").select();
			return;
		}
		if (document.getElementById("managerContact2").value == "" || document.getElementById("managerContact3").value == "") {
			alert("국내 연락처를 확인해주세요.");
			document.getElementById("managerContact2").select();
			return;
		}

		document.getElementById("btn_reserv").style.display = "none";

		// 이중 클릭 방지
		if (submited) return;
		submited = true;
		
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
<?
} 
?>
	}
//]]>
</script>