<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
$needUserLv[1];

$userId = trim($_REQUEST["userId"]);
if (strlen($userId) == 0) {
	alertBack("잘못된 접근입니다.");
} 

$page = trim($_REQUEST["page"]);
if ((strlen($page)==0)) {
	$page=1;
} 

# 현재 로그인 세션
$sessions = new __construct();

# 선교사 정보
$m_Helper = new MemberHelper();
$member = $m_Helper->getMemberByUserId($userId);
$mission = $m_Helper->getMissionInfoByUserId($userId);

# 커멘트 정보
$c_Helper = new CommentHelper();
$c_Helper->HostUserId = $mission->UserId;
$comments = $c_Helper->getCommentList($page);
$strPage = $c_Helper->makePagingHTML($page);

//call showHeader("HOME > 어깨동무 > 선교사 가족과 함께", "cooperate", "tit_0401.gif")
showHeader("HOME > 선교사후원 > 선교사 가족과 함께","sponsor","tit_0401.gif");
body();
showFooter();

function body() {
?>
	<!-- //content -->
	<div id="content">

		<!-- //view -->
		<p class="btn_right b5"><img src="../images/board/txt_family.gif" class="r5">
<?php 
	if (($m_Helper->getFamilyType($mission->UserId.$sessions->UserId)=="F0002")) {
?>
		<img src="../images/board/btn_family_01.gif" border="0" class="m2" onclick="joinFamily(1)" />
<?php 
	} else {
?>
		<img src="../images/board/btn_family_02.gif" border="0" class="m2" onclick="joinFamily(2)" />
<?php 
	} 
?>
		</p>

		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_con2 b20">
			<col width="29%" />
			<col width="20%" />
			<col />
			<tr>
				<th rowspan="4">
					<div id="showimage" style="position:absolute;visibility:hidden;border:1px solid black"></div>
					<img src="<?php echo $mission->Image;?>" id="imgProfile" width="190" height="120" onclick="showImage(this, event)" alt="크게보기" style="cursor:pointer" />
				</th>
				<th>이름</th>
				<td><?php echo $mission->MissionName;?></td>
			</tr>
			<tr>
				<th>지역</th>
				<td><?php echo $mission->Nation;?></td>
			</tr>
			<tr>
				<th>파송단체</th>
				<td><?php echo $mission->Ngo;?></td>
			</tr>
			<tr>
				<th class="ltd">가족소개</th>
				<td class="ltd">
<?php 
	if (($mission->FlagFamily==1)) {
		$familyList = $mission->Family;
		for ($i=0; $i<=count($familyList); $i = $i+1) {
			$familyObj = $familyList[$i];
?>
				<b>자녀 <?php echo count($mission->Family)+1;?>명</b> <br />
				<?php echo $familyObj->Relation;?> : <?php echo $familyObj->Name;?> (<?php echo (intval(substr(time(),0,4))-$familyObj->Age);?>세 / <?php echo $familyObj->Sex;?>)<br />
<?php 

		}
	} else {
?>
					비공개<br />
<?php 
	} 
?>
				</td>
			</tr>
			<tr>
				<th>소개</th>
				<td colspan="2"><?php echo str_replace(chr(13),"<br>",$mission->Memo);?> </td>
			</tr>
			<tr>
				<th>기도제목</th>
				<td colspan="2"> <?php echo str_replace(chr(13),"<br>",$mission->PrayList);?> </td>
			</tr>
		</table>
		<!-- view// -->

		<!-- //방명록 -->
		<div class="view_re"><dl><dd class="reply">
		<div class="formBox">
			<table>
				<tr>
					<td><textarea name="newComment" id="newComment" cols="100" rows="" class="taBox" title="의견 입력공간"></textarea></td>
					<td class="taBtn"><img src="../images/board/btn_re_add.gif" alt="의견등록" class="b5" onclick="new_comment()" /></td>
					</tr>
			</table>
			<div class="taInfo">
				<div class="rSec"><input name="newSecret" id="newSecret" type="checkbox" value="1" class="chk">비밀기능</div>
			</div>
		</div>
		<!-- 의견 리스트 -->
<?php 
	if ((count($comments)>0)) {
		for ($i=0; $i<=count($comments)-1; $i = $i+1) {
			$comment = $comments[$i];
?>
		<dl id="trComment<?php echo $comment->ID;?>">
			<dt>
				<img src="../images/board/ico_n.gif" alt="" valign="absmiddle" />
				<strong><?php echo $comment->FollowId;?></strong><span class="line">|</span>
				<span id="date<?php echo $comment->ID;?>"><?php echo dateFormat($comment->RegDate, 1);?></span>
				<span id="button<?php echo $comment->ID;?>">
<?php 
			if (($comment->FollowId == $sessions->UserId)) {
?> 
				<img src="../images/board/btn_m_modify.gif" border="0" class="r5" onclick="edit_form(<?php echo $comment->ID;?>)" alt="의견수정" valign="absmiddle" />
				<img src="../images/board/btn_m_del.gif" border="0" class="r5" onclick="delete_comment(<?php echo $comment->ID;?>)" alt="의견삭제" valign="absmiddle" />
				<img src="../images/board/btn_m_write.gif" border="0" class="r5" onclick="reply_form(<?php echo $comment->ID;?>)" alt="답글" valign="absmiddle" />
<?php 
			} else {
?>
				<img src="../images/board/icon_reply.gif" border="0" class="r5" onclick="reply_form(<?php echo $comment->ID;?>)" alt="답글" valign="absmiddle" />
<?php 
			} 
?>
				</span>
			</dt>
			<dd class="wsize" id="comment<?php echo $comment->ID;?>"><?php echo str_replace(chr(13),"<br />",$comment->Comments);?></dd>
			<dd class="wsize" id="commentFrm<?php echo $comment->ID;?>" style="display:none;">
				<div class="formBox">
					<table>
						<tr>
							<td><textarea name="editComment<?php echo $comment->ID;?>" id="editComment<?php echo $comment->ID;?>" cols="100" rows="" class="taBox" title="의견 입력공간"><?php echo $comment->Comments;?></textarea></td>
							<td class="taBtn"><img src="../images/board/btn_re_add.gif" alt="의견등록" class="b5" onclick="edit_comment(<?php echo $comment->ID;?>)" /></td>
						</tr>
					</table>
					<div class="taInfo">
						<div class="rSec"><input name="editSecret<?php echo $comment->ID;?>" id="editSecret<?php echo $comment->ID;?>" type="checkbox" value="1" class="chk">비밀기능</div>
					</div>
				</div>
			</dd>
		</dl>
		
		<div class="l15">
			<div class="formBox" id="trCommentReply<?php echo $comment->ID;?>" style="display:none;">
				<img src="../images/board/ioc-reply.gif" alt="" valign="absmiddle" />
				<table>
					<tr>
						<td><textarea name="reComment<?php echo $comment->ID;?>" id="reComment<?php echo $comment->ID;?>" cols="100" rows="" class="taBox" title="의견 입력공간"></textarea></td>
						<td class="taBtn">
							<img src="../images/board/btn_re_add.gif" alt="의견등록" class="b5" onclick="reply_comment(<?php echo $comment->ID;?>)" />
						</td>
					</tr>
				</table>
				<div class="taInfo">
					<div class="rSec"><input name="reSecret<?php echo $comment->ID;?>" id="reSecret<?php echo $comment->ID;?>" type="checkbox" value="1" class="chk">비밀기능</div>
				</div>
			</div>
<?php 
			if (($comment->ReplyCommentsExist)) {
				$replys = $comment->ReplyComments;
				for ($j=0; $j<=count($replys); $j = $j+1) {
					$replyComment = $replys[$j];
?>
			<dl id="trComment<?php echo $replyComment->ID;?>">
				<dt>
					<img src="../images/board/ioc-reply.gif" alt="" valign="absmiddle" />
					<strong><?php echo $replyComment->FollowId;?></strong><span class="line">|</span>
					<span id="date<?php echo $replyComment->ID;?>"><?php echo dateFormat($replyComment->RegDate, 1);?></span>
					<span id="button<?php echo $replyComment->ID;?>">
<?php 
					if (($replyComment->FollowId == $sessions->UserId)) {
?> 
					<img src="../images/board/btn_m_modify.gif" border="0" class="r5" onclick="edit_form(<?						 echo $replyComment->ID;?>)" alt="의견수정" valign="absmiddle" />
					<img src="../images/board/btn_m_del.gif" border="0" class="r5" onclick="delete_comment(<?						 echo $replyComment->ID;?>)" alt="의견삭제" valign="absmiddle" />
<?php 
					} 

?>
					</span>
				</dt>
				<dd class="wsize" id="comment<?php echo $replyComment->ID;?>">
					<?php echo str_replace(chr(13),"<br />",$replyComment->Comments);?>
				</dd>
				<dd class="wsize" id="commentFrm<?php echo $replyComment->ID;?>" style="display:none;">
					<div class="formBox">
						<table>
							<tr>
								<td><textarea name="editComment<?php echo $replyComment->ID;?>" id="editComment<?php echo $replyComment->ID;?>" cols="100" rows="" class="taBox" title="의견 입력공간"><?php echo $replyComment->Comments;?></textarea></td>
								<td class="taBtn"><img src="../images/board/btn_re_add.gif" alt="의견등록" class="b5" onclick="edit_comment(<?php echo $replyComment->ID;?>)" /></td>
							</tr>
						</table>
						<div class="taInfo">
							<div class="rSec"><input name="editSecret<?php echo $replyComment->ID;?>" id="editSecret<?php echo $replyComment->ID;?>" type="checkbox" value="1" class="chk">비밀기능</div>
						</div>
					</div>
				</dd>
			</dl>
<?php 
				}
			} 
?>
		</div>
		<!-- // 의견 리스트 -->
<?php 
		}
?>
		<br /><?php echo $strPage;?>
<?php 
	} 

?>
		</dd></dl></div>

		<!-- 방명록// -->
		<br /><p class="btn_right"><a href="family.php"><img src="../images/board/btn_list.gif" border="0" class="m2"></a></p>
	</div>
	<form action="process.php" method="post" name="dataFrm" id="dataFrm">
		<input type="hidden" name="mode" id="mode" value="editComment">
		<input type="hidden" name="editCommentId" id="editCommentId" value="-1">
		<input type="hidden" name="editComment" id="editComment" value="">
		<input type="hidden" name="editSecret" id="editSecret" value="">
		<input type="hidden" name="parentId" id="parentId" value="-1">
		<input type="hidden" name="userId" id="userId" value="<?php echo $userId;?>">
	</form>
	<!-- content// -->
<?php } ?>

