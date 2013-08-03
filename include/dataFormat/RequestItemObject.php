<?php 
# ************************************************************
#  Object : RequestItemObject
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************


class RequestItemObject {
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
		$this->record['reqItemId'] = -1;
		$this->record['reqId'] = "";
		$this->record['item'] = "";
		$this->record['descript'] = "";
		$this->record['cost'] = 0;
		$this->record['userId'] = "";
		$this->record['sendStatus'] = "s1001";
	}



	function Open($value) {
		global $mysqli;

		$column = array();
		/* create a prepared statement */
		$query = "SELECT reqId, item, descript, cost, userId, sendStatus FROM requestItem WHERE reqItemId = ? ";
		if ($stmt = $mysqli->prepare($query)) {

			/* bind parameters for markers */
			$stmt->bind_param("i", $value);

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
			$query = "INSERT INTO requestItem ('reqId', 'item', 'descript', 'cost', 'userId', 'sendStatus') VALUES ";

			$query = $query."(?, ?, ?, ?, ?, ?)";

			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("ississ", 
				$this->record['reqId'], 
				$this->record['item'], 
				$this->record['descript'], 
				$this->record['cost'], 
				$this->record['userId'],
				$this->record['sendStatus']);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();

			

			$stmt = $mysqli->prepare("SELECT MAX(reqItemId) as new_id FROM requestItem WHERE reqId = ?");
			$stmt->bind_param("s", $this->record['reqId']);
			$stmt->execute();
			$stmt->bind_result($this->record['reqItemId']);
			$stmt->close();
			
		} else {

			$query = "UPDATE requestItem SET ";
			$updateData = "`reqId` = ?, ";
			$updateData.= "`item` = ?, ";
			$updateData.= "`descript` = ?, ";
			$updateData.= "`cost` = ?, ";
			$updateData.= "`secret` = ?, ";
			$updateData.= "`userId` = ?, ";
			$updateData.= "`sendStatus` = ?, ";
			$query .= $updateData." WHERE `reqItemId` = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			# New Data
			$stmt->bind_param("ississ", 
				$this->record['reqId'], 
				$this->record['item'], 
				$this->record['descript'], 
				$this->record['cost'],
				$this->record['secret'], 
				$this->record['userId'],
				$this->record['sendStatus'],
				$this->record['reqItemId']);
				
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
		}
	}

	function Insert($userId,$supType) {
		$query = "SELECT supId from requestInfo WHERE userId = ? AND supType = ?";
		
		$supId = -1;
		$stmt = $mysqli->prepare($query);
				$stmt->bind_param("ss", $userId, $supType);
				$stmt->execute();
				$stmt->bind_result($supId);
				$stmt->close();

		if($supId > 0) {
			$query = "INSERT INTO requestItem (supId, reqItemId, cost) VALUES (?, ?, ?)";
			$stmt = $mysqli->prepare($query);
			$stmt->bind_param("iii", $supId, $this->record['reqItemId'], $this->record['cost']);
			$stmt->execute();
			$stmt->close();

		}
	} 

	function Delete() {
		global $mysqli;
		if ($this->record['reqItemId'] > -1) {
			$stmt = $mysqli->prepare("DELETE FROM requestItem WHERE reqItemId = ?");
			$stmt->bind_param("i", $this->record['reqItemId']);
			$stmt->execute();
			$stmt->close();
		}
	}


	function showPrice() {
		return priceFormat($this->record['cost'], 1);
	} 
}	

