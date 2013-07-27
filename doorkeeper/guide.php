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
					<li><a href="guide.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_01','','<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0101_on.gif',1)"><img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0101_on.gif" name="tab_01"></a></li>
					<li><a href="guide1.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_02','','<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0102_on.gif',1)"><img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0102.gif" name="tab_02"></a></li>
					<li><a href="guide2.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_03','','<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0103_on.gif',1)"><img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0103.gif" name="tab_03"></a></li>
					<li><a href="guide3.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_04','','<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0104_on.gif',1)"><img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0104.gif" name="tab_04"></a></li>
				</ul>
			</div>
		<H3>함께하는 약속</H3>
			<p>갓러브하우스(이하, 센터)는 센터에 등록되어진 선교관, 이를 관리하는 교회 및 기관, 센터를 이용하는 선교사 그리고 센터를 기도와 물질로 후원하는 후원자가 회원으로 활동하는 공간입니다. 이 사이트에 관계된 사람과 기관이 만족하고 보람을 가지기를 간절히 바라지만 그렇지 못할 때도 있습니다. 이에 상호간의 성실함과 배려가 꼭 필요합니다. 이러한 관계들 속에서 저희 센터는 정확한 정보와 재정의 투명성과 이용자의 감사를 잘 전하도록 노력하겠습니다.<br><br>
			<ul class="list_con b30">
				<li>후원하신 물질은 전액 원하시는 곳에 사용하게 됩니다.</li>
				<li>선교사와 가족이 되신 분은 선교사님의 최근 사진 및 근황을 전달받습니다. </li>
				<li>매년 후원금 명세서를 받아 보시게 됩니다. </li>
				<li>자원봉사 참여를 신청한 후원자님께는 필요시 센터 담당자로부터 즉시 연락을 받게 됩니다.</li>
			</ul>
			</p>
		<H3>후원자님께 드리는 부탁</H3>
			<ul class="list_con b30">
				<li>우선 기도와 마음으로 후원해 주시기를 간절히 바랍니다.</li>
		<li>후원을 지속하기 어려우실 때, 연락 주십시오. 센터에서 도와 드리겠습니다.</li>
				<li>후원금 납부는 CMS 자동출금이체를 이용하시면 시간과 비용을 줄일 수 있습니다.</li>
				<li>주소와 연락처가 변경될 경우에는, 꼭 센터에 알려주시기 바랍니다.</li>
			</ul>
		</div>
		<!-- content// -->
<?php } ?>
