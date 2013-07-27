<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

checkAuth();

$groupId = trim($_REQUEST["groupId"]);

if ((strlen($groupId)>0)) {
	$query = "SELECT * FROM boardGroup WHERE groupId = '".$groupId."'";
	$groupRS = $db->Execute($query);
	$name = $groupRS["name"];
	$authRead = $groupRS["authReadLv"];
	$authWrite = $groupRS["authWriteLv"];
	$managerId = $groupRS["managerId"];
	$groupRS = null;

} 


showAdminHeader("관리툴 - 게시판 정보수정","","","");
body();
showAdminFooter();

function body() {
?>
<div class="sub">
	<a href="index.php">게시판목록</a> | 
	<a href="boardList.php?groupId=notice">공지사항</a> | 
	<a href="boardList.php?groupId=free">자유게시판</a> | 
	<a href="boardList.php?groupId=impression">이용후기</a> | 
	<a href="boardList.php?groupId=need">필요게시판</a> | 
	<a href="boardList.php?groupId=share">나눔게시판</a> | 
	<a href="boardList.php?groupId=outGoing">지출게시판</a></div>
</div>
<div id="wrap">
	<div class="lSec">
		<ul>
			<li><img src="/images/manager/lm_0600.gif"></li>
			<li><a href="boardList.php?groupId=notice"><img src="/images/manager/lm_0601.gif"></a></li>
			<li><a href="boardList.php?groupId=free"><img src="/images/manager/lm_0602.gif"></a></li>
			<li><a href="boardList.php?groupId=impression"><img src="/images/manager/lm_0603.gif"></a></li>
			<li><a href="boardList.php?groupId=need"><img src="/images/manager/lm_0604.gif"></a></li>
			<li><a href="boardList.php?groupId=share"><img src="/images/manager/lm_0605.gif"></a></li>
			<li><a href="boardList.php?groupId=outGoing"><img src="/images/manager/lm_0606.gif"></a></li>
			<li><img src="/images/manager/lm_bot.gif"></li>
		</ul>
	</div>
	<div class="rSec">
	<!-- 컨텐츠 들어가는 부분 -->

		<dl>
		<form name="dataForm" id="dataForm" method="post">
		<input type="hidden" name="mode" value="editGroup" />
		<input type="hidden" name="groupId" value="<?php echo $groupId;?>" />
			<dt>
				게시판이름
			<dd>
				<?php echo $name;?>
			<dt>
				글쓰기권한
			<dd>
				<input type="radio" name="authWrite" value="0" <?php if (($authWrite=="0")) { print "checked"; } ?> /> 비회원
				<input type="radio" name="authWrite" value="1" <?php if (($authWrite=="1")) { print "checked"; } ?> /> 일반회원
				<input type="radio" name="authWrite" value="3" <?php if (($authWrite=="3")) { print "checked"; } ?> /> 선교사
				<input type="radio" name="authWrite" value="7" <?php if (($authWrite=="7")) { print "checked"; } ?> /> 선교관관리자
				<input type="radio" name="authWrite" value="9" <?php if (($authWrite=="9")) { print "checked"; } ?> /> 관리자만 
			<dt>
				글읽기권한
			<dd>
				<input type="radio" name="authRead" value="0" <?php if (($authRead=="0")) { print "checked"; } ?> /> 비회원
				<input type="radio" name="authRead" value="1" <?php if (($authRead=="1")) { print "checked"; } ?> /> 일반회원
				<input type="radio" name="authRead" value="3" <?php if (($authRead=="3")) { print "checked"; } ?> /> 선교사
				<input type="radio" name="authRead" value="7" <?php if (($authRead=="7")) { print "checked"; } ?> /> 선교관관리자
				<input type="radio" name="authRead" value="9" <?php if (($authRead=="9")) { print "checked"; } ?> /> 관리자만 
			<dt>
				관리자 아이디
			<dd>
				<input type="text" name="managerId" size="20" value="<?php echo $managerId;?>" />
			<dt>
			<dd>
				<input type="button" value="확인" border="0" onclick="check();" style="cursor:pointer;">&nbsp;&nbsp;&nbsp;
				<input type="button" value="취소" border="0" onclick="history.back(-1);" style="cursor:pointer"></a>
		</form>
		</dl>
	</div>
</div>
<?php } ?>

<script type="text/javascript">
//<![CDATA[
	function check() {
		document.getElementById("dataForm").action="process.php";
		document.getElementById("dataForm").submit();
	}
//]]>
</script>
