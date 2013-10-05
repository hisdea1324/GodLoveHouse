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
			default:
				$this->record[$name] = $value; 
				break;
		}
	}

	public function __get($name) { 		
		$name = strtolower($name);

		switch($name) {
			case "zipcode":
				return array(substr($this->record["zipcode"], 0, 3), substr($this->record["zipcode"], 3, 3));
			case "phone":
				return explode('-', $this->record["phone"]);
			case "mobile":
				return explode('-', $this->record["mobile"]);
			case "email":
				return explode('@', $this->record["email"]);
			case "level":
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
    function __construct($userId = -1) {
    	if ($userId == -1) {
    		$this->isNew = true;
    		$this->initialize();
    	} else {
    		$this->isNew = false;
    		$this->Open($userId);
    	}
	}

	private function initialize() {
		$this->record['userid'] = "";
		$this->record['password'] = "a";
		$this->record['passquest'] = 0;
		$this->record['passanswer'] = "a";
		$this->record['memo'] = "a";
		$this->record['name'] = "a";
		$this->record['nick'] = "a";
		$this->record['userlv'] = 0;
		$this->record['email'] = "a";
		$this->record['jumin'] = "0000000000000";
		$this->record['address1'] = "a";
		$this->record['address2'] = "a";
		$this->record['zipcode'] = "000000";
		$this->record['phone'] = "a";
		$this->record['mobile'] = "a";
		$this->record['msgok'] = 0;
	}


	function Open($userid) {
		global $mysqli;

		$column = array();
		/* create a prepared statement */
		if ($stmt = $mysqli->prepare("SELECT * from users WHERE userid = ?")) {

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

	function OpenByNick($userNick) {
		global $mysqli;

		$column = array();
		/* create a prepared statement */
		if ($stmt = $mysqli->prepare("SELECT * FROM member WHERE nick = ?")) {

			/* bind parameters for markers */
			$stmt->bind_param("s", $userNick);

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

		if ($this->isNew) {
			$query = "INSERT INTO `member` (`userid`, `password`, `passquest`, `passanswer`, ";
			$query = $query."`memo`, `name`, `nick`, `userlv`, ";
			$query = $query."`email`, `jumin`, `address1`, `address2`, ";	
			$query = $query."`zipcode`, `phone`, `mobile`, `msgok`) VALUES ";
			$query = $query."(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			if ($stmt = $mysqli->prepare($query)) {

				# New Data
				$stmt->bind_param("isissssisssssssi", 
					$this->record['userid'], 
					crypt($this->record['password'], $this->record['userid']),
					$this->record['passquest'], 
					$this->record['passanswer'], 
					$this->record['memo'], 
					$this->record['name'], 
					$this->record['nick'], 
					$this->record['userlv'], 
					$this->record['email'], 
					$this->record['jumin'], 
					$this->record['address1'], 
					$this->record['address2'],
					$this->record['zipcode'],
					$this->record['phone'],
					$this->record['mobile'],
					$this->record['msgok']);
	
				# execute query
				$stmt->execute();
			
				# close statement
				$stmt->close();
			}
		} else {

			$query = "UPDATE member SET ";
			$updateData = "`nick` = ?, ";
			$updateData.= "`userlv` = ?, ";
			$updateData.= "`email` = ?, ";
			$updateData.= "`jumin` = ?, ";
			$updateData.= "`address1` = ?, ";
			$updateData.= "`address2` = ?, ";
			$updateData.= "`zipcode` = ?, ";
			$updateData.= "`phone` = ?, ";
			$updateData.= "`mobile` = ?, ";
			$updateData.= "`msgok` = ? ";
			$query .= $updateData." WHERE `userid` = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			$stmt->bind_param("sssssssssi", 
				$this->record['nick'], 
				$this->record['userlv'], 
				$this->record['email'], 
				$this->record['jumin'], 
				$this->record['address1'], 
				$this->record['address2'], 
				$this->record['zipcode'], 
				$this->record['phone'], 
				$this->record['mobile'], 
				$this->record['msgok']);
				
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
		}
	} 

	function Delete() {
		global $mysqli;

		if ($this->record['userid'] > -1) {
			$stmt = $mysqli->prepare("DELETE FROM member WHERE userid = ?");
			$stmt->bind_param("i", $this->record['userid']);
			$stmt->execute();
			$stmt->close();
		}
	} 



}

/*
class MemberObject {
	function Open($userid) {
		$query = "SELECT * from users WHERE userId = '".$mssqlEscapeString[$userid]."'";
		$memberRS = $objDB->execute_query($query);

		if ((!$memberRS->eof && !$memberRS->bof)) {
			$m_userid = $memberRS["userID"];
			$m_nick = $memberRS["nick"];
			$m_name = $memberRS["name"];
			$m_userLevel = $memberRS["userLv"];
			$m_email=explode("@",$memberRS["email"]);
			if ((count($m_email)<1)) {
				$m_email = array("","","");
			} 
			$m_jumin[0]=substr($memberRS["jumin"],0,6);
			$m_jumin[1]=substr($memberRS["jumin"],strlen($memberRS["jumin"])-(7));
			$m_address1 = $memberRS["address1"];
			$m_address2 = $memberRS["address2"];
			$m_zipcode[0]=substr($memberRS["zipcode"],0,3);
			$m_zipcode[1]=substr($memberRS["zipcode"],strlen($memberRS["zipcode"])-(3));
			$m_phone=explode("-",$memberRS["phone"]);
			if ((count($m_phone)<2)) {
				$m_phone = array("","","");
			} 
			$m_mobile=explode("-",$memberRS["mobile"]);
			if ((count($m_mobile)<2)) {
				$m_mobile = array("","","");
			} 
			$m_msgOk = $memberRS["msgOK"];
		}
	} 

	function OpenByNick($nick) {
		$query = "SELECT * from users WHERE nick = '".$mssqlEscapeString[$nick]."'";
		$memberRS = $objDB->execute_query($query);

		if ((!$memberRS->eof && !$memberRS->bof)) {
			$m_userid = $memberRS["userID"];
			$m_nick = $memberRS["nick"];
			$m_name = $memberRS["name"];
			$m_userLevel = $memberRS["userLv"];
			$m_email=explode("@",$memberRS["email"]);
			if ((count($m_email)<1)) {
				$m_email = array("","","");
			} 
			$m_jumin[0]=substr($memberRS["jumin"],0,6);
			$m_jumin[1]=substr($memberRS["jumin"],strlen($memberRS["jumin"])-(7));
			$m_address1 = $memberRS["address1"];
			$m_address2 = $memberRS["address2"];
			$m_zipcode[0]=substr($memberRS["zipcode"],0,3);
			$m_zipcode[1]=substr($memberRS["zipcode"],strlen($memberRS["zipcode"])-(3));
			$m_phone=explode("-",$memberRS["phone"]);
			if ((count($m_phone)<2)) {
				$m_phone = array("","","");
			} 
			$m_mobile=explode("-",$memberRS["mobile"]);
			if ((count($m_mobile)<2)) {
				$m_mobile = array("","","");
			} 
			$m_msgOk = $memberRS["msgOK"];
		} 
	}

	function Update() {
		$query = "SELECT * from users WHERE userId = '".$mssqlEscapeString[$m_userid]."'";
		$memberRS = $objDB->execute_query($query);

		if (($memberRS->eof || $memberRS->bof)) {
			$query = "INSERT INTO users (userId, password, passQuest, passAnswer, name, nick, userLv, email, jumin, address1, address2, zipcode, phone, mobile, msgOk) VALUES ";
			$insertData="'".$mssqlEscapeString[$m_userid]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_password]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_passQuest]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_passAnswer]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_name]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_nick]."', ";
			$insertData = $insertData."'".$m_userLevel."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$join[$m_email]["@"]]."', ";
			$insertData = $insertData."'".$m_jumin[0].$m_jumin[1]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_address1]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_address2]."', ";
			$insertData = $insertData."'".$m_zipcode[0].$m_zipcode[1]."', ";
			$insertData = $insertData."'".$join[$m_phone]["-"]."', ";
			$insertData = $insertData."'".$join[$m_mobile]["-"]."', ";
			$insertData = $insertData."'".$m_msgOK."'";
			$query = $query."(".$insertData.") ";
			$objDB->execute_command($query);
		} else {
			$query = "UPDATE users SET ";
			$updateData=" name = '".$mssqlEscapeString[$m_name]."', ";
			$updateData = $updateData." nick = '".$mssqlEscapeString[$m_nick]."', ";
			$updateData = $updateData." userLv = '".$m_userLevel."', ";
			$updateData = $updateData." email = '".$mssqlEscapeString[$join[$m_email]["@"]]."', ";
			$updateData = $updateData." jumin = '".$m_jumin[0].$m_jumin[1]."', ";
			$updateData = $updateData." address1 = '".$mssqlEscapeString[$m_address1]."', ";
			$updateData = $updateData." address2 = '".$mssqlEscapeString[$m_address2]."', ";
			$updateData = $updateData." zipcode = '".$m_zipcode[0].$m_zipcode[1]."', ";
			$updateData = $updateData." phone = '".$join[$m_phone]["-"]."', ";
			$updateData = $updateData." mobile = '".$join[$m_mobile]["-"]."', ";
			$updateData = $updateData." msgOK = '".$m_msgOK."'";
			$query = $query.$updateData." WHERE userId = '".$mssqlEscapeString[$m_userId]."'";
			$objDB->execute_command($query);
		}
	} 

	
} 
*/
?>
