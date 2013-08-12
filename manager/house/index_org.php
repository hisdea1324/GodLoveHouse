<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

checkAuth();

//페이징 갯수 
$PAGE_COUNT=15;
$PAGE_UNIT=10;

$field = trim($_REQUEST["field"]);
$keyword = trim($_REQUEST["keyword"]);
$order = trim($_REQUEST["order"]);
$page = trim($_REQUEST["page"]);
$status = trim($_REQUEST["status"]);
if ((strlen($page)==0)) {
	$page=1;
} 
if ((strlen($field)==0)) {
	$field="houseName";
} 
if ((strlen($order)==0)) {
	$order="houseId";
} 
if ((strlen($status)==0)) {
	$status="S2002";
} 

// 조건문 작성
$strWhere=makeCondition($status,$field,$keyword);

$query = "SELECT COUNT(*) AS recordCount from house ".$strWhere;
$strPage = makePaging($page, $PAGE_COUNT, $PAGE_UNIT, $query);
$topNum = $PAGE_COUNT*$page;

$query = "SELECT top ".$topNum." * FROM house ".$strWhere." ORDER BY ".$order;
$db->CursorLocation=3;
$listRS = $db->Execute($query);
if (($listRS->RecordCount>0)) {
	$listRS->PageSize = $PAGE_COUNT;
	$listRS->AbsolutePage = $page;
} 


// 테이블 생성
$objTable = new tableBuilder();
if (($status=="S2002")) {
	$objTable->setButton(array("방정보","수 정","대기리스트로"));
} else {
	$objTable->setButton(array("방정보","수 정","삭 제","승인하기"));
} 

$objTable->setColumn(array("선교관코드","단체명","선교관이름","지역코드","관리자","연락처","방갯수"));
$objTable->setField(array("houseId","assocName","houseName","regionCode","manager1","contact1","roomCount"));
$objTable->setOrder($order);
$objTable->setKeyValue(array("houseId"));
$objTable->setGotoPage($page);
$htmlTable = $objTable->getTable($listRS);

showAdminHeader("관리툴 - 선교관관리","","","");
//call showAdminMenu()
body();
showAdminFooter();

$listRS = null;

$objTable = null;


function makeCondition($status,$field,$keyword) {
	$strWhere=" WHERE status = '".$status."'";
	if ((strlen($field)>0 && strlen($keyword)>0)) {
		$strWhere = $strWhere." AND ".$field." LIKE '%".$keyword."%'";
	} 

	return $strWhere;
} 

function body() {
?>

	<div class="main">
		<div id="subMenu">
		선교관 관리 > 선교관 :: <a href="index.php">선교관</a> | <a href="index.php?status=S2001">대기리스트</a>
		</div>
		<table cellpadding=0 cellspacing=0 border=0 width=100%>
			<form name="findForm" method="get" action="<?php echo $CurUrl;?>">
			<tr>
				<td><input type="button" value="선교관 추가" onclick="addHouse()" style="cursor:hand;" /></td>
				<td align="right">
					<select name="field">
						<option value="assocName" <?php if (($field=="assocName")) {
?>selected<?php } ?>>단체명</option>
						<option value="houseName" <?php if (($field=="houseName")) {
?>selected<?php } ?>>선교관이름</option>
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
	var searchString = '&keyword=<?php echo $keyword;?><%&field=<?php echo $field;?><%';
	
	function clickButton(no, houseId) {
		switch(no) {
<?php 
if (($status=="S2002")) {
?>
			case 0: goShow(houseId); break;
			case 1: goEdit(houseId); break;
			case 2: goConfirm(houseId, 'S2001'); break;
<?php 
} else {
?>
			case 0: goShow(houseId); break;
			case 1: goEdit(houseId); break;
			case 2: goDelete(houseId); break;
			case 3: goConfirm(houseId, 'S2002'); break;
<?php 
} 

?><%
			default: break;
		}
	}

	function goShow(houseId) 
	{
		location.href = 'roomList.php?houseId=' + houseId + searchString;
	}

	function goEdit(houseId) 
	{
		location.href = 'editHouse.php?mode=editHouse&houseId=' + houseId + searchString;
	}

	function goDelete(houseId) 
	{
		if (confirm("정말 삭제 하시겠습니까?")) {
			location.href = 'process.php?mode=deleteHouse&houseId=' + houseId + searchString;
		}
	}
	
	function goConfirm(houseId, value) 
	{
		if (confirm("정말 승인 하시겠습니까?")) {
			location.href = 'process.php?mode=confirmHouse&value=' + value + '&houseId=' + houseId + searchString;
		}
	}
	
	function addHouse() 
	{
		location.href = 'editHouse.php?mode=addHouse' + searchString;
	}
//]]>
</script>
