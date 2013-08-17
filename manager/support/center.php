<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

checkAuth();

//페이징 갯수 
$PAGE_COUNT=15;
$PAGE_UNIT=10;

$supportType="03002";
$goToPage = (isset($_REQUEST["gotoPage"])) ? trim($_REQUEST["gotoPage"]) : "";




$query = "SELECT * FROM requestInfo WHERE supportType = '".$supportType."' ORDER BY regDate DESC";
// 테이블 생성
$objTable = new tableBuilder();
$objTable->setButton(array("후원자","수 정","삭 제"));
$objTable->setColumn(array("후원코드","제목","등록일"));
$objTable->setField(array("reqId","title","regDate"));
$objTable->setKeyValue(array("reqId"));
$objTable->setGotoPage($goToPage);
$htmlTable = $objTable->getTable($query);
//$htmlPaging = $objTable->displayListPage();


showAdminHeader("관리툴 - 후원관리","","","");
body();
showAdminFooter();

$supportRS = null;


function body() {
	global $htmlTable;
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
		<?php echo $htmlTable;?>
	</div>

<?php } ?>

<script type="text/javascript">
//<![CDATA[
	var searchString = '&keyword=<?php echo $keyword;?>&field=<?php echo $field;?>';
	
	function clickButton(no, reqId)	{
		switch(no) {
			case 0: goSupporter(reqId); break;
			case 1: goEdit(reqId); break;
			case 2: goDelete(reqId); break;
			default: break;
		}
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
