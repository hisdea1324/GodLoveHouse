<?php 
# url을 parsing하여 dictionary 형식으로 return하는 함수
# { 'protocol', 'domain', 'path', 'query' }
function public() {	
	ParsingURL($sUrl);
	# $parsedURL is of type "Scripting.Dictionary"
	$urlPattern="http:#[a-zA-Z0-9]/([a-zA-Z0-9]/)*([a-zA-Z.])*";

	$pos=(strpos($sUrl,":#") ? strpos($sUrl,":#")+1 : 0)-1;
	if (($pos>-1)) {
		$parsedURL["protocol"]=substr($sUrl,0,$pos+3);
		$sUrl=substr($sUrl,strlen($sUrl)-(strlen($sUrl)-$pos-3));
	} else {
		$parsedURL["protocol"]="";
	} 

	$pos=(strpos($sUrl,"/") ? strpos($sUrl,"/")+1 : 0)-1;
	if (($pos>-1)) {
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

	$ParsingURL = $parsedURL;
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

function StrFormatByLength($str,$length) {
	if ((strlen($str) >= $length)) {
		$retString=substr($str,0,$length-2)."‥";
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

function textFormat($text,$stype) {
	if ((!!isset($text))) {
		switch (($stype)) {
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

function priceFormat($price,$stype) {
	switch (($stype)) {
		case 1:
			$retString="$".number_format($price,2);
			$retString=substr($retString,strlen($retString)-(strlen($retString)-1))."원";
			break;
		case 2:
			$price = $price/1000;
			$retString="$".number_format($price,2);
			$retString=substr($retString,strlen($retString)-(strlen($retString)-1));
			break;
		default:
			$retString="";
			break;
	} 

	return $retString;
} 

function URLEncode($url) {
	$url=str_replace("%","%25",$url);
	$url=str_replace(":","%3A",$url);
	$url=str_replace("/","%2F",$url);
	$url=str_replace(".","%2E",$url);
	$url=str_replace("?","%3F",$url);
	$url=str_replace("=","%3D",$url);
	$url=str_replace("&","%26",$url);
	$url=str_replace("+","%2B",$url);
	$url=str_replace("@","%40",$url);
	$url=str_replace("#","%23",$url);
	$url=str_replace("!","%21",$url);

	return $url;
} 

function URLDecode($url) {
	$url=str_replace("%3A",":",$url);
	$url=str_replace("%2F","/",$url);
	$url=str_replace("%2E",".",$url);
	$url=str_replace("%3F","?",$url);
	$url=str_replace("%3D","=",$url);
	$url=str_replace("%26","&",$url);
	$url=str_replace("%2B","+",$url);
	$url=str_replace("%40","@",$url);
	$url=str_replace("%23","#",$url);
	$url=str_replace("%21","!",$url);
	$url=str_replace("%25","%",$url);

	return $url;
} 

function public() {	
	mssqlEscapeString($str);
	$str=str_replace("'","''",$str); # 특수문자 제거
	return $str;
} 

function public() {	
	mssqlEscapeForLikeSearch($str);
	$str=str_replace("'","''",$str); # 특수문자 제거
	$str=str_replace("%","[%]",$str); # 특수문자 제거
	$str=str_replace("_","[_]",$str); # 특수문자 제거
	return $str;
} 

function Encrypt($string) {
	$string="god".str_repeat("house");
	for ($i=1; $i <= strlen(str_repeat()); $i = $i+1) {
		$x=substr(str_repeat($i));
		$tmp = $tmp.chr(ord($x)+1);

	}

	$tmp=strrev($tmp);
	return $tmp;
} 

function Decrypt($encryptedstring) {
	$encryptedstring=strrev($encryptedstring);
	for ($i=1; $i<=strlen($encryptedstring); $i = $i+1) {
		$x=substr($encryptedstring,$i-1,1);
		$tmp = $tmp.chr(ord($x)-1);

	}

	return $tmp;
} 
?>
