<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
$needUserLv[1];

//=================에디터용 ==================
if ((strlen($lang)==0)) {
	$lang="utf-8";
} 
$editor_mode = $_REQUEST["editor_mode"];
if ((strlen($editor_mode)==0)) {
	$editor_mode="1";
} 
if ((strlen($editor_Url)==0)) {
	$editor_Url="/editor";
} 
if ((strlen($formName)==0)) {
	$formName="writeForm";
} 

if ((strlen($contentForm)==0)) {
	$contentForm="contents";
} 
if ((strlen($formPost)==0)) {
	$formPost="process.php";
} 
if ((strlen($upload_image)==0)) {
	$upload_image="/upload/board"; // 이미지 업로드 사용 (1은 사용안함)	
	if ((strlen($upload_media)==0)) {
		$upload_media="/upload/board"; // 미디어 업로드 사용 (1은 사용안함)//=================에디터용 ==================
	} 
	$id = trim($_REQUEST["id"]);
} 
$field = trim($_REQUEST["field"]);
$keyword = trim($_REQUEST["keyword"]);
$groupId = trim($_REQUEST["groupId"]);
$userId = $_SESSION['UserID'];
$mode = trim($_REQUEST["mode"]);
$page = trim($_REQUEST["page"]);
if ((strlen($mode)==0)) {
	$mode="addPost";
} 
if ((strlen($page)==0)) {
	$page=0;
} 

if ((strlen($id)>0)) {
	$query = "select * from board where groupId='".$mssqlEscapeString[$groupId]."' AND id= '".$mssqlEscapeString[$id]."'";
	$boardRS = $db->Execute($query);
	$title = $boardRS["title"];
	$contents = $boardRS["contents"];
	$userId = $boardRS["userId"];
	$id = $boardRS["id"];
	$boardRS = null;
} 


//쓰기권한 체크
if (($checkAuthorize[$groupId]["W"]==false)) {
	alertGoPage("권한이 없습니다.","board.php?groupId=".$groupId."&page=".$page."&field=".$field."&keyword=".$keyword);
} else if (($mode=="edit" && $userId != $_SESSION['UserId'] && $_SESSION['UserLv']<9)) {
	alertGoPage("권한이 없습니다.","board.php?groupId=".$groupId."&page=".$page."&field=".$field."&keyword=".$keyword);
} 


switch (($groupId)) {
	case "notice":
		$headerSet = array("HOME > 커뮤니티 > 공지사항","community","tit_0601.gif");
		break;
	case "impression":
		$headerSet = array("HOME > 커뮤니티 > 이용후기","community","tit_0602.gif");
		break;
	case "free":
		$headerSet = array("HOME > 커뮤니티 > 자유게시판","community","tit_0603.gif");
		break;
	case "event":
		$headerSet = array("HOME > 커뮤니티 > 선교단체행사","community","tit_0605.gif");
		break;
	case "column":
		$headerSet = array("HOME > 커뮤니티 > 칼럼","community","tit_0604.gif");
		break;
	case "need":
		$headerSet = array("HOME > 물류창고 > 필요물품","cooperate","tit_0506.gif");
		break;
	case "share":
		$headerSet = array("HOME > 물류창고 > 나눔물품","cooperate","tit_0507.gif");
		break;
	case "outGoing":
		$headerSet = array("HOME > 동역자소식 > 센터소식","fiscal","tit_0505.gif");
		break;
	case "mission_news":
		$headerSet = array("HOME > 동역자소식 > 선교지소식","fiscal","tit_0504.gif");
		break;
	case "support_news":
		$headerSet = array("HOME > 동역자소식 > 후원자소식","fiscal","tit_0505n.gif");
		break;
	default:


alertGoPage("잘못된 접근입니다",$Application["WebRoot"]."index.php");
		break;
} 

showHeader($headerSet[0],$headerSet[1],$headerSet[2]);
body();
showFooter();

function body() {
?>
		<!-- //content -->
		<div id="content">
			<!-- //write -->
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_write">
			<form name="writeForm" id="writeForm" method="post" action="process.php">
		<input type="hidden" name="mode" id="mode" value="<?php echo $mode;?>" />
		<input type="hidden" name="groupId" id="groupId" value="<?php echo $groupId;?>" />
		<input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
		<input type="hidden" name="userId" id="userId" value="<?php echo $_SESSION['UserId'];?>" />
			<col width="15%">
		<col />
				<tr>
					<td class="td01">제목</td>
					<td>
						<input type="text" id="title" name="title" style="width:600px" value="<?php echo $title;?>">
					</td>
				</tr>
				<tr>
					<td class="td01">작성자</td>
					<td>
						<?php echo $userId;?>
					</td>
				</tr>
		<?php if (($mode=="edit")) {
?>
				<tr>
					<td class="td01">수정자</td>
					<td>
						<?php echo $_SESSION['UserId'];?> (작성자는 수정한 사람 아이디로 바뀝니다.)
					</td>
				</tr>
		<?php } ?>
				<tr>
					<td class="td01">내용</td>
					<td>
			<?php 
	if (($mode=="replyPost" || $mode=="editPost")) {
		$contentData = $contents;
	} 

?>	
			<!-- #include Virtual = "/editor/editor.php" -->
					</td>
				</tr>
		</form>
			</table>
			<!-- write// -->
		<p class="btn_right">
		<img src="../images/board/btn_ok.gif" border="0" class="m2" onclick="frmSubmit()" style="cursor:hand"> 
		<a href="board.php?groupId=<?php echo $groupId;?>&keyword=<?php echo $keyword;?>&field=<?php echo $field;?>"><img src="../images/board/btn_cancel.gif" border="0" class="m2"></a>
		</p>
		</div>
		<!-- content// -->
<?php } ?>

<script language="javascript" src="/css/putflash.js"></script>
<script type="text/javascript">
//<![CDATA[
	function frmSubmit(){
		var theForm = document.getElementById("<?php echo $formName;?><%");
		
		if (document.getElementById('title').value=="") {
			alert("제목을 입력하세요.");
			document.getElementById('title').focus();
			return false;
		}
		// 일단 임시로 수정
		if (checkContent()) {
			alert("내용을 입력하세요.");
			return false;
		}
		
		document.getElementById('contents').value = SubmitHTML();
		theForm.submit();
	}
	
	function checkContent() {
		var string = gmFrame.document.body.innerHTML;
		
		string = string.replace("<br>", "");
		string = string.replace("<BR>", "");
		string = string.replace("<P>", "");
		string = string.replace("<p>", "");
		string = string.replace("</P>", "");
		string = string.replace("</p>", "");
		string = string.replace("&nbsp;", "");
		string = string.replace(" ", "");
		
		if (string.length < 1) {
			return true;
		} else {
			return false;
		}
	}
//]]>
</script>
