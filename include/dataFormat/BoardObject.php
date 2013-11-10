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
		switch ($name) {
			case 'replyimage':
				$replyImageTag="";
				if ($this->record['answerlv'] == 0) {
					return $replyImageTag;
				} else {
					for ($i = 1; $i <= $this->record['answerlv']; $i++) {
						$replyImageTag = $replyImageTag."&nbsp;&nbsp;";
					}

					$replyImageTag = $replyImageTag."<img src=\"../images/board/icon_board_reply.gif\" border=\"0\"> ";
					return $replyImageTag;
				} 
				break;
			case 'boardid':
				return $this->record['id'];
			default:
				return $this->record[$name];
				break;
		}
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
		$this->countComment = 0;
		$this->answerId = -1;
		$this->answerNum = 1000;
		$this->answerLv = 0;
	}

	function Open($value) {
		global $mysqli;

		$query = "SELECT * from board WHERE `id` = ".$mysqli->real_escape_string($value);

		$result = $mysqli->query($query);
		if (!$result) return;
		
		while ($row = $result->fetch_assoc()) {
			$this->id = $row['id'];
			$this->groupId = $row['groupId'];
			$this->title = $row['title'];
			$this->contents = $row['contents'];
			$this->password = $row['password'];
			$this->regDate = $row['regDate'];
			$this->editDate = $row['editDate'];
			$this->userid = $row['userid'];
			$this->countView = $row['countView'];
			$this->countComment = $row['countComment'];
			$this->answerId = $row['answerId'];
			$this->answerNum = $row['answerNum'];
			$this->answerLv = $row['answerLv'];
		}
		$result->close();
	}


	function Update() {
		global $mysqli;

		if ($this->record['id'] == -1) {

			$temp = "'".$mysqli->real_escape_string($this->groupId)."'";
			$temp = $temp.", '".$mysqli->real_escape_string($this->title)."'";
			$temp = $temp.", '".$mysqli->real_escape_string($this->contents)."'";
			$temp = $temp.", '".$mysqli->real_escape_string($this->password)."'";
			$temp = $temp.", '".$mysqli->real_escape_string($this->userid)."'";
			$temp = $temp.", ".$mysqli->real_escape_string($this->answerId);
			$temp = $temp.", ".$mysqli->real_escape_string($this->answerNum);
			$temp = $temp.", ".$mysqli->real_escape_string($this->answerLv);

			$query = "INSERT INTO board (`groupId`, `title`, `contents`, `password`, `userid`, `answerId`, `answerNum`, `answerLv`) VALUES ($temp)";
			
			$result = $mysqli->query($query);
			if (!$result) {
				return false;
			}
			$this->id = $mysqli->insert_id;

			if ($this->answerId == -1) {
				$query = "UPDATE board SET `answerId` = `id` WHERE `id` = ".$this->id;
				$result = $mysqli->query($query);
			}
			else {
				$query = "UPDATE board SET `answerNum` = `answerNum` - 1";
				$query.= " WHERE `answerId` = {$this->answerId} AND `answerNum` <= {$this->answerNum} AND `answerLv` > 0 AND `id` <> {$this->id} ";
				$result = $mysqli->query($query);
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

		if ($this->id > -1) {
			$query = "DELETE FROM board WHERE `id` = ".$this->id;
			$result = $mysqli->query($query);
		}
	}



	function AddView() {
		global $mysqli;

		if ($this->id > -1) {
			$query = "UPDATE board SET `countView` = `countView` + 1 WHERE `id` = ".$this->id;
			$result = $mysqli->query($query);

			$this->countView++;
		} 
	}

	function AddComment() {
		global $mysqli;

		if ($this->id > -1) {
			$query = "UPDATE board SET `countComment` = `countComment` + 1 WHERE `id` = ".$this->id;
			$result = $mysqli->query($query);

			$this->countComment++;
		} 

	} 


	function checkEditPermission() {
		$userLevel = isset($_SESSION["userLv"]) ? trim($_SESSION["userLv"]) : "";
		if ($this->id == -1 || $this->userid == 0 || $userLevel == 9) {
			return true;
		} else {
			return false;
		} 

	} 
}
?>
