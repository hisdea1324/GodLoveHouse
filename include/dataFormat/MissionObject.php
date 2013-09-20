<?php
class MissionObject {
	protected $record = array();
	protected $family = array();
	
	public function __set($name,$value) { 
		$this->record[$name] = $value; 
	}

	public function __get($name) { 
		return $this->record[$name];
	}

	public function __isset($name) {
		return isset($this->record[$name]); 
  }
  
  function __construct($idx = -1) {
  	if ($idx == -1) {
  		$this->initialize();
  	} else {
  		$this->Open($idx);
  	}
	}

	function initialize() {
		$this->record['userId'] = "";
		$this->record['missionName'] = "";
		$this->record['church'] = "";
		$this->record['churchContact'] = "";
		$this->record['ngo'] = "";
		$this->record['ngoContact'] = "";
		$this->record['nationCode'] = "";
		$this->record['nation'] = "";
		$this->record['accountNo'] = "";
		$this->record['bank'] = "";
		$this->record['accountName'] = "";
		$this->record['homepage'] = "";
		$this->record['manager'] = "";
		$this->record['managerContact'] = "";
		$this->record['managerEmail'] = "";
		$this->record['memo'] = "";
		$this->record['prayList'] = "";
		$this->record['familyCount'] = "";
		$this->record['fileImage'] = "noimg.gif";
		$this->record['imageId'] = 0;
		$this->record['approval'] = 0;
		$this->record['flagFamily'] = "";
		$this->record['family'] = "";
	}
	
	function Open($userId) {
		global $mysqli;

		$column = array();
		$query = "SELECT A.*, B.name AS nation";
		$query.= " FROM missionary A, code B ";
		$query.= " WHERE A.userId = ? AND A.nationCode = B.code";

		if ($stmt = $mysqli->prepare($query)) {
			$stmt->bind_param("s", $userId);
			$stmt->execute();
			
			$metaResults = $stmt->result_metadata();
			$fields = $metaResults->fetch_fields();
			$statementParams='';
			
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

			$stmt->close();


			if($this->record['imageId'] > 0) {
				$query = "SELECT `name` FROM attachFile WHERE `id` = '" . $this->record['imageId'] . "'";

				if ($result = $mysqli->query($query)) {

				    while ($finfo = $result->fetch_field()) {
				    	$this->record['fileImage'] = $finfo->name;
				    }
				    $result->close();
				}
			}
			
			//나중에 확인 해볼 것 
			$familyId = -1;
			if (($this->record['familyCount'] > 0)) {
				$stmt = $mysqli->prepare("SELECT `id` FROM missionary_family WHERE `userId` = ?");
				$stmt->bind_param("s", $this->record['userId']);
				$stmt->execute();
				$stmt->bind_result($familyId);
				while ($stmt->fetch()) {
//					$mFamily->Open($familyId);
					$this->family[] = new MissionaryFamily($familyId);;
				}
   				$stmt->close();
			}
		}
	}
	
	function Update() {
		global $mysqli;


		if (($this->record['userId'] == "")) {
			$query = "INSERT INTO missionary (`userId`, `missionName`, `church`, `churchContact`, ";
			$query = $query."`ngo`, `ngoContact`, `nationCode`, `accountNo`, ";
			$query = $query."`bank`, `accountName`, `homepage`, `manager`, ";	
			$query = $query."`managerContact`, `managerEmail`, `memo`, `prayList`, ";
			$query = $query."`approval`, `imageId`, `flagFamily`) VALUES ";
			$query = $query."(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("ssssssssssssssssiss", 
				$this->record['userId'], 
				$this->record['missionName'], 
				$this->record['church'], 
				$this->record['churchContact'], 
				$this->record['ngo'], 
				$this->record['ngoContact'], 
				$this->record['nationCode'], 
				$this->record['accountNo'], 
				$this->record['bank'], 
				$this->record['accountName'], 
				$this->record['homepage'], 
				$this->record['manager'],
				$this->record['managerContact'],
				$this->record['managerEmail'],
				$this->record['memo'],
				$this->record['prayList'],
				$this->record['approval'],
				$this->record['imageId'],
				$this->record['flagFamily']);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
			
		} else {

			$query = "UPDATE missionary SET ";
			$updateData = "`missionName` = ?, ";
			$updateData.= "`church` = ?, ";
			$updateData.= "`churchContact` = ?, ";
			$updateData.= "`ngo` = ?, ";
			$updateData.= "`ngoContact` = ?, ";
			$updateData.= "`nationCode` = ?, ";
			$updateData.= "`accountNo` = ?, ";
			$updateData.= "`bank` = ?, ";
			$updateData.= "`accountName` = ?, ";
			$updateData.= "`homepage` = ?, ";
			$updateData.= "`manager` = ?, ";
			$updateData.= "`managerContact` = ?, ";
			$updateData.= "`managerEmail` = ?, ";
			$updateData.= "`memo` = ?, ";
			$updateData.= "`prayList` = ?, ";
			$updateData.= "`approval` = ?, ";
			$updateData.= "`imageId` = ?, ";
			$updateData.= "`flagFamily` = ? ";
			$query .= $updateData." WHERE `userId` = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			$stmt->bind_param("ssssssssssssssssiss", 
				$this->record['missionName'], 
				$this->record['church'], 
				$this->record['churchContact'], 
				$this->record['ngo'], 
				$this->record['ngoContact'], 
				$this->record['nationCode'], 
				$this->record['accountNo'], 
				$this->record['bank'], 
				$this->record['accountName'], 
				$this->record['homepage'], 
				$this->record['manager'],
				$this->record['managerContact'],
				$this->record['managerEmail'],
				$this->record['memo'],
				$this->record['prayList'],
				$this->record['approval'],
				$this->record['imageId'],
				$this->record['flagFamily']);
				$this->record['userId'];
				
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
		}
	} 
	
	function Delete() {
		global $mysqli;

		if ($this->record['userId'] > -1) {
			$stmt = $mysqli->prepare("DELETE FROM missionary WHERE `userId` = ?");
			$stmt->bind_param("s", $this->record['userId']);
			$stmt->execute();
			$stmt->close();
		}
	} 
	
	function GetFamilyPrayCount($userId) {
		$resultCount = -1;
		$query = "SELECT ";
		$query.= " SUM(CASE WHEN `familyType` = 'F0001' THEN 1 ELSE 0 END) AS FamilyPrayCnt ";
		$query.= " FROM family WHERE `userId` = ? ";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param("s", $this->record['userId']);
		$stmt->execute();
		$stmt->bind_result($resultCount);
		$stmt->close();

		return $resultCount;
	}


	
  
}
?>