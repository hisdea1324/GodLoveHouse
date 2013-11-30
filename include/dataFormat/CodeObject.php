<?php 
# ************************************************************
#  Object : CodeObject
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************
class CodeObject {
	protected $record = array();

	public function __set($name,$value) { 
		switch ($name) {
			case 'Code':
				$this->record['code'] = $value;
				break;
			case 'Name':
				$this->record['name'] = $value;
				break;
			case 'CodeType':
				$this->record['type'] = $value;
				break;
			default:
				$this->record[$name] = $value;
		}
	}

	public function __get($name) { 
		switch ($name) {
			case 'Code':
				return $this->record['code'];
			case 'Name':
				return $this->record['name'];
			case 'CodeType':
				return $this->record['type'];
			default:
				return $this->record[$name];
		}
	}

	public function __isset($name) {
		return isset($this->record[$name]); 
    }

    function __construct($value = "", $field = "code") {
		$this->initialize();
		if ($value != "") {
			$this->Open($field, $value);
		}
	}

    function initialize() {
		$this->id = -1;
		$this->code = "";
		$this->type = "";
		$this->name = "";
	}

	function Open($field, $value) {
		global $mysqli;

		$query = "SELECT * FROM code WHERE `".$mysqli->real_escape_string($field)."` = '".$mysqli->real_escape_string($value)."'";

		$result = $mysqli->query($query);
		if (!$result) return;
		
		while ($row = $result->fetch_assoc()) {
			$this->id = $row['id'];
			$this->code = $row['code'];
			$this->name = $row['name'];
			$this->type = $row['type'];
		}
		
		$result->close();
	}

	function Update() {
		global $mysqli;

		if ($this->record['id'] == -1) {
			$query = "INSERT INTO `code` (`code`, `type`, `name`) VALUES ";
			$query = $query."(?, ?, ?);";

			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("sss", 
				$this->record['code'], 
				$this->record['type'], 
				$this->record['name']);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();

			$this->record['id'] = $mysqli->insert_id;
		} else {

			$query = "UPDATE `code` SET ";
			$updateData = "`code` = ?, ";
			$updateData.= "`type` = ?, ";
			$updateData.= "`name` = ? ";
			$query .= $updateData." WHERE `index` = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			$stmt->bind_param("sssi", 
				$this->record['code'], 
				$this->record['type'], 
				$this->record['name'], 
				$this->record['id']);
				
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
		}
	} 


	function Delete() {
		global $mysqli;

		if ($this->record['id'] > -1) {
			$stmt = $mysqli->prepare("DELETE FROM code WHERE `id` = ?");
			$stmt->bind_param("i", $this->record['id']);
			$stmt->execute();
			$stmt->close();
		}
	} 
}




/*
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
*/
?>
