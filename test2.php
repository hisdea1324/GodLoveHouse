<?php
require "include/config.php";
require_once($_SERVER['DOCUMENT_ROOT']."/include/dbconn.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/dataFormat/HouseObject.php");

$test = new HouseObject();
$test->Update();
print_r($test);
echo "<br><br>";
$test->Open(4);
print_r($test);
?>
