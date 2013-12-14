<?php 
# url을 parsing하여 dictionary 형식으로 return하는 함수
# { 'protocol', 'domain', 'path', 'query' }
function ParsingURL($sUrl) {
	# $parsedURL is of type "Scripting.Dictionary"
	$urlPattern="http://[a-zA-Z0-9]/([a-zA-Z0-9]/)*([a-zA-Z.])*";

	$pos = (strpos($sUrl,"://") ? strpos($sUrl,"://")+1 : 0)-1;
	if ($pos>-1) {
		$parsedURL["protocol"]=substr($sUrl,0,$pos+3);
		$sUrl=substr($sUrl,strlen($sUrl)-(strlen($sUrl)-$pos-3));
	} else {
		$parsedURL["protocol"]="";
	} 

	$pos=(strpos($sUrl,"/") ? strpos($sUrl,"/")+1 : 0)-1;
	if ($pos>-1) {
		$parsedURL["domain"]=substr($sUrl,0,$pos);
		$sUrl=substr($sUrl,strlen($sUrl)-(strlen($sUrl)-$pos));
	} else {
		$parsedURL["domain"] = $sUrl;
		$sUrl="";
	} 

	$pos=(strpos($sUrl,"?") ? strpos($sUrl,"?")+1 : 0)-1;
	if (($pos>-1)) {
		$parsedURL["path"]=substr($sUrl,0,$pos);
		$sUrl=substr($sUrl,strlen($sUrl)-(strlen($sUrl)-$pos));
		$parsedURL["query"] = $sUrl;
	} else {
		$parsedURL["path"] = $sUrl;
		$parsedURL["query"]="";
	} 

	return $parsedURL;
} 

function replace_sp($str) {
	$reValue = $str;

	$revalue=str_replace("<","&lt;",$revalue);
	$revalue=str_replace(">","&gt;",$revalue);
	$revalue=str_replace("'","''",$revalue);

	return $reValue;
} 

function titleFormat($title,$length) {
	$retString=StrFormatByLength($title,$length);
	$retString=str_replace("[RE]","☞",$retString);
	return $retString;
} 

function StrFormatByLength($str, $length) {
	if (strlen($str) >= $length) {
		$retString = mb_substr($str, 0, $length - 2, 'UTF-8')."‥";
	} else {
		$retString = $str;
	}

	return $retString;
} 

function dateFormat($sdate,$stype) {
	if ((!!isset($sdate))) {
		switch (($stype)) {
			case 1:
				$retString=str_replace("-",".",substr($sdate,0,10));
				break;
			case 2:
				$retString=substr($sdate,0,10);
				break;
			default:
				$retString="";
				break;
		} 
	} else {
		$retString="날짜없음";
	} 

	return $retString;
} 

function textFormat($text, $stype) {
	if (isset($text)) {
		switch ($stype) {
			case 1:
				# CR&LF만 <br>로 수정	
				$retString=str_replace(chr(13),"<br>",$text);
				$retString=str_replace(" ","&nbsp;",$retString);
				break;
			case 2:
				# No Use Html Code	
				$retString=str_replace("<br>",chr(13),$text);
				$retString=str_replace("&nbsp;"," ",$retString);
				break;
			default:
				$retString="";
				break;
		} 
	} else {
		$retString = $text;
	} 

	return $retString;
} 

function priceFormat($price, $stype) {
	switch ($stype) {
		case 1:
			$retString = "$".number_format($price, 2);
			$retString = substr($retString,strlen($retString)-(strlen($retString)-1))."원";
			break;
		case 2:
			$price = $price / 1000;
			$retString="$".number_format($price,2);
			$retString=substr($retString,strlen($retString)-(strlen($retString)-1));
			break;
		default:
			$retString="";
			break;
	} 

	return $retString;
} 

function dateToTimestamp($date) {
	if (strlen($date) == 8) {
		$y = substr($date, 0, 4);
		$m = substr($date, 4, 2);
		$d = substr($date, 6, 2);
		return mktime(0, 0, 0, $m, $d, $y);
	}

	return -1;
}

function mssqlEscapeString($str) {
	$str=str_replace("'","''",$str); # 특수문자 제거
	return $str;
} 

function mssqlEscapeForLikeSearch($str) {
	$str=str_replace("'","''",$str); # 특수문자 제거
	$str=str_replace("%","[%]",$str); # 특수문자 제거
	$str=str_replace("_","[_]",$str); # 특수문자 제거
	return $str;
} 

// function Encrypt(byval string)
// 	dim x, i, tmp
// 	string = "god" & string & "house"
// 	for i = 1 to len( string )
// 		x = mid( string, i, 1 )
// 		tmp = tmp & chr( asc( x ) + 1 )
// 	Next
// 	tmp = StrReverse( tmp )
// 	Encrypt = tmp
// end function

function Encrypt($str) {
	$tmp = "";
	$str = "god".$str."house";
	for ($i = 0; $i < strlen($str); $i++) {
		$x = substr($str, $i, 1);
		$tmp = $tmp.chr(ord($x) + 1);
	}

	$tmp = strrev($tmp);
	return $tmp;
} 

// function Decrypt(byval encryptedstring)
// 	dim x, i, tmp
// 	encryptedstring = strreverse( encryptedstring )
// 	for i = 1 to len( encryptedstring )
// 		x = mid( encryptedstring, i, 1 )
// 		tmp = tmp & chr( asc( x ) - 1 )
// 	next
// 	Decrypt = tmp
// end function

function Decrypt($encryptedstring) {
	$tmp = "";
	$encryptedstring = strrev($encryptedstring);
	for ($i = 1; $i <= strlen($encryptedstring); $i++) {
		$x = substr($encryptedstring, $i - 1, 1);
		$tmp = $tmp.chr(ord($x)-1);
	}

	$tmp = substr($tmp, 3);
	$tmp = substr($tmp, 0, strlen($tmp) - 5);

	return $tmp;
} 
?>
