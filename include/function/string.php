<?php 
# ===================================================================
# == 설명 : 허용태그 외의 모든 태그제거 함수
# == 이름 : strip_tags(sContent, sAllowTags)
# == 변수 : sContent(String), sAllowTags(String)
# == 반환 : String
# ===================================================================
function strip_tags($sContent,$sAllowTags) {

	$tags=str_replace(",","|",$sAllowTags);
	$sContent=eregi_replace("<(/?)(?!/|".$tags.")([^<>]*)?>","&lt;$1$2&gt;",$sContent);
	$sContent=eregi_replace("(javascript:|vbscript:)+","$1# ",$sContent);
	$sContent=eregi_replace("(.location|location.|onload=|.cookie|alert(|window.open(|onmouse|onkey|onclick|view-source:)+","# ",$sContent); # # 자바스크립트 실행방지
	return $sContent;
} 

# ===================================================================
# == 설명 : 문장을 해당 자릿수만큼 <br>이 들어간 문장으로 변환
# == 이름 : append_tag_br(sText, iPos)
# == 변수 : sText(String),iPos(Integer)
# == 반환 : String
# ===================================================================
function append_tag_br($sText,$iPos) {
	$iTextLen=strlen($sText);

	if (($iTextLen%$iPos)==0) {
		$iMidCnt = $fix[$iTextLen/$iPos];
	} else {
		$iMidCnt = $fix[$iTextLen/$iPos]+1;
	} 

	for ($i=1; $i <= $iMidCnt; $i = $i+1) {
		if ($i==1) {
			$sTempText = $sTempText.substr($sText,$i-1,$iPos)."<br>";
		} else {
			$sTempText = $sTempText.substr($sText,(($i-1)*$iPos)+1-1,$iPos)."<br>";
		} 
	}
	
	return $sTempText;
} 

# ===================================================================
# == 설명 : text내의 url, mail의 pattern을 찾아서	링크를 넣어준다.
# == 이름 : auto_link(sText)
# == 변수 : sText(String)
# == 반환 : String
# ===================================================================
function auto_link($sText) {	
	$oReg = new RegExp();

	#  First Pass for http	
	$oReg->global=true;
	$oReg->ignoreCase=true;

	#  ASP seems to be not supporting .MultiLine method.
	# oReg.MultiLine = True
	$oReg->pattern="(w+):// ([^/:]+)(:d*b)?([^# n<]*).*n";
	$oReg->pattern="http:// ([0-9a-zA-Z./@:~?&=_-]+)";
	$sText = $oReg->replace($sText);

	#  Second Pass for mail	
	$oReg->Pattern="([_0-9a-zA-Z-]+(.[_0-9a-zA-Z-]+)*)@([0-9a-zA-Z-]+(.[0-9a-zA-Z-]+)*)";
	$sText = $oReg->replace($sText);

	return $sText;
} 

# =================================================================== 
# == 설명 : HTML 태그 지우기 (정규식표현) 
# == 이름 : remove_tag(sHtml, sPattern)
# == 변수 : sHtml(String),sPattern(String) 
# == 반환 : String 
# =================================================================== 
function remove_tag($sHtml,$sPattern) {	
	$oRegExp = new Regexp();

	$oRegExp->IgnoreCase=true;
	$oRegExp->Global=true;
	if ((strlen($sPattern)==0)) {
		$oRegExp->Pattern="<.+?>"; #  태그완전히없앰	
	} else {
		$oRegExp->Pattern = $sPattern;
	} 

	$sOutput = $oRegExp->replace($sHtml);
	return $sOutput;
} 
?>
