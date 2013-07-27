<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

checkAuth();
showAdminHeader("관리툴 메인","","","");
?>

<div id="main">
	<span id="welcome">관리자 페이지 입니다.</span>
	<hr>
</div>

<?php 
showAdminMenu();
//call AdminSubMenu()
showAdminFooter();
?>
