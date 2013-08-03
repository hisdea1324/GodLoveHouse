<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

//call showAdminHeader("관리툴 - 로그인", "", "", "")
body();
showAdminFooter();

function body() {
	global $Application;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"	"http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $Application["Charset"];?>" />
<title>::: God Love House Manager Page :::</title>
<link href='/include/css/style_admin.css' rel='StyleSheet' type='text/css' title='css'>
</head>
<body>
	<div class="global">
	<ul>
		<li><strong>::: God Love House Manager Page :::</strong></li>
	</ul>
</div>
<div class="login_main">
	<form id="login_form" method="post" action="login.php" >
	<!-- S login -->
		<dl id="login">
			<dt>아이디</dt>
			<dd>
				<input id="userid" name="userid" type="text" class="userid" tabindex="1" value="<?php echo $_REQUEST["userid"];?>"/>
			</dd>
			<dd class="button">
				<button type="submit" tabindex="3" class="btn-login" value="로그인"><span>로그인</span></button>
			</dd>
			<dt>비밀번호</dt>
			<dd>
				<input id="password" name="password" type="password" tabindex="2" class="loginpw" />
			</dd>
		</dl>
		<?php if (strlen($_REQUEST["userid"])>0) {
?>
		<div class="error_line" id="errorMsg">
			아이디 또는 비밀번호가 일치하지 않습니다.	
		</div>
		<?php } ?>
	</form>
</div>
<?php 
} 
?>
