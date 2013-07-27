<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
$userid = $_REQUEST["userid"];
$backURL = trim($_REQUEST["backURL"]);

if ((strlen($backURL)==0)) {
	$backURL = $URLEncode[$_SERVER["HTTP_REFERER"]];
} 


showHeader("HOME > 멤버쉽 > 로그인","member","tit_0702.gif");
body();
showFooter();

function body() {
?>
		<!-- //content -->
		<div id="content">
			<div class="login">
				<table border="0" cellspacing="0" cellpadding="0">
		<form action="process.php" method="post" id="frmLogin">
		<input type="hidden" name="mode" value="login">
		<input type="hidden" name="backURL" value="<?php echo $backURL;?>">
					<tr>
						<td><img src="../images/sub/txt_id.gif">
							<input type="text" name="userId" id="userId" style="width:150px" tabindex="1">
						</td>
						<td rowspan="2"><a href="#" onclick="frmSubmit()"><img src="../images/sub/btn_login.gif" border="0" tabindex="3"></a></td>
					</tr>
					<tr>
						<td><img src="../images/sub/txt_pw.gif">
							<input type="password" name="password" id="password" style="width:150px" tabindex="2" onKeyPress="CheckEnter(event);">
						</td>
					</tr>
			<?php if (strlen($userid)>0) {
?>
					<tr>
						<td>
			<div class="error_line" id="errorMsg">
				아이디 또는 비밀번호를 확인해주세요.	
			</div>						
			</td>
					</tr>
			<?php } ?>
				</table>
			</div>
		<p class="login_txt">
		<img src="../images/sub/txt_login.gif" class="r20" align="absmiddle" tabindex="3"><a href="join.php"><img src="../images/sub/btn_join.gif" align="absmiddle"></a>
		</p>
	</form>
		</div>

		<!-- content// -->
<?php } ?>

<script type="text/javascript">
//<![CDATA[
	function frmSubmit() {
		document.getElementById("frmLogin").submit();
	}

	function CheckEnter(event) {
		if (event.keyCode == 13) 
			document.getElementById("frmLogin").submit();
	}
//]]>
</script>
