<?php 
# ************************************************************
#  Object : BoardObject
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************

class BoardObject {
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

	function __construct($id = -1) {
		$this->initialize();
		
		if ($id != -1) {
			$this->Open($id);
		}
	}

	function initialize() {
		$this->id = -1;
		$this->groupId = "";
		$this->title = "";
		$this->contents = "";
		$this->password = "";
		$this->regDate = "";
		$this->editDate = "";
		$this->userid = (isset($_SESSION["userid"])) ? trim($_SESSION["userid"]) : 0;
		$this->countView = 0;
		$this->countCommnent = 0;
		$this->answerId = -1;
		$this->answerNum = 1000;
		$this->answerLv = 0;
	}

	function Open($value) {
		global $mysqli;

		$column = array();
		/* create a prepared statement */
		$query = "SELECT * from board WHERE `id` = ? ";

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
			$query = "INSERT INTO board (`groupId`, `title`, `contents`, `password`, `userid`, ";
			$query.= "`answerId`, `answerNum`, `answerLv`) VALUES ";
			$query.= "(?, ?, ?, ?, ?, ?, ?, ?)";

			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("sssssiii", 
				$this->record['groupId'], 
				$this->record['title'],
				$this->record['contents'],
				$this->record['password'],
				$this->record['userid'],
				$this->record['answerId'],
				$this->record['answerNum'],
				$this->record['answerLv']);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();

			$stmt = $mysqli->prepare("SELECT MAX(id) as new_id FROM board WHERE `userid` = ?");
			$stmt->bind_param("s", $this->record['userid']);
			$stmt->execute();
			$stmt->bind_result($this->record['id']);
			$stmt->close();

			if($this->record['answerId'] == -1) {
				$stmt = $mysqli->prepare("UPDATE board SET `answerId` = `id` WHERE `id` = ? ");
				$stmt->bind_param("i", $this->record['id']);
				$this->record['answerId'] = $this->record['id'];
				$stmt->execute();
				$stmt->close();
			}
			else {
				$query = "UPDATE board SET `answerNum` = `answerNum` - 1 ";
				$query.= "WHERE `answerId` = ? AND `answerNum` <= ? ";
				$query.= "AND `answerLv` > 0 AND `id` <> ? ";
				$stmt->bind_param("iii", 
						$this->record['answerId'], 
						$this->record['answerNum'], 
						$this->record['id'] );
				$stmt->execute();
				$stmt->close();
			}
		} else {

			$query = "UPDATE board SET ";
			$updateData = "`groupId` = ?, ";
			$updateData = "`title` = ?, ";
			$updateData = "`contents` = ?, ";
			$updateData = "`password` = ?, ";
			$updateData = "`editDate` =  getDate(), ";
			$updateData = "`userid` = ?, ";
			$updateData = "`countView` = ?, ";
			$updateData = "`countComment` = ?, ";
			$updateData = "`answerId` = ?, ";
			$updateData.= "`answerNum` = ?, ";
			$updateData.= "`answerLv` = ? ";
			$query .= $updateData." WHERE `id` = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			$stmt->bind_param("sss", 
				$this->record['groupId'], 
				$this->record['title'],
				$this->record['contents'],
				$this->record['password'],
				$this->record['userid'],
				$this->record['countView'],
				$this->record['countComment'],
				$this->record['answerId'],
				$this->record['answerNum'],
				$this->record['answerLv'],
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
			$stmt = $mysqli->prepare("DELETE FROM board WHERE `id` = ?");
			$stmt->bind_param("i", $this->record['id']);
			$stmt->execute();
			$stmt->close();
		}
	}



	function AddView() {
		if ($this->record['id'] > -1) {
			$query = "UPDATE board SET `countView` = `countView` + 1 WHERE `id` = ? ";
			$stmt = $mysqli->prepare($query);
			$stmt->bind_param("i", $this->record['id']);
			$stmt->execute();
			$stmt->close();
			$this->record['countView'] = $this->record['countView'] + 1;
		} 
	}

	function AddComment() {
		if (($this->record['id'] > -1)) {
			$query = "UPDATE board SET `countComment` = `countComment` + 1 WHERE `id` = ?";
			$stmt = $mysqli->prepare($query);
			$stmt->bind_param("i", $this->record['id']);
			$stmt->execute();
			$stmt->close();
			$this->record['countComment'] = $this->record['countComment'] + 1;
		} 

	} 


	function checkEditPermission() {
		$userLevel = (isset($_SESSION["userLv"])) ? trim($_SESSION["userLv"]) : "";
		if (($this->record['id'] == -1 || $this->record['userid'] ==0 || $userLevel==9)) {
			return true;
		} else {
			return false;
		} 

	} 



}


