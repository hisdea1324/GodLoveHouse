<?php
global $mysqli;
$mysqli = new mysqli('localhost', 'root', 'root', 'mysql');
    
/* check connection */
if ($mysqli->connect_errno) {
	printf("Connect failed: %s\n", $mysqli->connect_error);
	exit();
}
?>
