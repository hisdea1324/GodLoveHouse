<?php
class DataManager {
	public $fieldList;
	public $fieldCount;
	public $valueList;
	public $valueCount;

	public $fieldNList;
	public $fieldNCount;
	public $valueNList;
	public $valueNCount;

	public $tableName;
	public $strCondition;

	public function __construct() {
		$this->fieldList = array();
		$this->fieldCount = -1;
		$this->valueList = array();
		$this->valueCount = -1;
		$this->fieldNList = array();
		$this->fieldNCount = -1;
		$this->valueNList = array();
		$this->valueNCount = -1;
		$this->strCondition = "";
	} 

	public function __destruct() {

	}
}
/*
	function setTable($tName) {
		$this->tableName = $tName;
	} 

	function setField($fList) {
		if ($this->valueCount >= 0 && $this->valueCount != count($fList)) {
			print "Error: DataManager Error 1";
			exit();
		} else {
			$this->fieldList = $fList;
			$this->fieldCount=count($fList);
		} 

	} 

	function setValue($vList) {
		if ($this->fieldCount >= 0 && $this->fieldCount != count($vList)) {
			print "Error: DataManager Error 2";
			exit();

		} else {
			$this->valueList = $vList;
			$this->valueCount=count($vList);
		} 
	} 

	function setFieldNum($fList) {
		if ($this->valueNCount >= 0 && $this->valueNCount != count($fList)) {
			print "Error: DataManager Error 3";
			exit();
		} else {
			$this->fieldNList = $fList;
			$this->fieldNCount=count($fList);
		} 
	} 

	function setValueNum($vList) {
		if ($this->fieldNCount >= 0 && $this->fieldNCount != count($vList)) {
			print "Error: DataManager Error 4";
			exit();
		} else {
			$this->valueNList = $vList;
			$this->valueNCount = count($vList);
		} 

	} 

	function setCondition($strCond) {
		if (strlen($strCond) == 0) {
			print "Error: DataManager Error 5";
			exit();

		} 

		return "WHERE ".$strCond;
	} 

	function insert() {
		checkField();

		if ($this->fieldNCount < 0) {
			$strField = join($this->fieldList, ",");
			$strValue = "'".join($this->valueList, "', '")."'";
		} elseif ($this->fieldCount < 0) {
			$strField = join($this->fieldNList, ",");
			$strValue = join($this->valueNList, ",");
		} else {
			$strField = join($this->fieldList, ",").", ".join($this->fieldNList, ",");
			$strValue = "'".join($this->valueList, "','")."', ".join($this->valueNList, ",");
		} 


		$query = "INSERT INTO ".$this->tableName;
		$query = $query."(".$strField.") VALUES";
		$query = $query."(".$strValue.")";
		print $query;
		$mysqli->execute($query);
	} 

	function update() {
		checkField();
		checkCondition();

		$query = "UPDATE ".$this->tableName." SET ";
		for ($i=0; $i <= $this->fieldCount; $i = $i+1) {
			$query = $query.$this->fieldList[$i]."='".$this->valueList[$i]."', ";
		}

		for ($i=0; $i <= $this->fieldNCount; $i = $i+1) {
			$query = $query.$this->fieldNList[$i]."=".$this->valueNList[$i].", ";
		}

		$query = substr($query, 0, strlen($query) - 2)." ".$this->strCondition();
		$mysqli->execute($query);
	} 

	function delete() {
		checkCondition();
		$query = "DELETE FROM ".$this->tableName." ".$this->strCondition();
		$mysqli->execute($query);
	} 

	function checkField() {
		$check1=false;
		$check2=false;

		if ($this->valueCount < 0 || $this->fieldCount < 0) {
			$check1=true;
		} 

		if ($this->valueNCount < 0 || $this->fieldNCount < 0) {
			$check2=true;
		} 

		if ($check1 && $check2) {
			print "Error: DataManager Error 6";
			exit();
		} 

	} 

	function checkCondition() {
		if (strlen($this->strCondition()) == 0) {
			print "Error: DataManager Error 7";
			exit();
		} 

	} 
	function Check_sql($str) {
		$val = strtoupper($str);
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
*/
?>