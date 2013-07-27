<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

showHeader("HOME > 갓러브하우스 > 후원자가이드","doorkeeper","tit_0104.gif");
body();
showFooter();

function body() {
	global $Application;
?>
		<!-- //content -->
		<div id="content">
			<div class="tab">
				<ul>
					<li><a href="guide.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_01','','<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0101_on.gif',1)"><img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0101.gif" name="tab_01"></a></li>
					<li><a href="guide1.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_02','','<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0102_on.gif',1)"><img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0102.gif" name="tab_02"></a></li>
					<li><a href="guide2.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_03','','<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0103_on.gif',1)"><img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0103_on.gif" name="tab_03"></a></li>
					<li><a href="guide3.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_04','','<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0104_on.gif',1)"><img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0104.gif" name="tab_04"></a></li>
				</ul>
			</div>
			<H3>후원금 납부안내 </H3>
			<p class="b20">후원금은 각 항목에 정한 바에 따라 후원자님께서 가장 편리한 방법으로 납부해 주십시오. </p>
			<H4>계좌이체</H4>
			<ul class="list_con b20">
				<li>국민은행 639001-01-564452 (예금주: 갓러브하우스) 으로 입금해 주십시오.</li>
		<li>입금 후 반드시 센터에 연락 주십시오.</li>
			</ul>
			<H4>일반 자동이체</H4>
			<p class="b20">후원자님께서 거래하시는 은행에서 원하시는 날짜에 자동출금 되도록 예약 하실 수 있습니다.</p>
			<H4>CMS 자동출금이체</H4>
			<p class="b10">CMS 란 Cash Management Service 의 약자로서 금융기관에서 통신과 컴퓨터를 이용하여 제공 하는 금융결제 방법 서비스입니다. 금융결제원이 CMS 이용자와 CMS 참가 금융기관의 전산 시스템을 상호 접속시켜 이용기관의 참가 은행과의 금융거래를 전자적으로 처리 할 수 있도록 시스템을 구성하고 관련 서비스를 제공하여 주는 서비스 입니다.<br>
				CMS 자동이체는 두가지 방법으로 신청 하실 수 있습니다.</p>
			<ul class="list_con">
				<li><b>센터에 신청 하시는 방법</b><br>
					필요한 서류 : CMS 신청서를 친필로 작성하시고, 서명하셔서 팩스로 보내주시기 바랍니다. (Fax 번호: 0505-911-0811)</li>
				<li><b>은행에 신청하시는 방법</b><br>
					후원자님께서 거래 하시는 은행에 방문하시어 신청해 주시는 방법입니다. 가셔서 신청 하시면 됩니다. 가실 때에는 신분증 및 통장과 함께 갓러브하우스에서 송부하여 드린 ‘후원금 납부 안내서’를 지참해 주시기 바랍니다. 은행에서 요구하는 정보(후원자 번호 6자리와 갓러브하우스 기관코드)를 안내받으실 수 있습니다. 또한, 전화로 문의해 주셔도 친절한 안내를 받으실 수 있습니다. </li>
			</ul>
		</div>
		<!-- content// -->
<?php } ?>
