<?php 
function show_request_variable_list() {
	# post 방식으로 넘어오는 object list 출력	
	foreach ($_POST as $objItem) {
		$makestr = $objItem." = ";
		$makestr = $makestr."request(".chr(34).$objItem.chr(34).")";
		$println[$makestr." ': ".$_POST[$objItem]];
	}

	# get 방식으로 넘어오는 object list 출력	
	foreach ($_GET as $objItem) {
		$makestr = $objItem." = ";
		$makestr = $makestr."request(".chr(34).$objItem.chr(34).")";
		$println[$makestr." ': ".$_GET[$objItem]];
	}
} 

# pattern0 = "[^가-힣]"	'한글만
# pattern1 = "[^-0-9 ]"	'숫자만
# pattern2 = "[^-a-zA-Z]"	'영어만
# pattern3 = "[^-가-힣a-zA-Z0-9/ ]" '숫자와 영어 한글만 
# pattern4 = "<[^>]*>"	 '태그만
# pattern5 = "[^-a-zA-Z0-9/ ]"		'영어 숫자만
function public() {	
	RegixCheck($sText,$sPattern);
	$regEx = new RegExp();

	$regEx->Pattern = $sPattern; #  패턴을 설정합니다.	
	$regEx->IgnoreCase=true; #  대/소문자를 구분하지 않도록 합니다.	
	$regEx->Global=true; #  전체 문자열을 검색하도록 설정합니다.
	$matches = $regEx->execute($sText);
	if (($matches->count>0)) {
		return false;
	} else {
		return true;
	} 
} 

function validate_index($value) {
	if ((gettype($value)==2 && $value>=-1)) {
		return true;
	} else {
		return false;
	} 
} 

function validate_boolean($value) {
	if ((gettype($value)==11)) {
		return true;
	} else {
		return false;
	} 
} 

# 반환값 의미 :
# 0 (공백)
# 1 (널)
# 2 integer
# 3 Long
# 4 Single
# 5 Double
# 6 Currency
# 7 Date
# 8 String
# 9 OLE Object
# 10 Error
# 11 Boolean
# 12 Variant
# 13 Non-OLE Object
# 17 Byte
# 8192 Array
?>
