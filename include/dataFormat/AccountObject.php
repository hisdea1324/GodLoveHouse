<?php 
#************************************************************
# Object : AccountObject
#
# editor : Sookbun Lee 
# last update date : 2010.03.04
#************************************************************
class AccountObject {
	protected $record = array();

	public function __set($name,$value) { 
		$name = strtolower($name);
		switch ($name) {
			case "method" :
				if($value == "CM5")
					$this->record['method'] = 1;
				else if($value == "DIRECT")
					$this->record['method'] = 2;
				else if($value == "ZIRO")
					$this->record['method'] = 3;
				break;

			case "jumin" : 
				$this->record[$name] = join("-", $value);
			default : 
				$this->record[$name] = $value;
				break;
		}
	}

	public function __get($name) { 
		$name = strtolower($name);
		switch ($name) {
			case "method" :
				if ($this->record[$name] == 1) 
					return "CMS";
				else if ($this->record[$name] == 2) 
					return "DIRECT";
				else if ($this->record[$name] == 3) 
					return "GIRO";
				else
					return "";
			case "jumin" : 
				return explode("-", $this->record[$name]);

			default : 
				return $this->record[$name];
		}
	}

	public function __isset($name) {
		$name = strtolower($name);
		return isset($this->record[$name]); 
    }

    function __construct($userid = "") {
		$this->initialize();
		if ($userid != "") {
			$this->Open($userid);
		}
	}

    function initialize() {
		$this->id = -1;
		$this->userid = "";
		$this->name = "";
		$this->bank = "";
		$this->method = "";
		$this->number = "";
		$this->jumin = "000000-0000000";
		$this->senddate = -1;
		$this->expectdate = -1;
		$this->regdate = "";
	}

	function Open($userid) {
		global $mysqli;

		$query = "SELECT * from account WHERE userid = '".$mysqli->real_escape_string($userid)."'";

		$result = $mysqli->query($query);
		if (!$result) return;
		
		while ($row = $result->fetch_assoc()) {
			$this->id = $row['id'];
			$this->userid = $row['userid'];
			$this->name = $row['name'];
			$this->bank = $row['bank'];
			$this->method = $row['method'];
			$this->number = $row['number'];
			$this->senddate = $row['senddate'];
			$this->expectdate = $row['expectdate'];
			$this->regdate = $row['regdate'];
		}
		$result->close();
	}


	function Update() {
		global $mysqli;

		if (($this->record['userid'] == "")) {
			$query = "INSERT INTO account (`userid`, `name`, `bank`, `method`, ";
			$query = $query."`number`, `nid`, `senddate`, `expectdate`) VALUES ";
			$query = $query."(?, ?, ?, ?, ?, ?, ?, ?)";

			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("sssssssss", 
				$this->record['userid'], 
				$this->record['name'], 
				$this->record['bank'], 
				$this->record['method'], 
				$this->record['number'], 
				$this->record['nid'], 
				$this->record['senddate'], 
				$this->record['expectdate']);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
			
		} else {

			$query = "UPDATE account SET ";
			$updateData = "`name` = ?, ";
			$updateData.= "`bank` = ?, ";
			$updateData.= "`method` = ?, ";
			$updateData.= "`number` = ?, ";
			$updateData.= "`nid` = ?, ";
			$updateData.= "`senddate` = ?, ";
			$updateData.= "`expectdate` = ? ";
			$query .= $updateData." WHERE `userid` = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			$stmt->bind_param("sssssssss", 
				$this->record['expectdate'], 
				$this->record['name'], 
				$this->record['bank'], 
				$this->record['method'], 
				$this->record['number'], 
				$this->record['nid'], 
				$this->record['senddate'], 
				$this->record['userid']);

				
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
		}
	} 

	function Delete() {
		global $mysqli;

		if ($this->record['userid'] > -1) {
			$stmt = $mysqli->prepare("DELETE FROM account WHERE userid = ?");
			$stmt->bind_param("s", $this->record['userid']);
			$stmt->execute();
			$stmt->close();
		}
	}
}


