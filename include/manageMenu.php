<?php
require "/include/function/manager.php";

function checkAuth() {
	if ($_SESSION['userLv']<9) {
		header("Location: "."/");
		exit();

	} 

} 
?>
