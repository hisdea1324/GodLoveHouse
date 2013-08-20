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
	$field="hospitalName";
} 
if ((strlen($order)==0)) {
	$order="regDate DESC";
} 
if ((strlen($status)==0)) {
	$status="S2002";
} 

// 조건문 작성
$strWhere=makeCondition($status,$field,$keyword);

$query = "SELECT COUNT(*) AS recordCount FROM hospital ".$strWhere;
$strPage = makePaging($page, $PAGE_COUNT, $PAGE_UNIT, $query);
$topNum = $PAGE_COUNT*$page;

$query = "SELECT top ".$topNum." * FROM hospital ".$strWhere." ORDER BY ".$order;
$db->CursorLocation=3;
$listRS = $db->Execute($query);
if (($listRS->RecordCount>0)) {
	$listRS->PageSize = $PAGE_COUNT;
	$listRS->AbsolutePage = $page;
} 


// 테이블 생성
$objTable = new tableBuilder();
if (($status=="S2002")) {
	$objTable->setButton(array("수정","대기"));
} else {
	$objTable->setButton(array("수정","삭제","승인"));
} 

$objTable->setColumn(array("병원코드","병원이름","관리자","연락처","일일진료인원"));
$objTable->setField(array("hospitalId","hospitalName","userId","contact1","personLimit"));
$objTable->setOrder($order);
$objTable->setKeyValue(array("hospitalId"));
$objTable->setGotoPage($page);
$htmlTable = $objTable->getTable($listRS);

showAdminHeader("관리툴 - 선교관관리","","","");
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
	global $CurUrl, $strPage, $htmlTable;
	global $field, $keyword;
?>
	<div class="sub">
	<a href="editHouse.php?mode=addHouse&keyword=<?php echo $keyword;?>&field=<?php echo $field;?>">선교관추가</a> | 
	<a href="index.php">등록된 선교관</a> | 
	<a href="index.php?status=S2001">대기중 선교관</a> | 
	<a href="editHospital.php??mode=addHospital&keyword=<?php echo $keyword;?>&field=<?php echo $field;?>">병원 추가</a> | 
	<a href="hospital.php">등록된 병원</a> | 
	<a href="hospital.php?status=S2001">대기중 병원</a>
	</div>
	</div>
	<div id="wrap">
		<div class="lSec">
		<ul>
			<li><img src="/images/manager/lm_0200.gif"></li>
		<li><a href="editHouse.php?mode=addHouse&keyword=<?php echo $keyword;?>&field=<?php echo $field;?>"><img src="/images/manager/lm_0201.gif"></a></li>
		<li><a href="index.php"><img src="/images/manager/lm_0202.gif"></a></li>
		<li><a href="index.php?status=S2001"><img src="/images/manager/lm_0203.gif"></a></li>
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
						<option value="userId" <?php if (($field=="userId")) {
?>selected<?php } ?>>관리자</option>
						<option value="hospitalName" <?php if (($field=="hospitalName")) {
?>selected<?php } ?>>병원이름</option>
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
	
	function clickButton(no, hospitalId) {
		switch(no) {
<?php 
if (($status=="S2002")) {
?>
			case 0: goEdit(hospitalId); break;
			case 1: goConfirm(hospitalId, 'S2001'); break;
<?php 
} else {
?>
			case 0: goEdit(hospitalId); break;
			case 1: goDelete(hospitalId); break;
			case 2: goConfirm(hospitalId, 'S2002'); break;
<?php 
} 

?>
			default: break;
		}
	}

	function goEdit(hospitalId) {
		location.href = 'editHospital.php?mode=editHospital&hospitalId=' + hospitalId + searchString;
	}

	function goDelete(hospitalId) {
		if (confirm("정말 삭제 하시겠습니까?")) {
			location.href = 'process.php?mode=deleteHospital&hospitalId=' + hospitalId + searchString;
		}
	}
	
	function goConfirm(hospitalId, value) {
		if (confirm("정말 승인 하시겠습니까?")) {
			location.href = 'process.php?mode=confirmHospital&value=' + value + '&hospitalId=' + hospitalId + searchString;
		}
	}
//]]>
</script>	