<script type="text/javascript">
//<![CDATA[	
	function joinFamily(type) {
		<?php if ((strlen($_SESSION['userId'])==0)) { ?>
		alert('로그인한 후에 이용할 수 있습니다.');
		<?php } else { ?>
		if (type == 1) {
			location.href = "process.php?mode=addFamily01&userId=<?php echo $userId;?>"
		} else {
			location.href = "process.php?mode=addFamily02&userId=<?php echo $userId;?>"
		}
		<?php } ?>
	}
	
	function cancel_comment(commentId) {
		var comment = document.getElementById("editComment"+commentId).textContent;
		document.getElementById("comment"+commentId).innerHTML = comment;
		document.getElementById("date"+commentId).style.display = '';

		var btnObj = document.getElementById("button"+commentId);
		btnObj.innerHTML = '<img src="../images/board/btn_m_modify.gif" border="0" class="r5" onclick="edit_form('+commentId+')" alt="의견수정" valign="absmiddle" />';
		btnObj.innerHTML += '<img src="../images/board/btn_m_del.gif" border="0" class="r5" onclick="delete_comment('+commentId+')" alt="의견삭제" valign="absmiddle" />';
		btnObj.innerHTML += '<img src="../images/board/btn_m_write.gif" border="0" class="r5" onclick="reply_form('+commentId+')" alt="답글" valign="absmiddle" />';
	}
	
	function cancel_reply_comment(commentId) {
		document.getElementById("trCommentReply"+commentId).style.display = 'none';
	}

	function edit_form(commentId) {
		var value;
		value = document.getElementById("comment"+commentId).style.display;

		if (value == 'none') {
			document.getElementById("comment"+commentId).style.display = '';
			document.getElementById("commentFrm"+commentId).style.display = 'none';
		} else {
			document.getElementById("comment"+commentId).style.display = 'none';
			document.getElementById("commentFrm"+commentId).style.display = '';
		}
	}
	
	function reply_form(commentId) {
		var value;
		value = document.getElementById("trCommentReply"+commentId).style.display;
		if (value == 'none') {
			document.getElementById("trCommentReply"+commentId).style.display = "";
		} else {
			document.getElementById("trCommentReply"+commentId).style.display = "none";
		}
	}

	function new_comment() {
		document.getElementById("editComment").value = document.getElementById("newComment").value;
		document.getElementById("editSecret").value = document.getElementById("newSecret").value;
		document.getElementById("dataFrm").submit();
	}

	function edit_comment(commentId) {
		document.getElementById("editCommentId").value = commentId;
		document.getElementById("editComment").value = document.getElementById("editComment"+commentId).value;
		document.getElementById("editSecret").value = document.getElementById("editSecret"+commentId).value;
		document.getElementById("dataFrm").submit();
	}

	function reply_comment(commentId) {
		document.getElementById("editCommentId").value = -1;
		document.getElementById("editComment").value = document.getElementById("reComment"+commentId).value;
		document.getElementById("editSecret").value = document.getElementById("reSecret"+commentId).value;
		document.getElementById("parentId").value = commentId;
		document.getElementById("dataFrm").submit();
	}
	
	function delete_comment(commentId) {
		if (confirm("정말 삭제하시겠습니까")) {
			document.getElementById("editCommentId").value = commentId;
			document.getElementById("mode").value = 'deleteComment';
			document.getElementById("dataFrm").submit();
		}
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
