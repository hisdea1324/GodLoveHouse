<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

showHeader("HOME > 갓러브하우스 > 인사말","doorkeeper","tit_manage.gif");
body();
showFooter();

function body() {
	global $Application;
?>
		<!-- //content -->
		<div id="content">
			<img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/sub/img_manage.gif">
		</div>
		<!-- content// -->
<?php } ?>
