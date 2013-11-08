<?php 
# ************************************************************
#  Object : AttachFile
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************

class AttachFile {
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
		$this->record['image'] = -1;
		$this->record['id'] = "";
		$this->record['userid'] = "";
		$this->record['name'] = "";
	}

	function Open($value) {
		global $mysqli;

		$column = array();
		/* create a prepared statement */
		$query = "SELECT * from attachFile WHERE `id` = ? ";

		if ($stmt = $mysqli->prepare($query)) {

			/* bind parameters for markers */
			$stmt->bind_param("s", $value);

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


		if (($this->record['id'] == -1)) {
			$query = "INSERT INTO attachFile (`userid`, `name`) VALUES ";
			$query = $query."(?, ?)";

			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("ss", 
				$this->record['userid'], 
				$this->record['name']);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();

			$stmt = $mysqli->prepare("SELECT MAX(id) AS new_id FROM attachFile WHERE `userid` = ?");
			$stmt->bind_param("s", $this->record['code']);
			$stmt->execute();
			$stmt->bind_result($this->record['id']);
			$stmt->close();


			
		} else {

			$query = "UPDATE attachFile SET ";
			$updateData = "`userid` = ?, ";
			$updateData.= "`name` = ?, ";
			$query .= $updateData." WHERE `id` = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			$stmt->bind_param("sss", 
				$this->record['userid'], 
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
			$stmt = $mysqli->prepare("DELETE FROM attachFile WHERE `userid` = ?");
			$stmt->bind_param("s", $this->record['id']);
			$stmt->execute();
			$stmt->close();
		}
	}




}

/*
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

	function userid() {
		$userid = $m_userid;
	} 

	function Name() {
		$Name = $m_name;
	} 

	#  Set property
	# ***********************************************
	function ImageID($value) {
		$m_index=intval($value);
	} 

	function userid($value) {
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
			$query = "INSERT INTO attachFile (userid, name) VALUES ";
			$insertData="'".$m_userid."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_name]."'";
			$query = $query."(".$insertData.")";
			$objDB->execute_command($query);

			$query = "SELECT MAX(id) AS new_id FROM attachFile WHERE userid = '".$m_userid."'";
			$rs_image = $objDB->execute_query($query);
			if ((!$rs_image->eof && !$rs_image->bof)) {
				$m_index=intval($rs_image["new_id"]);
			} 

		} else {
			$query = "UPDATE attachFile SET ";
			$updateData="userid = '".$m_userid."', ";
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
*/
?>
