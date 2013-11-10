<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
$id = (isset($_REQUEST["id"])) ? trim($_REQUEST["id"]) : 0;
$field = (isset($_REQUEST["field"])) ? trim($_REQUEST["field"]) : "";
$keyword = (isset($_REQUEST["keyword"])) ? trim($_REQUEST["keyword"]) : "";
$groupId = (isset($_REQUEST["groupId"])) ? trim($_REQUEST["groupId"]) : 0;

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

$b_Helper = new BoardHelper();
$boardGrp = $b_Helper->getBoardGroupByGroupId($groupId);
$board = $b_Helper->getBoardInfoById($id);

showHeader($headerSet[0],$headerSet[1],$headerSet[2]);
body();
showFooter();

$board->AddView();

function body() {
	global $board, $boardGrp;
	global $id, $groupId, $field, $keyword;
?>
		<!-- #content -->
		<div id="content">
			<!-- #view -->
			<div class="bg_view">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="board_view">
					<col width="10%">
					<col width="20%">
					<col width="15%">
					<col width="20%">
					<col width="10%">
					<col width="30%">
					<tr>
						<th class="th01">제목</th>
						<th colspan="5"><?=$board->Title?></th>
					</tr>
					<tr>
						<td class="td01">작성자</td>
						<td><?=$board->userid?></td>
						<td class="td01">작성일</td>
						<td><?=date("Y.m.d", $board->RegDate)?></td>
						<td class="td01">조회</td>
						<td><?=$board->CountView?></td>
					</tr>
					<tr>
						<td colspan="6" class="td02"><?=$board->Contents?></td>
					</tr>
				</table>
			<table border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td><a class="addthis_button_facebook"><img src="/images/icon_face.gif" alt="페이스북 공유하기"></a></td>
						<td><a class="addthis_button_twitter"><img src="/images/icon_t.gif" alt="트위터 공유하기"></a></td>
						<td><img src="/images/icon_me.gif" style="cursor:pointer;" onclick="javascript:sns_share('m2d','<?=$board->Title?>');" alt="미투데이 공유하기"></td>
						<td><img src="/images/icon_yo.gif" style="cursor:pointer;" onclick="javascript:sns_share('yzm','<?=$board->Title?>');" alt="요즘 공유하기"></td>
						<td><img src="/images/icon_cy.gif" style="cursor:pointer;" onclick="javascript:sns_share('cwd','<?=$board->Title?>');" alt="싸이공감 공유하기"></td>
					</tr>
				</table>
			<script src="/community/js/addthis_widget.js" type="text/javascript"></script>
			<script language="javascript">
			function sns_share(sns_type,c_title) {
				var now_u = "http:#"+"<?=$_SERVER[SERVER_NAME]?>"+"<?=$_SERVER[REQUEST_URI]?>";
				switch(sns_type) {
					case "fb" : # 페이스북
						share_url = "http:#www.facebook.com/sharer.php?u="+now_u;
						break;
					case "tt" : # 트위터
						share_url = "http:#twitter.com/share?url="+now_u;
						break;
					case "m2d" : # 미투데이
						share_url = "http:#me2day.net/posts/new?new_post[body]="+c_title+" "+now_u;
						share_url = encodeURI(share_url);
						break;
					case "yzm" : # daum 요즘
						share_url = "http:#yozm.daum.net/api/popup/prePost?prefix="+c_title+"&link="+now_u;
						share_url = encodeURI(share_url);
						break;
					case "cwd" : # Cyworld
						share_url = "http:#csp.cyworld.com/bi/bi_recommend_pop.php?string="+c_title+"&url="+now_u;
						break;
				}
				window.open(share_url,"SNS","left=100,top=100,width=500,height=500,scrollbars=yes,resizable=yes");
			}
			</script>
		</div>
			<!-- view# -->
		<p class="btn_right">
		<a href="board.php?groupId=<?=$groupId?>&keyword=<?=$keyword?>&field=<?=$field?>"><img src="../images/board/btn_list.gif" border="0" class="m2"></a>
		<?php if ($boardGrp->WritePermission()) { ?><img src="../images/board/btn_modify.gif" border="0" class="m2" style="cursor:hand" onclick="location.href='write.php?mode=editPost&groupId=<?=$groupId?>&id=<?=$id?>';"><?php } ?>
		<?php if ($boardGrp->WritePermission()) { ?><img src="../images/board/btn_reply.gif" border="0" class="m2" style="cursor:hand" onclick="location.href='write.php?mode=replyPost&groupId=<?=$groupId?>&id=<?=$id?>';"><?php } ?>
		<?php if ($boardGrp->WritePermission()) { ?><img src="../images/board/btn_delete.gif" border="0" class="m2" style="cursor:hand" onclick="location.href='process.php?mode=deletePost&groupId=<?=$groupId?>&id=<?=$id?>';"><?php } ?>
		</p>
		</div>
		<!-- content# -->
<?php } ?>
