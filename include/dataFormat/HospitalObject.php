<?php 
# ************************************************************
#  Object : HospitalObject
# 
#  editor : Sookbun Lee 
#  last update date : 2012.02.10
# ************************************************************


class HospitalObject {
	protected $record = array();
	protected $image = array();

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
		$this->record['hospitalId'] = -1;
		$this->record['hospitalName'] = -1;
		$this->record['assocName'] = "";
		$this->record['userId'] = "";
		$this->record['manager1'] = "";
		$this->record['manager2'] = "";
		$this->record['contact1'] = "";
		$this->record['contact2'] = "";
		$this->record['price'] = 0;
		$this->record['zipcode'] = "000-000";
		$this->record['address1'] = "";
		$this->record['address2'] = "";
		$this->record['explain'] = "";
		$this->record['regionCode'] = "";
		$this->record['regDate'] = "";
		$this->record['status'] = "s2001";
		$this->record['homepage'] = "";
		$this->record['personLimit'] = 0;
		$this->record['document'] = "";
		$this->record['documentId'] = -1;
		$this->record['imageId1'] = -1;
		$this->record['imageId2'] = -1;
		$this->record['imageId3'] = -1;
		$this->record['imageId4'] = -1;
	}

	function Open($value) {
		global $mysqli;

		$column = array();
		/* create a prepared statement */
		$query = "SELECT * from hospital WHERE hospitalId = ? ";

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


			if ($this->record['imageId1'] > 0) {
				$query = "SELECT name FROM attachFile WHERE id = '".$this->record['imageId1']."'";
				if ($result = $mysqli->query($query)) {
				    /* Get field information for all columns */
				    while ($finfo = $result->fetch_field()) {
				    	$this->image[0] = $finfo->name;
				    }
				    $result->close();
				}
			} 
			if ($this->record['imageId2'] > 0) {
				$query = "SELECT name FROM attachFile WHERE id = '".$this->record['imageId2']."'";
				if ($result = $mysqli->query($query)) {
				    /* Get field information for all columns */
				    while ($finfo = $result->fetch_field()) {
				    	$this->image[1] = $finfo->name;
				    }
				    $result->close();
				}
			} 
			if ($this->record['imageId3'] > 0) {
				$query = "SELECT name FROM attachFile WHERE id = '".$this->record['imageId3']."'";
				if ($result = $mysqli->query($query)) {
				    /* Get field information for all columns */
				    while ($finfo = $result->fetch_field()) {
				    	$this->image[2] = $finfo->name;
				    }
				    $result->close();
				}
			} 
			if ($this->record['imageId4'] > 0) {
				$query = "SELECT name FROM attachFile WHERE id = '".$this->record['imageId4']."'";
				if ($result = $mysqli->query($query)) {
				    /* Get field information for all columns */
				    while ($finfo = $result->fetch_field()) {
				    	$this->image[3] = $finfo->name;
				    }
				    $result->close();
				}
			} 
		}
	}

	function Update() {
		global $mysqli;


		if (($this->record['id'] == -1)) {
			$query = "INSERT INTO hospital (`hospitalName`, `assocName`, 'address1', 'address2', ";
			$query.= "'zipcode', 'regionCode', 'explain', 'userId', 'manager1', 'manager2', 'contact1', 'contact2', ";
			$query.= "'price', 'personLimit', 'homepage',  ";
			$query.= "'documentId', 'document', 'imageId1', 'imageId2', 'imageId3', 'imageId4') VALUES ";
			$query = $query."(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			$stmt = $mysqli->prepare($query);

			# New Data
			$stmt->bind_param("ssssssssssssiisisssss", 
				$this->record['hospitalName'],
				$this->record['assocName'],
				$this->record['address1'],
				$this->record['address2'],
				$this->record['zipcode'],
				$this->record['regionCode'],
				$this->record['explain'],
				$this->record['userId'],
				$this->record['manager1'],
				$this->record['manager2'],
				$this->record['contact1'],
				$this->record['contact2'],
				$this->record['price'],
				$this->record['personLimit'],
				$this->record['homepage'],
				$this->record['documentId'],
				$this->record['document'],
				$this->record['imageId1'],
				$this->record['imageId2'],
				$this->record['imageId3'],
				$this->record['imageId4']);

			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();

			$stmt = $mysqli->prepare("SELECT MAX(hospitalId) as new_id FROM hospital WHERE hospitalName = ?");
			$stmt->bind_param("s", $this->record['hospitalName']);
			$stmt->execute();
			$stmt->bind_result($this->record['hospitalId']);
			$stmt->close();


			
		} else {

			$query = "UPDATE hospital SET ";
			$updateData = "`hospitalName` = ?, ";
			$updateData.= "`assocName` = ?, ";
			$updateData.= "`address1` = ?, ";
			$updateData.= "`address2` = ?, ";
			$updateData.= "`regionCode` = ?, ";
			$updateData.= "`zipcode` = ?, ";
			$updateData.= "`explain` = ?, ";
			$updateData.= "`userId` = ?, ";
			$updateData.= "`manager1` = ?, ";
			$updateData.= "`manager2` = ?, ";
			$updateData.= "`contact1` = ?, ";
			$updateData.= "`contact2` = ?, ";
			$updateData.= "`price` = ?, ";
			$updateData.= "`personLimit` = ?, ";
			$updateData.= "`homepage` = ?, ";
			$updateData.= "`status` = ?, ";
			$updateData.= "`document` = ?, ";
			$updateData.= "`documentId` = ?, ";
			$updateData.= "`imageId1` = ?, ";
			$updateData.= "`imageId2` = ?, ";
			$updateData.= "`imageId3` = ?, ";
			$updateData.= "`imageId4` = ? ";
			$query .= $updateData." WHERE `hospitalId` = ?";

			# create a prepared statement
			$stmt = $mysqli->prepare($query);
			
			$stmt->bind_param("ssssssssssssiisisssss", 
				$this->record['hospitalName'],
				$this->record['assocName'],
				$this->record['address1'],
				$this->record['address2'],
				$this->record['zipcode'],
				$this->record['regionCode'],
				$this->record['explain'],
				$this->record['userId'],
				$this->record['manager1'],
				$this->record['manager2'],
				$this->record['contact1'],
				$this->record['contact2'],
				$this->record['price'],
				$this->record['personLimit'],
				$this->record['homepage'],
				$this->record['documentId'],
				$this->record['document'],
				$this->record['imageId1'],
				$this->record['imageId2'],
				$this->record['imageId3'],
				$this->record['imageId4'],
				$this->record['hospitalId']);

				
			# execute query
			$stmt->execute();
		
			# close statement
			$stmt->close();
		}
	} 

	function Delete() {
		global $mysqli;

		if ($this->record['id'] > -1) {
			$stmt = $mysqli->prepare("DELETE FROM hospital WHERE hospitalId = ?");
			$stmt->bind_param("s", $this->record['hospitalId']);
			$stmt->execute();
			$stmt->close();
		}
	}

	function showContactInfo() {
		$cont1 = join("-", $this->record['contact1']);
		$cont2 = join("-", $this->record['contact2']);
		if ((strlen($cont1)>10)) {
			$retString = $this->record['manager1']." ".$this->record['contact1'];
		} 

		if ((strlen($cont2)>10)) {
			$retString = $retString." / ".$this->record['manager2']." ".$this->record['contact2'];
		} 

		return $retString;
	} 



	function showFee() {
		if (($this->record['price'] > 0)) {
			$retString = priceFormat($this->record['id'], 1)."/일";
		} else {
			$retString = "무료";
		} 
		return $retString;
	}


}	



