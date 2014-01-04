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

					$replyImageTag = $replyImageTag."<img src='../images/board/icon_board_reply.gif' border='0'> ";
					
					return $replyImageTag;
				} 
				break;
			case 'boardid':
				return $this->record['id'];
			case "contents":
				return stripslashes($this->record[$name]);
			case "attachfile":
				return $this->record['attachfile'];
			case "attachfilelink":
				global $mysqli;
				$query = "SELECT name FROM attachFile WHERE id = {$this->record['attachfile']}";
				$result = $mysqli->query($query);
				if ($result) {
					$row = $result->fetch_assoc();
					return "<a href=\"/upload/board/{$row['name']}\" target=\"_blank\">{$row['name']}</a>";
				} else {
					return "";
				}
			case "attachfilename":
				global $mysqli;
				$query = "SELECT name FROM attachFile WHERE id = {$this->record['attachfile']}";
				$result = $mysqli->query($query);
				if ($result) {
					$row = $result->fetch_assoc();
					return $row['name'];
				} else {
					return "";
				}
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
		$this->attachFile = "";
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
			$this->attachFile = $row['attachFile'];
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
			$temp = $temp.", '".$mysqli->real_escape_string($this->record['attachfile'])."'";
			$temp = $temp.", ".time();

			$query = "INSERT INTO board (`groupId`, `title`, `contents`, `password`, `userid`, `answerId`, `answerNum`, `answerLv`, `regDate`, `attachFile`) VALUES ($temp)";

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
			$updateData=" groupId = '".$mysqli->real_escape_string($this->groupId)."', ";
			$updateData = $updateData." title = '".$mysqli->real_escape_string($this->title)."', ";
			$updateData = $updateData." contents = '".$mysqli->real_escape_string($this->contents)."', ";
			$updateData = $updateData." `password` = '".$mysqli->real_escape_string($this->password)."', ";
			$updateData = $updateData." editDate = ".time().", ";
			$updateData = $updateData." `userid` = '".$mysqli->real_escape_string($this->userid)."', ";
			$updateData = $updateData." countView = '".$mysqli->real_escape_string($this->countView)."', ";
			$updateData = $updateData." countComment = '".$mysqli->real_escape_string($this->countComment)."', ";
			$updateData = $updateData." attachFile = '".$mysqli->real_escape_string($this->record['attachfile'])."', ";
			$updateData = $updateData." answerId = ".$mysqli->real_escape_string($this->answerId).", ";
			$updateData = $updateData." answerNum = ".$mysqli->real_escape_string($this->answerNum).", ";
			$updateData = $updateData." answerLv = ".$mysqli->real_escape_string($this->answerLv);
			$query = $query.$updateData." WHERE `id` = ".$mysqli->real_escape_string($this->id);

			$result = $mysqli->query($query);

			if (!$result) {
				return false;
			}
		}

		return true;
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

		if (!isset($_SESSION['board_view_list'])) {
			$_SESSION['board_view_list'] = array();
		}

		if ($this->id > -1 && array_search($this->id, $_SESSION['board_view_list']) === FALSE) {
			$query = "UPDATE board SET `countView` = `countView` + 1 WHERE `id` = ".$this->id;
			$result = $mysqli->query($query);
			
			$this->countView++;

			array_push($_SESSION['board_view_list'], $this->id);
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
