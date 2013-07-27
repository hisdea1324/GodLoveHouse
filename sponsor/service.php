<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
$sessions = new __construct();
$s_Helper = new SupportHelper();

$supporter = $s_Helper->getServiceSupportByUserId($sessions->UserId);
$reqList = $s_Helper->getServiceList();

showHeader("HOME > 후원 > 자원봉사 참여","sponsor","tit_0303.gif");
body();
showFooter();

function body() {
?>
	<!-- //content -->
	<div id="content">

		<!-- //list -->
		<div class="bg_list">

		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_list">
		<form action="process.php" name="dataFrm" id="dataFrm" method="post" />
			<input type="hidden" name="mode" value="addServiceSupport" />
			<col width="20%" />
			<col />
			<col width="10%" />
			<tr>
				<th>항목</th>
				<th class="th01">내용</th>
				<th class="th01">체크</th>
			</tr>
<?php 
	if ((count($reqList)==0)) {
?>
			<tr>
				<td colspan="3">리스트가 없습니다</td>
			</tr>
<?php 
	} else {
		for ($i=0; $i<=count($reqList)-1; $i = $i+1) {
			$requestInfo = $reqList[$i];
?>
			<tr>
				<td>
					<p class="b">[<?php echo $requestInfo->Title;?>]</p>
					<img src="<?php echo $requestInfo->Image;?>" width="120" height="75" class="img">
				</td>
				<td class="ltd"><?php echo $textFormat[$requestInfo->Explain][1];?></td>
				<td>
					<input type="checkbox" name="check" id="check" value="<?php echo $requestInfo->RequestID;?>" class="chk"<?			 if (((!$supporter->IsNew()) && $supporter->Support($requestInfo->RequestID))) {
?> checked<?			 } ?> />
				</td>
			</tr>
<?php 

		}

	} 

?>
		</form>
		</table>

		</div>
		<!-- list// -->
		<p class="btn_right"><img src="../images/board/btn_part.gif" border="0" onclick="frmSubmit()" style="cursor:pointer;" /></p>
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
