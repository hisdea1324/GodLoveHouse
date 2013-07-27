<?php
require "include/include.php";

showHeader("HOME","main","");
body();
showFooter();

function body() { 
	global $Application;
?>
	<div class="dan1">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tab">
			<tr>
				<td>
			<img name="imgNotice" id="imgNotice" src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/main/tab_01_on.gif" border="0" style="cursor:hand" onclick="selectTab(1)"><img name="imgFree" id="imgFree" src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/main/tab_02.gif" border="0" style="cursor:hand" onclick="selectTab(2)">
		</td>
				<td align="right"><img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/main/btn_more.gif" border="0" style="cursor:hand" onclick="clickMore()"></td>
			</tr>
		</table>
		<ul class="notice" id="shortList" name="shortList">
		</ul>
	</div>
	<div class="dan2"> 
		<a href="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/living/reservation.php"><img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/main/link_01.gif" border="0"></a><a href="#" onclick="centerWinOpen(600, 400, '<?php echo "http://".$_SERVER['SERVER_NAME'];?>/common/usingForm.php', 'usingForm')"><img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/main/link_02.gif" border="0"></a><a href="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/cooperate/family.php"><img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/main/link_03.gif" border="0"></a>
	</div>
	<div class="dan3">
		<ul class="link">
			<li class="bot"><a href="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/doorkeeper/map.php"><img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/main/link_04.gif" border="0"></a></li>
		<li><a href="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/community/board.php?groupId=impression"><img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/main/link_05.gif" border="0"></a></li>
		<li><img src="<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/main/img_bank.gif"></li>
		</ul>
	</div>
<?php }	?>

<script type="text/javascript">
//<![CDATA[
	var selectedBoard = 'notice';
	selectTab(1);
	
	function clickMore() {
		location.href = 'community/board.php?groupId=' + selectedBoard;
	}
		function selectTab(selected) {
		if (selected == 1) {
			document.getElementById('imgNotice').src = '<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/main/tab_01_on.gif';
			document.getElementById('imgFree').src = '<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/main/tab_02.gif';
			selectedBoard = 'notice';
		} else {
			document.getElementById('imgNotice').src = '<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/main/tab_01.gif';
			document.getElementById('imgFree').src = '<?php echo "http://".$_SERVER['SERVER_NAME'];?>/images/main/tab_02_on.gif';
			selectedBoard = 'free';
		}
		
		var url = 'ajax.php?mode=board&groupId=' + selectedBoard;
		var myAjax = new Ajax.Request(url, {method: 'post', parameters: '', onComplete: cb_callPage});
	}
		function cb_callPage(reqResult) {
		var dataobj = reqResult.responseText;
		document.getElementById('shortList').innerHTML = dataobj;
	}

//]]>
</script>