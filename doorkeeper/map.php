<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

showHeader("HOME > 갓러브하우스 > 오시는길","doorkeeper","tit_0105.gif");
body();
showFooter();

function body() {
	global $Application;
?>
		<!-- //content -->
		<div id="content">
		<iframe src="/doorkeeper/daum_map.php" frameborder="0" border="0" width="700" height="400"></iframe>
		<!--img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/img_010501.gif"-->
		</div>
		<!-- content// -->
<?php } ?>
