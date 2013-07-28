<?php 
//************************************************************
// Object : MissionaryFamily
//
// editor : Sookbun Lee 
// last update date : 2010.03.04
//************************************************************
class MissionaryFamily {
	var $memberRS;

	var $m_index;
	var $m_userid;
	var $m_name;
	var $m_age;
	var $m_sex;
	var $m_relation;

	// property getter
	//***********************************************
	function familyID() {
		$familyID = $m_index;
	} 

	function UserID() {
		$UserID = $m_userid;
	} 

	function Name() {
		$Name = $m_name;
	} 

	function Age() {
		$Age=intval($m_age);
	} 

	function Sex() {
		$Sex = $m_sex;
	} 

	function Relation() {
		$Relation = $m_relation;
	} 

	// property setter
	//***********************************************
	function familyID($value) {
		$m_index=intval($value);
	} 

	function UserID($value) {
		$m_userid = trim($value);
	} 

	function Name($value) {
		$m_name = trim($value);
	} 

	function Age($value) {
		$m_age=intval($value);
	} 

	function Sex($value) {
		$m_sex = trim($value);
	} 

	function Relation($value) {
		$m_relation = trim($value);
	} 

	// class initialize
	//***********************************************
	function __construct() {
		$m_index=-1;
		$m_userid="";
		$m_name="";
		$m_age=0;
		$m_sex="";
		$m_relation="";
	} 

	function __destruct() {
		$memberRS = null;
	} 

	function Open($id) {
		$query = "SELECT * from missionary_family WHERE id = '".$mssqlEscapeString[$id]."'";
		$memberRS = $objDB->execute_query($query);
		if ((!$memberRS->eof && !$memberRS->bof)) {
			$m_index=intval($memberRS["id"]);
			$m_userid = $memberRS["userID"];
			$m_name = $memberRS["name"];
			$m_age=intval($memberRS["age"]);
			$m_sex = $memberRS["sex"];
			$m_relation = $memberRS["relation"];
		} 
	} 

	function Update() {
		if (($m_index==-1)) {
			//New Data
			$query = "INSERT INTO missionary_family (userId, name, age, sex, relation) VALUES ";
			$insertData="'".$mssqlEscapeString[$m_userid]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_name]."', ";
			$insertData = $insertData."'".$m_age."', ";
			$insertData = $insertData."'".$m_sex."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_relation]."'";
			$query = $query."(".$insertData.") ";
			$objDB->execute_command($query);

			$query = "SELECT MAX(id) AS new_id FROM missionary_family WHERE userId = '".$mssqlEscapeString[$m_userid]."'";
			$missionRS = $objDB->execute_query($query);
			if ((!$missionRS->eof && !$missionRS->bof)) {
				$m_index=intval($missionRS["new_id"]);
			} 
		} else {
			$query = "UPDATE missionary_family SET ";
			$updateData=" userId = '".$mssqlEscapeString[$m_userid]."', ";
			$updateData = $updateData." name = '".$mssqlEscapeString[$m_name]."', ";
			$updateData = $updateData." age = '".$m_age."', ";
			$updateData = $updateData." sex = '".$m_sex."', ";
			$updateData = $updateData." relation = '".$mssqlEscapeString[$m_relation]."'";
			$query = $query.$updateData." WHERE id = '".$m_index."'";
			$objDB->execute_command($query);
		} 
	} 

	function Delete() {
		if (($m_index>-1)) {
			$query = "DELETE from missionary_family WHERE id = '".$mssqlEscapeString[$m_index]."'";
			$objDB->execute_command($query);
		} 
	} 

} 
?>
