<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/class/BoardHelper.php");

# 페이징 갯수 
$PAGE_COUNT = 15;
$PAGE_UNIT = 10;
$field = isset($_REQUEST["field"]) ? trim($_REQUEST["field"]) : "";
$keyword = isset($_REQUEST["keyword"]) ? trim($_REQUEST["keyword"]) : "";
$page = isset($_REQUEST["page"]) ? trim($_REQUEST["page"]) : 1;
$groupId = isset($_REQUEST["groupId"]) ? trim($_REQUEST["groupId"]) : ""; 

switch ($groupId) {
	case "notice":
		$headerSet = array("HOME > 커뮤니티 > 공지사항","community","tit_0601.gif");
		break;
	case "impression":
		$headerSet = array("HOME > 커뮤니티 > 이용후기","community","tit_0602.gif");
		break;
	case "free":
		$headerSet = array("HOME > 커뮤니티 > 자유게시판","community","tit_0603.gif");
		break;
	case "event":
		$headerSet = array("HOME > 커뮤니티 > 선교단체행사","community","tit_0605.gif");
		break;
	case "column":
		$headerSet = array("HOME > 커뮤니티 > 칼럼","community","tit_0604.gif");
		break;
	case "need":
		$headerSet = array("HOME > 물류창고 > 필요물품","cooperate","tit_0506.gif");
		break;
	case "share":
		$headerSet = array("HOME > 물류창고 > 나눔물품","cooperate","tit_0507.gif");
		break;
	case "outGoing":
		$headerSet = array("HOME > 동역자소식 > 센터소식","fiscal","tit_0505.gif");
		break;
	case "mission_news":
		$headerSet = array("HOME > 동역자소식 > 선교지소식","fiscal","tit_0504.gif");
		break;
	case "support_news":
		$headerSet = array("HOME > 동역자소식 > 후원자소식","fiscal","tit_0505n.gif");
		break;
	default:
		alertGoPage("잘못된 접근입니다", $_SERVER['DOCUMENT_ROOT']."/index.php");
		break;
} 

$b_Helper = new boardHelper();
$b_Helper->PAGE_UNIT=10; # 하단 페이징 단위 
$b_Helper->PAGE_COUNT=15; # 한페이지에 보여줄 리스트 갯수 
$b_Helper->setCondition($field, $keyword, $groupId); # 조건문 작성
$strPage = $b_Helper->makePagingHTML($page);
$boardList = $b_Helper->getBoardListWithPaging($page);

showHeader($headerSet[0], $headerSet[1], $headerSet[2]);
body();
showFooter();

function body() {
	global $groupId, $keyword, $field, $page;
	global $strPage, $boardList;
	global $b_Helper;
?>
		<!-- //content -->
		<div id="content">
			<!-- //search -->
		<form name="findFrm" id="findFrm" action="board.php" method="get">
		<input type="hidden" name="groupId" id="groupId" value="<?=$groupId?>">
			<div id="search"> <img src="../images/board/img_search.gif" class="r10" align="absmiddle">
				<select name="field" id="field">
					<option value="title" <?php if ($field == "title") { print "selected"; } ?>>제목</option>
					<option value="userid" <?php if ($field == "userid") { print "selected"; } ?>>작성자</option>
				</select>
				<input type="text" name="keyword" id="keyword" style="width:150px" class="input" value="<?=$keyword?>">
				<img src="../images/board/btn_search.gif" border="0" align="absmiddle" onclick="frmSubmit();" style="cursor:hand"></div>
		</form>
			<!-- search// -->
			<!-- //list -->
			<div class="bg_list">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_list">
					<col width="10%" />
					<col />
					<col width="15%" />
					<col width="15%" />
					<col width="10%" />
					<tr>
						<th>번호</th>
						<th class="th01">제목</th>
						<th class="th01">작성자</th>
						<th class="th01">작성일</th>
						<th class="th01">조회</th>
					</tr>
<?php 
	if (count($boardList) == 0) {
?>
					<tr>
						<td colspan="5">작성된 글이 없습니다.</td>
					</tr>
<?php 
	} else {
		foreach ($boardList as $boardObj) {
?>
				<tr>
				<td><?=$boardObj->id?></td>
				<td class="ltd"><?=$boardObj->ReplyImage?><a href="view.php?groupId=<?=$groupId?>&keyword=<?=$keyword?>&field=<?=$field?>&id=<?=$boardObj->BoardID?>"><?=$boardObj->Title?></a></td>
				<td><?=$boardObj->userid?></td>
				<td><?=date("Y.m.d", $boardObj->RegDate)?></td>
				<td><?=$boardObj->CountView?></td>
				</tr>
				<?php 
		}
	} 
?>
				</table>
			</div>
			<!-- list// -->
			<!-- //page -->
			<?=$strPage?>
			<!-- page// -->
		
		<p class="btn_right"><a href="write.php?groupId=<?=$groupId?>&keyword=<?=$keyword?>&field=<?=$field?>"><img src="../images/board/btn_write.gif" border="0"></a></p>
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
