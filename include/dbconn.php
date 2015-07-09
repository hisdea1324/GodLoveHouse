<?php
global $mysqli, $Application;
$mysqli = new mysqli($Application["ex_server"], $Application["ex_user"], $Application["ex_pass"], $Application["ex_db"]);

/* check connection */
if ($mysqli->connect_errno) {
	printf("Connect failed: %s\n", $mysqli->connect_error);
	exit();
}
?>