/*
class BoardObject {
	var $boardRS;

	#  class member variable
	# ***********************************************
	var $m_index;
	var $m_groupId;
	var $m_title;
	var $m_contents;
	var $m_password;
	var $m_regDate;
	var $m_editDate;
	var $m_userid;
	var $m_countView;
	var $m_countComment;
	var $m_answerId;
	var $m_answerNum;
	var $m_answerLv;

	#  Get property
	# ***********************************************
	function BoardID() {
		$BoardID = $m_index;
	} 

	function GroupID() {
		$GroupID = $m_groupId;
	} 

	#  답변 글일 때, reply image의 html tag를 return 하는 property
	#  reply level에 따라서 앞에 빈공간을 조절한다.	
	function ReplyImage() {
		$replyImageTag="";

		if (($m_answerLv==0)) {
			$ReplyImage = $replyImageTag;
		} else {
			for ($i=1; $i <= $m_answerLv; $i = $i+1) {
				$replyImageTag = $replyImageTag."&nbsp;&nbsp;";

			}

			$replyImageTag = $replyImageTag."<img src=\"../images/board/icon_board_reply.gif\" border=\"0\"> ";
			$ReplyImage = $replyImageTag;
		} 

	} 

	function Title() {
		$Title = $m_title;
	} 

	function Contents() {
		$Contents = $m_contents;
	} 

	function RegDate() {
		$RegDate = $m_regDate;
	} 

	function EditDate() {
		$EditDate = $m_editDate;
	} 

	function userid() {
		$userid = $m_userid;
	} 

	function CountView() {
		$CountView = $m_countView;
	} 

	function CountComment() {
		$CountComment = $m_countComment;
	} 

	function AnswerID() {
		$AnswerID = $m_answerId;
	} 

	function AnswerNum() {
		$AnswerNum = $m_answerNum;
	} 

	function AnswerLv() {
		$AnswerLv = $m_answerLv;
	} 

	#  Set property 
	# ***********************************************
	function BoardID($value) {
		$m_index=intval($value);
	} 

	function GroupID($value) {
		$m_groupId = trim($value);
	} 

	function Title($value) {
		$m_title = trim($value);
	} 

	function Contents($value) {
		$m_contents = trim($value);
	} 

	function userid($value) {
		$m_userid = trim($value);
	} 

	function AnswerID($value) {
		$m_answerId=intval($value);
	} 

	function AnswerNum($value) {
		$m_answerNum=intval($value);
	} 

	function AnswerLv($value) {
		$m_answerLv=intval($value);
	} 

	#  class initialize
	# ***********************************************
	function __construct() {
		$m_index=-1;
		$m_groupId="";
		$m_title="";
		$m_contents="";
		$m_password="";
		$m_regDate="";
		$m_editDate="";
		$m_userid = $_SESSION["userid"];
		$m_countView=0;
		$m_countComment=0;
		$m_answerId=-1;
		$m_answerNum=1000;
		$m_answerLv=0;
	} 

	function __destruct() {
		$boardRS = null;

	} 

	#  class method
	# ***********************************************
	function Open($index) {
		$query = "SELECT * from board WHERE id = '".$mssqlEscapeString[$index]."'";
		$boardRS = $objDB->execute_query($query);
		if ((!$boardRS->eof && !$boardRS->bof)) {
			$m_index=intval($boardRS["id"]);
			$m_groupId = $boardRS["groupId"];
			$m_title = $boardRS["title"];
			$m_contents = $boardRS["contents"];
			$m_password = $boardRS["password"];
			$m_regDate = $boardRS["regDate"];
			$m_editDate = $boardRS["editDate"];
			$m_userid = $boardRS["userid"];
			$m_countView=intval($boardRS["countView"]);
			$m_countComment=intval($boardRS["countComment"]);
			$m_answerId=intval($boardRS["answerId"]);
			$m_answerNum=intval($boardRS["answerNum"]);
			$m_answerLv=intval($boardRS["answerLv"]);
		} 

	} 

	function Update() {
		if (($m_index==-1)) {
			# New Data
			# transation이 필요함 나중에 처리	
			$query = "INSERT INTO board (groupId, title, contents, password, userid, answerId, answerNum, answerLv) VALUES ";
			$insertData="'".$mssqlEscapeString[$m_groupId]."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_title]."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_contents]."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_password]."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_userid]."', ";
			$insertData = $insertData."'".$m_answerId."', ";
			$insertData = $insertData."'".$m_answerNum."', ";
			$insertData = $insertData."'".$m_answerLv."'";
			$query = $query."(".$insertData.")";
			$objDB->execute_command($query);

			$query = "SELECT MAX(id) AS new_id FROM board WHERE userid = '".$m_userid."'";
			$boardRS = $objDB->execute_query($query);
			if ((!$boardRS->eof && !$boardRS->bof)) {
				$m_index=intval($boardRS["new_id"]);
			} 

			if (($m_answerId==-1)) {
				$query = "UPDATE board SET answerId = id WHERE id = '".$m_index."'";
				$m_answerId = $m_index;
			} else {
				$query = "UPDATE board SET answerNum = answerNum - 1 ";
				$query = $query."WHERE answerId = '".$mssqlEscapeString[$m_answerId]."' AND answerNum <= '".$mssqlEscapeString[$m_answerNum]."' ";
				$query = $query."AND answerLv > 0 AND id <> '".$m_index."'";
			} 

			$objDB->execute_command($query);
		} else {
			$query = "UPDATE board SET ";
			$updateData="groupId = '".$mssqlEscapeString[$m_groupId]."', ";
			$updateData = $updateData."title = '".$mssqlEscapeString[$m_title]."', ";
			$updateData = $updateData."contents = '".$mssqlEscapeString[$m_contents]."', ";
			$updateData = $updateData."password = '".$mssqlEscapeString[$m_password]."', ";
			$updateData = $updateData."editDate = getDate(), ";
			$updateData = $updateData."userid = '".$mssqlEscapeString[$m_userid]."', ";
			$updateData = $updateData."countView = '".$m_countView."', ";
			$updateData = $updateData."countComment = '".$m_countComment."', ";
			$updateData = $updateData."answerId = '".$m_answerId."', ";
			$updateData = $updateData."answerNum = '".$m_answerNum."', ";
			$updateData = $updateData."answerLv = '".$m_answerLv."' ";
			$query = $query.$updateData." WHERE id = '".$m_index."'";
			$objDB->execute_command($query);
		} 

	} 

	function Delete() {
		if (($m_index>-1)) {
			$query = "DELETE FROM board WHERE id = '".$m_index."'";
			$objDB->execute_command($query);
		} 

	} 

	function AddView() {
		if (($m_index>-1 && !$sessions->checkReadList($m_index))) {
			$query = "UPDATE board SET countView = countView + 1 WHERE id = '".$m_index."'";
			$objDB->execute_command($query);
			$m_countView = $m_countView+1;
		} 
	} 

	function AddComment() {
		if (($m_index>-1)) {
			$query = "UPDATE board SET countComment = countComment + 1 WHERE id = '".$m_index."'";
			$objDB->execute_command($query);
			$m_countComment = $m_countComment+1;
		} 

	} 

	function checkEditPermission() {
		if (($m_index==-1 || strcmp($_SESSION["userid"],$m_userid)==0 || $_SESSION["userLv"]==9)) {
			return true;
		} else {
			return false;
		} 

	} 
} 
*/
?>
