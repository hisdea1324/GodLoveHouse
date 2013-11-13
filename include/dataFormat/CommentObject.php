<?php 
//************************************************************
// Object : CommentObject
//
// editor : Sookbun Lee 
// last update date : 2010.03.04
//************************************************************

class CommentObject {
	protected $record = array();

	public function __set($name,$value) { 
		$name = strtolower($name);
		$this->record[$name] = $value;
	}

	public function __get($name) { 
		$name = strtolower($name);
		return $this->record[$name];
	}

	public function __isset($name) {
		$name = strtolower($name);
		return isset($this->record[$name]); 
    }

    function __construct($value = -1) {
    	$this->initialize();
    	if ($value != -1) {
    		$this->Open($value);
    	}
    }

    function initialize() {
		$this->id = -1;
		$this->parentId = -1;
		$this->hostuserid = "";
		$this->followId = "";
		$this->comments = "";
		$this->regDate = "";
		$this->secret = 0;
		$this->replyExist = false;
	}

	function Open($value) {
		global $mysqli;

		$column = array();
		/* create a prepared statement */
		$query = "SELECT `id`, `parentId`, `hostuserid`, `followId`, `comments`, `regDate`, `secret` FROM familyComment WHERE `id` = ? ";
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

			$commentColumn = array();

			$query = "SELECT `id` FROM familyComment WHERE `parentId` = ? ";
			$stmt->bind_param("i", $value);
			$stmt->execute();
			
			$metaResults = $stmt->result_metadata();
			$fields = $metaResults->fetch_fields();
			$statementParams='';


			foreach ($fields as $field) {
				if (empty($statementParams)) {
					$statementParams.="\$commentColumn['".$field->name."']";
				} else {
					$statementParams.=", \$commentColumn['".$field->name."']";
				}
			}

			$statment = "\$stmt->bind_result($statementParams);";
			eval($statment);
			
			while($stmt->fetch()){
				//Now the data is contained in the assoc array $column. Useful if you need to do a foreach, or 
				//if your lazy and didn't want to write out each param to bind.
				$cmtObj = new CommentObject();
				echo $cmtObj;
				$cmtObj->Open($commentColumn);
				$this->record['replyExist'] = true;
			}
			$stmt->close();
		}
	}


	function Update() {
		global $mysqli;


		if (($this->record['id'] == -1)) {
			$query = "INSERT INTO familyComment (`parentId`, `hostuserid`, `followId', `comments`, `secret`) VALUES ";
			$query = $query."(?, ?, ?, ?, ?)";

			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("iiisi", 
				$this->record['parentId'], 
				$this->record['hostuserid'], 
				$this->record['followId'], 
				$this->record['comments'], 
				$this->record['secret']);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();

			

			$stmt = $mysqli->prepare("SELECT MAX(id) as new_id FROM familyComment WHERE `followId` = ?");
			$stmt->bind_param("s", $this->record['followId']);
			$stmt->execute();
			$stmt->bind_result($this->record['id']);
			$stmt->close();
			
		} else {

			$query = "UPDATE familyComment SET ";
			$updateData = "`parentId` = ?, ";
			$updateData.= "`hostuserid` = ?, ";
			$updateData.= "`followId` = ?, ";
			$updateData.= "`comments` = ?, ";
			$updateData.= "`secret` = ?, ";
			$query .= $updateData." WHERE `id` = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			$stmt->bind_param("sss", 
				$this->record['parentId'], 
				$this->record['hostuserid'], 
				$this->record['followId'], 
				$this->record['comments'],
				$this->record['secret'],
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
			$stmt = $mysqli->prepare("DELETE FROM familyComment WHERE `id` = ?");
			$stmt->bind_param("s", $this->record['id']);
			$stmt->execute();
			$stmt->close();
		}
	} 
}
  
?>
