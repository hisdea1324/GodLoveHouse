<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
$s_Helper = new SupportHelper();

$supporter = $s_Helper->getCenterSupportByuserid($_SESSION['userid']);
$reqList = $s_Helper->getCenterList();

showHeader("HOME > 후원 > 센터사역 후원","sponsor","tit_0302.gif");
body();
showFooter();

function body() {
	global $supporter, $reqList;
?>
	<!-- //content -->
	<div id="content">

		<!-- //list -->
		<div class="bg_list">

		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_list">
		<form action="centerSubmit.php" name="dataFrm" id="dataFrm" method="post" />
			<col width="20%" />
			<col />
			<col width="10%" />
			<tr>
				<th>항목</th>
				<th class="th01">내용</th>
				<th class="th01">체크</th>
			</tr>
<?php 
	if (count($reqList) == 0) {
?>
			<tr>
				<td colspan="3">리스트가 없습니다</td>
			</tr>
<?php 
	} else {
		foreach ($reqList as $requestInfo) {
?>
			<tr>
				<td>
					<p class="b">[<?php echo $requestInfo->Title;?>]</p>
					<img src="<?php echo $requestInfo->Image;?>" width="120" height="75" class="img">
				</td>
				<td class="ltd"><?php echo textFormat($requestInfo->Explain, 1);?></td>
				<td>
					<input type="checkbox" name="chkCenter" id="chkCenter" value="<?php echo $requestInfo->RequestID;?>" class="chk"<? if (!$supporter->IsNew && $supporter->Support($requestInfo->RequestID)) {?> checked<? } ?> />
				</td>
<?php 

		}

	} 

?>
			</tr>
		</form>
		</table>

		</div>
		<!-- list// -->
		<p class="btn_right"><img src="../images/board/btn_support2.gif" border="0" onclick="frmSubmit()" style="cursor:pointer;"></p>

	</div>
	<!-- content// -->
<?php } ?>

<script type="text/javascript">
//<![CDATA[
	function frmSubmit() {
		document.getElementById('dataFrm').submit();
	}
//]]>
</script>
