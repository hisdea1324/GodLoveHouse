<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");

$tag = trim($_REQUEST["tag"]);
$path = trim($_REQUEST["path"]);
if ((strlen($tag)==0)) {
	upload();
} else {
	uploadForm();
} 


function upload() {	
	$tagName = trim($ABCU["tagName"]);
	$pathName = trim($ABCU["pathName"]);
	$theField = $ABCU["imgFile"];

	if ($theField->FileExists) {
		$fileImage = $uploadFile_upload["/"]["imgFile"][$theField->FileName][0][$pathName];
	} 

	$attach = new AttachFile();
	$attach->UserID = $_SESSION["userId"];
	$attach->Name = $fileImage;
	$attach->Update();
?>
<html>
<head>
<SCRIPT LANGUAGE="JavaScript">
<!--
choice();

function choice() {
	opener.document.getElementById("id<?php echo $tagName;?>").value = "<?php echo $attach->ImageID;?>";
	if (opener.document.getElementById("img<?php echo $tagName;?>") != null) {
		opener.document.getElementById("img<?php echo $tagName;?>").src = "/upload/<?php echo $pathName;?>/<?php echo $attach->Name;?>";
	}
	if (opener.document.getElementById("txt<?php echo $tagName;?>") != null) {
		opener.document.getElementById("txt<?php echo $tagName;?>").value = "<?php echo $attach->Name;?>";
	}
	if (opener.pasteHTMLDemo != null) {
		opener.pasteHTMLDemo();
	}
	window.close();
}
//-->
</SCRIPT>
</head>
</html>
<?php 
	print $attach->ImageID;
	$theField = null;
	$attach = null;
} 

function uploadForm() {	
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
							<input type="hidden" id="tagName" name="tagName" value="<?php echo $tag;?>" />
							<input type="hidden" id="pathName" name="pathName" value="<?php echo $path;?>" />
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
