<?php
header("Location: http://".$_SERVER["HTTP_HOST"]."/house_manager/reserve_1.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
//***************************************************************
// member edit page//
// last update date : 2013.10.28
// updated by blackdew// To do List
//	 - 비밀번호 변경하는 페이지는 따로 추가해야 함
//	 - 자바 스크립트 추가 & update process 진행
//***************************************************************
checkUserLogin(7);

showHouseManagerHeader();
showHouseManagerLeft();
body();
showHouseManagerFooter();

function body() {
?>
							<!-- rightSec -->
							<div id="rightSec">
								<div class="lnb">
									<strong>Home</strong>
								</div>
								<div id="content">
									<!-- content -->
									<div class="mt20"><img src="images/index_title.gif" /></div>
									<div class="main_box">
										<div class="notice">
											<div class="tit">공지사항</div>
											<ul>
												<li><a href="#">공지사항입니다.</a></li>
												<li><a href="#">공지사항입니다.</a></li>
												<li><a href="#">공지사항입니다.</a></li>
												<li><a href="#">공지사항입니다.</a></li>
												<li><a href="#">공지사항입니다.</a></li>
											</ul>
										</div>
										<div class="notice">
											<div class="tit">자유게시판</div>
											<ul>
												<li><a href="#">자유게시판입니다.</a></li>
												<li><a href="#">자유게시판입니다.</a></li>
												<li><a href="#">자유게시판입니다.</a></li>
												<li><a href="#">자유게시판입니다.</a></li>
												<li><a href="#">자유게시판입니다.</a></li>
											</ul>
										</div>
										<div class="notice">
											<div class="tit">이용후기</div>
											<ul>
												<li><a href="#">이용후기입니다.</a></li>
												<li><a href="#">이용후기입니다.</a></li>
												<li><a href="#">이용후기입니다.</a></li>
												<li><a href="#">이용후기입니다.</a></li>
												<li><a href="#">이용후기입니다.</a></li>
											</ul>
										</div>
									</div>
									<!-- // content -->
								</div>
							</div>
							<!-- // rightSec -->
<?php } ?>

