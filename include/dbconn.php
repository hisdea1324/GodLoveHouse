<?php
global $mysqli;
$mysqli = new mysqli('localhost', 'lovehouse', '6394', 'godlovehouse');

/* check connection */
if ($mysqli->connect_errno) {
	printf("Connect failed: %s\n", $mysqli->connect_error);
	exit();
}
?>
