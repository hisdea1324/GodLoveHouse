<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

checkAuth();

$supportType="03001";
//페이징 갯수 
$PAGE_COUNT=15;
$PAGE_UNIT=10;


$field = (isset($_REQUEST["field"])) ? trim($_REQUEST["field"]) : "";
$keyword = (isset($_REQUEST["keyword"])) ? trim($_REQUEST["keyword"]) : "";
$order = (isset($_REQUEST["order"])) ? trim($_REQUEST["order"]) : "A.regDate DESC";
$page = (isset($_REQUEST["page"])) ? trim($_REQUEST["page"]) : 1;


// 조건문 작성
$strWhere=makeCondition($field,$keyword);

$query = "SELECT * AS recordCount from requestInfo A, requestAddInfo B ".$strWhere;
$strPage = makePaging($page, $PAGE_COUNT, $PAGE_UNIT, $query);
$topNum = $PAGE_COUNT * ($page - 1);


$query = "SELECT * FROM requestInfo A, requestAddInfo B $strWhere ORDER BY $order LIMIT $topNum, $PAGE_COUNT";


// 테이블 생성
$objTable = new tableBuilder();
$objTable->setButton(array("상세내역","후원자","수정","삭제"));
$objTable->setColumn(array("후원코드","제목","회원아이디","상태코드","등록일","마감일"));
$objTable->setOrder($order);
$objTable->setField(array("reqId","title","userid","status","regDate","dueDate"));
$objTable->setKeyValue(array("reqId"));
$objTable->setGotoPage($page);
$htmlTable = $objTable->getTable($query);

showAdminHeader("관리툴 - 후원관리","","","");
body();
showAdminFooter();

$listRS = null;


function makeCondition($field,$keyword) {
	global $supportType;
	$strWhere=" WHERE A.reqId = B.reqId AND A.supportType = '".$supportType."'";
	if ((strlen($field)>0 && strlen($keyword)>0)) {
		$strWhere = $strWhere." AND ".$field." LIKE '%".$keyword."%'";
	} 

	return $strWhere;
} 

function body() {
	global $keyword, $field;
	global $htmlTable, $strPage;
?>
	<div class="sub">
	<a href="addRequest.php">후원추가</a> | 
	<a href="index.php">특별후원</a> | 
	<a href="center.php">센터후원</a> | 
	<a href="service.php">자원봉사</a> |
	<a href="supportList.php?wait=1">후원자등록요청</a> |
	<a href="supportList.php?wait=0">후원자리스트</a>
	</div>
	</div>
	<div id="wrap">
		<div class="lSec">
		<ul>
			<li><img src="/images/manager/lm_0400.gif"></li>
		<li><a href="index.php"><img src="/images/manager/lm_0401.gif"></a></li>
		<li><a href="center.php"><img src="/images/manager/lm_0402.gif"></a></li>
		<li><a href="service.php"><img src="/images/manager/lm_0403.gif"></a></li>
		<li><a href="supportList.php"><img src="/images/manager/lm_0404.gif"></a></li>
		<li><img src="/images/manager/lm_bot.gif"></li>
		</ul>
	</div>
	<div class="rSec">
	<!-- 컨텐츠 들어가는 부분 -->

		<table cellpadding=0 cellspacing=0 border=0 width=100%>
			<form name="findForm" method="get" action="<?php echo $CurUrl;?>">
			<tr>
				<td align="right">
					<select name="field">
						<option value="title" <?php if (($field=="title")) {?>selected<?php } ?>>제목</option>
						<option value="userid" <?php if (($field=="userid")) {?>selected<?php } ?>>선교사</option>
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
	
	function clickButton(no, reqId) {
		switch(no) {
			case 0: goShow(reqId); break;
			case 1: goSupporter(reqId); break;
			case 2: goEdit(reqId); break;
			case 3: goDelete(reqId); break;
			default: break;
		}
	}

	function goShow(reqId) {
		location.href = 'subRequest.php?reqId=' + reqId + searchString;
	}

	function goEdit(reqId) {
		location.href = 'addRequest.php?reqId=' + reqId + searchString;
	}

	function goDelete(reqId) {
		if (confirm("정말 삭제 하시렵니까?")) {
			location.href = 'process.php?supportType=<?php echo $supportType;?>&mode=deleteRequest&reqId=' + reqId + searchString;
		}
	}
	
	function goSupporter(reqId) {
		location.href = 'supportList.php?reqId=' + reqId ;
	}
	
	function addRequest() {
		location.href = 'addRequest.php?supportType=<?php echo $supportType;?>' + searchString;
	}
//]]>
</script>
