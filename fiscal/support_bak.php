<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
# 페이징 갯수
$PAGE_COUNT=10;
$PAGE_UNIT=10;
$supprotType="03001";

$field = trim($_REQUEST["field"]);
$keyword = trim($_REQUEST["keyword"]);
$page = trim($_REQUEST["page"]);
if ((strlen($page)==0)) {
	$page=1;
} 

$strWhere=makeCondition($supprotType,$field,$keyword);
$query = "SELECT COUNT(*) AS recordCount FROM request A, code B, user C".$strWhere;
$strPage = makePaging($page, $PAGE_COUNT, $PAGE_UNIT, $query);
$topNum = $PAGE_COUNT*$page;

$query = "SELECT top ".$topNum." A.*, C.nick, B.name AS regionName FROM request A, code B, user C".$strWhere." ORDER BY A.reqId DESC";
$db->CursorLocation=3;
$listRS = $db->Execute($query);
if (($listRS->RecordCount>0)) {
	$listRS->PageSize = $PAGE_COUNT;
	$listRS->AbsolutePage = $page;
} 

showHeader("HOME > 재정보고 > 수입보고","fiscal","tit_0501.gif");
body();
showFooter();

$listRS = null;

function makeCondition($supprotType,$field,$keyword) {
	$strWhere=" WHERE A.nationCode = B.code AND A.userId = C.userId AND A.supportType = '".$supprotType."'";
	if ((strlen($field)>0 && strlen($keyword)>0)) {
		$strWhere = $strWhere." AND ".$field." LIKE '%".$keyword."%'";
	} 

	return $strWhere;
} 

function body() {
?>
		<!-- # content -->
		<div id="content">

			<!-- # search -->
		<form name="findFrm" id="findFrm" action="special.php" method="get">
			<div id="search"> <img src="../images/board/img_search.gif" class="r10" align="absmiddle">
				<select name="field" id="field">
					<option value="A.title" <?php if (($field=="A.title")) { print "selected"; } ?> >제목</option>
					<option value="C.nick" <?php if (($field=="C.nick")) { print "selected"; } ?> >선교사명</option>
				</select>
				<input type="text" name="keyword" id="keyword" style="width:150px" class="input" value="<?php echo $keyword;?>">
				<img src="../images/board/btn_search.gif" border="0" align="absmiddle" onclick="frmSubmit();" style="cursor:hand"></div>
		</form>
			<!-- search#  -->
		
			<!-- # list -->
			<div class="bg_list">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_list">
					<tr>
						<th>번호</th>
						<th class="th01">지역</th>
						<th class="th01">선교사명</th>
						<th class="th01">후원제목</th>
						<th class="th01">총입금액</th>
						<th class="th01">입금일자</th>
					</tr>
<?php
			if ($listRS->EOF || $listRS->BOF) {
?>
					<tr>
						<td colspan="5">
							리스트가 없습니다
						</td>
					</tr>
<?php 
			} else {
				while(!($listRS->EOF || $listRS->BOF)) {
?>
					<tr>
						<td><?php echo $listRS["reqId"];?></td>
						<td><?php echo $listRS["regionName"];?></td>
						<td><?php echo $listRS["nick"];?></td>
						<td><?php echo $listRS["title"];?></td>
						<td><?php echo priceFormat($listRS["costCurrent"], 1);?></td>
						<td><?php echo dateFormat($listRS["sendDate"], 1);?></td>
					</tr>
<?php
					$listRS->MoveNext;
				}
			} 
?>
				</table>
			</div>
			<!-- list#  -->
			<!-- # page -->
			<?php echo $strPage;?>
			<!-- page#  -->
		</div>
		<!-- content#  -->
<?php } ?>
