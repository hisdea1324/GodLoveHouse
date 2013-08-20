<?php 
# ************************************************************
#  name : CodeHelper Class
#  description
#  		help to use Code Object 
#   	create a Code Object or Code Object List
#  to do : 
# 		1. delete method getCodeListByType 
# 
#  editor : Sookbun Lee 
#  last update date : 2009/12/17
# ************************************************************
class CodeHelper {
	function getCodeName($value) {
		global $mysqli;
		
		$query = "SELECT name from code WHERE code = '{$value}'";
		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_array()) {
				if ($row["name"] ==! null) {
					return $row["name"];
				}
			}
		}
		
		return false;
	} 

	function getSupportCode($index) {
		switch ($index) {
			case 1:
				$value="03001"; #특별후원
				break;
			case 2:
				$value="03002"; #센터후원
				break;
			default:
				$value="03003";
				break;
		} 
		return $value;
	} 

	function getSupportStatusCode($index) {
		switch ($index) {
			case 1:
				$value="S2001";
				break;
			case 2:
				$value="S2002";
				break;
			default:
				$value=false;
				break;
		} 
		return $value;
	} 

	function getCodeListByType($value,$index) {
		$query = "SELECT * from code WHERE type = '{$value}'";
		$codeRS = $db->Execute($query);

		return $codeRS;
	} 

	function getCodeList($query) {
		global $mysqli;
		$return = array();
		
		if ($result = $mysqli->query($query)) {
			while($row = $result->fetch_array()) {
				$codeInfo = new CodeObject();
				$codeInfo->Open($row["code"]);
				$return[] = $codeInfo;
			} 
		} 

		return $return;
	} 

	function getNationCodeList() {
		$query = "SELECT code FROM `code` WHERE type = '선교지' ORDER BY name";
		return $this->getCodeList($query);
	} 

	function getSupportCodeList() {
		$query = "SELECT code FROM `code` WHERE type = '후원타입' ORDER BY name";
		return $this->getCodeList($query);
	} 

	function getLocalCodeList() {
		$query = "SELECT code FROM `code` WHERE type = '지역코드' ORDER BY name";
		return $this->getCodeList($query);
	} 

	function getHouseStatusCodeList() {
		$query = "SELECT code FROM `code` WHERE type = '상태코드2' ORDER BY name";
		return $this->getCodeList($query);
	} 

	function getStatusCodeList() {
		$query = "SELECT code FROM `code` WHERE type = '상태코드' ORDER BY name";
		return $this->getCodeList($query);
	} 
} 
?>