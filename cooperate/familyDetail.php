<?
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
needUserLv(1);

if (!isset($_REQUEST["userid"])) {
	alertBack("잘못된 접근입니다.");
}
$userid = trim($_REQUEST["userid"]); 
$page = isset($_REQUEST["page"]) ? trim($_REQUEST["page"]) : 1;

# 선교사 정보
$m_Helper = new MemberHelper();
$member = $m_Helper->getMemberByuserid($userid);
$mission = $m_Helper->getMissionInfoByuserid($userid);

# 커멘트 정보
$c_Helper = new CommentHelper();
$c_Helper->Hostuserid = $mission->userid;
$comments = $c_Helper->getCommentList($page);
$strPage = $c_Helper->makePagingHTML($page);

//call showHeader("HOME > 어깨동무 > 선교사 가족과 함께", "cooperate", "tit_0401.gif")
showHeader("HOME > 선교사후원 > 선교사 가족과 함께","sponsor","tit_0401.gif");
body();
showFooter();

function body() {
	global $m_Helper, $mission, $comments;
?>
	<!-- //content -->
	<div id="content">

		<!-- //view -->
		<p class="btn_right b5"><img src="../images/board/txt_family.gif" class="r5">
<? 
	if ($m_Helper->getFamilyType($mission->userid, $_SESSION["userid"]) == "F0002") {
?>
		<img src="../images/board/btn_family_01.gif" border="0" class="m2" onclick="joinFamily(1)" />
<? 
	} else {
?>
		<img src="../images/board/btn_family_02.gif" border="0" class="m2" onclick="joinFamily(2)" />
<? 
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
					<img src="<?=$mission->Image?>" id="imgProfile" width="190" height="120" onclick="showImage(this, event)" alt="크게보기" style="cursor:pointer" />
				</th>
				<th>이름</th>
				<td><?=$mission->MissionName?></td>
			</tr>
			<tr>
				<th>지역</th>
				<td><?=$mission->Nation?></td>
			</tr>
			<tr>
				<th>파송기관</th>
				<td><?=$mission->Church?></td>
			</tr>
			<tr>
				<th class="ltd">가족소개</th>
				<td class="ltd">
<? 
	if (($mission->FlagFamily==1)) {
		$familyList = $mission->Family;
		for ($i=0; $i<=count($familyList); $i = $i+1) {
			$familyObj = $familyList[$i];
?>
				<b>자녀 <?=count($mission->Family)+1?>명</b> <br />
				<?=$familyObj->Relation?> : <?=$familyObj->Name?> (<?=(intval(substr(time(),0,4))-$familyObj->Age)?>세 / <?=$familyObj->Sex?>)<br />
<? 

		}
	} else {
?>
					비공개<br />
<? 
	} 
?>
				</td>
			</tr>
			<tr>
				<th>소개</th>
				<td colspan="2"><?=str_replace(chr(13),"<br>",$mission->Memo)?> </td>
			</tr>
			<tr>
				<th>기도제목</th>
				<td colspan="2"> <?=str_replace(chr(13),"<br>",$mission->PrayList)?> </td>
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

<? 
	if (false && count($comments) > 0) {
		for ($i=0; $i<=count($comments)-1; $i = $i+1) {
			$comment = $comments[$i];
?>
		<dl id="trComment<?=$comment->ID?>">
			<dt>
				<img src="../images/board/ico_n.gif" alt="" valign="absmiddle" />
				<strong><?=$comment->FollowId?></strong><span class="line">|</span>
				<span id="date<?=$comment->ID?>"><?=dateFormat($comment->RegDate, 1)?></span>
				<span id="button<?=$comment->ID?>">
<? 
			if ($comment->FollowId == $_SESSION["userid"]) {
?> 
				<img src="../images/board/btn_m_modify.gif" border="0" class="r5" onclick="edit_form(<?=$comment->ID?>)" alt="의견수정" valign="absmiddle" />
				<img src="../images/board/btn_m_del.gif" border="0" class="r5" onclick="delete_comment(<?=$comment->ID?>)" alt="의견삭제" valign="absmiddle" />
				<img src="../images/board/btn_m_write.gif" border="0" class="r5" onclick="reply_form(<?=$comment->ID?>)" alt="답글" valign="absmiddle" />
<? 
			} else {
?>
				<img src="../images/board/icon_reply.gif" border="0" class="r5" onclick="reply_form(<?=$comment->ID?>)" alt="답글" valign="absmiddle" />
<? 
			} 
?>
				</span>
			</dt>
			<dd class="wsize" id="comment<?=$comment->ID?>"><?=str_replace(chr(13),"<br />", $comment->Comments)?></dd>
			<dd class="wsize" id="commentFrm<?=$comment->ID?>" style="display:none;">
				<div class="formBox">
					<table>
						<tr>
							<td><textarea name="editComment<?=$comment->ID?>" id="editComment<?=$comment->ID?>" cols="100" rows="" class="taBox" title="의견 입력공간"><?=$comment->Comments?></textarea></td>
							<td class="taBtn"><img src="../images/board/btn_re_add.gif" alt="의견등록" class="b5" onclick="edit_comment(<?=$comment->ID?>)" /></td>
						</tr>
					</table>
					<div class="taInfo">
						<div class="rSec"><input name="editSecret<?=$comment->ID?>" id="editSecret<?=$comment->ID?>" type="checkbox" value="1" class="chk">비밀기능</div>
					</div>
				</div>
			</dd>
		</dl>
		
		<div class="l15">
			<div class="formBox" id="trCommentReply<?=$comment->ID?>" style="display:none;">
				<img src="../images/board/ioc-reply.gif" alt="" valign="absmiddle" />
				<table>
					<tr>
						<td><textarea name="reComment<?=$comment->ID?>" id="reComment<?=$comment->ID?>" cols="100" rows="" class="taBox" title="의견 입력공간"></textarea></td>
						<td class="taBtn">
							<img src="../images/board/btn_re_add.gif" alt="의견등록" class="b5" onclick="reply_comment(<?=$comment->ID?>)" />
						</td>
					</tr>
				</table>
				<div class="taInfo">
					<div class="rSec"><input name="reSecret<?=$comment->ID?>" id="reSecret<?=$comment->ID?>" type="checkbox" value="1" class="chk">비밀기능</div>
				</div>
			</div>
<? 
			if (($comment->ReplyCommentsExist)) {
				$replys = $comment->ReplyComments;
				for ($j=0; $j<=count($replys); $j = $j+1) {
					$replyComment = $replys[$j];
?>
			<dl id="trComment<?=$replyComment->ID?>">
				<dt>
					<img src="../images/board/ioc-reply.gif" alt="" valign="absmiddle" />
					<strong><?=$replyComment->FollowId?></strong><span class="line">|</span>
					<span id="date<?=$replyComment->ID?>"><?=dateFormat($replyComment->RegDate, 1)?></span>
					<span id="button<?=$replyComment->ID?>">
<? 
					if ($replyComment->FollowId == $_SESSION["userid"]) {
?> 
					<img src="../images/board/btn_m_modify.gif" border="0" class="r5" onclick="edit_form(<?						 echo $replyComment->ID?>)" alt="의견수정" valign="absmiddle" />
					<img src="../images/board/btn_m_del.gif" border="0" class="r5" onclick="delete_comment(<?						 echo $replyComment->ID?>)" alt="의견삭제" valign="absmiddle" />
<? 
					} 

?>
					</span>
				</dt>
				<dd class="wsize" id="comment<?=$replyComment->ID?>">
					<?=str_replace(chr(13),"<br />",$replyComment->Comments)?>
				</dd>
				<dd class="wsize" id="commentFrm<?=$replyComment->ID?>" style="display:none;">
					<div class="formBox">
						<table>
							<tr>
								<td><textarea name="editComment<?=$replyComment->ID?>" id="editComment<?=$replyComment->ID?>" cols="100" rows="" class="taBox" title="의견 입력공간"><?=$replyComment->Comments?></textarea></td>
								<td class="taBtn"><img src="../images/board/btn_re_add.gif" alt="의견등록" class="b5" onclick="edit_comment(<?=$replyComment->ID?>)" /></td>
							</tr>
						</table>
						<div class="taInfo">
							<div class="rSec"><input name="editSecret<?=$replyComment->ID?>" id="editSecret<?=$replyComment->ID?>" type="checkbox" value="1" class="chk">비밀기능</div>
						</div>
					</div>
				</dd>
			</dl>
<? 
				}
			} 
?>
		</div>
		<!-- // 의견 리스트 -->
<? 
		}
?>
		<br /><?=$strPage?>
<? 
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
		<input type="hidden" name="userid" id="userid" value="<?=$userid?>">
	</form>
	<!-- content// -->
<? } ?>

<script type="text/javascript">
//<![CDATA[	
	function joinFamily(type) {
		<? if ((strlen($_SESSION['userid'])==0)) { ?>
		alert('로그인한 후에 이용할 수 있습니다.');
		<? } else { ?>
		if (type == 1) {
			location.href = "process.php?mode=addFamily01&userid=<?=$userid?>"
		} else {
			location.href = "process.php?mode=addFamily02&userid=<?=$userid?>"
		}
		<? } ?>
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
