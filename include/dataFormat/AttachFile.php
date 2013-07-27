<?php 
# ************************************************************
#  Object : AttachFile
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************
class AttachFile {
	var $rs_image;

	var $m_index;
	var $m_userid;
	var $m_name;

	#  Get property
	# ***********************************************
	function ImageID() {
		$ImageID=intval($m_index);
	} 

	function UserID() {
		$UserID = $m_userid;
	} 

	function Name() {
		$Name = $m_name;
	} 

	#  Set property
	# ***********************************************
	function ImageID($value) {
		$m_index=intval($value);
	} 

	function UserID($value) {
		$m_userid = trim($value);
	} 

	function Name($value) {
		$m_name = trim($value);
	} 

	function __construct() {
		$m_index=-1;
		$m_userid="";
		$m_name="";
	} 

	function __destruct() {
		$rs_image = null;

	} 

	function Open($imageId) {
		$query = "SELECT * from attachFile WHERE id = '".$mssqlEscapeString[$imageId]."'";
		$rs_image = $objDB->execute_query($query);

		if ((!$rs_image->eof && !$rs_image->bof)) {
			$m_index=intval($rs_image["id"]);
			$m_userid = $rs_image["userid"];
			$m_name = $rs_image["name"];
		} 
	} 

	function Update() {
		if (($m_index==-1)) {
			# New Data
			$query = "INSERT INTO attachFile (userId, name) VALUES ";
			$insertData="'".$m_userid."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_name]."'";
			$query = $query."(".$insertData.")";
			$objDB->execute_command($query);

			$query = "SELECT MAX(id) AS new_id FROM attachFile WHERE userId = '".$m_userId."'";
			$rs_image = $objDB->execute_query($query);
			if ((!$rs_image->eof && !$rs_image->bof)) {
				$m_index=intval($rs_image["new_id"]);
			} 

		} else {
			$query = "UPDATE attachFile SET ";
			$updateData="userId = '".$m_userid."', ";
			$updateData = $updateData."name = '".$mssqlEscapeString[$m_name]."' ";
			$query = $query.$updateData." WHERE id = ".$m_index;
			$objDB->execute_command($query);
		} 

	} 

	function Delete() {
		if (($m_index>-1)) {
			$query = "DELETE FROM attachFile WHERE id = ".$mssqlEscapeString[$m_index];
			$objDB->execute_command($query);
		} 

	} 
} 
?>