/*




class HospitalObject {
	var $hospitalRS;
	var $attachRS;

	#  class member variable
	# ***********************************************
	var $m_hospitalId;
	var $m_hospitalName;
	var $m_assocName;
	var $m_userId;
	var $m_manager1;
	var $m_manager2;
	var $m_contact1;
	var $m_contact2;
	var $m_price;
	var $m_zipcode;
	var $m_address1;
	var $m_address2;
	var $m_explain;
	var $m_regionCode;
	var $m_regDate;
	var $m_status;
	var $m_homepage;
	var $m_personLimit;
	var $m_document;
	var $m_documentId;
	var $m_image;
	var $m_imageId;

	#  Get property
	# ***********************************************
	function HospitalID() {
		$HospitalID = $m_hospitalId;
	} 

	function HospitalName() {
		$HospitalName = $m_hospitalName;
	} 

	function AssocName() {
		$AssocName = $m_assocName;
	} 

	function UserID() {
		$UserID = $m_userId;
	} 

	function Manager1() {
		$Manager1 = $m_manager1;
	} 

	function Contact1() {
		if ((count($m_contact1)!=2)) {
			$m_contact1 = array("","","");
		} 

		$Contact1 = $m_contact1;
	} 

	function Manager2() {
		$Manager2 = $m_manager2;
	} 

	function Contact2() {
		if ((count($m_contact2)!=2)) {
			$m_contact2 = array("","","");
		} 

		$Contact2 = $m_contact2;
	} 

	function Price() {
		$Price = $m_price;
	} 

	function PersonLimit() {
		$PersonLimit = $m_personLimit;
	} 

	function RegionCode() {
		$RegionCode = $m_regionCode;
	} 

	function Region() {
		$c_Helper = new CodeHelper();
		$Region = $c_Helper->getCodeName($m_regionCode);
	} 

	function Zipcode() {
		$Zipcode = $m_zipcode;
	} 

	function Post() {
		$Post = $m_zipcode;
	} 

	function Address1() {
		$Address1 = $m_address1;
	} 

	function Address2() {
		$Address2 = $m_address2;
	} 

	function Explain() {
		$retValue=str_replace(chr(13),"<br>",$m_explain);

		$Explain = $retValue;
	} 

	function RegDate() {
		$RegDate = $m_regDate;
	} 

	function Status() {
		$c_Helper = new CodeHelper();
		$Status = $c_Helper->getCodeName($m_status);
	} 

	function StatusCode() {
		$StatusCode = $m_status;
	} 

	function HomepageNoLink() {
		$HomepageNoLink = $m_homepage;
	} 

	function Homepage() {
		if ((!isset($m_homepage) || strlen($m_homepage)==0)) {
			$retValue="없음";
		}
			else
		if ((substr($m_homepage,0,"4")!="http")) {
			$retValue="<a href='http:# ".$m_homepage."' target='_blank'>http:# ".$m_homepage."</a>";
		} else {
			$retValue="<a href='".$m_homepage."' target='_blank'>".$m_homepage."</a>";
		} 

		$Homepage = $retValue;
	} 

	function DocumentID() {
		$DocumentID = $m_documentId;
	} 

	function Document() {
		if ((strlen($m_document)>0)) {
			$retValue="<a href=\"/upload/room/".$m_document."\">".$m_document."</a>";
		} 

	} 

	function ImageID1() {
		$ImageID1 = $m_imageId[0];
	} 

	function ImageID2() {
		$ImageID2 = $m_imageId[1];
	} 

	function ImageID3() {
		$ImageID3 = $m_imageId[2];
	} 

	function ImageID4() {
		$ImageID4 = $m_imageId[3];
	} 

	function Image1() {
		if ((strlen($m_image[0])>0)) {
			$retValue = $m_image[0];
		} else {
			$retValue="ex_01.gif";
		} 

		$Image1="/upload/room/".$retValue;
	} 

	function Image2() {
		if ((strlen($m_image[1])>0)) {
			$retValue = $m_image[1];
		} else {
			$retValue="ex_01.gif";
		} 

		$Image2="/upload# room/".$retValue;
	} 

	function Image3() {
		if ((strlen($m_image[2])>0)) {
			$retValue = $m_image[2];
		} else {
			$retValue="ex_01.gif";
		} 

		$Image3="/upload# room/".$retValue;
	} 

	function Image4() {
		if ((strlen($m_image[3])>0)) {
			$retValue = $m_image[3];
		} else {
			$retValue="ex_01.gif";
		} 

		$Image4="/upload/room/".$retValue;
	} 

	#  Set property 
	# ***********************************************
	function HospitalID($value) {
		$m_hospitalId=intval($value);
	} 

	function HospitalName($value) {
		$m_hospitalName = trim($value);
	} 

	function AssocName($value) {
		$m_assocName = trim($value);
	} 

	function UserID($value) {
		$m_userId = trim($value);
	} 

	function Manager1($value) {
		$m_manager1 = trim($value);
	} 

	function Contact1($value) {
		$m_contact1[0] = trim($value[0]);
		$m_contact1[1] = trim($value[1]);
		$m_contact1[2] = trim($value[2]);
	} 

	function Manager2($value) {
		$m_manager2 = trim($value);
	} 

	function Contact2($value) {
		$m_contact2[0] = trim($value[0]);
		$m_contact2[1] = trim($value[1]);
		$m_contact2[2] = trim($value[2]);
	} 

	function Price($value) {
		$m_price=intval($value);
	} 

	function PersonLimit($value) {
		$m_personLimit=intval($value);
	} 

	function RegionCode($value) {
		$m_regionCode = trim($value);
	} 

	function Zipcode($value) {
		$m_zipcode[0] = trim($value[0]);
		$m_zipcode[1] = trim($value[1]);
	} 

	function Post($value) {
		$m_zipcode[0] = trim($value[0]);
		$m_zipcode[1] = trim($value[1]);
	} 

	function Address1($value) {
		$m_address1 = trim($value);
	} 

	function Address2($value) {
		$m_address2 = trim($value);
	} 

	function Explain($value) {
		$m_explain = trim($value);
	} 

	function RegDate($value) {
		$m_regDate = trim($value);
	} 

	function Status($value) {
		$m_status = trim($value);
	} 

	function Homepage($value) {
		$m_homepage = trim($value);
	} 

	function DocumentID($value) {
		$m_documentId = trim($value);
	} 

	function Document($value) {
		$m_document = trim($value);
	} 

	function ImageID1($value) {
		$m_imageId[0]=intval($value);
	} 

	function ImageID2($value) {
		$m_imageId[1]=intval($value);
	} 

	function ImageID3($value) {
		$m_imageId[2]=intval($value);
	} 

	function ImageID4($value) {
		$m_imageId[3]=intval($value);
	} 

	#  class initialize
	# ***********************************************
	function __construct() {
		$m_hospitalId=-1;
		$m_hospitalName="";
		$m_assocName="";
		$m_userId="";
		$m_manager1="";
		$m_manager2="";
		$m_price=0;
		$m_personLimit=0;
		$m_zipcode[2] = array("","");
		$m_address1="";
		$m_address2="";
		$m_explain="";
		$m_regionCode="";
		$m_regDate="";
		$m_status="S2001";
		$m_homepage="";
		$m_document="";
		$m_documentId=-1;
		$m_image[4] = array("ex_01.gif","ex_01.gif","ex_01.gif","ex_01.gif");
		$m_imageId[0]=-1;
		$m_imageId[1]=-1;
		$m_imageId[2]=-1;
		$m_imageId[3]=-1;
	} 

	function __destruct() {
		$attachRS = null;
		$hospitalRS = null;
	} 

	#  class method
	# ***********************************************
	function Open($hospitalId) {
		$query = "SELECT * from hospital WHERE hospitalId = '".$mssqlEscapeString[$hospitalId]."'";
		$hospitalRS = $objDB->execute_query($query);
		if ((!$hospitalRS->eof && !$hospitalRS->bof)) {
			$m_hospitalId=intval($hospitalRS["hospitalId"]);
			$m_userid = $hospitalRS["userid"];
			$m_hospitalName = $hospitalRS["hospitalName"];
			$m_assocName = $hospitalRS["assocName"];
			$m_manager1 = $hospitalRS["manager1"];
			$m_manager2 = $hospitalRS["manager2"];
			$m_contact1=explode("-",$hospitalRS["contact1"]);
			$m_contact2=explode("-",$hospitalRS["contact2"]);
			$m_price=intval($hospitalRS["price"]);
			$m_personLimit=intval($hospitalRS["personLimit"]);
			$m_zipcode[0]=substr($hospitalRS["zipcode"],0,3);
			$m_zipcode[1]=substr($hospitalRS["zipcode"],strlen($hospitalRS["zipcode"])-(3));
			$m_address1 = $hospitalRS["address1"];
			$m_address2 = $hospitalRS["address2"];
			$m_explain = $hospitalRS["explain"];
			$m_regionCode = $hospitalRS["regionCode"];
			$m_regDate = $hospitalRS["regDate"];
			$m_status = $hospitalRS["status"];
			$m_homepage = $hospitalRS["homepage"];
			$m_documentId = $hospitalRS["documentId"];
			$m_imageId[0]=intval($hospitalRS["imageId1"]);
			$m_imageId[1]=intval($hospitalRS["imageId2"]);
			$m_imageId[2]=intval($hospitalRS["imageId3"]);
			$m_imageId[3]=intval($hospitalRS["imageId4"]);

			if (($m_documentId>0)) {
				$query = "SELECT name FROM attachFile WHERE id = '".$m_documentId."'";
				$attachRS = $objDB->execute_query($query);
				if ((!$attachRS->eof && !$attachRS->bof)) {
					$m_document = $attachRS["name"];
				} 
			} else {
				$m_document = $hospitalRS["document"];
			} 

			if (($m_imageId[0]>0)) {
				$query = "SELECT name FROM attachFile WHERE id = '".$m_imageId[0]."'";
				$attachRS = $objDB->execute_query($query);
				if ((!$attachRS->eof && !$attachRS->bof)) {
					$m_image[0] = $attachRS["name"];
				} 
			} 

			if (($m_imageId[1]>0)) {
				$query = "SELECT name FROM attachFile WHERE id = '".$m_imageId[1]."'";
				$attachRS = $objDB->execute_query($query);
				if ((!$attachRS->eof && !$attachRS->bof)) {
					$m_image[1] = $attachRS["name"];
				} 
			} 

			if (($m_imageId[2]>0)) {
				$query = "SELECT name FROM attachFile WHERE id = '".$m_imageId[2]."'";
				$attachRS = $objDB->execute_query($query);
				if ((!$attachRS->eof && !$attachRS->bof)) {
					$m_image[2] = $attachRS["name"];
				} 
			} 

			if (($m_imageId[3]>0)) {
				$query = "SELECT name FROM attachFile WHERE id = '".$m_imageId[3]."'";
				$attachRS = $objDB->execute_query($query);
				if ((!$attachRS->eof && !$attachRS->bof)) {
					$m_image[3] = $attachRS["name"];
				} 
			} 
		} 

		$attachRS = null;
		$hosepitalRS = null;
	} 

	function Update() {
		if (($m_hospitalId==-1)) {
			# New Data
			$query = "INSERT INTO hospital (hospitalName, assocName, address1, address2, zipcode, regionCode, explain, userId, ";
			$query = $query."manager1, manager2, contact1, contact2, price, personLimit, homepage, documentId, document, imageId1, imageId2, imageId3, imageId4) VALUES ";
			$insertData="'".$mssqlEscapeString[$m_hospitalName]."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_assocName]."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_address1]."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_address2]."',";
			$insertData = $insertData."'".$m_zipcode[0].$m_zipcode[1]."',";
			$insertData = $insertData."'".$m_regionCode."',";
			$insertData = $insertData."'".$mssqlEscapeString[$m_explain]."',";
			$insertData = $insertData."'".$m_userId."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_manager1]."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_manager2]."', ";
			$insertData = $insertData."'".$m_contact1[0]."-".$m_contact1[1]."-".$m_contact1[2]."', ";
			$insertData = $insertData."'".$m_contact2[0]."-".$m_contact2[1]."-".$m_contact2[2]."', ";
			$insertData = $insertData."'".$m_price."', ";
			$insertData = $insertData."'".$m_personLimit."', ";
			$insertData = $insertData."'".$mssqlEscapeString[$m_homepage]."',";
			$insertData = $insertData."'".$m_documentId."', ";
			$insertData = $insertData."'".$m_document."', ";
			$insertData = $insertData."'".$m_imageId[0]."', ";
			$insertData = $insertData."'".$m_imageId[1]."', ";
			$insertData = $insertData."'".$m_imageId[2]."', ";
			$insertData = $insertData."'".$m_imageId[3]."'";
			$query = $query."(".$insertData.")";
			$objDB->execute_command($query);

			$query = "SELECT MAX(hospitalId) AS new_id FROM hospital WHERE hospitalName = '".$mssqlEscapeString[$m_hospitalName]."'";
			$hospitalRS = $objDB->execute_query($query);
			if ((!$hospitalRS->eof && !$hospitalRS->bof)) {
				$m_hospitalId=intval($hospitalRS["new_id"]);
			}
		} else {
			$query = "UPDATE hospital SET ";
			$updateData="hospitalName = '".$mssqlEscapeString[$m_hospitalName]."', ";
			$updateData = $updateData."assocName = '".$mssqlEscapeString[$m_assocName]."', ";
			$updateData = $updateData."address1 = '".$mssqlEscapeString[$m_address1]."', ";
			$updateData = $updateData."address2 = '".$mssqlEscapeString[$m_address2]."', ";
			$updateData = $updateData."regionCode = '".$m_regionCode."', ";
			$updateData = $updateData."zipcode = '".$m_zipcode[0].$m_zipcode[1]."', ";
			$updateData = $updateData."explain = '".$mssqlEscapeString[$m_explain]."', ";
			$updateData = $updateData."userId = '".$m_userId."', ";
			$updateData = $updateData."manager1 = '".$mssqlEscapeString[$m_manager1]."', ";
			$updateData = $updateData."contact1 = '".$m_contact1[0]."-".$m_contact1[1]."-".$m_contact1[2]."', ";
			$updateData = $updateData."manager2 = '".$mssqlEscapeString[$m_manager2]."', ";
			$updateData = $updateData."contact2 = '".$m_contact2[0]."-".$m_contact2[1]."-".$m_contact2[2]."', ";
			$updateData = $updateData."price = '".$m_price."', ";
			$updateData = $updateData."personLimit = '".$m_personLimit."', ";
			$updateData = $updateData."homepage = '".$mssqlEscapeString[$m_homepage]."', ";
			$updateData = $updateData."status = '".$m_status."',";
			$updateData = $updateData."document = '".$m_document."', ";
			$updateData = $updateData."documentId = '".$m_documentId."', ";
			$updateData = $updateData." imageId1 = '".$m_imageId[0]."', ";
			$updateData = $updateData." imageId2 = '".$m_imageId[1]."', ";
			$updateData = $updateData." imageId3 = '".$m_imageId[2]."', ";
			$updateData = $updateData." imageId4 = '".$m_imageId[3]."'";
			$query = $query.$updateData." WHERE hospitalId = ".$m_hospitalId;
			$objDB->execute_command($query);
		} 
	} 

	function Delete() {
		if (($m_hospitalId>-1)) {
			$query = "DELETE FROM hospital WHERE hospitalId = ".$mssqlEscapeString[$m_hospitalId];
			$objDB->execute_command($query);
		} 
	} 

	function showContactInfo() {
		$cont1 = $join[$m_contact1]["-"];
		$cont2 = $join[$m_contact2]["-"];
		if ((strlen($cont1)>10)) {
			$retString = $m_manager1." ".$cont1;
		} 

		if ((strlen($cont2)>10)) {
			$retString = $retString." / ".$m_manager2." ".$cont2;
		} 

		return $retString;
	} 

	function showFee() {
		if (($m_price>0)) {
			$retString = priceFormat($m_price, 1)."/일";
		} else {
			$retString = "무료";
		} 

		return $retString;
	}
} 
*/
?>
