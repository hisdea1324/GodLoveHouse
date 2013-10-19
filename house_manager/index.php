<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

showHouseManagerHeader();
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

