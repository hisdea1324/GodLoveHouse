<?php
global $mysqli, $Application;
$mysqli = new mysqli($Application["server"], $Application["user"], $Application["pass"], $Application["db"]);

/* check connection */
if ($mysqli->connect_errno) {
	printf("Connect failed: %s\n", $mysqli->connect_error);
	exit();
}
?>