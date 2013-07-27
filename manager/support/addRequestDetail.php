<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

$reqId = trim($_REQUEST["reqId"]);
$id = trim($_REQUEST["id"]);

$c_Helper = new CodeHelper();
$s_Helper = new SupportHelper();
$requestItemObj = $s_Helper->getRequestItemById($id);
$statusCodes = $c_Helper->getStatusCodeList();

checkAuth();
showAdminHeader("관리툴 - 후원 등록","","","");
body();
showAdminFooter();

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
		<dl>
		<form name="dataForm" id="dataForm" method="post">
		<input type="hidden" name="mode" value="editRequestDetail" />
		<input type="hidden" name="reqId" value="<?php echo $reqId;?>" />
		<input type="hidden" name="id" value="<?php echo $requestItemObj->RequestItemID;?>" />
			<dt>
				아이템
			<dd>
				<input type="text" name="item" size="20" maxlength=40 value="<?php echo $requestItemObj->RequestItem;?>" />
			<dt>
				설명
			<dd>
				<input type="text" name="descript" size="50" maxlength=256 value="<?php echo $requestItemObj->Descript;?>" />&nbsp;&nbsp;
			<dt>
				비용
			<dd>
				<input type="text" name="cost" size="20" maxlength=30 value="<?php echo $requestItemObj->Cost;?>" /> 원
			<dt>
				상태코드
			<dd>
				<select name="status" id="status" tabindex="32">
				<?php 
	for ($i=0; $i<=count($statusCodes)-1; $i = $i+1) {
		$codeObj = $statusCodes[$i];
		if (($codeObj->Code == $requestItemObj->SendStatus)) {
			print "<option value=\"".$codeObj->Code."\" selected>".$codeObj->Name."</option>";
		} else {
			print "<option value=\"".$codeObj->Code."\">".$codeObj->Name."</option>";
		} 


	}

?>
				</select>
			<dt>&nbsp;
			<dd>
				<img src="/images/board/btn_ok.gif" border="0" onclick="check();" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;
				<img src="/images/board/btn_cancel.gif" border="0" onclick="history.back(-1);" style="cursor:pointer"></a>
		</form>
		</dl>
	</div>

<?php 
} 
?>

<script type="text/javascript">
//<![CDATA[
	function check() {
		document.getElementById("dataForm").action="process.php";
		document.getElementById("dataForm").submit();
	}
//]]>
</script>
