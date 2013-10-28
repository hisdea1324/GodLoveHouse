<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

showHouseManagerHeader();
showHouseManagerLeft();
body();
showHouseManagerFooter();

function body() {
	$roomId = (isset($_REQUEST["roomId"])) ? trim($_REQUEST["roomId"]) : "";
	$houseId = (isset($_REQUEST["houseId"])) ? trim($_REQUEST["houseId"]) : "";

	$h_Helper = new HouseHelper();
	$room = $h_Helper->getRoomInfoById($roomId);
	$house = $h_Helper->getHouseInfoById($houseId);

	setTestValue($room);
?>
	<!-- rightSec -->
	<div id="rightSec">
		<div class="lnb">
			<strong>Home</strong> &gt; 선교관 관리 &gt; 등록하기
		</div>
		<div id="content">
			<!-- content -->
			<h1>선교관 관리</h1>
			<table class="write mt30">
				<colgroup>
					<col width="20%" />
					<col width="80%" />
				</colgroup>
				<tbody>
					<tr>
						<th>선교관이름</th>
						<td><?=$house->houseName?></td>
					</tr>
					<tr>
						<th>방이름</th>
						<td>
							<input type="text" name="roomName" class="inputTxt" size="50" maxlength="20" value="<?=$room->RoomName;?>" />
						</td>
					</tr>
					<tr>
						<th>인터넷 유무</th>
						<td>
							<input type="radio" name="network" id="network" value="Y" class="ml20" <?php if ($house->Network == "Y") { print "checked"; } ?> /> 있음
							<input type="radio" name="network" id="network" value="N" class="ml20" <?php if ($house->Network != "Y") { print "checked"; } ?> /> 없음 
						</td>
					</tr>
					<tr>
						<th>취사여부</th>
						<td>
							<input type="radio" name="kitchen" id="kitchen" value="Y" class="ml20" <?php if ($house->kitchen == "Y") { print "checked"; } ?> /> 가능
							<input type="radio" name="kitchen" id="kitchen" value="N" class="ml20" <?php if ($house->kitchen != "Y") { print "checked"; } ?> /> 불가능 
						</td>
					</tr>
					<tr>
						<th>세탁여부</th>
						<td>
							<input type="radio" name="laundary" id="laundary" value="Y" class="ml20" <?php if ($house->laundary == "Y") { print "checked"; } ?> /> 가능
							<input type="radio" name="laundary" id="laundary" value="N" class="ml20" <?php if ($house->laundary != "Y") { print "checked"; } ?> /> 불가능
						</td>
					</tr>
					<tr>
						<th>요금</th>
						<td>
							<input type="text" name="fee" class="inputTxt" size="30" value="<?=$room->Fee;?>" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" /> 원
						</td>
					</tr>
					<tr>
						<th>방인원수</th>
						<td>
							<input type="text" name="limit" class="inputTxt" size="30" value="<?php echo $room->Limit;?>" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" /> 명
						</td>
					</tr>
					<!--tr>
						<th>색상선택</th>
						<td>
							<ul class="chart_c">
								<li><input type="radio" value="1"/><div class="color c1"></div></li>
								<li><input type="radio" value="2"/><div class="color c2"></div></li>
								<li><input type="radio" value="3"/><div class="color c3"></div></li>
								<li><input type="radio" value="4"/><div class="color c4"></div></li>
								<li><input type="radio" value="5"/><div class="color c5"></div></li>
								<li><input type="radio" value="6"/><div class="color c6"></div></li>
								<li><input type="radio" value="7"/><div class="color c7"></div></li>
								<li><input type="radio" value="8"/><div class="color c8"></div></li>
								<li><input type="radio" value="9"/><div class="color c9"></div></li>
								<li><input type="radio" value="10"/><div class="color c10"></div></li>
							</ul>
						</td>
					</tr-->
					<tr>
						<th>이미지</th>
						<td>
							<span class="btn1"><a href="#">이미지등록</a></span> <span class="btn1g"><a href="#">+ 이미지추가</a></span><br />
							<div class="img"></div>
							<div class="img"></div>
							<div class="img"></div>
							<div class="img"></div>
						</td>
					</tr>
				</tbody>
			</table>
			<div class="aRight mt20">
				<span class="btn2"><a href="#">수정</a></span>
				<span class="btn2"><a href="#">삭제</a></span>
			</div>
			<!-- // content -->
		</div>
	</div>
	<!-- // rightSec -->
<?php } ?>
