<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

showHeader("HOME > 사이트맵","doorkeeper","tit_0107.gif");
body();
showFooter();

function body() {	
?>
		<!-- //content -->
		<div id="content">
			<div>
				<ul class="sitemap r20">
					<li><img src="/images/sub/sitemap_01.gif"></li>
					<li class="list"><a href="/doorkeeper/welcome.php">인사말</a></li>
					<li class="list"><a href="/doorkeeper/introduce.php">센터소개</a></li>
					<li class="list"><a href="/doorkeeper/company.php">함께하는기업/단체</a></li>
					<li class="list"><a href="/doorkeeper/guide.php">후원자가이드</a></li>
					<li class="list"><a href="/doorkeeper/map.php">찾아오시는길</a></li>
				</ul>
				<ul class="sitemap r20">
					<li><img src="/images/sub/sitemap_02.gif"></li>
					<li class="list"><a href="/living/reservation.php">선교관 예약하기</a></li>
					<li class="list"><a href="/living/etc.php">기타 선교관 안내</a></li>
					<li class="list"><a href="/living/registHouse.php">선교관 등록요청</a></li>
				</ul>
				<ul class="sitemap r20">
					<li><img src="/images/sub/sitemap_02.gif"></li>
					<!--li class="list"><a href="/hospital/reservation.php">병 예약하기</a></li-->
					<li class="list"><a href="/hospital/etc.php">병원 안내</a></li>
					<li class="list"><a href="/hospital/registHouse.php">병원 등록요청</a></li>
				</ul>
				<ul class="sitemap">
					<li><img src="/images/sub/sitemap_03.gif"></li>
					<li class="list"><a href="/cooperate/family.php">선교사 가족과 함께 </a></li>
					<li class="list"><a href="/sponsor/special.php">특별후원</a></li>
					<li class="list"><a href="/sponsor/center.php">센터사역 후원 </a></li>
					<li class="list"><a href="/sponsor/service.php">자원봉사 참여 </a></li>
				</ul>
				<!--ul class="sitemap">
					<li><img src="/images/sub/sitemap_04.gif"></li>
					<li class="list"><a href="/community/mission_news.php">선교지 소식</a></li>
					<li class="list"><a href="/fiscal/outgoings.php">센터 소식</a></li>
					<li class="list"><a href="/fiscal/support_news.php">후원자 소식</a></li>
				</ul-->
			</div>
			<div>
				<!--ul class="sitemap r20">
					<li><img src="/images/sub/sitemap_05.gif"></li>
					<li class="list"><a href="/community/board.php?groupId=need">필요물품</a></li>
					<li class="list"><a href="/community/board.php?groupId=share">나눔물품</a></li>
				</ul-->
				<ul class="sitemap r20">
					<li><img src="/images/sub/sitemap_06.gif"></li>
					<li class="list"><a href="/community/notice.php">공지사항</a></li>
					<li class="list"><a href="/community/event.php">선교단체행사</a></li>
					<!--li class="list"><a href="/community/column.php">칼럼</a></li-->
					<li class="list"><a href="/community/impression.php">이용후기</a></li>
					<li class="list"><a href="/community/free.php">자유게시판</a></li>
				</ul>
			</div>
		</div>
		<!-- content// -->
<?php } ?>
