<?php 
# ************************************************************
#  Object : RequestObject
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************

class RequestObject {
	protected $record = array();

	public function __set($name,$value) { 
		switch($name) {
			case "supportType" : 
				switch (($value)) {
					case "03001":
						$value="특별후원";
						break;
					case "03002":
						$value="센터후원";
						break;
					case "03003":
						$value="자원봉사";
						break;
				} 
				break;

			case "fileImage" :
				if ((strlen($this->record["fileImage"])==0)) {
					$this_record["fileImage"] = "noimg.gif";
				} 
				$this_record["fileImage"] = "/upload/support/".$m_fileImage;
				break;

			default :
				$this->record[$name] = $value;
				break;	
		}
	}


	public function __get($name) { 
		switch($name) {
			case "fileImage":
				return $this->record["fileImage"];
			default : 
				return $this->record[$name];
		}
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
    $c_Helper = new CodeHelper();

		$this->record['regId'] = -1;
		$this->record['title'] = "";
		$this->record['explain'] = "";
		$this->record['supportType'] = $c_Helper->getSupportCode(2);
		$this->record['regDate'] = -1;
		$this->record['imageId'] = -1;
		$this->record['fileImage'] = "noimg.gif";
	}


	function Open($value) {
		global $mysqli;

		$column = array();
		/* create a prepared statement */
		$query = "SELECT `reqId`, `title`, `explain`, `supportType`, `regDate`, `imageId` FROM requestInfo WHERE `reqId` = ? ";

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


			if ($this->record['imageId'] > 0) {
				$stmt = $mysqli->prepare("SELECT name FROM attachFile WHERE id = ?");
				$stmt->bind_param("i", $this->record['imageId']);
				$stmt->execute();
				$stmt->bind_result($this->record["fileImage"]);
				$stmt->close();
			} 
			//else {
			//	$this->mDocument = $this->record["fileImage"];
			//} 
		}
	}


	function Update() {
		global $mysqli;

		if ($this->record['regId'] == -1) {
			$query = "INSERT INTO requestInfo (`title`, `explain`, `supportType`, `imageId`) VALUES ";
			$query = $query."(?, ?, ?, ?)";

			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("sssi", 
				$this->record['title'], 
				$this->record['explain'],
				$this->record['supportType'], 
				$this->record['imageId']);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();

			$this->record['regId'] = $mysqli->insert_id;
		} else {

			$query = "UPDATE requestInfo SET ";
			$updateData = "`title` = ?, ";
			$updateData = "`explain` = ?, ";
			$updateData.= "`supportType` = ?, ";
			$updateData.= "`imageId` = ? ";
			$query .= $updateData." WHERE `regId` = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			$stmt->bind_param("sssii", 
				$this->record['title'], 
				$this->record['explain'], 
				$this->record['supportType'], 
				$this->record['imageId'],
				$this->record['regId']);
				
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
		}
	}

	function Delete() {
		global $mysqli;

		if ($this->record['reqId'] > -1) {
			$stmt = $mysqli->prepare("DELETE FROM requestInfo WHERE `reqId` = ?");
			$stmt->bind_param("i", $this->record['regId']);
			$stmt->execute();
			$stmt->close();
		}
	}  
}	

