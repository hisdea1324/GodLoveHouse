<?php 
# ************************************************************
#  Object : CodeObject
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************
class CodeObject {
	var $rs_code;

	var $m_index;
	var $m_code;
	var $m_ctype;
	var $m_name;

	#  Get property
	# ***********************************************
	function Code() {
		$Code = $m_code;
	} 

	function CodeType() {
		$CodeType = $m_ctype;
	} 

	function Name() {
		$Name = $m_name;
	} 

	#  Set property
	# ***********************************************
	function Code($value) {
		if ((strlen(trim($value))==5)) {
			$m_code = trim($value);
		} 
	} 

	function CodeType($value) {
		$m_ctype = trim($value);
	} 

	function Name($value) {
		$m_name = trim($value);
	} 

	function __construct() {
		$m_index=-1;
		$m_code="";
		$m_ctype="";
		$m_name="";
	} 

	function __destruct() {
		$rs_code = null;

	} 

	function OpenQuery($sQuery) {
		$rs_code = $objDB->execute_query($sQuery);

		if ((!$rs_code->eof && !$rs_code->bof)) {
			$m_index=intval($rs_code["id"]);
			$m_code = $rs_code["code"];
			$m_ctype = $rs_code["type"];
			$m_name = $rs_code["name"];
		} 

	} 

	function Open($value) {
		$query = "SELECT * from code WHERE code = '".$mssqlEscapeString[$value]."'";
		$me->OpenQuery($query);
	} 

	function OpenById($value) {
		$query = "SELECT * from code WHERE id = '".$mssqlEscapeString[$value]."'";
		$me->OpenQuery($query);
	} 

	function Update() {
		if (($m_index==-1)) {
			# New Data
			$query = "INSERT INTO code (code, type, name) VALUES ";
			$insertData="'".$mssqlEscapeString[$m_code]."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_ctype]."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_name]."'";
			$query = $query."(".$insertData.")";
			$objDB->execute_command($query);

			$query = "SELECT MAX(id) AS new_id FROM code WHERE code = '".$mssqlEscapeString[$m_code]."'";
			$rs_code = $objDB->execute_query($query);
			if ((!$rs_code->eof && !$rs_code->bof)) {
				$m_index=intval($rs_code["new_id"]);
			} 
		} else {
			$query = "UPDATE code SET ";
			$updateData="code = '".$mssqlEscapeString[$m_code]."', ";
			$updateData = $updateData."type = '".$mssqlEscapeString[$m_ctype]."', ";
			$updateData = $updateData."name = '".$mssqlEscapeString[$m_name]."' ";
			$query = $query.$updateData." WHERE id = ".$m_index;
			$objDB->execute_command($query);
		} 
	} 

	function Delete() {
		if (($m_index>-1)) {
			$query = "DELETE FROM code WHERE id = '".$m_index."'";
			$objDB->execute_command($query);
		} 
	} 
} 
?>
