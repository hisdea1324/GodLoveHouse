<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

checkAuth();

//페이징 갯수 
$PAGE_COUNT=15;
$PAGE_UNIT=10;

$field = trim($_REQUEST["field"]);
$keyword = trim($_REQUEST["keyword"]);
$page = trim($_REQUEST["page"]);
$houseId = trim($_REQUEST["houseId"]);
if ((strlen($page)==0)) {
	$page=1;
} 

// 조건문 작성
$strWhere=makeCondition($houseId,$field,$keyword);

$query = "SELECT COUNT(*) AS recordCount from room ".$strWhere;
$strPage = makePaging($page, $PAGE_COUNT, $PAGE_UNIT, $query);
$topNum = $PAGE_COUNT*$page;

$query = "SELECT top ".$topNum." * FROM room ".$strWhere." ORDER BY roomName";
$db->CursorLocation=3;
$listRS = $db->Execute($query);
if (($listRS->RecordCount>0)) {
	$listRS->PageSize = $PAGE_COUNT;
	$listRS->AbsolutePage = $page;
} 


$query = "SELECT houseName FROM house WHERE houseId = ".$houseId;
$houseRS = $db->Execute($query);

// 테이블 생성
$objTable = new tableBuilder();
$objTable->setButton(array("수 정","삭 제","예약하기"));
$objTable->setColumn(array("방코드","방이름","인원","인터넷","주방","세탁","요금"));
$objTable->setField(array("roomId","roomName","limit","network","kitchen","laundary","fee"));
$objTable->setKeyValue(array("roomId"));
$objTable->setGotoPage($page);
$htmlTable = $objTable->getTable($listRs);
$htmlPaging = $objTable->displayListPage();

showAdminHeader("관리툴 - 방관리","","","");
body();
showAdminFooter();

$objTable = null;

$houseRS = null;

$listRs = null;


function makeCondition($houseId,$field,$keyword) {
	$strWhere=" WHERE houseId = '".$houseId."'";
	if ((strlen($field)>0 && strlen($keyword)>0)) {
		$strWhere = $strWhere." AND ".$field." LIKE '%".$keyword."%'";
	} 

	return $strWhere;
} 

function body() {
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
		선교관 관리 > 선교관 > 방관리 <br>

		<table cellpadding=0 cellspacing=0 border=0 width=100%>
			<tr>
				<td><input type="button" value="방 추가" onclick="addRoom()" style="cursor:hand;" /></td>
				<td align="right"><h3>선교관 관리 : <?php echo $houseRS["houseName"];?></h3></td>
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
	
	function clickButton(no, roomId) {
		switch(no) {
			case 0: goEdit(roomId); break;
			case 1: goDelete(roomId); break;
			case 2: goReservation(roomId); break;
			default: break;
		}
	}

	function goEdit(roomId) {
		location.href = 'editRoom.php?mode=editRoom&houseId=<?php echo $houseId;?><%&roomId=' + roomId + searchString;
	}

	function goDelete(roomId) {
		if (confirm("정말 삭제 하시렵니까?")) {
			location.href = 'process.php?mode=deleteRoom&houseId=<?php echo $houseId;?><%&roomId=' + roomId + searchString;
		}
	}

	function goReservation(roomId) {
		alert('준비중입니다.');
		return;
		location.href = 'editRoom.php?mode=editRoom&roomId=' + roomId + searchString;
	}
	
	function addRoom() {
		location.href = 'editRoom.php?mode=addRoom&houseId=<?php echo $houseId;?><%&' + searchString;
	}
//]]>
</script>
