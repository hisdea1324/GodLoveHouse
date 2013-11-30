<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
checkUserLogin();

$mode = isset($_REQUEST["mode"]) ? trim($_REQUEST["mode"]) : "";
$checkuserid = isset($_REQUEST["userid"]) ? trim($_REQUEST["userid"]) : "";
$user_list = array();

if (($mode == "search") && (strlen($checkuserid) > 1)) {
	global $mysqli;
	$query = "SELECT * FROM users WHERE userid like '%".$mysqli->real_escape_string($checkuserid)."%'";
	if ($result = $mysqli->query($query)) {
		while ($row = $result->fetch_assoc()) {
			array_push($user_list, $row);
		}
	} 
} 

?>

<HTML>
<HEAD>
<TITLE> GodLoveHouse - 회원아이디 찾기 </TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html;charset=utf-8">
<link rel='StyleSheet' HREF='/include/css/pop_style.css' type='text/css' title='CSS'>
<meta http-equiv="imagetoolbar" content="no">
<body onselectstart="return false" ondragstart="return false">
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="height:100%">
	<tr style="height:5">
		<td bgcolor="EEB41B"></td>
	</tr>
	<tr>
		<td align="center" valign="top">
			<div style="margin-top:10px">
			</div>
			<table border="0" cellpadding="8" cellspacing="0" width="100%">
				<tr>
					<td><img src="/images/common/popup_tab_idsearch.gif" width="68" height="16"></td>
				</tr>
				<tr>
					<td align="center">
					
					<?php if ($mode=="") { ?>
					<table border="0" cellpadding="6" cellspacing="1" width="100%" bgcolor="e0e0e0">
						<tr>
							<td bgcolor="f5f5f5" align="center" class="ls">아이디를 입력하신 후 검색버튼을 눌러주십시요</td>
						</tr>
					</table>
<?php } else if (count($user_list) > 0) { ?>
					<table border="0" cellpadding="6" cellspacing="1" width="100%" bgcolor="e0e0e0">
						<? foreach ($user_list as $user) { ?>
						<tr>
							<td bgcolor="f5f5f5" align="center" class="ls">
								<a href="javascript:choice('<?=$user['userid']?>','<?=$user['name']?>','<?=$user['mobile']?>')"><font color="#A5423D"><b><?=$user['userid']?></b></font> <?=$user['name']?> [선택]</a>
							</td>
						</tr>
						<? } ?>
					</table>
<?php } else { ?>
					<table border="0" cellpadding="6" cellspacing="1" width="100%" bgcolor="e0e0e0">
						<tr>
							<td bgcolor="f5f5f5" align="center" class="ls">아이디 <font color="#A5423D"><b><?php echo $checkuserid;?></b></font> 검색 결과가 없습니다.</td>
						</tr>
					</table>
<?php } ?>
					</td>
				</tr>
				<tr>
					<td align="center">
						<table border="0" cellpadding="0" cellspacing="1" width="100%" bgcolor="e0e0e0">
							<form id="searchId" name="searchId" method="post" onsubmit="return doSearch();">
							<tr>
								<td bgcolor="f5f5f5" align="center">
									<table border="0" cellpadding="6" cellspacing="0">
										<tr>
											<td><b class="ls">아이디 검색</b></td>
											<td><input type="text" id="userid" name="userid" style="ime-mode:disabled;width:150;" /></td>
											<td><img src="/images/btn_find.gif" alt="아이디검색" width="54" height="20" border="0" onClick="doSearch()" style="cursor:hand"></td>
										</tr>
									</table>
								</td>
							</tr>
							</form>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

</BODY>
<SCRIPT LANGUAGE="JavaScript">
<!--
document.getElementById("userid").focus();

function doSearch() {
	var object = document.getElementById("userid");
	if (object.value == "") {
		alert("아이디를 미입력하셨거나 숫자만 입력하셨습니다.");
		object.focus();
		return false;
	}

	if(object.value.length < 2 || object.value.length >12){
		alert("아이디는 2~12자 사이로 입력하세요!");
		object.focus();
		return false;
	}

	for (i = 0 ; i < object.value.length ; i++) {
		sko = object.value.charAt(i);
		if ((sko < '0' || sko > '9')&&(sko < 'a' || sko > 'z')) {
			alert("영문 소문자와 숫자만 입력하세요!");
			object.focus();
			return false;
		}
	}

	document.getElementById("searchId").action="searchUser.php?mode=search";
	document.getElementById("searchId").submit();
	return true;
}

function choice(userid, name, phone){
	opener.document.getElementById("userid").value = userid;
	opener.document.getElementById("resv_name").value = name;
	opener.document.getElementById("resv_phone").value = phone;
	window.close();
}

//-->
</SCRIPT>
</HTML>