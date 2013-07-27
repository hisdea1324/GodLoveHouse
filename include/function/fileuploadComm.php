<OBJECT RUNAT="Server" SCOPE="PAGE" PROGID="Scripting.FileSystemObject" ID="fFSO"></OBJECT>
<OBJECT RUNAT="Server" SCOPE="PAGE" PROGID=ABCUpload4.XForm id=ABCU></OBJECT>
<?php 
set_time_limit(10000); //10000
$ABCU->CodePage = 65001;
$ABCU->MaxUploadSize = 1024*1024*100;
$ABCU->Overwrite=true; 
$ABCU->AbsolutePath=true; 
function uploadFile_msg($m) {
	$db->Close;
	$db = null;
?>
	<SCRIPT LANGUAGE=javascript>
	<!--
		alert("<?php echo $m;?>");
		history.back();
	//-->
	</SCRIPT>
<?php 
	exit();
} 

function uploadFile_available($maxFileSize,$fileKind,$fileObject) {
	$checkFile = $ABCU[$fileObject];
	$reValue=true;

	if ($checkFile->FileExists) {
		$strFileName = $checkFile->SafeFileName;
		$strExt=explode(".",$strFileName);
		$Ext=strtolower($strExt[count($strExt)]);
		$Size=intval($checkFile->Length); 
		$permitFile="jpg, jpeg, bmp, gif, txt, ppt, doc, exl, hwp, pdf, avi, mp3, alx, asf, asx, axv, wav, zip, alz, htm, html ";
		$permitImgFile="jpg, jpeg, gif ";
		$notPermitFile="exe, dll, asp, jsp, php";

		if ($Size>intval($maxFileSize*(1024*1024*100))) {
			uploadFile_msg("a ".$maxFileSize."MB b.\\n\\n ".$maxFileSize."MB c.");
			$reValue=false;
		} 


		switch ($fileKind) {
			case "image":
				if ((strpos($permitImgFile,$Ext) ? strpos($permitImgFile,$Ext)+1 : 0)) {
				} else {
					uploadFile_msg("d.");
					$reValue=false;
				} 

				break;
			case "img":
				if ((strpos($permitFile,$Ext) ? strpos($permitFile,$Ext)+1 : 0)) {
				} else {
					uploadFile_msg("e.");
					$reValue=false;
				} 

				break;
			case "etc":
				if ((strpos($notPermitFile,$Ext) ? strpos($notPermitFile,$Ext)+1 : 0)) {
					uploadFile_msg("f.");
					$reValue=false;
				} 

				break;
			default:
				$reValue=false;
				break;
		} 
	} else {
		$reValue=false;
	} 

	$checkFile = null;

	return $reValue;
} 

function uploadFile_upload($fileDir,$fileObject,$gfileName,$overWriteCd,$sub_path) {
	$oattFile = $ABCU[$fileObject];

	if ($oattFile->FileExists) {
		$strFileName = $oattFile->SafeFileName;
		$strExt=explode(".",$strFileName);
		$Ext=strtolower($strExt[count($strExt)]);
		$Size=intval($oattFile->Length); // 파일 사이즈
		if ($overWriteCd==1) {
			$fileName = $DOCUMENT_ROOT.$fileDir."\\upload\\".$sub_path."\\".$gfileName.".".$Ext;
			$ofileName = $gfileName.".".$Ext;
		} else {
			$fileName = $DOCUMENT_ROOT.$fileDir."\\upload\\".$sub_path."\\".$strFileName;
			if ($fFSO->FileExists($fileName)) {
				$count=0;
				while($fFSO->FileExists($fileName)) {
					$count = $count+1;
					$fileName = $DOCUMENT_ROOT.$fileDir."\\upload\\".$sub_path."\\".($count).$strFileName;
				} 
				$ofileName=($count).$strFileName;
			} else {
				$ofileName = $strFileName;
			} 
		} 
		$oattFile->Save($fileName);
	} else {
		$ofileName="";
	} 

	$oattFile = null;
	return $ofileName;
} 

function uploadFile_deleteFile($fileDir,$fileName,$sub_path) {
	$fileName = $DOCUMENT_ROOT.$fileDir."\\upload\\".$sub_path."\\".$fileName;
	if ($fFSO->FileExists($fileName)) {
		$fFSO->DeleteFile($fileName);
	} 
} 
?>
