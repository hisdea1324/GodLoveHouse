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
		$this->record['parentId'] = -1;
		$this->record['hostUserId'] = "";
		$this->record['followId'] = "";
		$this->record['comments'] = "";
		$this->record['regDate'] = "";
		$this->record['secret'] = 0;
		$this->record['replyExist'] = false;
	}

	function Open($value) {
		global $mysqli;

		$column = array();
		/* create a prepared statement */
		$query = "SELECT id, parentId, hostUserId, followId, comments, regDate, secret FROM familyComment WHERE id = ? ";
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

			$query = "SELECT id FROM familyComment WHERE parentId = ? ";
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
				$cmtObj->Open($commnetColumn);
				$this->record['replyExist'] = true;
			}
			$stmt->close();
		}
	}


	function Update() {
		global $mysqli;


		if (($this->record['id'] == -1)) {
			$query = "INSERT INTO familyComment (`parentId`, `hostUserId`, `followId', 'comments', 'secret') VALUES ";
			$query = $query."(?, ?, ?, ?, ?)";

			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("iiisi", 
				$this->record['parentId'], 
				$this->record['hostUserId'], 
				$this->record['followId'], 
				$this->record['comments'], 
				$this->record['secret']);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();

			

			$stmt = $mysqli->prepare("SELECT MAX(id) as new_id FROM familyComment WHERE followId = ?");
			$stmt->bind_param("s", $this->record['followId']);
			$stmt->execute();
			$stmt->bind_result($this->record['id']);
			$stmt->close();
			
		} else {

			$query = "UPDATE familyComment SET ";
			$updateData = "`parentId` = ?, ";
			$updateData.= "`hostUserId` = ?, ";
			$updateData.= "`followId` = ?, ";
			$updateData.= "`comments` = ?, ";
			$updateData.= "`secret` = ?, ";
			$query .= $updateData." WHERE `id` = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			$stmt->bind_param("sss", 
				$this->record['parentId'], 
				$this->record['hostUserId'], 
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
			$stmt = $mysqli->prepare("DELETE FROM familyComment WHERE id = ?");
			$stmt->bind_param("s", $this->record['id']);
			$stmt->execute();
			$stmt->close();
		}
	} 
}

class CommentObject {
	var $m_index;
	var $m_parentId;
	var $m_hostUserId;
	var $m_followId;
	var $m_comments;
	var $m_regDate;
	var $m_secret;
	var $m_replyComments;
	var $m_replyExist;

	// Get property
	//***********************************************
	function ID() {
		$ID = $m_index;
	} 

	function HostUserId() {
		$HostUserId = $m_hostUserId;
	} 

	function FollowId() {
		$FollowId = $m_followId;
	} 

	function Comments() {
		$Comments = $m_comments;
	} 

	function RegDate() {
		$RegDate = $m_regDate;
	} 

	function Secret() {
		$Secret = $m_secret;
	} 

	function ReplyComments() {
		$ReplyComments = $m_replyComments;
	} 

	function ReplyCommentsExist() {
		$ReplyCommentsExist = $m_replyExist;
	} 

	// Set property
	//***********************************************
	function HostUserId($value) {
		$m_hostUserId = trim($value);
	} 

	function FollowId($value) {
		$m_followId = trim($value);
	} 

	function ParentID($value) {
		$m_parentId=intval($value);
	} 

	function Comments($value) {
		$m_comments = trim($value);
	} 

	function Secret($value) {
		if ((strlen(trim($value))==0)) {
			$value=0;
		} 

		$m_secret=intval($value);
	} 

	// class initialize
	//***********************************************
	function __construct() {
		$m_index=-1;
		$m_parentId=-1;
		$m_hostUserId="";
		$m_followId="";
		$m_comments="";
		$m_regDate="";
		$m_secret=0;
		$m_replyExist=false;
	} 

	function __destruct() {
		$replyCmtRS = null;
		$commentRS = null;
	} 

	// class method
	//***********************************************
	function Open($idx) {
		$query = "SELECT id, parentId, hostUserId, followId, comments, regDate, secret FROM familyComment WHERE id = '".$mssqlEscapeString[$idx]."'";
		$commentRS = $objDB->execute_query($query);

		if ((!$commentRS->eof && !$commentRS->bof)) {
			$m_index=intval($commentRS["id"]);
			$m_parentId=intval($commentRS["parentId"]);
			$m_hostUserId = $commentRS["hostUserId"];
			$m_followId = $commentRS["followId"];
			$m_comments = $commentRS["comments"];
			$m_regDate = $commentRS["regDate"];
			$m_secret=intval($commentRS["secret"]);

			$query = "SELECT id FROM familyComment WHERE parentId = '".$mssqlEscapeString[$idx]."'";
			$objDB->CursorLocation=3;
			$replyCmtRS = $objDB->execute_query($query);

			if ((!$replyCmtRS->eof && !$replyCmtRS->bof)) {
				$index = $replyCmtRS->RecordCount-1;
				for ($i=0; $i <= $index; $i = $i+1) {
					$m_replyComments = $i;					echo new CommentObject();
					$m_replyComments[$i]->$Open[$replyCmtRS["id"]];
					$replyCmtRS->MoveNext;

				}

				$m_replyExist=true;
			} 
		} 
	} 

	function Update() {
		if (($m_index==-1)) {
			//New Data
			$query = "INSERT INTO familyComment (parentId, hostUserId, followId, comments, secret) VALUES ";
			$insertData="'".$m_parentId."', ";
			$insertData = $insertData."'".$m_hostUserId."', ";
			$insertData = $insertData."'".$m_followId."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_comments]."', ";
			$insertData = $insertData."'".$m_secret."'";
			$query = $query."(".$insertData.")";
			$objDB->execute_command($query);

			$query = "SELECT MAX(id) AS new_id FROM familyComment WHERE followId = '".$m_followId."'";
			$commentRS = $objDB->execute_query($query);
			if ((!$commentRS->eof && !$commentRS->bof)) {
				$m_index=intval($commentRS["new_id"]);
			} 

		} else {
			$query = "UPDATE familyComment SET ";
			$updateData="parentId = '".$m_parentId."', ";
			$updateData = $updateData."hostUserId = '".$m_hostUserId."', ";
			$updateData = $updateData."followId = '".$m_followId."', ";
			$updateData = $updateData."comments = '".$mssqlEscapeString[$m_comments]."', ";
			$updateData = $updateData."secret = '".$m_secret."' ";
			$query = $query.$updateData." WHERE id = '".$m_index."'";
			$objDB->execute_command($query);
		}
	} 

	function Delete() {
		if (($m_index>-1)) {
			$query = "DELETE FROM familyComment WHERE id = ".$mssqlEscapeString[$m_index];
			$objDB->execute_command($query);
		}
	} 
} 
?>
