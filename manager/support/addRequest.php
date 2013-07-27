<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

$supportType = trim($_REQUEST["supportType"]);
$reqId = trim($_REQUEST["reqId"]);
$field = trim($_REQUEST["field"]);
$keyword = trim($_REQUEST["keyword"]);
$gotoPage = trim($_REQUEST["gotoPage"]);

$c_Helper = new CodeHelper();
$s_Helper = new SupportHelper();
$requestObj = $s_Helper->getRequestInfoByReqId($reqId);
$requestAdd = $s_Helper->getRequestAddInfoByReqId($reqId);
$nationCodes = $c_Helper->getNationCodeList();
$supportCodes = $c_Helper->getSupportCodeList();

checkAuth();
showAdminHeader("관리툴 - 후원 등록","","","");
body();
showAdminFooter();

function body() {
?>
	<div class="sub">
	<a href="addRequest.php">후원추가</a> | 
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
		<input type="hidden" name="field" value="<?php echo $field;?>" />
		<input type="hidden" name="keyword" value="<?php echo $keyword;?>" />
		<input type="hidden" name="gotoPage" value="<?php echo $gotoPage;?>" />
		<input type="hidden" name="mode" value="editRequest" />
		<input type="hidden" name="reqId" value="<?php echo $requestObj->RequestID;?>" />
			<dt>
				후원타입
			<dd>
				<select name="supportType" id="supportType" tabindex="32">
				<?php 
	for ($i=0; $i<=count($supportCodes)-1; $i = $i+1) {
		$codeObj = $supportCodes[$i];
		if (($codeObj->Code == $requestObj->SupportTypeCode)) {
			print "<option value=\"".$codeObj->Code."\" selected>".$codeObj->Name."</option>";
		} else {
			print "<option value=\"".$codeObj->Code."\">".$codeObj->Name."</option>";
		} 


	}

?>
				</select>
				<dt>
				후원 제목
			<dd>
				<input type="text" name="title" size="50" maxlength=100 value="<?php echo $requestObj->Title;?>" />
			<dt>
				상세설명
			<dd>
				<textarea name="explain" cols=50 rows=5><?php echo $requestObj->Explain;?></textarea>
			<dt>
				이미지
			<dd>
				<div id="showimage" style="position:absolute;visibility:hidden;border:1px solid black"></div>
				<input type="button" name="imgUpload" id="imgUpload" value="이미지 업로드" onclick="uploadImage(event, 'ImageFile', 'support')" style="cursor:pointer" /> (106x66)<br />
				<input type="hidden" name="idImageFile" id="idImageFile" value="<?php echo $requestObj->ImageID;?>" />
				<img src="<?php echo $requestObj->Image;?>" id="imgImageFile" width="106" height="66" onclick="showImage(this, event)" alt="크게보기" style="cursor:pointer" />
			<dt>
				요청 선교사
			<dd>
				<input type="text" name="userId" size="20" maxlength=30 value="<?php echo $requestAdd->UserID;?>" /> (특별후원일 경우만 입력, 회원 ID를 정확히 입력해야 함)
			<dt>
				마감일
			<dd>
				<input type="text" name="dueDate" size="20" maxlength=30 value="<?php echo $requestAdd->Due;?>" /> (특별후원일 경우만 입력, 입력형식:2010-04-01)
			<dt>
				선교지코드
			<dd>
				<select name="nationCode" id="nationCode" tabindex="32">
				<?php 
	for ($i=0; $i<=count($nationCodes)-1; $i = $i+1) {
		$codeObj = $nationCodes[$i];
		if (($codeObj->Code == $requestAdd->NationCode)) {
			print "<option value=\"".$codeObj->Code."\" selected>".$codeObj->Name."</option>";
		} else {
			print "<option value=\"".$codeObj->Code."\">".$codeObj->Name."</option>";
		} 


	}

?>
				</select> (특별후원일 경우만 입력)
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
	
	function showImage(obj, e) {
		crossobj = document.getElementById("showimage");
		
		if (crossobj.style.visibility == "hidden") {
			crossobj.style.left = e.clientX;
			crossobj.style.top = e.clientY;
			crossobj.innerHTML = '<img src="' + obj.src + '" style="cursor:pointer" onClick="closepreview()" />';
			crossobj.style.visibility = "visible";
		} else {
			crossobj.style.visibility = "hidden";
		}
	}

	function closepreview(){
		crossobj.style.visibility="hidden"
	}
//]]>
</script>