/*
class AccountObject {
	var $rs_account;

	# class member variable
	#***********************************************
	var $m_index;
	var $m_userid;
	var $m_name;
	var $m_bank;
	var $m_method;
	var $m_number;
	var $m_jumin;
	var $m_sendDate;
	var $m_expectDate;
	var $m_regDate;

	# Get property
	#***********************************************
	function userid() {
		$userid = $m_userid;
	} 

	function Bank() {
		$Bank = $m_bank;
	} 

	function Name() {
		$Name = $m_name;
	} 

	function Method() {
		switch ($m_method) {
			case 1:
				$retValue="CMS";
				break;
			case 2:
				$retValue="DIRECT";
				break;
			default:
				$retValue="GIRO";
				break;
		} 

		$Method = $retValue;
	} 

	function Jumin() {
		$Jumin = $m_jumin;
	} 

	function MethodCode() {
		$MethodCode = $m_method;
	} 

	function Number() {
		$Number = $m_number;
	} 

	function SendDate() {
		$SendDate = $m_sendDate;
	} 

	function ExpectDate() {
		$ExpectDate = $m_expectDate;
	} 

	# Set property
	#***********************************************
	function userid($value) {
		$m_userid = trim($value);
	} 

	function Bank($value) {
		$m_bank = trim($value);
	} 

	function Name($value) {
		$m_name = trim($value);
	} 

	function Method($value) {
		switch (trim($value)) {
			case "CMS":
				$m_method=1;
				break;
			case "DIRECT":
				$m_method=2;
				break;
			default:
				$m_method=3;
				break;
		} 
	} 

	function Jumin($value) {
		$m_jumin[0]=substr(trim($value),0,6);
		$m_jumin[1]=substr(trim($value),strlen(trim($value))-(7));
	} 

	function Number($value) {
		$m_number = trim($value);
	} 

	function SendDate($value) {
		$m_sendDate=intval($value);
	} 

	function ExpectDate($value) {
		$m_expectDate=intval($value);
	} 

	# class initialize
	#***********************************************
	function __construct() {
		$m_index=-1;
		$m_userid="";
		$m_name="";
		$m_bank="";
		$m_method=1;
		$m_number="";
		$m_jumin[2] = array("","");
		$m_sendDate=5;
		$m_expectDate=1;
		$m_regDate="";
	} 

	function __destruct() {
		$rs_account = null;
	}

	# class method
	#***********************************************
	function IsEnabledMessage() {
		return $m_msgOk;
	} 

	function Open($userid) {
		$m_userid = $userid;
		$query = "SELECT * from account WHERE userid = '".$mssqlEscapeString[$m_userid]."'";
		$rs_account = $objDB->execute_query($query);
		if ((!$rs_account->eof && !$rs_account->bof)) {
			$m_index=intval($rs_account["id"]);
			$m_userid = $rs_account["userid"];
			$m_name = $rs_account["name"];
			$m_bank = $rs_account["bank"];
			$m_method=intval($rs_account["method"]);
			$m_number = $rs_account["number"];
			$m_jumin[0]=substr($rs_account["nid"],0,6);
			$m_jumin[1]=substr($rs_account["nid"],strlen($rs_account["nid"])-(7));
			$m_sendDate=intval($rs_account["sendDate"]);
			$m_expectDate=intval($rs_account["expectDate"]);
			$m_regDate = $rs_account["regDate"];
		} 

	} 

	function Update() {
		if (($m_index==-1)) {
			#New Data
			$query = "INSERT INTO account (userid, name, bank, method, number, nid, sendDate, expectDate) VALUES ";
			$insertData="'".$mssqlEscapeString[$m_userid]."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_name]."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_bank]."',";
			$insertData = $insertData."'".$m_method."',";
			$insertData = $insertData."'".$m_number."',";
			$insertData = $insertData."'".$m_jumin[0].$m_jumin[1]."',";
			$insertData = $insertData."'".$m_sendDate."', ";
			$insertData = $insertData."'".$m_expectDate."'";
			$query = $query."(".$insertData.")";
			$objDB->execute_command($query);

			$query = "SELECT MAX(id) AS new_id FROM account WHERE userid = '".$m_userid."'";
			$rs_account = $objDB->execute_query($query);
			if ((!$rs_account->eof && !$rs_account->bof)) {
				$m_index=intval($rs_account["new_id"]);
			} 
		} else {
			$query = "UPDATE account SET ";
			$updateData="name = '".$mssqlEscapeString[$m_name]."', ";
			$updateData = $updateData."bank = '".$mssqlEscapeString[$m_bank]."', ";
			$updateData = $updateData."method = '".$m_method."', ";
			$updateData = $updateData."number = '".$m_number."', ";
			$updateData = $updateData."nid = '".$m_jumin[0].$m_jumin[1]."', ";
			$updateData = $updateData."sendDate = '".$m_sendDate."', ";
			$updateData = $updateData."expectDate = '".$m_expectDate."' ";
			$query = $query.$updateData." WHERE id = ".$m_index;
			$objDB->execute_command($query);
		} 
	} 

	function Delete() {
		if (($m_index>-1)) {
			$query = "DELETE FROM account WHERE id = ".$mssqlEscapeString[$m_index];
			$objDB->execute_command($query);
		} 
	}

	function setuserid($userid) {
		return Open($userid);
	} 
} 
*/
?>
