<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

checkAuth();

//페이징 갯수 
$PAGE_COUNT=15;
$PAGE_UNIT=10;

$reqId = trim($_REQUEST["reqId"]);
$order = trim($_REQUEST["order"]);
$page = trim($_REQUEST["page"]);
if ((strlen($page)==0)) {
	$page=1;
} 
if ((strlen($order)==0)) {
	$order="reqId";
} 

$query = "select A.title, B.userId, B.dueDate from requestInfo A, requestAddInfo B WHERE A.reqId = B.reqId AND A.reqId = ".$reqId;
$viewRS = $db->Execute($query);

$query = "select sum(cost) as costTotal from (select cost from requestItem WHERE reqId = ".$reqId." union select 0) T";
$costRS = $db->Execute($query);

$query = "SELECT COUNT(*) AS recordCount from requestItem WHERE reqId = ".$reqId;
$strPage = makePaging($page, $PAGE_COUNT, $PAGE_UNIT, $query);
$topNum = $PAGE_COUNT*$page;

$query = "SELECT top ".$topNum." * from requestItem WHERE reqId = ".$reqId." ORDER BY ".$order;
$db->CursorLocation=3;
$listRS = $db->Execute($query);
if (($listRS->RecordCount>0)) {
	$listRS->PageSize = $PAGE_COUNT;
	$listRS->AbsolutePage = $page;
} 


// 테이블 생성
$objTable = new tableBuilder();
$objTable->setButton(array("후원자","수 정","삭 제"));
$objTable->setColumn(array("개별코드","후원요청코드","아이템","요청금액","후원상태","후원자"));
$objTable->setField(array("reqItemId","reqId","item","cost","sendStatus","userId"));
$objTable->setOrder($order);
$objTable->setKeyValue(array("reqItemId"));
$objTable->setGotoPage($page);
$htmlTable = $objTable->getTable($listRS);
$htmlPaging = $objTable->displayListPage();

showAdminHeader("관리툴 - 후원관리","","","");
body();
showAdminFooter();

$listRS = null;

$viewRS = null;

$costRS = null;

$objTable = null;


function body() {
?>
	<div class="sub">
	<a href="addRequest.php">후원추가</a> | 
	<a href="index.php">특별후원</a> | 
	<a href="center.php">센터후원</a> | 
	<a href="service.php">자원봉사</a> |
	<a href="supportList.php">후원자리스트</a>
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

		<h3>후원 제목: <?php echo $viewRS["title"];?></h3>
		<ul>
			<li>선교사: <?php echo $viewRS["userId"];?></li>
			<li>총비용: <?php echo $costRS["costTotal"];?></li>
			<li>후원 마감일: <?php echo $viewRS["dueDate"];?></li>
		</ul>

		<input type="button" value="후원상세목록 추가" onclick="addRequestDetail()" style="cursor:hand;" />
		<?php echo $htmlTable;?>

		<table cellpadding=0 cellspacing=0 border=0 width=100%>
		<tr><td align="center" height="60"><?php echo $strPage;?></td></tr>
		</table>
	</div>

<?php } ?>

<script type="text/javascript">
//<![CDATA[	
	function clickButton(no, reqId) {
		switch(no) {
			case 0: goSupport(reqId); break;
			case 1: goEdit(reqId); break;
			case 2: goDelete(reqId); break;
			default: break;
		}
	}

	function goSupport(id) {
		location.href = 'supportList.php?reqId=<?php echo $reqId;?>';
		return;
	}

	function goEdit(id) {
		location.href = 'addRequestDetail.php?reqId=<?php echo $reqId;?>&id=' + id;
	}

	function goDelete(id) {
		if (confirm("정말 삭제 하십니까?")) {
			location.href = 'process.php?mode=deleteRequestDetail&id=' + id;
		}
	}
	
	function addRequestDetail() {
		location.href = 'addRequestDetail.php?reqId=<?php echo $reqId;?>';
	}
//]]>
</script>
