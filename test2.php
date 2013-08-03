<?php
require "include/_config.php";
require_once($_SERVER['DOCUMENT_ROOT']."/include/dbconn.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/dataFormat/HouseObject.php");

$test = new HouseObject();
//$test->Update(); // initiaize code in db? 
//print_r($test); // test class member are right?
//
echo "<br><br>";
$test->Open(0); // selected data?
print_r($test); // 
$test->Delete(); // delete ok?



/*
class test {
	public $value;
	
	function __construct($v = -1) {
		if ($v > -1) {
			$this->value = $v;
			echo $v;
		} else {
			$this->value = 0;
			echo 0;
		}
	}
/*	
	function __construct($v) {
		$this->value = $v;
		echo $v;
	}
	*/
//}

//$test1 = new test();
//$test2 = new test(2);
?>
