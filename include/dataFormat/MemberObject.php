<?php 
# ************************************************************
#  Object : MemberObject
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************
# ************************************************************
#  MemberObject Class
#  description : get member data from member table 
# 				each member variable name is same with field name of the member table	
# ************************************************************
// 수정 작업 : HISDES(윤성환)
// 수정 일자 : 2013년 7월 27일 토요일 

class MemberObject {
	protected $record = array();
	protected $isNew = false;

	public function __set($name, $value) { 
		$name = strtolower($name);
		switch($name) {
			case "post":
				$this->record['zipcode'] = $value; 
				break;
			default:
				$this->record[$name] = $value; 
				break;
		}
	}

	public function __get($name) { 		
		$name = strtolower($name);

		switch($name) {
			case "phone":
			case "mobile":
				if (substr_count($this->record[$name], '-') != 2) {
					$this->record[$name] .= "--";
				}
				return explode("-", $this->record[$name]);
			case "zipcode":
			case "jumin":
				if (substr_count($this->record[$name], '-') != 1) {
					$this->record[$name] .= "-";
				}
				return explode("-", $this->record[$name]);
			case "email":
				return explode('@', $this->record["email"]);
			case "level":
			case "userlevel":
				return $this->record["userlv"];				
			default:
				return $this->record[$name];
		}
	}

	public function __isset($name) {
		$name = strtolower($name);
		switch($name) {
			case "level":
				return isset($this->record["userlv"]);
			default:
				return isset($this->record[$name]); 
		}
    }

    // 멤버 오브젝트 초기화 함수  
    function __construct($userid = -1) {
    	$this->isNew = true;
    	$this->initialize();

		if ($userid != -1 && $userid != "") {
			$this->Open($userid);
		}
	}

	private function initialize() {
		$this->userid = "";
		$this->password = "";
		$this->passquest = 0;
		$this->passanswer = "";
		$this->memo = "";
		$this->name = "";
		$this->nick = "";
		$this->userlv = 0;
		$this->email = "";
		$this->jumin = "000000-0000000";
		$this->address1 = "";
		$this->address2 = "";
		$this->zipcode = "000-000";
		$this->phone = "";
		$this->mobile = "000-000-0000";
		$this->msgok = 0;
	}


	function Open($userid) {
		global $mysqli;
		
		$query = "SELECT * from users WHERE userid = '".$mysqli->real_escape_string($userid)."'";
		$result = $mysqli->query($query);
		if (!$result) return;
		
		while ($row = $result->fetch_assoc()) {
			$this->userid = $row['userid'];
			$this->password = $row['password'];
			$this->passquest = $row['passquest'];
			$this->passanswer = $row['passanswer'];
			$this->memo = $row['memo'];
			$this->name = $row['name'];
			$this->nick = $row['nick'];
			$this->userlv = $row['userlv'];
			$this->email = $row['email'];
			$this->jumin = $row['jumin'];
			$this->address1 = $row['address1'];
			$this->address2 = $row['address2'];
			$this->zipcode = $row['zipcode'];
			$this->phone = $row['phone'];
			$this->mobile = $row['mobile'];
			$this->msgok = $row['msgok'];

			$this->isNew = false;
		}
		$result->close();
	}

	function OpenByNick($userNick) {
		global $mysqli;
		$query = "SELECT * from users WHERE nick = '".$mysqli->real_escape_string($userNick)."'";

		$result = $mysqli->query($query);
		if (!$result) return;
		
		while ($row = $result->fetch_assoc()) {
			$this->userid = $row['userid'];
			$this->password = $row['password'];
			$this->passquest = $row['passquest'];
			$this->passanswer = $row['passanswer'];
			$this->memo = $row['memo'];
			$this->name = $row['name'];
			$this->nick = $row['nick'];
			$this->userlv = $row['userlv'];
			$this->email = $row['email'];
			$this->jumin = $row['jumin'];
			$this->address1 = $row['address1'];
			$this->address2 = $row['address2'];
			$this->zipcode = $row['zipcode'];
			$this->phone = $row['phone'];
			$this->mobile = $row['mobile'];
			$this->msgok = $row['msgok'];

			$this->isNew = false;
		}
		$result->close();
	}



	function Update() {
		global $mysqli;

		if ($this->isNew) {

			$values = "'".$mysqli->real_escape_string($this->userid)."'";
			$values .= ", '".$mysqli->real_escape_string(Encrypt($this->password))."'";
			$values .= ", '".$mysqli->real_escape_string($this->passquest)."'";
			$values .= ", '".$mysqli->real_escape_string($this->passanswer)."'";
			$values .= ", '".$mysqli->real_escape_string($this->memo)."'";
			$values .= ", '".$mysqli->real_escape_string($this->name)."'";
			$values .= ", '".$mysqli->real_escape_string($this->nick)."'";
			$values .= ", '".$mysqli->real_escape_string($this->userlv)."'";
			$values .= ", '".$mysqli->real_escape_string($this->record['email'])."'";
			$values .= ", '".$mysqli->real_escape_string($this->record['jumin'])."'";
			$values .= ", '".$mysqli->real_escape_string($this->address1)."'";
			$values .= ", '".$mysqli->real_escape_string($this->address2)."'";
			$values .= ", '".$mysqli->real_escape_string($this->record['zipcode'])."'";
			$values .= ", '".$mysqli->real_escape_string($this->record['phone'])."'";
			$values .= ", '".$mysqli->real_escape_string($this->record['mobile'])."'";
			$values .= ", ".$mysqli->real_escape_string($this->msgok);
			$values .= ", ".time();

			$query = "INSERT INTO `users` (`userid`, `password`, `passquest`, `passanswer`, ";
			$query = $query."`memo`, `name`, `nick`, `userlv`, ";
			$query = $query."`email`, `jumin`, `address1`, `address2`, ";	
			$query = $query."`zipcode`, `phone`, `mobile`, `msgok`, registdate) VALUES ";
			$query = $query."($values)";

			$result = $mysqli->query($query);
			if (!$result) {
				return false;
			}

		} else {

			$query = "UPDATE users SET ";
			$updateData = "`nick` = '".$mysqli->real_escape_string($this->nick)."', ";
			$updateData.= "`userlv` = ".$mysqli->real_escape_string($this->userlv).", ";
			$updateData.= "`email` = '".$mysqli->real_escape_string($this->record['email'])."', ";
			$updateData.= "`jumin` = '".$mysqli->real_escape_string($this->record['jumin'])."', ";
			$updateData.= "`address1` = '".$mysqli->real_escape_string($this->address1)."', ";
			$updateData.= "`address2` = '".$mysqli->real_escape_string($this->address2)."', ";
			$updateData.= "`zipcode` = '".$mysqli->real_escape_string($this->record['zipcode'])."', ";
			$updateData.= "`phone` = '".$mysqli->real_escape_string($this->record['phone'])."', ";
			$updateData.= "`mobile` = '".$mysqli->real_escape_string($this->record['mobile'])."', ";
			$updateData.= "`msgok` = ".$mysqli->real_escape_string($this->msgok)." ";
			$query .= $updateData." WHERE `userid` = '".$mysqli->real_escape_string($this->userid)."'";

			$result = $mysqli->query($query);
			if (!$result) {
				return false;
			}
		}
	} 

	function Delete() {
		global $mysqli;
		
		$query = "DELETE FROM users WHERE userid = '" . $mysqli->real_escape_string($this->userid) . "'";
		$result = $mysqli->query($query);
		return $result;
	} 
}
?>
