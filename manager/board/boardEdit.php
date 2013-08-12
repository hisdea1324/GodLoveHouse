<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

checkAuth();

if (strlen(trim($_REQUEST["groupId"])) == 0) {
	alertGoPage("잘못된 접근입니다","/index.php");
} else {
	$groupId = trim($_REQUEST["groupId"]);
} 

if (trim($mode)=="") {
	$mode="addPost";
} 

if ((strlen($id)>0)) {
	$query = "select * from board where groupId='".$groupId."' AND id=".$id;
	$rs = $db->execute($query);
	$title = $Rs["title"];
	$contents = $Rs["contents"];
	$Rs = null;

} 


if (($mode=="replyPost")) {
	$rs = $db->execute($query);
	$title="[RE] ".$title;
	$contents="<br /><br /><br /> ========================== 원문입니다. ========================= <br /><br /><br />".$contents;
} 


showAdminHeader("관리툴 - 게시판 글쓰기","","","");
body($groupId);
showAdminFooter();

function body($groupId) {
	//=================에디터용 ==================
	$lang = $_REQUEST["lang"];
	if (strlen($lang) == 0) {
		$lang = "utf-8";
	} 
	$editor_mode = $_REQUEST["editor_mode"];
	if (strlen($editor_mode) == 0) {
		$editor_mode = "1";
	} 
	$editor_Url = $_REQUEST["editor_Url"];
	if (strlen($editor_Url) == 0) {
		$editor_Url = "/editor";
	} 
	$formName = $_REQUEST["formName"];
	if (strlen($formName) == 0) {
		$formName="dataForm";
	} 
	
	$contentForm = $_REQUEST["contentForm"];
	if (strlen($contentForm) == 0) {
		$contentForm = "contents";
	} 
	$formPost = $_REQUEST["formPost"];
	if (strlen($formPost) == 0) {
		$formPost = "process.php";
	} 
	$upload_image = $_REQUEST["upload_image"];
	if (strlen($upload_image) == 0) {
		$upload_image = "/upload/board"; // 이미지 업로드 사용 (1은 사용안함)
	} 
	$upload_media = $_REQUEST["upload_media"];
	if (strlen($upload_media) == 0) {
		$upload_media = "/upload/board"; // 미디어 업로드 사용 (1은 사용안함)
	} 
	//=================에디터용 ==================

	$mode = trim($_REQUEST["mode"]);
	$field = trim($_REQUEST["field"]);
	$keyword = trim($_REQUEST["keyword"]);
	$gotoPage = trim($_REQUEST["gotoPage"]);
	$contents = trim($_REQUEST["contents"]);
	$title = trim($_REQUEST["title"]);
	$id = trim($_REQUEST["id"]);
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

		<form name="<?php echo $formName;?>" id="<?php echo $formName;?>" method="post" action="<?php echo $formPost;?>" onsubmit="return dataCheck()">
		<input type="hidden" name="field" value="<?php echo $field;?>" />
		<input type="hidden" name="keyword" value="<?php echo $keyword;?>" />
		<input type="hidden" name="gotoPage" value="<?php echo $gotoPage;?>" />
		<input type="hidden" name="mode" value="<?php echo $mode;?>" />
		<input type="hidden" name="groupId" value="<?php echo $groupId;?>" />
		<input type="hidden" name="id" value="<?php echo $id;?>" />

		<dl>
			<dt>
				제 목
			<dd>
				<input type="text" name="title" value="<?php echo $title;?>" size="80" />&nbsp;&nbsp;
			<dt>
				내 용
			<dd>
				<?php 
	if ($mode!="reply") {
		$contentData = $contents;
	} 

?>	
				<!-- #include Virtual = "/editor/editor.php" -->
			<dt>
			<dd>
				<input type="image" src="/images/button/btn_ok.gif" border="0">&nbsp;&nbsp;&nbsp;
				<img align="absmiddle" src="/images/button/btn_cancel.gif" border="0" onclick="history.back()" style="cursor:hand;"/>
		</dl>

		</form>
	</div>
</div>
<?php } ?>

<script language="javascript" src="/css/putflash.js"></script>
<script src="/common/js/global.js"></script>
<script language="javascript">
	function dataCheck(){
		var theForm = document.getElementById("<?php echo $formName;?><%");

		if (document.getElementById('title').value=="") {
			alert("제목을 입력하세요.");
			document.getElementById('title').focus();
			return false;
		}
		if (document.getElementById('contents').value=="") {
			alert("내용을 입력하세요.");
			document.getElementById('contents').focus();
			return false;
		}
		
		theForm.<?php echo $contentForm;?><%.value = SubmitHTML();
	}
</script>
