<?php
	//session_start();
	//session_register("userid_session");
	//session_register("userid_session");
	//session_register("UserLv_session");
	//session_register("userid_session");
	//header("Content-type: "."text/html;charset=utf-8");

	global $Application;
	$Application["FileRoot"]="F:\\HOME\\hisdea1324\\www\\";
	$Application["WebRoot"]="localhost";
	$Application["Title"]="::: God's LoveHouse :::";
	$Application["Charset"]="utf-8";

	$Application["pass"]="root";
	$Application["user"]="root";
	$Application["db"]="mysql";
	$Application["server"]="localhost";

	$Application["ex_pass"]="root";
	$Application["ex_user"]="root";
	$Application["ex_db"]="mysql";
	$Application["ex_server"]="localhost";

	$Application["QueryDebug"]=true;
	
	$_SERVER["TEST_IP"] = "59.13.120.79";
?>