/*
class RequestItemObject {
	var $reqItemRS;

	var $m_reqItemId;
	var $m_reqId;
	var $m_item;
	var $m_descript;
	var $m_cost;
	var $m_userId;
	var $m_sendStatus;

	#  Get property
	# ***********************************************
	function RequestItemID() {
		$RequestItemID = $m_reqItemId;
	} 

	function RequestID() {
		$RequestID = $m_reqId;
	} 

	function RequestItem() {
		$RequestItem = $m_item;
	} 

	function Descript() {
		$Descript = $m_descript;
	} 

	function Cost() {
		$Cost = $m_cost;
	} 

	function HasSupport() {
		if ((strlen($m_userId)>0)) {
			$retValue=true;
		} else {
			$retValue=false;
		}

		$HasSupport = $retValue;
	} 

	function Status() {
		if ((strlen($m_userId)>0)) {
			$retValue="후원예약";
		} else {
			$retValue="미후원";
		} 

		$Status = $retValue;
	} 

	function SendUser() {
		$SendUser = $m_userId;
	} 

	function SendStatus() {
		$SendStatus = $m_sendStatus;
	} 

	#  Set property 
	# ***********************************************
	function RequestItemID($value) {
		$m_reqItemId=intval(trim($value));
	} 

	function RequestID($value) {
		$m_reqId=intval(trim($value));
	} 

	function RequestItem($value) {
		$m_item = trim($value);
	} 

	function Descript($value) {
		$m_descript = trim($value);
	} 

	function Cost($value) {
		$m_cost=intval($value);
	} 

	function SendUser($value) {
		$m_userId = trim($value);
		if ((strlen($m_userId)==0)) {
			$m_sendStatus = "S1001";
		} else {
			$m_sendStatus = "S1002";
		} 
	} 

	function Status($value) {
		$m_sendStatus = trim($value);
	} 

	#  class initialize
	# ***********************************************
	function __construct() {
		$m_reqItemId=-1;
		$m_reqId="";
		$m_item="";
		$m_descript="";
		$m_cost=0;
		$m_userId="";
		$m_sendStatus="S1001";
	} 

	function __destruct() {
		$reqItemRS = null;
	} 

	#  class method
	# ***********************************************
	function Open($reqItemId) {
		$query = "SELECT reqId, item, descript, cost, userId, sendStatus FROM requestItem WHERE reqItemId = '".$mssqlEscapeString[$reqItemId]."'";
		$reqItemRS = $objDB->execute_query($query);

		if ((!$reqItemRS->eof && !$reqItemRS->bof)) {
			$m_reqItemId=intval($reqItemId);
			$m_reqId=intval($reqItemRS["reqId"]);
			$m_item = $reqItemRS["item"];
			$m_descript = $reqItemRS["descript"];
			$m_cost = $reqItemRS["cost"];
			$m_userId = $reqItemRS["userId"];
			$m_sendStatus = $reqItemRS["sendStatus"];
		} 
	} 

	function Update() {
		if ((intval($m_reqItemId)==-1)) {
			# New Data
			$query = "INSERT INTO requestItem (reqId, item, descript, cost, userId, sendStatus) VALUES ";
			$insertData="'".$m_reqId."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_item]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_descript]."', ";
			$insertData = $insertData."'".$m_cost."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_userId]."', ";
			$insertData = $insertData."'".$m_sendStatus."'";
			$query = $query."(".$insertData.")";
			$objDB->execute_command($query);

			$query = "SELECT MAX(reqItemId) AS new_id FROM requestItem WHERE reqId = '".$m_reqId."'";
			$reqItemRS = $objDB->execute_query($query);
			if ((!$reqItemRS->eof && !$reqItemRS->bof)) {
				$m_reqItemId=intval($reqItemRS["new_id"]);
			} 
		} else {
			$query = "UPDATE requestItem SET ";
			$updateData="reqId = '".$m_reqId."', ";
			$updateData = $updateData."item = '".$mssqlEscapeString[$m_item]."', ";
			$updateData = $updateData."descript = '".$mssqlEscapeString[$m_descript]."', ";
			$updateData = $updateData."cost = '".$m_cost."', ";
			$updateData = $updateData."userId = '".$mssqlEscapeString[$m_userId]."', ";
			$updateData = $updateData."sendStatus = '".$m_sendStatus."' ";
			$query = $query.$updateData." WHERE reqItemId = ".$m_reqItemId;
			$objDB->execute_command($query);
		} 
	} 

	function Insert($userId,$supType) {
		$query = "SELECT supId from requestInfo WHERE userId = '".$mssqlEscapeString[$userId]."' AND supType = '".$mssqlEscapeString[$supType]."'";
		$supportRS = $objDB->execute_query($query);

		if ((!$supportRS->eof && !$supportRS->bof)) {
			$query = "INSERT INTO requestItem (supId, reqItemId, cost) VALUES ";
			$insertData="'".$supportRS["supId"]."', ";
			$insertData = $insertData."'".$m_requestItemId."', ";
			$insertData = $insertData."'".$m_cost."'";
			$query = $query."(".$insertData.")";
			$objDB->execute_command($query);
		} 

		$supportRS = null;
	} 

	function Delete() {
		$query = "delete from requestItem WHERE reqItemId = '".$mssqlEscapeString[$m_reqItemId]."'";
		$objDB->execute_command($query);
	} 

	function showPrice() {
		return priceFormat($m_cost, 1);
	} 
} 

*/
?>
