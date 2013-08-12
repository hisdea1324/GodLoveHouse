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
$query = "SELECT COUNT(*) AS recordCount FROM request A, code B, users C".$strWhere;
#$strPage = makePaging(page, PAGE_COUNT, PAGE_UNIT, query)
#$topNum = $PAGE_COUNT*$page;

$query = "SELECT top ".$topNum." A.*, C.nick, B.name AS regionName FROM request A, code B, users C".$strWhere." ORDER BY A.reqId DESC";
#Set listRS = db.Execute(query)
#if (listRS.RecordCount > 0) then 
#	listRS.PageSize = PAGE_COUNT
#	listRS.AbsolutePage = page
#end if
showHeader("HOME > 재정보고 > 지출보고","fiscal","tit_0505.gif");
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
		<!-- #content -->
		<div id="content">
			<!-- #search -->
			<div id="search"> <img src="../images/board/img_search.gif" class="r10" align="absmiddle">
				<select name="select">
					<option>제목</option>
				</select>
				<input type="text" name="textfield" style="width:150px" class="input">
				<a href="#"><img src="../images/board/btn_search.gif" border="0" align="absmiddle"></a></div>
			<!-- search# -->
			<!-- #list -->
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
					<tr>
						<td>100</td>
						<td>홍길동</td>
						<td class="ltd">캄보디아 학교설립 </td>
						<td class="rtd">20,000</td>
						<td>2008.08.08</td>
						<td class="ltd">캄보디아 학교설립 
						<td>홍길동</td>
						<td>2008.08.08</td>
					</tr>
				</table>
			</div>
			<!-- list# -->
			<!-- #page -->
			<div class="paging"> <a href="#"><img src="../images/board/btn_pre_02.gif" alt=""/></a> <a href="#"><img src="../images/board/btn_pre_01.gif" alt="" /></a> <span class="pagingText"><b><a href="#">1</a></b> | <a href="#">2</a> | <a href="#">3</a> | <a href="#">4</a> | <a href="#">5</a> | <a href="#">6</a> | <a href="#">7</a> | <a href="#">8</a> | <a href="#">9</a> | <a href="#">10</a></span> <a href="#"><img src="../images/board/btn_next_01.gif" alt="" /></a> <a href="#"><img src="../images/board/btn_next_02.gif" alt="" /></a> </div>
			<!-- page# -->
		</div>
		<!-- content# -->
<?php } ?>
