<?php 
class DataManager {
	var $fieldList;
	var $fieldCount;
	var $valueList;
	var $valueCount;

	var $fieldNList;
	var $fieldNCount;
	var $valueNList;
	var $valueNCount;

	var $tableName;
	var $strCondition;

	function __construct() {
		$fieldList = array();
		$fieldCount=-1;
		$valueList = array();
		$valueCount=-1;
		$fieldNList = array();
		$fieldNCount=-1;
		$valueNList = array();
		$valueNCount=-1;
		$strCondition="";
	} 

	function __destruct() {

	} 

	function setTable($tName) {
		$tableName = $tName;
	} 

	function setField($fList) {
		if (($valueCount>=0 && $valueCount!=count($fList))) {
			print "Error: DataManager Error 1";
			exit();
		} else {
			$fieldList = $fList;
			$fieldCount=count($fList);
		} 

	} 

	function setValue($vList) {
		if (($fieldCount>=0 && $fieldCount!=count($vList))) {
			print "Error: DataManager Error 2";
			exit();

		} else {
			$valueList = $vList;
			$valueCount=count($vList);
		} 
	} 

	function setFieldNum($fList) {
		if (($valueNCount>=0 && $valueNCount!=count($fList))) {
			print "Error: DataManager Error 3";
			exit();
		} else {
			$fieldNList = $fList;
			$fieldNCount=count($fList);
		} 
	} 

	function setValueNum($vList) {
		if (($fieldNCount>=0 && $fieldNCount!=count($vList))) {
			print "Error: DataManager Error 4";
			exit();
		} else {
			$valueNList = $vList;
			$valueNCount=count($vList);
		} 

	} 

	function setCondition($strCond) {
		if ((strlen($strCond)==0)) {
			print "Error: DataManager Error 5";
			exit();

		} 

		$strCondition="WHERE ".$strCond;
	} 

	function insert() {
		checkField();

		if (($fieldNCount<0)) {
			$strField = $join[$fieldList][","];
			$strValue="'".$join[$valueList]["', '"]."'";
		}
			else
		if (($fieldCount<0)) {
			$strField = $join[$fieldNList][","];
			$strValue = $join[$valueNList][","];
		} else {
			$strField = $join[$fieldList][","].", ".$join[$fieldNList][","];
			$strValue="'".$join[$valueList]["','"]."', ".$join[$valueNList][","];
		} 


		$query = "INSERT INTO ".$tableName;
		$query = $query."(".$strField.") VALUES";
		$query = $query."(".$strValue.")";
		print $query;
		$Rs = $db->Execute($query);
	} 

	function update() {
		checkField();
		checkCondition();

		$query = "UPDATE ".$tableName." SET ";
		for ($i=0; $i <= $fieldCount; $i = $i+1) {
			$query = $query.$fieldList[$i]."='".$valueList[$i]."', ";

		}

		for ($i=0; $i <= $fieldNCount; $i = $i+1) {
			$query = $query.$fieldNList[$i]."=".$valueNList[$i].", ";

		}

		$query=substr($query,0,strlen($query)-2)." ".$strCondition;

		$Rs = $db->Execute($query);
	} 

	function delete() {
		checkCondition();
		$query = "DELETE FROM ".$tableName." ".$strCondition;
		$Rs = $db->Execute($query);
	} 

	function checkField() {
		$check1=false;
		$check2=false;

		if (($valueCount<0 || $fieldCount<0)) {
			$check1=true;
		} 

		if (($valueNCount<0 || $fieldNCount<0)) {
			$check2=true;
		} 

		if (($check1 && $check2)) {
			print "Error: DataManager Error 6";
			exit();

		} 

	} 

	function checkCondition() {
		if ((strlen($strCondition)==0)) {
			print "Error: DataManager Error 7";
			exit();

		} 

	} 

	function Check_sql($str) {
		$val=strtoupper($str);
		if ((strpos($val,";") ? strpos($val,";")+1 : 0)!=0 || 
			 (strpos($val,"@variable") ? strpos($val,"@variable")+1 : 0)!=0 || 
			 (strpos($val,"@@variable") ? strpos($val,"@@variable")+1 : 0)!=0 || 
			 (strpos($val,"+") ? strpos($val,"+")+1 : 0)!=0 || 
			 (strpos($val,"print") ? strpos($val,"print")+1 : 0)!=0 || 
			 (strpos($val,"set") ? strpos($val,"set")+1 : 0)!=0 || 
			 (strpos($val,"%") ? strpos($val,"%")+1 : 0)!=0 || 
			 (strpos($val,"<script>") ? strpos($val,"<script>")+1 : 0)!=0 || 
			 (strpos($val,"<SCRIPT>") ? strpos($val,"<SCRIPT>")+1 : 0)!=0 || 
			 (strpos($val,"script") ? strpos($val,"script")+1 : 0)!=0 || 
			 (strpos($val,"SCRIPT") ? strpos($val,"SCRIPT")+1 : 0)!=0 || 
			 (strpos($val,"or") ? strpos($val,"or")+1 : 0)!=0 || 
			 (strpos($val,"union") ? strpos($val,"union")+1 : 0)!=0 || 
			 (strpos($val,"and") ? strpos($val,"and")+1 : 0)!=0 || 
			 (strpos($val,"insert") ? strpos($val,"insert")+1 : 0)!=0 || 
			 (strpos($val,"openrowset") ? strpos($val,"openrowset")+1 : 0)!=0 || 
			 (strpos($val,"xp_") ? strpos($val,"xp_")+1 : 0)!=0 || 
			 (strpos($val,"decare") ? strpos($val,"decare")+1 : 0)!=0 || 
			 (strpos($val,"select") ? strpos($val,"select")+1 : 0)!=0 || 
			 (strpos($val,"update") ? strpos($val,"update")+1 : 0)!=0 || 
			 (strpos($val,"delete") ? strpos($val,"delete")+1 : 0)!=0 || 
			 (strpos($val,"shutdown") ? strpos($val,"shutdown")+1 : 0)!=0 || 
			 (strpos($val,"drop") ? strpos($val,"drop")+1 : 0)!=0 || 
			 (strpos($val,"--") ? strpos($val,"--")+1 : 0)!=0 || 
			 (strpos($val,"/*") ? strpos($val,"/*")+1 : 0)!=0 || 
			 (strpos($val,"*/") ? strpos($val,"*/")+1 : 0)!=0 || 
			 (strpos($val,"XP_") ? strpos($val,"XP_")+1 : 0)!=0 || 
			 (strpos($val,"DECLARE") ? strpos($val,"DECLARE")+1 : 0)!=0 || 
			 (strpos($val,"SELECT") ? strpos($val,"SELECT")+1 : 0)!=0 || 
			 (strpos($val,"UPDATE") ? strpos($val,"UPDATE")+1 : 0)!=0 || 
			 (strpos($val,"DELETE") ? strpos($val,"DELETE")+1 : 0)!=0 || 
			 (strpos($val,"INSERT") ? strpos($val,"INSERT")+1 : 0)!=0 || 
			 (strpos($val,"SHUTDOWN") ? strpos($val,"SHUTDOWN")+1 : 0)!=0 || 
			 (strpos($val,"DROP") ? strpos($val,"DROP")+1 : 0)!=0) {
			print "에러메세지출력";
			exit();
		} else {
			return $str;
		}
	} 
} 
?>