/*
class RequestObject {
	#  class member variable
	# ***********************************************
	var $m_reqId;
	var $m_title;
	var $m_explain;
	var $m_supType;
	var $m_regDate;
	var $m_fileImage;
	var $m_imageId;

	#  Get property
	# ***********************************************
	function RequestID() {
		$RequestID = $m_reqId;
	} 

	function Title() {
		$Title = $m_title;
	} 

	function Explain() {
		$Explain = $m_explain;
	} 

	function SupportType() {
		switch (($m_supType)) {
			case "03001":
				$retValue="특별후원";
				break;
			case "03002":
				$retValue="센터후원";
				break;
			case "03003":
				$retValue="자원봉사";
				break;
		} 
		$SupportType = $retValue;
	} 

	function SupportTypeCode() {
		$SupportTypeCode = $m_supType;
	} 

	function RegistDate() {
		$RegistDate = $m_regDate;
	} 

	function Image() {
		if ((strlen($m_fileImage)==0)) {
			$m_fileImage="noimg.gif";
		} 

		$Image="/upload/support/".$m_fileImage;
	} 

	function ImageID() {
		$ImageID = $m_imageId;
	} 

	#  Set property 
	# ***********************************************
	function RequestID($value) {
		$m_reqId=intval(trim($value));
	} 

	function Title($value) {
		$m_title = trim($value);
	} 

	function Explain($value) {
		$m_explain = trim($value);
	} 

	function SupportType($value) {
		$m_supType = trim($value);
	} 

	function ImageID($value) {
		$m_imageId=intval($value);
	} 

	#  class initialize
	# ***********************************************
	function __construct() {
		$c_Helper = new CodeHelper();

		$m_reqId=-1;
		$m_title="";
		$m_explain="";
		$m_supType = $c_Helper->getSupportCode(2);
		$m_regDate="";
		$m_fileImage="noimg.gif";
		$m_imageId=-1;
	} 

	function __destruct() {
	} 

	#  class method
	# ***********************************************
	function IsNew() {
		if (($m_reqtId<0)) {
			return true;
		} else {
			return false;
		} 
	} 

	function Open($reqId) {
		$query = "SELECT reqId, title, explain, supportType, regDate, imageId FROM requestInfo WHERE reqId = '".$mssqlEscapeString[$reqId]."'";
		$reqInfoRS = $objDB->execute_query($query);

		if ((!$reqInfoRS->eof && !$reqInfoRS->bof)) {
			$m_reqId=intval($reqInfoRS["reqId"]);
			$m_title = $reqInfoRS["title"];
			$m_explain = $reqInfoRS["explain"];
			$m_supType = $reqInfoRS["supportType"];
			$m_regDate = $reqInfoRS["regDate"];
			$m_imageId=intval($reqInfoRS["imageId"]);

			$query = "SELECT name FROM attachFile WHERE id = '".$m_imageId."'";
			$attachRS = $objDB->execute_query($query);
			if ((!$attachRS->eof && !$attachRS->bof)) {
				$m_fileImage = $attachRS["name"];
			} 
		} 

		$attachRS = null;
		$reqInfoRS = null;
	} 

	function Update() {
		if (($m_reqId==-1)) {
			# New Data
			$query = "INSERT INTO requestInfo (title, explain, supportType, imageId) VALUES ";
			$insertData="'".$mssqlEscapeString[$m_title]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_explain]."', ";
			$insertData = $insertData."'".$m_supType."', ";
			$insertData = $insertData."'".$m_imageId."' ";
			$query = $query."(".$insertData.")";
			$objDB->execute_command($query);

			$query = "SELECT max(reqId) as new_id from requestInfo WHERE title = '".$mssqlEscapeString[$m_title]."'";
			$reqInfoRS = $objDB->execute_query($query);
			if ((!$reqInfoRS->eof && !$reqInfoRS->bof)) {
				$m_reqId=intval($reqInfoRS["new_id"]);
			} 
		} else {
			$query = "UPDATE requestInfo SET ";
			$updateData="title = '".$mssqlEscapeString[$m_title]."', ";
			$updateData = $updateData."explain = '".$mssqlEscapeString[$m_explain]."', ";
			$updateData = $updateData."supportType = '".$m_supType."',";
			$updateData = $updateData."imageId = '".$m_imageId."'";
			$query = $query.$updateData." WHERE reqId = ".$m_reqId;
			$objDB->execute_command($query);
		} 

		$reqInfoRS = null;
	} 

	function Delete() {
		$query = "delete from requestInfo where reqId = '".$m_reqId."'";
		$objDB->execute_command($query);
	} 
} 
*/
?>
