<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

checkAuth();

//페이징 갯수 
$PAGE_COUNT = 15;
$PAGE_UNIT = 10;

$field = (isset($_REQUEST["field"])) ? trim($_REQUEST["field"]) : "houseName";
$keyword = (isset($_REQUEST["keyword"])) ? trim($_REQUEST["keyword"]) : "";
$order = (isset($_REQUEST["order"])) ? trim($_REQUEST["order"]) : "regDate DESC";
$page = (isset($_REQUEST["page"])) ? trim($_REQUEST["page"]) : 1;
$status = (isset($_REQUEST["status"])) ? trim($_REQUEST["status"]) : "S2002";

// 조건문 작성
$strWhere = makeCondition($status, $field, $keyword);

$query = "SELECT * FROM house ".$strWhere;
$strPage = makePaging($page, $PAGE_COUNT, $PAGE_UNIT, $query);
$topNum = $PAGE_COUNT * ($page - 1);

$query = "SELECT * FROM house $strWhere ORDER BY $order LIMIT $topNum, $PAGE_COUNT";
// 테이블 생성
$objTable = new tableBuilder();
if ($status == "S2002") {
	$objTable->setButton(array("방정보","수정","대기"));
} else {
	$objTable->setButton(array("방정보","수정","삭제","승인"));
} 

$objTable->setColumn(array("선교관코드","단체명","선교관이름","관리자","연락처","방갯수"));
$objTable->setField(array("houseId","assocName","houseName","userId","contact1","roomCount"));
$objTable->setOrder($order);
$objTable->setKeyValue(array("houseId"));
$objTable->setGotoPage($page);
$htmlTable = $objTable->getTable($query);

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
	global $keyword, $field;
	global $htmlTable, $strPage;
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
			<form name="findForm" method="get" action="./index.php">
			<tr>
				<td align="right">
					<select name="field">
						<option value="userId" <?php if (($field=="userId")) {
?>selected<?php } ?>>관리자</option>
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
	
	</div>

<?php } ?>

<script type="text/javascript">
//<![CDATA[
	var searchString = '&keyword=<?php echo $keyword;?>&field=<?php echo $field;?>';
	
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

?>
			default: break;
		}
	}

	function goShow(houseId) {
		location.href = 'roomList.php?houseId=' + houseId + searchString;
	}

	function goEdit(houseId) {
		location.href = 'editHouse.php?mode=editHouse&houseId=' + houseId + searchString;
	}

	function goDelete(houseId) {
		if (confirm("정말 삭제 하시겠습니까?")) {
			location.href = 'process.php?mode=deleteHouse&houseId=' + houseId + searchString;
		}
	}
	
	function goConfirm(houseId, value) {
		if (confirm("정말 승인 하시겠습니까?")) {
			location.href = 'process.php?mode=confirmHouse&value=' + value + '&houseId=' + houseId + searchString;
		}
	}
//]]>
</script>	

