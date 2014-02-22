<?
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");


//페이징 갯수 
$PAGE_COUNT=15;
$PAGE_UNIT=10;

$field = (isset($_REQUEST["field"])) ? trim($_REQUEST["field"]) : "";
$keyword = (isset($_REQUEST["keyword"])) ? trim($_REQUEST["keyword"]) : "";
$page = (isset($_REQUEST["page"])) ? trim($_REQUEST["page"]) : 1;
checkAuth();
$topNum = $PAGE_COUNT * ($page - 1);
$query = "select * from boardGroup";

// 테이블 생성
$objTable = new tableBuilder();
$objTable->setButton(array("글목록","권한변경"));
$objTable->setColumn(array("GroupId","관리자아이디","읽기권한","쓰기권한","글갯수","게시판명"));
$objTable->setField(array("groupId","managerId","authReadLv","authWriteLv","countList","name"));
$objTable->setKeyValue(array("groupId"));
$objTable->setGotoPage($page);
$htmlTable = $objTable->getTable($query);

showAdminHeader("관리툴 - 게시판관리","","","");
//call showAdminMenu()
body();
showAdminFooter();


$objTable = null;


function body() {
	global $htmlTable, $keyword, $field;
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
		<?=$htmlTable?>
	</div>
</div>
<? } ?>

<script type="text/javascript">
//<![CDATA[
	var searchString = '&keyword=<?=$keyword?>&field=<?=$field?>';
	
	function clickButton(no, groupId) {
		switch(no) {
			case 0: goShow(groupId); break;
			case 1: goEdit(groupId); break;
			case 2: goDelete(groupId); break;
			default: break;
		}
	}

	function goShow(groupId) {
		location.href = 'boardList.php?groupId=' + groupId + searchString;
	}

	function goEdit(groupId) {
		location.href = 'editBoard.php?groupId=' + groupId + searchString;
	}

	function goDelete(groupId) {
		alert("준비중입니다.");
	}
//]]>
</script>
