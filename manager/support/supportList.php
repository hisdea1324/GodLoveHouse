<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

checkAuth();

//페이징 갯수 
$PAGE_COUNT=15;
$PAGE_UNIT=10;

$wait = trim($_REQUEST["wait"]);
$field = trim($_REQUEST["field"]);
$keyword = trim($_REQUEST["keyword"]);
$reqId = trim($_REQUEST["reqId"]);
$supId = trim($_REQUEST["supId"]);
$order = trim($_REQUEST["order"]);
$page = trim($_REQUEST["page"]);
if ((strlen($page)==0)) {
	$page=1;
} 
if ((strlen($order)==0)) {
	$order="reqId";
} 

$strWhere=makeCondition($supId,$reqId,$field,$keyword,$wait);

$query = "SELECT COUNT(*) AS recordCount FROM supportInfo ".$strWhere;
$strPage = makePaging($page, $PAGE_COUNT, $PAGE_UNIT, $query);
$topNum = $PAGE_COUNT*$page;

$query = "SELECT top ".$topNum." supId, userId, name, regDate, sumPrice FROM supportInfo ".$strWhere;
$db->CursorLocation=3;
$listRS = $db->Execute($query);
if (($listRS->RecordCount>0)) {
	$listRS->PageSize = $PAGE_COUNT;
	$listRS->AbsolutePage = $page;
} 


// 테이블 생성
$objTable = new tableBuilder();
if (($wait=="1")) {
	$objTable->setButton(array("상세보기","회원정보","승 인"));
} else {
	$objTable->setButton(array("상세보기","회원정보","비승인"));
} 

$objTable->setColumn(array("후원코드","후원자ID","입금자명","금액","등록일"));
$objTable->setField(array("supId","userId","name","sumPrice","regDate"));
$objTable->setOrder($order);
$objTable->setKeyValue(array("supId","userId"));
$objTable->setGotoPage($page);
$htmlTable = $objTable->getTable($listRS);
$htmlPaging = $objTable->displayListPage();

showAdminHeader("관리툴 - 후원관리","","","");
body();
showAdminFooter();

$listRS = null;

$objTable = null;


function makeCondition($supId,$reqId,$field,$keyword,$wait) {
	$strWhere=" WHERE";
	if ((strlen($reqId)>0)) {
		$strWhere = $strWhere." AND supId IN (SELECT supId FROM supportItem WHERE reqId = '".$reqId."')";
	} 

	if ((strlen($supId)>0)) {
		$strWhere = $strWhere." AND supId = '".$supId."'";
	} 

	if (($wait=="1")) {
		$strWhere = $strWhere." AND status = 'S2001'";
	} else {
		$strWhere = $strWhere." AND status = 'S2002'";
	} 

	if ((strlen($field)>0 && strlen($keyword)>0)) {
		$strWhere = $strWhere." AND ".$field." LIKE '%".$keyword."%'";
	} 


	if (($strWhere==" WHERE")) {
		$strWhere="";
	} else {
		$strWhere=str_replace(" WHERE AND"," WHERE",$strWhere);
	} 


	return $strWhere;
} 

function body() {
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

		<table cellpadding=0 cellspacing=0 border=0 width=100%>
			<form name="findForm" method="get" action="<?php echo $CurUrl;?>">
			<tr>
				<td align="right">
					<select name="field">
						<option value="A.name" <?php if (($field=="A.name")) {
?>selected<?php } ?>>입금자명</option>
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

<?php } ?>

<script type="text/javascript">
//<![CDATA[	
	function clickButton(no, supId, userId) {
		switch(no) {
			case 0: goShow(supId); break;
			case 1: goShowUser(userId); break;
			case 2: goChange(supId); break;
			default: break;
		}
	}

	function goShow(supId) {
		location.href = 'supportDetailList.php?supId=' + supId;
	}
	
	function goShowUser(userId) {
		location.href = '<?php echo "http://".$_SERVER['SERVER_NAME'];?>/<%manager/member/editForm.php?mode=editUser&userId=' + userId;
	}

	function goChange(supId) {
		var str;
		str = "<?php if (($wait=="0")) { ?>비<?php } ?><%승인합니다.";
		
		if (confirm(str)) {
			location.href = 'process.php?mode=statusChange&supId=' + supId + '&wait=<?php echo $wait;?><%';
		}
	}
	
	function addSupportDetail() {
		location.href = 'addRequestDetail.php?mode=addSupportDetail&supId=<?php echo $supId;?><%';
	}
//]]>
</script>
