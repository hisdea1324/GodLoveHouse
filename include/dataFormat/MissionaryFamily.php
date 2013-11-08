<?php 
//************************************************************
// Object : MissionaryFamily
//
// editor : Sookbun Lee 
// last update date : 2010.03.04
//************************************************************

class MissionaryFamily {

	protected $record = array();


	public function __set($name,$value) { 
		$this->record[$name] = $value;
	}

	public function __get($name) { 
		return $this->record[$name];
	}

	public function __isset($name) {
		return isset($this->record[$name]); 
    }

    function __construct() {
    	$this->record['id'] = -1;
		$this->record['userid'] = -1;
		$this->record['name'] = "";
		$this->record['age'] = 0;
		$this->record['sex'] = "";
		$this->record['relation'] = "";
	}

	function Open($userid) {
		global $mysqli;

		$column = array();
		/* create a prepared statement */
		$query = "SELECT * FROM missionary_family WHERE id = ? ";

		if ($stmt = $mysqli->prepare($query)) {

			/* bind parameters for markers */
			$stmt->bind_param("s", $userid);

			/* execute query */
			$stmt->execute();
			
			$metaResults = $stmt->result_metadata();
			$fields = $metaResults->fetch_fields();
			$statementParams='';
			
			//build the bind_results statement dynamically so I can get the results in an array
			foreach ($fields as $field) {
				if (empty($statementParams)) {
					$statementParams.="\$column['".$field->name."']";
				} else {
					$statementParams.=", \$column['".$field->name."']";
				}
			}


			$statment = "\$stmt->bind_result($statementParams);";
			eval($statment);
			
			while($stmt->fetch()){
				//Now the data is contained in the assoc array $column. Useful if you need to do a foreach, or 
				//if your lazy and didn't want to write out each param to bind.
				$this->record = $column;
			}
			
			/* close statement */
			$stmt->close();
		}
	}



	function Update() {
		global $mysqli;


		if (($this->record['userid'] == "")) {
			$query = "INSERT INTO missionary_family (`userid`, `name`, `age`, `sex`, 'relation' ";
			$query = $query.") VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("ssiis", 
				$this->record['userid'], 
				$this->record['name'], 
				$this->record['age'], 
				$this->record['sex'], 
				$this->record['relation']);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
			
			$stmt = $mysqli->prepare("SELECT MAX(id) as new_id FROM missionary_family WHERE userid = ?");
			$stmt->bind_param("s", $this->record['userid']);
			$stmt->execute();
			$stmt->bind_result($this->record['id']);
			$stmt->close();

		} else {

			$query = "UPDATE missionary_family SET ";
			$updateData = "`userid` = ?, ";
			$updateData.= "`name` = ?, ";
			$updateData.= "`age` = ?, ";
			$updateData.= "`sex` = ?, ";
			$updateData.= "`relation` = ?, ";
			$query .= $updateData." WHERE `id` = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			$stmt->bind_param("ssiiss", 
				$this->record['userid'], 
				$this->record['name'], 
				$this->record['age'], 
				$this->record['sex'], 
				$this->record['relation'], 
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
			$stmt = $mysqli->prepare("DELETE FROM missionary_family WHERE id = ?");
			$stmt->bind_param("s", $this->record['id']);
			$stmt->execute();
			$stmt->close();
		}
	}
}	

/*





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

	function userid() {
		$userid = $m_userid;
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

	function userid($value) {
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
			$m_userid = $memberRS["userid"];
			$m_name = $memberRS["name"];
			$m_age=intval($memberRS["age"]);
			$m_sex = $memberRS["sex"];
			$m_relation = $memberRS["relation"];
		} 
	} 

	function Update() {
		if (($m_index==-1)) {
			//New Data
			$query = "INSERT INTO missionary_family (userid, name, age, sex, relation) VALUES ";
			$insertData="'".$mssqlEscapeString[$m_userid]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_name]."', ";
			$insertData = $insertData."'".$m_age."', ";
			$insertData = $insertData."'".$m_sex."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_relation]."'";
			$query = $query."(".$insertData.") ";
			$objDB->execute_command($query);

			$query = "SELECT MAX(id) AS new_id FROM missionary_family WHERE userid = '".$mssqlEscapeString[$m_userid]."'";
			$missionRS = $objDB->execute_query($query);
			if ((!$missionRS->eof && !$missionRS->bof)) {
				$m_index=intval($missionRS["new_id"]);
			} 
		} else {
			$query = "UPDATE missionary_family SET ";
			$updateData=" userid = '".$mssqlEscapeString[$m_userid]."', ";
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
*/
?>
