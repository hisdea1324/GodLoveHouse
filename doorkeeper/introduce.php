<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

showHeader("HOME > 갓러브하우스 > 인사말","doorkeeper","tit_vision.gif");
body();
showFooter();

function body() {
?>
		<!-- //content -->
		<div id="content">
			<img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/img_vision.gif">
		</div>
		<!-- content// -->
<?php } ?>
