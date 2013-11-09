<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

needUserLv(3);

$id = (isset($_REQUEST["id"])) ? trim($_REQUEST["id"]) : 0;
$field = (isset($_REQUEST["field"])) ? trim($_REQUEST["field"]) : "";
$keyword = (isset($_REQUEST["keyword"])) ? trim($_REQUEST["keyword"]) : "";
$groupId = (isset($_REQUEST["groupId"])) ? trim($_REQUEST["groupId"]) : 0;
$mode = (isset($_REQUEST["mode"])) ? trim($_REQUEST["mode"]) : "editPost";
$page = (isset($_REQUEST["page"])) ? trim($_REQUEST["page"]) : 1;

$b_Helper = new BoardHelper();
$boardGrp = $b_Helper->getBoardGroupByGroupId($groupId);
if (strcmp($mode,"replyPost") == 0) {
# for reply	
	$boardInfo = $b_Helper->getReplyInfoById($id);
} else {
# for edit & new insert	
	$boardInfo = $b_Helper->getBoardInfoById($id);
} 

# 쓰기권한 체크

//$userLevel = (isset($_SESSION["userLv"])) ? trim($_SESSION["userLv"]) : 0;
//echo "UserLevel ::: " . $userLevel;

if (!$boardGrp->WritePermission()) {
	alertGoPage("쓰기 권한이 없습니다.","board.php?groupId=".$groupId."&page=".$page."&field=".$field."&keyword=".$keyword);
} else if (!$boardInfo->checkEditPermission()) {
	alertGoPage("수정 권한이 없습니다.","board.php?groupId=".$groupId."&page=".$page."&field=".$field."&keyword=".$keyword);
} 

switch ($groupId) {
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
		alertGoPage("잘못된 접근입니다", "http://".$_SERVER['HTTP_HOST']."/index.php");
		break;
} 

showHeader($headerSet[0],$headerSet[1],$headerSet[2]);
body();
showFooter();

function body() {
	global $boardInfo, $boardGrp;
	global $mode, $page, $groupId;
?>
	<!--link href="css/default.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="js/HuskyEZCreator.js" charset="utf-8"></script-->

		<!-- //content -->
		<div id="content">
			<!-- //write -->
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_write">
			<form name="writeForm" id="writeForm" method="post" action="process.php">
		<input type="hidden" name="mode" id="mode" value="<?php echo $mode;?>" />
		<input type="hidden" name="groupId" id="groupId" value="<?php echo $groupId;?>" />
		<input type="hidden" name="id" id="id" value="<?php echo $boardInfo->id;?>" />
		<input type="hidden" name="answerId" id="answerId" value="<?php echo $boardInfo->answerId;?>" />
		<input type="hidden" name="answerNum" id="answerNum" value="<?php echo $boardInfo->answerNum;?>" />
		<input type="hidden" name="answerLv" id="answerLv" value="<?php echo $boardInfo->answerLv;?>" />
		<input type="hidden" name="userid" id="userid" value="<?php echo $_SESSION["userid"];?>" />
			<col width="15%">
		<col />
				<tr>
					<td class="td01">제목</td>
					<td>
						<input type="text" id="title" name="title" style="width:600px" value="<?php echo $boardInfo->title;?>">
					</td>
				</tr>
				<tr>
					<td class="td01">작성자</td>
					<td><?php echo $boardInfo->userid;?></td>
				</tr>
		<?php if (($boardInfo->id>-1)) {
?>
				<tr>
					<td class="td01">수정자</td>
					<td>
						<?php echo $_SESSION["userid"];?> (작성자는 수정한 사람 아이디로 바뀝니다.)
					</td>
				</tr>
		<?php } ?>
				<tr>
					<td class="td01">내용</td>
					<td>
			<textarea name="contents" id="contents" style="width:600px; height:300px;"><?php echo $boardInfo->contents;?></textarea>
					</td>
				</tr>
		<tr>
			<td class="td01">이미지</td>
			<td>
				<div id="showimage1" style="position:absolute;visibility:hidden;border:1px solid black"></div>
				<input type="button" name="imgUpload" id="imgUpload" value=" 이미지 업로드 " onclick="uploadImage(event, 'boardImage', 'board')" style="cursor:pointer" /> <br />
				<input type="hidden" name="idboardImage" id="idboardImage" value="" />
				<input type="hidden" name="txtboardImage" id="txtboardImage" value="" />
			</td>
		</tr>
		<tr>
			<td class="td01">파일</td>
			<td>
				<div id="showimage1" style="position:absolute;visibility:hidden;border:1px solid black"></div>
				<input type="button" name="fileUpload" id="fileUpload" value=" 파일 업로드 " onclick="uploadFile(event, 'boardFile', 'board')" style="cursor:pointer" /> <br />
				<input type="hidden" name="idboardFile" id="idboardFile" value="" />
				<input type="hidden" name="txtboardFile" id="txtboardFile" value="" />
			</td>
		</tr>
		</form>
			</table>
			<!-- write// -->
		<p class="btn_right">
		<img src="../images/board/btn_ok.gif" border="0" class="m2" onclick="javascript:frmSubmit(this)" style="cursor:pointer"> 
		<a href="board.php?groupId=<?php echo $groupId;?>&keyword=<?php echo $keyword;?>&field=<?php echo $field;?>"><img src="../images/board/btn_cancel.gif" border="0" class="m2"></a>
		</p>
		</div>
		<!-- content// -->

<script language="javascript">
/*
var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
	oAppRef: oEditors,
	elPlaceHolder: "contents",
	sSkinURI: "SEditorSkin.html",
	fCreator: "createSEditorInIFrame"
]);
*/
function pasteHTMLDemo(){
	image = "/upload/board/" + document.getElementById("txtboardImage").value;
	sHTML = "<span style='color:#FF0000'><img src='" + image + "' /></span>";
	oEditors.getById["contents"].exec("PASTE_HTML", [sHTML]);
}

function pasteAttachFile(){
	fileName = document.getElementById("txtboardFile").value;
	sHTML = "<span style='color:#FF0000'><a href='/upload/board/" + fileName + "'>download : " + fileName + "</a></span>";
	//oEditors.getById["contents"].exec("PASTE_HTML", [sHTML]);
}

function showHTML(){
	//alert(oEditors.getById["contents"].getIR());
}

function frmSubmit(elClicked){
	//oEditors.getById["contents"].exec("UPDATE_IR_FIELD", []);
	
	var theForm = document.getElementById("writeForm");
	
	if (document.getElementById('title').value == "") {
		alert("제목을 입력하세요.");
		document.getElementById('title').focus();
		return false;
	}
	if (document.getElementById('contents').value == "") {
		alert("내용을 입력하세요.");
		document.getElementById('contents').focus();
		return false;
	}
	
	theForm.submit();
}
</script>
<?php } ?>
