<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

checkAuth();
showAdminHeader("관리툴 - 메인","","","");
?>
	<div class="sub">
	<a href="member/editForm.php?mode=addUser">회원등록</a> | 
	<a href="member/index.php?userLv=0">전체목록</a> | 
	<a href="member/index.php?userLv=1">일반회원</a> | 
	<a href="member/index.php?userLv=3">선교사</a> | 
	<a href="member/index.php?userLv=7">선교관관리자</a>	
	</div>		
	</div>	
	<div id="wrap">
		<div class="lSec">
		<ul>
			<li><img src="/images/manager/lm_0100.gif"></li>
		<li><a href="member/editForm.php?mode=addUser"><img src="/images/manager/lm_0101.gif"></a></li>
		<li><a href="member/index.php?userLv=0"><img src="/images/manager/lm_0102.gif"></a></li>
		<li><a href="member/index.php?userLv=1"><img src="/images/manager/lm_0103.gif"></a></li>
		<li><a href="member/index.php?userLv=3"><img src="/images/manager/lm_0104.gif"></a></li>
		<li><a href="member/index.php?userLv=7"><img src="/images/manager/lm_0105.gif"></a></li>
		<li><img src="/images/manager/lm_bot.gif"></li>
		</ul>
	</div>
	<div class="rSec">
	<!-- 컨텐츠 들어가는 부분 -->
	</div>
	</div>
<?php 
showAdminFooter();
?>

