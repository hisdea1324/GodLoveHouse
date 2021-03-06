<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

global $mysqli;
checkAuth();

//페이징 갯수 
$PAGE_COUNT=15;
$PAGE_UNIT=10;

$field = (isset($_REQUEST["field"])) ? trim($_REQUEST["field"]) : "";
$keyword = (isset($_REQUEST["keyword"])) ? trim($_REQUEST["keyword"]) : "";
$groupId = (isset($_REQUEST["groupId"])) ? trim($_REQUEST["groupId"]) : "notice";
$order = (isset($_REQUEST["order"])) ? trim($_REQUEST["order"]) : "id";
$page = (isset($_REQUEST["page"])) ? trim($_REQUEST["page"]) : 1;


//$groupId = trim($_REQUEST["groupId"]);
/*
$order = trim($_REQUEST["order"]);
$page = trim($_REQUEST["page"]);
if ((strlen($page)==0)) {
	$page=1;
} 
if ((strlen($groupId)==0)) {
	$groupId="notice";
} 
if ((strlen($order)==0)) {
	$order="id";
} 
*/

//$query = "SELECT name FROM boardGroup where groupId = '".$groupId."'";
//if ($result = $mysqli->query($query)) {
//	while ($row = $result->fetch_assoc()) {
//		$boardName = $row["name"];
//	}
//}

// 조건문 작성


//현재 페이지 저장
$CurUrl = $_SERVER['PHP_SELF'];

//$query = "SELECT COUNT(*) AS recordCount from board".$strWhere;
//$strPage = makePaging($page, $PAGE_COUNT, $PAGE_UNIT, $query);
//$topNum = $PAGE_COUNT*$page;

//조건문 작성
$strWhere=makeCondition($groupId,$field,$keyword);

//페이지 네비게이션 
$query = "SELECT * FROM board ".$strWhere;
$strPage = makePaging($page, $PAGE_COUNT, $PAGE_UNIT, $query);

//현재 페이지에 보여줄 레코드 
$topNum = $PAGE_COUNT * ($page - 1);
$query = "SELECT * FROM board ".$strWhere." ORDER BY ".$order." DESC LIMIT $topNum, $PAGE_COUNT";




//$strPage = makePaging($page, $PAGE_COUNT, $PAGE_UNIT, $query);
//$topNum = $PAGE_COUNT * ($page - 1);
//$query = "select top ".$topNum." * from board ".$strWhere." ORDER BY ".$order." DESC";



//$query = "select top ".$topNum." * from board ".$strWhere." ORDER BY ".$order." DESC";
//if ($result = $mysqli->query($query)) {
//	while ($row = $result->fetch_assoc()) {
//		$boardName = $row["name"];
//	}
//}


// 테이블 생성
$objTable = new tableBuilder();
$objTable->setButton(array("수 정","답 글","삭 제"));
$objTable->setColumn(array("글번호","제목","작성자","작성일","조회수"));
$objTable->setOrder($order);
$objTable->setField(array("id","title","userid","editDate","countView"));
$objTable->setKeyValue(array("groupId","id"));
$objTable->setGotoPage($page);
$htmlTable = $objTable->getTable($query);

showAdminHeader("관리툴 - 게시판","","","");
body();
showAdminFooter();

//$listRS = null;

$objTable = null;


function makeCondition($groupId,$field,$keyword) {
	$strWhere=" WHERE groupId = '".$groupId."'";
	if ((strlen($field)>0 && strlen($keyword)>0)) {
		$strWhere = $strWhere." AND ".$field." LIKE '%".$keyword."%'";
	} 

	return $strWhere;
} 

function body() {
	global $groupId, $field, $keyword, $htmlTable, $strPage, $CurUrl;
?>
<div class="sub">
	<a href="index.php">게시판목록</a> | 
	<a href="boardList.php?groupId=notice">공지사항</a> | 
	<a href="boardList.php?groupId=free">자유게시판</a> | 
	<a href="boardList.php?groupId=impression">이용후기</a> | 
	<a href="boardList.php?groupId=need">필요게시판</a> | 
	<a href="boardList.php?groupId=share">나눔게시판</a> | 
	<a href="boardList.php?groupId=outGoing">지출게시판</a></div>
</div>
<div id="wrap">
	<div class="lSec">
		<ul>
			<li><img src="/images/manager/lm_0600.gif"></li>
			<li><a href="boardList.php?groupId=notice"><img src="/images/manager/lm_0601.gif"></a></li>
			<li><a href="boardList.php?groupId=free"><img src="/images/manager/lm_0602.gif"></a></li>
			<li><a href="boardList.php?groupId=impression"><img src="/images/manager/lm_0603.gif"></a></li>
			<li><a href="boardList.php?groupId=need"><img src="/images/manager/lm_0604.gif"></a></li>
			<li><a href="boardList.php?groupId=share"><img src="/images/manager/lm_0605.gif"></a></li>
			<li><a href="boardList.php?groupId=outGoing"><img src="/images/manager/lm_0606.gif"></a></li>
			<li><img src="/images/manager/lm_bot.gif"></li>
		</ul>
	</div>
	<div class="rSec">
	<!-- 컨텐츠 들어가는 부분 -->
		
		<table cellpadding=0 cellspacing=0 border=0 width=100%>
			<form name="findForm" method="get" action="<?php echo $CurUrl;?>">
			<tr>
				<td>
					<input type="button" value="글쓰기" onclick="location.href='/community/write.php?groupId=<?php echo $groupId;?>';" style="cursor:pointer" />
				</td>
				<td align="right">
					<input type="hidden" name="groupId" value="<?php echo $groupId;?>">
					<select name="field">
						<option value="name" <?php if ($field == "name") {
?>selected<?php } ?>>이름</option>
						<option value="userid" <?php if ($field == "userid") {
?>selected<?php } ?>>아이디</option>
						<option value="title" <?php if ($field == "title") {
?>selected<?php } ?>>제목</option>
					</select>
					<input type="text" name="keyword" size="15" value="<?php echo $keyword;?>">
					<input type="image" src="/images/btn_find.gif" border="0" align="absmiddle">
				</td>
			</tr>
			</form>
		</table>

		<?php echo $htmlTable;?>

		<table cellpadding=0 cellspacing=0 border=0 width=100%>
		<tr><td align="center" height="60"><?php echo $strPage;?></td></tr>
		</table>

		</div>
	</div>
<?php } ?>

<script type="text/javascript">
//<![CDATA[
	var searchString = '&keyword=<?php echo $keyword;?>&field=<?php echo $field;?>';
	
	function clickButton(no, groupId, id) {
		switch(no) {
			case 0: goEdit(groupId, id); break;
			case 1: goReply(groupId, id); break;
			case 2: goDelete(groupId, id); break;
			default: break;
		}
	}

	function goEdit(groupId, id) {
		location.href = '/community/write.php?mode=editPost&groupId=' + groupId + '&id=' + id + searchString;
	}

	function goReply(groupId, id) {
		location.href = '/community/write.php?mode=replyPost&groupId=' + groupId + '&id=' + id + searchString;
	}

	function goDelete(groupId, id) {
		location.href = '/community/process.php?mode=deletePost&groupId=' + groupId + '&id=' + id + searchString;
	}
//]]>
</script>
