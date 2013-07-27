<?php 
# ===================================================================
# == 설명 : db error message 출력 
# == 이름 : print_error_msg(oErr)
# == 변수 : oErr(Object)
# == 반환 : none
# ===================================================================
function public() {	
	print_error_msg($oErr);

	foreach ($oErr->Errors as $errLoop) {
		println("#############");
		println("Error Number: ".$errLoop->Number);
		println("Description: ".$errLoop->Description);
		println("Source: ".$errLoop->Source);
		println("SQL State: ".$errLoop->SQLState);
		println("Native Error: ".$errLoop->NativeError);
		println("#############");
	}
} 

# ===================================================================
# == 설명 : debug moode일때 DB에 쿼리문 입력
# == 이름 : write_query_in_debug(sQuery)
# == 변수 : sQuery(String) 
# == 반환 : none 
# ===================================================================
function public() {	
	write_query_in_debug($sQuery);
	if (($Application["QueryDebug"])) {
		$db->Execute("INSERT INTO debug_query_list (query) VALUES ('".$mssqlEscapeString[$sQuery]."')");
	} 
} 

# ===================================================================
# == 설명 : Request Object 출력
# == 이름 : print_request_object(oReq)
# == 변수 : oReq(object) 
# == 반환 : none 
# =================================================================== 
function public() {	
	print_request_object($oReq);

	foreach ($oReq as $item) {
		println("#############");
		println($item." : ".$oReq[$item]);
		println("#############");
	}
} 

# =================================================================== 
# == 설명 : status 별 error 메세지 출력 후 프로세스 종료
# == 이름 : error_message(iStatus)
# == 변수 : iStatus(integer)
# == 반환 : none 
# ===================================================================
function public() {	
	error_message($iStatus);
	switch (($iStatus)) {
		case 101:
			println("Error: DataManager Error 1");
			break;
		case 102:
			println("Error: DataManager Error 2");
			break;
		case 103:
			println("Error: DataManager Error 3");
			break;
		case 104:
			println("Error: DataManager Error 4");
			break;
		case 105:
			println("Error: DataManager Error 5");
			break;
		case 106:
			println("Error: DataManager Error 6");
			break;
		case 107:
			println("Error: DataManager Error 7");
			break;
		case 108:
			println("에러메세지출력");
			break;
		case 500:
			println("잘못된 접근");
			break;
		default:
			println("undefined error");
			break;
	} 
	close();
} 

# ===================================================================
# == 설명 : html 출력을 위해 text에 <br>태그를 붙여서 출력
# == 이름 : println(sText)
# == 변수 : sText(String)
# == 반환 : none 
# =================================================================== 
function public() {	
	println($sText);
	print $sText."<br>";
} 

# =================================================================== 
# == 설명 : 프로세스 종료
# == 이름 : close()
# == 변수 : none
# == 반환 : none 
# =================================================================== 
function public() {	
	close();
	exit();
} 
?>
