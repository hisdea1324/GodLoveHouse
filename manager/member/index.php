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
	$order="registDate DESC";
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
		$objTable->setButton(array("선교사로","관리자로","정보변경","삭제"));
		break;
	case "3":
		$path="선교사";
		$objTable->setButton(array("일반회원으로","정보변경","삭제"));
		break;
	case "7":
		$path="선교관관리자";
		$objTable->setButton(array("일반회원으로","정보변경","삭제"));
		break;
	default:
		$path="전체리스트";
		$objTable->setButton(array("정보변경","삭제"));
		break;
} 
$objTable->setColumn(array("회원등급","이름","회원아이디","연락처","등록일"));
$objTable->setField(array("userLv","name","userid","mobile","registDate"));
$objTable->setOrder($order);
$objTable->setKeyValue(array("userId"));
$objTable->setGotoPage($page);
$htmlTable = $objTable->getTable($listRS);
$htmlPaging = $objTable->displayListPage();

//download Csv File
//test = makeExcelFile("test.csv", listRS)

showAdminHeader("관리툴 - 회원관리","","","");
//call showAdminMenu()
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
	<div class="sub">
	<a href="editForm.php">회원등록</a> | 
	<a href="index.php?userLv=0">전체목록</a> | 
	<a href="index.php?userLv=1">일반회원</a> |
	<a href="index.php?userLv=3">선교사</a> | 
	<a href="index.php?userLv=7">선교관관리자</a>
	</div>
	</div>
	<div id="wrap">
		<div class="lSec">
		<ul>
			<li><img src="/images/manager/lm_0100.gif"></li>
		<li><a href="editForm.php"><img src="/images/manager/lm_0101.gif"></a></li>
		<li><a href="index.php?userLv=0"><img src="/images/manager/lm_0102.gif"></a></li>
		<li><a href="index.php?userLv=1"><img src="/images/manager/lm_0103.gif"></a></li>
		<li><a href="index.php?userLv=3"><img src="/images/manager/lm_0104.gif"></a></li>
		<li><a href="index.php?userLv=7"><img src="/images/manager/lm_0105.gif"></a></li>
		<li><img src="/images/manager/lm_bot.gif"></li>
		</ul>
	</div>
	<div class="rSec">
	<!-- 컨텐츠 들어가는 부분 -->

		<table cellpadding=0 cellspacing=0 border=0 width=100%>
			<form name="findForm" method="get" action="<?php echo $CurUrl;?>">
			<!--tr><td align="right"><a href="test.csv">[download csv excel:test.csv]</a></td></tr-->
			<tr>
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
	</div>

<?php 
} 
?>

<script type="text/javascript">
//<![CDATA[
	var searchString = '&keyword=<?php echo $keyword;?><%&field=<?php echo $field;?><%';
	
	function clickButton(no, userId) {
		switch(no) {
<?php switch (($userLv)) {
	case "1":
?>
			case 0: changeGrade(1, userId); break;
			case 1: changeGrade(2, userId); break;
			case 2: goShow(userId); break;
			case 3: goDelete(userId); break;
			default: break;
<?		 break;
	case "3":
?>
			case 0: changeGrade(0, userId); break;
			case 1: goShow(userId); break;
			case 2: goDelete(userId); break;
			default: break;
<?		 break;
	case "7":
?>
			case 0: changeGrade(0, userId); break;
			case 1: goShow(userId); break;
			case 2: goDelete(userId); break;
			default: break;
<?		 break;
	default:

?>
			case 0: goShow(userId); break;
			case 1: goDelete(userId); break;
			default: break;
<?		 break;
} ?><%
		}
	}

	function goShow(userId) {
		location.href = 'editForm.php?userLv=<?php echo $userLv;?><%&userId=' + userId;
	}

	function goDelete(userId) {
		if (confirm("정말 삭제 하시겠습니까?")) {
			location.href = 'process.php?mode=deleteUser&userLv=<?php echo $userLv;?><%&userId=' + userId;
		}
	}
	
	function changeGrade(lv, userId) {
		switch (lv) {
			case 0:
				if (confirm("'일반회원'으로 회원의 등급을 변경합니다.")) {
					location.href = 'process.php?mode=upCommon&userId=' + userId;
				}
				break;
			case 1:
				if (confirm("'선교사'로 회원의 등급을 변경합니다.")) {
					location.href = 'process.php?mode=upMission&userId=' + userId;
				}
				break;
			default :
				if (confirm("'선교관 관리자'로 회원의 등급을 변경합니다.")) {
					location.href = 'process.php?mode=upHouse&userId=' + userId;
				}
				break;
		}
	}
//]]>
</script>
