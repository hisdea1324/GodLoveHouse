<?php 
class ErrorHandler {
	function __construct() {

	} 
	function __destruct() {

	} 

	function endProcess($errorMsg) {
		print $errorMsg;
		exit();
	} 

	function ignoreError($errorMsg) {
	} 
} 
?>
