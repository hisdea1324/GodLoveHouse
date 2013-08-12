<?php
	require "include/config.php";
	require_once($_SERVER['DOCUMENT_ROOT']."/include/dbconn.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/include/dataFormat/MemberObject.php");


	echo $_SERVER['DOCUMENT_ROOT'];

	$testObject = new MemberObject();
	//$testObject->Open();
	$testObject->Update();


	//$testObject->Update();
	print_r($testObject);
	//$testObject->Delete();


	//$testObject->Open(0); 
	//print_r($testObject);
	//$testObject->Delete(); 


	//$testObject->Open(1);

	//$testObject->Delete();


?>