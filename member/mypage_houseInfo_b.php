<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
checkUserLogin();
$toDate = trim($_REQUEST["toDate"]);
$fromDate = trim($_REQUEST["fromDate"]);

$m_Helper = new MemberHelper();
$member = $m_Helper->getMemberByuserid($_SESSION["userid"]);
$account = $m_Helper->getAccountInfoByuserid($_SESSION["userid"]);

$h_Helper = new HouseHelper();
$houseList1 = $h_Helper->getHouseListByuserid($_SESSION["userid"]);
$houseList2 = $h_Helper->getHouseListByuserid($_SESSION["userid"]);

if ($_SESSION["userLv"] >= 7) {
	showHeader("HOME > 멤버쉽 > 선교관 정보관리","mypage_manager","tit_0804.gif");
} else if ($_SESSION["userLv"] >= 3) {
	showHeader("HOME > 멤버쉽 > 선교관 정보관리","mypage_missionary","tit_0804.gif");
} else {
	showHeader("HOME > 멤버쉽 > 선교관 정보관리","mypage_normal","tit_0804.gif");
} 


body();
showFooter();

function body() {
?>
		<!-- //content -->
		<div id="content">
			<div class="mypage b20">
			<p class="hi"><strong><?php echo $member->Name;?></strong>님, 안녕하세요</p>
				<ul class="txt01">
			<li><strong>회원ID</strong> <?php echo $member->userid;?></li>
			<li class="btn">
			<img src="../images/sub/btn_out.gif" onclick="clickTopNavi(10)" class="r5">
			<img src="../images/sub/btn_logout.gif" onclick="clickTopNavi(4)" class="r5">
			</li>
		</ul>
		<ul class="txt02">
			<li class="tit"><?php echo $account->Method;?></li>
			<li>은행 : <?php echo $account->Bank;?></li>
			<li>예금주 : <?php echo $account->Name;?></li>
			<li>계좌번호 : <?php echo $account->Number;?></li>
			<li>이체일 : <?php echo $account->SendDate;?> 일</li>
		</ul> 
			</div>
		<!-- 정보// -->
		<!-- //선교관 정보 -->
			<p class="hi"><strong>등록된 선교관 </strong></p>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_write">
				<col width="25%" />
				<col />
		<?php 
	for ($i=0; $i<=count($houseList1)-1; $i = $i+1) {
		$houseObj = $houseList1[$i];
?>
				<tr>
					<td class="td01">
			<a href="/living/reservation.php?houseId=<?php echo $houseObj->HouseID;?>"><?php echo $houseObj->HouseName;?></a>
			</td>
					<td>
			<?php 
		$roomList = $houseObj->RoomList;
		for ($j=0; $j<=count($roomList); $j = $j+1) {
			$roomObj = $roomList[$j];
?>
				<a href="/living/reservationDetail.php?roomId=<?php echo $roomObj->RoomID;?>"><?php echo $roomObj->RoomName;?></a> (<?php echo $roomObj->showFee();?>) <a href="/living/reservationDetail.php?roomId=<?php echo $roomObj->RoomID;?>"><font color="red">[본인예약]</font></a><br>
			<?php 

		}

?>
			</td>
			<td align="right">
			<a href="/living/registHouse.php?houseId=<?php echo $houseObj->HouseID;?>">[수정]</a>
			</td>
				</tr>
		<?php 

	}

?>
			</table>
		
			<p class="hi"><strong>등록 대기중 선교관 </strong></p>
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_write">
				<col width="25%" />
				<col />
		<?php 
	for ($i=0; $i<=count($houseList2)-1; $i = $i+1) {
		$houseObj = $houseList2[$i];
?>
				<tr>
					<td class="td01"><?php echo $houseObj->HouseName;?></td>
					<td><?php echo $houseObj->Price;?></td>
			<td align="right">
			<a href="/living/registHouse.php?houseId=<?php echo $houseObj->HouseID;?>">[수정]</a>
			</td>
				</tr>
		<?php 

	}

?>
			</table>
			<!-- 선교사일경우// -->
		</div>
		<!-- content// -->
<?php } ?>
