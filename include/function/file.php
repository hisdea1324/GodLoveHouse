<?php 
function readHtmlFile($fileName) {	
	$readString="";
	# $fs is of type "Scripting.FileSystemObject"
	$objFile = fopen($_SERVER["DOCUMENT_ROOT"].$fileName,"r");
	while (feof($objFile) != true) {
		$readString = $readString.fgets($objFile).chr(13);
	} 
	return $readString;
} 

function makeExcelFile($fileName,$objrs) {
	# $fs is of type "Scripting.FileSystemObject"
	$objFile = fopen($_SERVER["DOCUMENT_ROOT"]."/upload/board".$fileName,"r");

	$tf->write("BTS_ID,PCX_ID,BSC_NO,BTS_NO,BTS_NAME,CLUSTER_NAME,ADDR_PROV,ADDR_SI,ADDR_GU,ADDR_DONG,ADDR_ADDR,BLD_NAME,FA_COUNT,SECTOR_COUNT,PN_A,PN_B,PN_G,BTS_TYPE,LATITUDE,LONGITUDE,BTS_MAKER,ANGLE_A,ANGLE_B,ANGLE_G,PRIVATE_LINE,SID");
	$tf->writeline("");

	$objrs->moveFirst;
	while((!$objrs->eof)) {
		$tf->write($objrs[0].",".$objrs[1].",".$objrs[2].",".$objrs[3].",".$objrs[4].",".$objrs[5].",".$objrs[6].",".$objrs[7].",".$objrs[8].",".$objrs[9].", ");
		$tf->write($objrs[10]." ,".$objrs[11].",".$objrs[12].",".$objrs[13].",".$objrs[14].",".$objrs[15].",".$objrs[16].",".$objrs[17].", ".$objrs[18]." , ".$objrs[19]." ,");
		$tf->write($objrs[20].",".$objrs[21].",".$objrs[22].",".$objrs[23].",".$objrs[24].",".$objrs[25]);
		$tf->writeline("");
		$objrs->movenext;
	} 
} 

function deleteFile($fielName) {
} 
?>
