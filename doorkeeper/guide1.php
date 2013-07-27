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
					<li><a href="guide1.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_02','','<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0102_on.gif',1)"><img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0102_on.gif" name="tab_02"></a></li>
					<li><a href="guide2.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_03','','<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0103_on.gif',1)"><img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0103.gif" name="tab_03"></a></li>
					<li><a href="guide3.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab_04','','<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0104_on.gif',1)"><img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/tab_0104.gif" name="tab_04"></a></li>
				</ul>
			</div>
			<H3>준수사항</H3>
			<H4>사생활 보호</H4>
			<p class="b20">이 센터를 통하여 얻게 된 개인정보를 소중히 다루어 주시기 바랍니다. 선교사의 이름과 신분에 관하여 철저히 보안을 유지해 주시기 바랍니다. 개인적인 요청이나 부탁은 최대한 삼가 하여 주시기 바랍니다.</p>
			<H4>선교관 이용에 관하여</H4>
			<ul class="list_con">
				<li>비치된 물품이나 시설을 깨끗하게 사용하여 주시기 바랍니다.</li>
		<li>선교관을 관리하는 사람이나 기관은 청결함을 유지하여 주시기 바랍니다.</li>
		<li>예약에 관련한 변동사항은 반드시 센터에 고지하여 주시기 바랍니다.
			(불이행시 불이익이 따를 수 있습니다)</li>
		<li>센터나 선교관을 이용하신 후에는 반드시 이용후기를 작성하여 주시기 바랍니다.
			(불이행시 불이익이 따를 수 있습니다)</li>
		<li>선교관 관리 단체의 요청 시 추가 서류를 요청할 수 있습니다.</li>
			</ul>
		</div>
		<!-- content// -->
<?php } ?>
