<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

global $mysqli;

checkAuth();

//페이징 갯수 
$PAGE_COUNT=15;
$PAGE_UNIT=10;

$supId = trim($_REQUEST["supId"]);
$order = trim($_REQUEST["order"]);
$page = trim($_REQUEST["page"]);
if ((strlen($page)==0)) {
	$page=1;
} 
if ((strlen($order)==0)) {
	$order="supId";
} 

$s_Helper = new SupportHelper();
$supporter = $s_Helper->getSupportBySupId($supId);
$account = $s_Helper->getAccountInfoByuserid($supporter->userid);

$query = "SELECT COUNT(*) AS recordCount from supportItem WHERE supId = ".$supId;
$strPage = makePaging($page, $PAGE_COUNT, $PAGE_UNIT, $query);
$topNum = $PAGE_COUNT*$page;

$query = "SELECT top ".$topNum." A.supItemId, B.title, A.cost FROM supportItem A, requestInfo B WHERE A.reqId = B.reqId AND A.supId = ".$supId." ORDER BY ".$order;

if ($result = $mysqli->query($query)) {
	$listRS = $result->fetch_assoc();
}

// 테이블 생성
$objTable = new tableBuilder();
$objTable->setButton(array("삭 제"));
$objTable->setColumn(array("개별코드","아이템","후원금액"));
$objTable->setField(array("supItemId","title","cost"));
$objTable->setOrder($order);
$objTable->setKeyValue(array("supItemId"));
$objTable->setGotoPage($page);
$htmlTable = $objTable->getTable($listRS);
$htmlPaging = $objTable->displayListPage();

showAdminHeader("관리툴 - 후원관리","","","");
body();
showAdminFooter();

$listRS = null;

$objTable = null;


function body() {
?>
	<div class="sub">
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
		<div class="subMenu">
		후원 관리 > 특별후원 > 상세내역 :: 
		<a href="index.php">특별후원</a> | 
		<a href="center.php">센터후원</a> | 
		<a href="service.php">자원봉사</a>
		</div>

		<h3>후원자 정보</h3>
		<ul>
			<li>후원자 : <?php echo $supporter->Name;?> (<?php echo $supporter->userid;?>)</li>
			<li>후원타입 : <?php echo $supporter->SupportType;?></li>
			<li>총 후원금액: <?php echo priceFormat($supporter->SumPrice, 1);?></li>
			<?	 $jumin = $supporter->Jumin;?>
			<li>주민등록번호: <?php echo $jumin[0];?>-<?php echo $jumin[1];?></li>
			<?	 $mobile = $supporter->Mobile;?>
			<li>핸드폰: <?php echo $mobile[0];?>-<?php echo $mobile[1];?>-<?php echo $mobile[2];?></li>
			<?	 $phone = $supporter->Phone;?>
			<li>전화번호: <?php echo $phone[0];?>-<?php echo $phone[1];?>-<?php echo $phone[2];?></li>
			<?	 $post = $supporter->Post;?>
			<li>주소: [<?php echo $post[0];?>-<?php echo $post[1];?>] <?php echo $supporter->Address1;?> <?php echo $supporter->Address2;?></li>
		</ul>
		
		<h3>계좌 정보</h3>
		<ul>
			<li>입금자: <?php echo $account->Name;?></li>
			<li>입금계좌: <?php echo $account->Bank;?> <?php echo $account->Number;?></li>
			<li>입금방법: <?php echo $account->Method;?></li>
			<li>입금예정일: <?php echo $account->SendDate;?> 일</li>
		</ul>

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
			case 0: goDelete(reqId); break;
			default: break;
		}
	}

	function goDelete(id) 
	{
		alert('준비안하고 있습니다');
	}
//]]>
</script>
