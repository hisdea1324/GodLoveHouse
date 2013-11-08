<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

checkAuth();

//페이징 갯수 
$PAGE_COUNT=15;
$PAGE_UNIT=10;

$field = trim($_REQUEST["field"]);
$keyword = trim($_REQUEST["keyword"]);
$userLv = trim($_REQUEST["userLv"]);
$order = trim($_REQUEST["order"]);
$page = trim($_REQUEST["page"]);
if ((strlen($page)==0)) {
	$page=1;
} 
if ((strlen($userLv)==0)) {
	$userLv=0;
} 
if ((strlen($order)==0)) {
	$order="name ASC";
} 

// 조건문 작성
$strWhere=makeCondition($userLv,$field,$keyword);
$query = "SELECT COUNT(*) AS recordCount from users".$strWhere;

// 페이징을 모두 만들어옴
$strPage = makePaging($page, $PAGE_COUNT, $PAGE_UNIT, $query);

$topNum = $PAGE_COUNT*$page;

$query = "select top ".$topNum." * from users ".$strWhere." ORDER BY ".$order;
$db->CursorLocation=3;
$listRS = $db->Execute($query);
if (($listRS->RecordCount>0)) {
	$listRS->PageSize = $PAGE_COUNT;
	$listRS->AbsolutePage = $page;
} 


$listRS = $db->Execute($query);

// 테이블 생성
$objTable = new tableBuilder();
switch (($userLv)) {
	case "1":
		$path="일반회원";
		$objTable->setButton(array("선교사로","관리자로","정보변경","삭 제"));
		break;
	case "3":
		$path="선교사";
		$objTable->setButton(array("일반회원으로","정보변경","삭 제"));
		break;
	case "7":
		$path="선교관관리자";
		$objTable->setButton(array("일반회원으로","정보변경","삭 제"));
		break;
	default:
		$path="전체리스트";
		break;
} 
$objTable->setColumn(array("회원등급","이름","회원아이디","연락처","등록일"));
$objTable->setField(array("userLv","name","userid","mobile","registDate"));
$objTable->setOrder($order);
$objTable->setKeyValue(array("userid"));
$objTable->setGotoPage($page);
$htmlTable = $objTable->getTable($listRS);
$htmlPaging = $objTable->displayListPage();

showAdminHeader("관리툴 - 회원관리","","");
showAdminMenu();
body();
showAdminFooter();

$listRS = null;

$objTable = null;


function makeCondition($userLv,$field,$keyword) {
	if (($userLv>0)) {
		$strWhere=" WHERE userLv = '".$userLv."'";
	} else {
		$strWhere=" WHERE userLv between 0 and 8 ";
	} 

	if ((strlen($field)>0 && strlen($keyword)>0)) {
		$strWhere = $strWhere." AND ".$field." LIKE '%".$keyword."%'";
	} 

	return $strWhere;
} 

function body() {
?>

	<div class="main">
		<div id="subMenu">
		회원 관리 > 회원리스트 > <?php echo $path;?> :: 
		<a href="index.php?userLv=0">전체목록</a> | 
		<a href="index.php?userLv=1">일반회원</a> |
		<a href="index.php?userLv=3">선교사</a> | 
		<a href="index.php?userLv=7">선교관관리자</a>
		</div>

		<table cellpadding=0 cellspacing=0 border=0 width=100%>
			<form name="findForm" method="get" action="<?php echo $CurUrl;?>">
			<tr>
				<td><input type="button" value="회원등록" onclick="location.href='editForm.php?mode=add'" style="cursor:hand"></td>
				<td align="right">
					<select name="field">
						<option value="name" <?php if (($field=="name")) {
?>selected<?php } ?>>이름</option>
						<option value="userid" <?php if (($field=="userid")) {
?>selected<?php } ?>>아이디</option>
						<option value="mobile" <?php if (($field=="mobile")) {
?>selected<?php } ?>>핸드폰</option>
						<option value="phone" <?php if (($field=="phone")) {
?>selected<?php } ?>>전화번호</option>
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

<?php 
} 
?>

<script type="text/javascript">
//<![CDATA[
	var searchString = '&keyword=<?php echo $keyword;?>&field=<?php echo $field;?>';
	
	function clickButton(no, userid) {
		switch(no) {
<?php switch (($userLv)) {
	case "1":
?>
			case 0: changeGrade(1, userid); break;
			case 1: changeGrade(2, userid); break;
			case 2: goShow(userid); break;
			case 3: goDelete(userid); break;
			default: break;
<?		 break;
	case "3":
?>
			case 0: changeGrade(0, userid); break;
			case 1: goShow(userid); break;
			case 2: goDelete(userid); break;
			default: break;
<?		 break;
	case "7":
?>
			case 0: changeGrade(0, userid); break;
			case 1: goShow(userid); break;
			case 2: goDelete(userid); break;
			default: break;
<?		 break;
	default:

?>
			case 0: goEdit(userid); break;
			case 1: goShow(userid); break;
			case 2: goDelete(userid); break;
			default: break;
<?		 break;
} ?>
		}
	}

	function goEdit(userid) 
	{
		alert("준비중입니다");
	}

	function goShow(userid) 
	{
		location.href = 'editForm.php?mode=editUser&userid=' + userid + searchString;
	}

	function goDelete(userid) 
	{
		if (confirm("정말 삭제 하시겠습니까?")) {
			location.href = 'process.php?mode=deleteUser&userid=' + userid;
		}
	}
	
	function changeGrade(lv, userid) {
		switch (lv) {
			case 0:
				if (confirm("'일반회원'으로 회원의 등급을 변경합니다.")) {
					location.href = 'process.php?mode=upCommon&userid=' + userid;
				}
				break;
			case 1:
				if (confirm("'선교사'로 회원의 등급을 변경합니다.")) {
					location.href = 'process.php?mode=upMission&userid=' + userid;
				}
				break;
			default :
				if (confirm("'선교관 관리자'로 회원의 등급을 변경합니다.")) {
					location.href = 'process.php?mode=upHouse&userid=' + userid;
				}
				break;
		}
	}
//]]>
</script>
