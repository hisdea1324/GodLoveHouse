<?php 
# ************************************************************
#  Object : BoardGroup
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************

class BoardGroup {
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
		$this->record['groupId'] = -1;
		$this->record['title'] = "";
		$this->record['contents'] = "";
		$this->record['password'] = "";
		$this->record['regDate'] = "";
		$this->record['editDate'] = "";
		$this->record['userId'] = "";
		$this->record['countView'] = 0;
		$this->record['countComment'] = 0;
		$this->record['countList'] = 0;
		$this->record['answerId'] = -1;
		$this->record['answerNum'] = 0;
		$this->record['answerLv'] = 0;
		$this->record['name'] = "";
	}


	function Open($value) {
		global $mysqli;

		$column = array();
		/* create a prepared statement */
		$query = "SELECT * from boardGroup WHERE groupId = ? ";

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

		$resultCount = 0;
		$stmt = $mysqli->prepare("SELECT CNT(*) FROM boardGroup WHERE groupId = ?");
		$stmt->bind_param("s", $this->record['groupId']);
		$stmt->execute();
		$stmt->bind_result($resultCount);
		$stmt->close();



		if (($resultCount == 0)) {
			$query = "INSERT INTO boardGroup (`groupId`, `managerId`, 'authReadLv', 'authWriteLv', ";
			$query.= "'authCommentLv', 'countList','name') VALUES ";
			$query = $query."(?, ?, ?, ?, ?, ?, ?)";

			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("ssiiiis", 
				$this->record['groupId'],
				$this->record['managerId'],
				$this->record['authReadLv'],
				$this->record['authWriteLv'],
				$this->record['authCommentLv'],
				$this->record['countList'], 
				$this->record['name']);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();

			
		} else {

			$query = "UPDATE boardGroup SET ";
			$updateData = "`managerId` = ?, ";
			$updateData = "`authReadLv` = ?, ";
			$updateData = "`authWriteLv` = ?, ";
			$updateData = "`authCommentLv` = ?, ";
			$updateData = "`countList` = ?, ";
			$updateData = "`name` = ?, ";
			$query .= $updateData." WHERE `groupId` = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			$stmt->bind_param("ssiiiis", 
				$this->record['groupId'],
				$this->record['managerId'],
				$this->record['authReadLv'],
				$this->record['authWriteLv'],
				$this->record['authCommentLv'],
				$this->record['countList'], 
				$this->record['name'],
				$this->record['groupId']);
				
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
		}
	} 

	function Delete() {
		global $mysqli;

		if ($this->record['userId'] > -1) {
			$stmt = $mysqli->prepare("DELETE FROM boardGroup WHERE groupId = ?");
			$stmt->bind_param("s", $this->record['groupId']);
			$stmt->execute();
			$stmt->close();
		}
	} 

	function AddList() {
		$this->record['countList'] = $this->record['countList'] + 1;
	}

	/*

	function WritePermission() {
		$sessions = new __construct();
		if (($m_authWriteLv <= $sessions->UserLevel)) {
			$WritePermission=true;
		} else {
			$WritePermission=false;
		} 

		$sessions = null;

	} 

	function ReadPermission() {
		$sessions = new __construct();
		if (($m_authReadLv <= $sessions->UserLevel)) {
			$ReadPermission=true;
		} else {
			$ReadPermission=false;
		} 

		$sessions = null;

	} 

	function CommentPermission() {
		$sessions = new __construct();
		if (($m_authCommentLv <= $sessions->UserLevel)) {
			$CommentPermission=true;
		} else {
			$CommentPermission=false;
		} 

		$sessions = null;

	} 
	*/
}
?>
