<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

checkAuth();

//페이징 갯수 
$PAGE_COUNT=15;
$PAGE_UNIT=10;

$field = (isset($_REQUEST["field"])) ? trim($_REQUEST["field"]) : "reservationNo";
$keyword = (isset($_REQUEST["keyword"])) ? trim($_REQUEST["keyword"]) : "";
$order = (isset($_REQUEST["order"])) ? trim($_REQUEST["order"]) : "reservationNo DESC";
$page = (isset($_REQUEST["page"])) ? trim($_REQUEST["page"]) : 1;
$status = (isset($_REQUEST["status"])) ? trim($_REQUEST["status"]) : "S0001";

// 조건문 작성
$strWhere = makeCondition($status, $field, $keyword);

// 페이지 네비게이션 
$query = "SELECT * FROM reservation, users ".$strWhere;
$strPage = makePaging($page, $PAGE_COUNT, $PAGE_UNIT, $query);

// 현재 페이지에 보여줄 레코드 
$topNum = $PAGE_COUNT * ($page - 1);
$query = "SELECT *, users.name as name FROM reservation, users $strWhere ORDER BY $order LIMIT $topNum, $PAGE_COUNT";

// 테이블 생성
$objTable = new tableBuilder();
if ($status == "S0001") {
	$path = " 신규예약신청리스트 ";
	$objTable->setButton(array("방정보","회원정보","수정","승인","승인불가", "프린트하기"));
} else if ($status == "S0002") {
	$path = " 승인리스트 ";
	$objTable->setButton(array("방정보","회원정보","수정","완료", "프린트하기"));
} else if ($status == "S0003") {
	$path=" 이전승인리스트";
	$objTable->setButton(array("방정보","회원정보","수정","삭제", "프린트하기"));
} else {
	$path = " 승인거절리스트";
	$objTable->setButton(array("방정보","회원정보","수정","삭제", "프린트하기"));
} 
$objTable->setColumn(array("예약번호","회원아이디", "예약자이름", "방번호","상태","숙박날짜","숙박날짜"));
$objTable->setOrder($order);
$objTable->setField(array("reservationNo","userid", "name", "roomId", "reservStatus","startDate","endDate"));
$objTable->setKeyValue(array("reservationNo","roomId","userid"));
$objTable->setGotoPage($page);
$htmlTable = $objTable->getTable($query);

showAdminHeader("관리툴 - 예약관리","","","");
//call showAdminMenu()
body();
showAdminFooter();

$objTable = null;

$listRs = null;


function makeCondition($status,$field,$keyword) {
	$strWhere = " WHERE reservStatus = '".$status."' AND reservation.userid = users.userid ";
	if (strlen($field) > 0 && strlen($keyword) > 0) {
		$strWhere = $strWhere." AND ".$field." LIKE '%".$keyword."%'";
	} 

	if ($strWhere == " WHERE") {
		return "";
	} else {
		return str_replace(" WHERE AND", " WHERE", $strWhere);
	} 

} 

function body() {
	global $path, $field, $keyword;
	global $CurUrl, $strPage, $htmlTable;
?>
	<div class="sub">
	<a href="index.php?status=S0001">신규예약</a> | 
	<a href="index.php?status=S0002">승인</a> | 
	<a href="index.php?status=S0003">완료</a> |
	<a href="index.php?status=S0004">승인불가</a>
	</div>
	</div>
	<div id="wrap">
		<div class="lSec">
		<ul>
			<li><img src="/images/manager/lm_0300.gif"></li>
		<li><a href="index.php?status=S0001"><img src="/images/manager/lm_0301.gif"></a></li>
		<li><a href="index.php?status=S0002"><img src="/images/manager/lm_0302.gif"></a></li>
		<li><a href="index.php?status=S0003"><img src="/images/manager/lm_0303.gif"></a></li>
		<li><a href="index.php?status=S0004"><img src="/images/manager/lm_0304.gif"></a></li>
		<li><img src="/images/manager/lm_bot.gif"></li>
		</ul>
	</div>
		
	<div class="rSec">
	<!-- 컨텐츠 들어가는 부분 -->
			
		<a href="/manager/csv_download.php?action=download&table=reservation" target="_blank">[엑셀 다운 로드]</a>
		
		<table cellpadding=0 cellspacing=0 border=0 width=100%>
			<form name="findForm" method="get" action="<?php echo $CurUrl;?>">
			<tr>
				<td><b><?php echo $path;?></b></td>
				<td align="right">
					<select name="field">
						<option value="name" <?php if ($field == "name") {
?>selected<?php } ?>>이름</option>
						<option value="userid" <?php if ($field == "userid") {
?>selected<?php } ?>>아이디</option>
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
	
	function clickButton(no, reservId, roomId, userid) {
		switch(no) {
<?php if (($status=="S0001")) { ?>
			case 0: goShowRoom(roomId); break;
			case 1: goShowUser(userid); break;
			case 2: goEdit(reservId); break;
			case 3: goChange(reservId, 'S0002'); break;
			case 4: goChange(reservId, 'S0004'); break;
			case 5: goPrint(userid, reservId); break;
<?php } else if(($status=="S0002")) { ?>
			case 0: goShowRoom(roomId); break;
			case 1: goShowUser(userid); break;
			case 2: goEdit(reservId); break;
			case 3: goChange(reservId, 'S0003'); break;
			case 4: goPrint(userid, reservId); break;
<?php } else if(($status=="S0003")) { ?>
			case 0: goShowRoom(roomId); break;
			case 1: goShowUser(userid); break;
			case 2: goEdit(reservId); break;
			case 3: goDelete(reservId); break;
			case 4: goPrint(userid, reservId); break;
<?php } else { ?>
			case 0: goShowRoom(roomId); break;
			case 1: goShowUser(userid); break;
			case 2: goEdit(reservId); break;
			case 3: goDelete(reservId); break;
			case 4: goPrint(userid, reservId); break;
<?php } ?>
			default: break;
		}
	}

	function goPrint(userid, reservId) {
		window.open('/house_manager/print.php?userid=' + userid + '&reservationNo=' + reservId, '');
	}
	
	function goShowRoom(roomId) 
	{
		window.open('/living/reservationDetail.php?roomId=' + roomId, 'new', '');
	}
	
	function goShowUser(userid) 
	{
		location.href = '/' + 'manager/member/editForm.php?mode=editUser&userid=' + userid;
	}

	function goEdit(reservId) 
	{
		location.href = 'editDetail.php?mode=editReserv&reservId=' + reservId + searchString;
	}

	function goDelete(reservId) 
	{
		if (confirm("정말 삭제 하십니까?")) {
			location.href = 'process.php?mode=deleteReserv&reservId=' + reservId + searchString;
		}
	}
	
	function goChange(reservId, value) 
	{
		if (confirm("상태변경 하시겠습니까?")) {
			location.href = 'process.php?mode=changeStatus&value=' + value + '&reservId=' + reservId + searchString;
		}
	}
//]]>
</script>
