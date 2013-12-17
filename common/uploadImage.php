<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

$upload = isset($_REQUEST["upload"]) ? trim($_REQUEST["upload"]) : "";
$tag = isset($_REQUEST["tag"]) ? trim($_REQUEST["tag"]) : "";
$path = isset($_REQUEST["path"]) ? trim($_REQUEST["path"]) : "";

if ($upload == "true") {
	upload();
} else {
	uploadForm();
}

function upload() {	
	global $path, $tag;

	$count = "";
	do {
		$uploaddir = "/home/hosting_users/godlovehouse/www/upload/$path/";
		$uploadfile = $uploaddir.basename($count.$_FILES['imgFile']['name']);
		$filename = $count.$_FILES['imgFile']['name'];
		$count += 1;
	} while (file_exists($uploadfile));
	
	
	echo '<pre>';
	if (move_uploaded_file($_FILES['imgFile']['tmp_name'], $uploadfile)) {
	    echo "파일이 유효하고, 성공적으로 업로드 되었습니다.\n";
	} else {
	    print "파일 업로드 공격의 가능성이 있습니다!\n";
	}

	// echo '자세한 디버깅 정보입니다:';
	// print_r($_FILES);

	print "</pre>";

	$attach = new AttachFile();
	$attach->userid = $_SESSION["userid"];
	$attach->Name = $filename;
	$attach->Update();
?>
<html>
<head>
<SCRIPT LANGUAGE="JavaScript">
<!--
choice();

function choice() {
	opener.document.getElementById("id<?=$tag?>").value = "<?=$attach->ImageID?>";
	if (opener.document.getElementById("img<?=$tag?>") != null) {
		opener.document.getElementById("img<?=$tag?>").src = "/upload/<?=$path?>/<?=$attach->Name?>";
	}
	if (opener.document.getElementById("txt<?=$tag?>") != null) {
		opener.document.getElementById("txt<?=$tag?>").value = "<?=$attach->Name?>";
	}
	/*
	if (opener.pasteHTMLDemo != null) {
		opener.pasteHTMLDemo();
	}
	*/
	window.close();
}
//-->
</SCRIPT>
</head>
</html>
<?php 
	print $attach->ImageID;
} 

function uploadForm() {	
	global $tag, $path;
?>
<html>
<head>
<title> GodLoveHouse - 이미지 업로드 하기 </title>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html;charset=utf-8">
<link rel='StyleSheet' HREF='/include/css/pop_style.css' type='text/css' title='CSS'>
<meta http-equiv="imagetoolbar" content="no">
</head>
<body onselectstart="return false" ondragstart="return false">
<table border="0" cellpadding="0" cellspacing="0" width="100%" style="height:100%">
	<tr style="height:5">
		<td bgcolor="EEB41B"></td>
	</tr>
	<tr>
		<td align="center" valign="top">
			<table border="0" cellpadding="8" cellspacing="0" width="100%">
				<tr>
					<td align="center">
						<table border="0" cellpadding="0" cellspacing="1" width="100%" bgcolor="e0e0e0">
							<form id="frmUploadImage" name="frmUploadImage" method="post" enctype="multipart/form-data" onSubmit="return checkFile();">
							<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
							<input type="hidden" id="upload" name="upload" value="true" />
							<input type="hidden" id="tag" name="tag" value="<?=$tag?>" />
							<input type="hidden" id="path" name="path" value="<?=$path?>" />
							<tr>
								<td bgcolor="f5f5f5" align="center">
									<table border="0" cellpadding="6" cellspacing="0" width="100%">
										<tr>
											<td width="25%"><b class="ls">파일 선택</b></td>
											<td><input type="file" id="imgFile" name="imgFile" /></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td align="center"><input type="submit" value="업로드" style="cursor:pointer"></td>
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
function checkFile() {
	var object = document.getElementById("imgFile");

	if(object.value == "") {
		alert("파일을 선택되지 않았습니다.");
		object.focus();
		return false;
	}
	// 이미지 확장자명 체크

	document.getElementById("frmUploadImage").action="uploadImage.php";
	document.getElementById("frmUploadImage").submit();
}
//-->
</SCRIPT>
</HTML>
<?php 
} 
?>
