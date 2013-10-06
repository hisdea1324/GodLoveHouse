<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

$field = isset($_REQUEST["field"]) ? trim($_REQUEST["field"]) : "";
$keyword = isset($_REQUEST["keyword"]) ? trim($_REQUEST["keyword"]) : "";
$page = isset($_REQUEST["page"]) ? trim($_REQUEST["page"]) : 1;

$s_Helper = new SupportHelper();

//페이징 갯수 
$s_Helper->PAGE_COUNT=10;
$s_Helper->PAGE_UNIT = 10;
if (strlen($field) && strlen($keyword)) {
	$s_Helper->setConditionRequestInfo($field, $keyword);
} 

$requests = $s_Helper->getSpecialList($page);
$strPage = $s_Helper->makePagingHTMLRequestInfo($page);

showHeader("HOME > 후원 > 특별후원","sponsor","tit_0301.gif");
body();
showFooter();

function body() {
	global $keyword, $strPage;
	global $requests;
?>
	<!-- //content -->
	<div id="content">
		<!-- //search -->
		<form name="findFrm" id="findFrm" action="special.php" method="get">
		<div id="search"> <img src="../images/board/img_search.gif" class="r10" align="absmiddle">
			<select name="field" id="field">
				<option value="title"<?php if ($field == "title") { ?> selected<?php } ?>>제목</option>
				<!--option value="C.nick"<?php if (($field=="C.nick")) { ?> selected<?php } ?>>선교사명</option-->
			</select>
			<input type="text" name="keyword" id="keyword" style="width:150px" class="input" value="<?php echo $keyword;?>">
			<img src="../images/board/btn_search.gif" border="0" align="absmiddle" onclick="frmSubmit();" style="cursor:pointer" />
		</div>
		</form>
		<!-- search// -->

		<!-- //list -->
		<div class="bg_list">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_list">
			<col width="20%" />
			<col width="15%" />
			<col />
			<col width="10%" />
			<col width="15%" />
			<tr>
				<th>이미지</th>
				<th class="th01">선교사(지역)</th>
				<th class="th01">제목</th>
				<th class="th01">후원상황</th>
				<th class="th01">후원마감일</th>
			</tr>
<?php 
	if (count($requests) == 0) {
?>
			<tr>
				<td colspan="5">리스트가 없습니다</td>
			</tr>
<?php 
	} else {
		foreach ($requests as $requestInfo) {
			$reqAddInfo = $s_Helper->getRequestAddInfoByReqID($requestInfo->RequestID);
?>
			<tr>
				<td>
					<a href="specialSubmit.php?reqId=<?php echo $requestInfo->RequestID;?>">
					<img src="<?php echo $requestInfo->Image;?>" width="100" height="60" border="0" class="img"></a>
				</td>
				<td><?php echo $reqAddInfo->Nick;?>(<?php echo $reqAddInfo->Nation;?>)</td>
				<td class="ltd">
					<p class="b"><?php echo $requestInfo->Title;?></p>
					<p><?php echo textFormat($requestInfo->Explain, 1);?></p>
				</td>
				<td><?php echo $reqAddInfo->SupportRatio;?> %</td>
				<td><?php echo dateFormat($reqAddInfo->Due, 1);?></td>
			</tr>
<?php 

		}

	} 

?>
		</table>
		</div>
		<!-- list// -->

		<!-- //page -->
		<?php echo $strPage;?>
		<!-- page// -->

		<!--p class="btn_right"><a href="#"><img src="../images/board/btn_support.gif" border="0"></a></p-->
	</div>
	<!-- content// -->
<?php } ?>

<script type="text/javascript">
//<![CDATA[
	function frmSubmit() {
		var findFrm = document.getElementById("findFrm");
		findFrm.submit();
	}
//]]>
</script>
