<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

$dong = (isset($_REQUEST["dong"])) ? $_REQUEST["dong"] : "";
$zipListTemplete = "		<tr><td width='17%' bgcolor='f5f5f5'> [zip1] - [zip2] </td><td width='83%' bgcolor='#F5F5F5'> <a href=\"javascript:sendzip('[zip1]','[zip2]','[addr2]')\"><font color='#6A6A6A'>[addr1]</font></a> </td>	</tr>";

$dong = preg_replace('/(\d*)(가|동|로|면|읍)$/', '', $dong);

if ($dong > "") {
	global $mysqli;
	$query = "SELECT zipcode, sido, gugun, dong, bunji, note FROM zipcode WHERE dong LIKE '%".mysql_escape_string($dong)."%'";
	
	$dongList = "";
	if ($result = $mysqli->query($query)) {
		while ($row = $result->fetch_array()) {
			$zipcode = explode('-', $row["zipcode"]);
			$addr1 = trim($row["sido"])." ".trim($row["gugun"])." ".trim($row["dong"])." ".trim($row["bunji"]);
			$addr2 = trim($row["sido"])." ".trim($row["gugun"])." ".trim($row["dong"]);
			$addr1 = trim($addr1);
			$addr2 = trim($addr2);
	
			$dongList = $dongList.str_replace("[addr2]",$addr2,str_replace("[addr1]",$addr1,str_replace("[zip2]",$zipcode[1],str_replace("[zip1]",$zipcode[0],$zipListTemplete))));
		} 
	}
} 

if (!isset($dongList)) {
	$dongList="	<tr bgcolor='f5f5f5'><td align='center'><font color='#666666'>검색된 주소가 없습니다</font></td></tr>";
} 
?>
<HTML>
<HEAD>
<TITLE> 우편번호 검색 </TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8">
<link rel='StyleSheet' HREF='/include/css/pop_style2.css' type='text/css' title='CSS'>
<meta http-equiv="imagetoolbar" content="no">
<script language='javascript'>
<!--
function sendzip(zip1, zip2, addr){
	opener.document.getElementById("post1").value = zip1;
	opener.document.getElementById("post2").value = zip2;
	opener.document.getElementById("addr1").value = addr;
	opener.document.getElementById("addr2").focus();
	window.close();
}

function addrM() {
	document.getElementById("addrForm").action = 'findPost.php';
	document.getElementById("addrForm").submit();
}
//-->
</SCRIPT>
</HEAD>
<body onselectstart="return false" ondragstart="return false" onload="document.addrForm.dong.focus()">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr style="height:5"><td bgcolor="EEB41B"></td></tr>
	<tr>
		<td align="center" valign="top">
			<table border="0" cellpadding="8" cellspacing="0" width="100%">
				<tr>
					<td><img src="/images/common/title_zipcode.gif"></td>
				</tr>
				<tr>
					<td align="center">
						<table border="0" cellpadding="6" cellspacing="1" width="100%" bgcolor="e0e0e0">
							<tr>
								<td bgcolor="f5f5f5" align="center" class="ls small">
									<table border="0" cellpadding="6" cellspacing="0">
									<form id="addrForm" name="addrForm" method="post">
										<tr>
											<td><B class="ls">동/읍/면 입력</B></td>
											<td><INPUT TYPE="text" style="IME-MODE:active" id="dong" name="dong" style="width:150;"></td>
											<td><A HREF="#"><img src="/images/common/btn_ok.gif" width="64" height="22" style="cursor:hand" onClick="addrM()"></A></td>
										</tr>
										</form>
									</table>
									(동/읍/면을 입력하세요. 예를 들어 "강남구 역삼동" 이면, "역삼동"만 입력하세요.)
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center" valign="top" >
						<div style="OVERFLOW-Y: auto; WIDTH: 430px; HEIGHT: 350px;" >
						<table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="e0e0e0">
							<tr>
								<td bgcolor="f5f5f5" align="center" valign="top" >
									<table width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#F5F5F5" align="center">
									<?php echo $dongList;?>
									</table>
								</td>
							</tr>
						</table>
						</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</BODY>
</HTML>

