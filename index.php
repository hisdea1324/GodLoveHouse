<?php
require "include/include.php";

showHeader("HOME","main","");
body();
showFooter();

function body() { 
?>
	<div class="dan1">
		<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tab">
			<tr>
				<td>
			<img name="imgNotice" id="imgNotice" src="/images/main/tab_01_on.gif" border="0" style="cursor:hand" onclick="selectTab(1)"><img name="imgFree" id="imgFree" src="/images/main/tab_02.gif" border="0" style="cursor:hand" onclick="selectTab(2)">
		</td>
				<td align="right"><img src="/images/main/btn_more.gif" border="0" style="cursor:hand" onclick="clickMore()"></td>
			</tr>
		</table>
		<ul class="notice" id="shortList" name="shortList">
		</ul>
	</div>
	<div class="dan2"> 
		<a href="/living/reservation.php"><img src="/images/main/link_01.gif" border="0"></a><a href="#" onclick="centerWinOpen(600, 400, '/common/usingForm.php', 'usingForm')"><img src="/images/main/link_02.gif" border="0"></a><a href="/cooperate/family.php"><img src="/images/main/link_03.gif" border="0"></a>
	</div>
	<div class="dan3">
		<ul class="link">
			<li class="bot"><a href="/doorkeeper/map.php"><img src="/images/main/link_04.gif" border="0"></a></li>
		<li><a href="/community/board.php?groupId=impression"><img src="/images/main/link_05.gif" border="0"></a></li>
		<li><img src="/images/main/img_bank.gif"></li>
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
			document.getElementById('imgNotice').src = '/images/main/tab_01_on.gif';
			document.getElementById('imgFree').src = '/images/main/tab_02.gif';
			selectedBoard = 'notice';
		} else {
			document.getElementById('imgNotice').src = '/images/main/tab_01.gif';
			document.getElementById('imgFree').src = '/images/main/tab_02_on.gif';
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