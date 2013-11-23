<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
showHeader("HOME > 갓러브하우스 > 센터소개","doorkeeper","tit_0106.gif");
body();
showFooter();

function body() {
?>
		<!-- //content -->
		<div id="content">
			<!-- //write -->
			<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_write">
			<col width="15%">
		<col />
				<tr>
					<td class="td01">회사명</td>
					<td>
						<input type="text" name="textfield" style="width:300px">
					</td>
				</tr>
				<tr>
					<td class="td01">담당자</td>
					<td>
						<input type="text" name="textfield" style="width:150px">
					</td>
				</tr>
				<tr>
					<td class="td01">전화번호</td>
					<td>
						<input type="password" name="textfield" style="width:150px">
					</td>
				</tr>
				<tr>
					<td class="td01">핸드폰</td>
					<td>
						<input type="password" name="textfield" style="width:150px">
					</td>
				</tr>
				<tr>
					<td class="td01">E-mail</td>
					<td>
						<input type="password" name="textfield" style="width:150px">
					</td>
				</tr>
				<tr>
					<td class="td01">하고싶은말</td>
					<td>
						<textarea name="textfield" style="width:95%"></textarea>
					</td>
				</tr>
			</table>
			<!-- write// -->
		<p class="btn_right"><a href="#"><img src="/images/board/btn_cancel.gif" border="0" class="m2"></a><a href="#"><img src="/images/board/btn_ok.gif" border="0" class="m2"></a></p>
		</div>
		<!-- content// -->
<?php } ?>
