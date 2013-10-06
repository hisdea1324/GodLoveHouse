<?php 
# ************************************************************
#  Object : SupportObject
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************
class SupportObject {
	protected $record = array();
	protected $image = array();
	protected $items = array();

	public function __set($name,$value) { 
		$name = strtolower($name);
		switch ($name) {
			default : 
				$this->record[$name] = $value;
				break;
		}
	}

	public function __get($name) { 
		$name = strtolower($name);
		switch ($name) {
			case 'isnew':
				return ($this->record['supportid'] == -1);
			case 'supporttype':
				switch ($this->record['suptype']) {
					case "03001":
						return "특별후원";
					case "03002":
						return "센터후원";
					case "03003":
						return "자원봉사";
				} 
			case 'email':
				return explode('@', $this->record[$name]);
			case 'jumin':
			case 'phone':
			case 'mobile':
			case 'zipcode':
				return explode('-', $this->record[$name]);
			case 'post':
				return explode('-', $this->record['zipcode']);
			case 'supportitem':
				return $this->items;
			default : 
				return $this->record[$name];
		}
	}

	public function __isset($name) {
		$name = strtolower($name);
		return isset($this->record[$name]); 
    }

    function __construct($userId = -1, $supId = -1) {
		$this->initialize();
		if ($userId > -1 && $supId > -1) {
			$this->Open($userId, $supId);
		}
	}

	private function initialize() {
		$c_Helper = new CodeHelper();

		$this->supportid = -1;
		$this->userid = -1;
		$this->name = '';
		$this->jumin = '000000-0000000';
		$this->suptype = $c_Helper->getSupportCode(2);
		$this->sumprice = 0;
		$this->phone = '000-000-0000';
		$this->mobile = '000-0000-0000';
		$this->email = 'a@b.c';
		$this->address1 = '';
		$this->address2 = '';
		$this->zipcode = '000-000';
		$this->status = $c_Helper->getSupportStatusCode(1);;
		$this->regdate = '';
	} 

	function OpenQuery($query) {
		global $mysqli;
		$result = $mysqli->query($query);
		if (!$result) return;

	    while ($row = mysqli_fetch_assoc($result)) {
			$this->supportId = $row['supId'];
			$this->userid = $row['userId'];
			$this->name = $row['name'];
			$this->jumin = $row['jumin'];
			$this->suptype = $row['supportType'];
			$this->phone = $row['phone'];
			$this->mobile = $row['mobile'];
			$this->email = $row['email'];
			$this->address1 = $row['address1'];
			$this->address2 = $row['address2'];
			$this->zipcode = $row['zipcode'];
			$this->status = $row['status'];
			$this->regdate = $row['regDate'];
	    }

		$query = "SELECT SUM(cost) as sumprice FROM supportItem WHERE supId = '".$mysqli->real_escape_string($this->supportid)."'";
		if ($result = $mysqli->query($query)) {
		    while ($row = mysqli_fetch_assoc($result)) {
				$this->sumprice = $row['sumprice'];
		    }
	    }

		$query = "SELECT supItemId FROM supportItem WHERE supId = '".$mysqli->real_escape_string($this->supportid)."'";
		if ($result = $mysqli->query($query)) {
		    while ($row = mysqli_fetch_assoc($result)) {
				$this->items[] = new SupportItemObject($row['supItemId']);
		    }
		} 
	}

	function OpenWithSupId($supId) {
		global $mysqli;
		$query = "SELECT supId, userId, name, supportType, status, jumin, phone, mobile, email, zipcode, address1, address2, regDate ";
		$query = $query."FROM supportInfo WHERE supId = '".$mysqli->real_escape_string($supId)."'";
		$this->OpenQuery($query);
	} 

	function Open($userid, $supType) {
		global $mysqli;
		$this->userid = $userid;
		$query = "SELECT supId, userId, name, supportType, status, jumin, phone, mobile, email, zipcode, address1, address2, regDate FROM supportInfo ";
		$query = $query." WHERE userId = '".$mysqli->real_escape_string($userid)."' AND supportType = '".$mysqli->real_escape_string($supType)."'";
		$this->OpenQuery($query);
	} 

	function Update() {
		global $mysqli;

		if ($this->record['supportid'] == -1) {
			# New Data
			$query = "INSERT INTO supportInfo (userId, supportType, status, name, jumin, phone, mobile, email, zipcode, address1, address2) VALUES ";
			$insertData="'".$mysqli->real_escape_string($this->userid)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->supporttype)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($m_sthis->status)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->name)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->jumin)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->phone)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->mobile)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->email)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->zipcode)."',";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->address1)."', ";
			$insertData = $insertData."'".$mysqli->real_escape_string($this->address2)."'";
			$query = $query."(".$insertData.")";
			$result = $mysqli->query($query);

			// new id
			$this->supportid = $mysqli->insert_id;

		} else {
			$query = "UPDATE supportInfo SET ";
			$updateData="name = '".$mysqli->real_escape_string($this->name)."', ";
			$updateData = $updateData."supportType = '".$mysqli->real_escape_string($this->supporttype)."',";
			$updateData = $updateData."status = '".$mysqli->real_escape_string($this->status)."',";
			$updateData = $updateData."jumin = '".$mysqli->real_escape_string($this->jumin)."',";
			$updateData = $updateData."phone = '".$mysqli->real_escape_string($this->phone)."',";
			$updateData = $updateData."mobile = '".$mysqli->real_escape_string($this->mobile)."',";
			$updateData = $updateData."email = '".$mysqli->real_escape_string($this->email)."',";
			$updateData = $updateData."zipcode = '".$mysqli->real_escape_string($this->zipcode)."',";
			$updateData = $updateData."address1 = '".$mysqli->real_escape_string($this->address1)."',";
			$updateData = $updateData."address2 = '".$mysqli->real_escape_string($this->address2)."'";
			$query = $query.$updateData." WHERE supId = ".$mysqli->real_escape_string($this->supportid);
			$result = $mysqli->query($query);
		} 
	} 

	function Delete($supId,$reqId) {
		global $mysqli;
		$query = "DELETE FROM supportInfo WHERE supId = ".$mysqli->real_escape_string($this->supportid);
		$result = $mysqli->query($query);
	} 

	function Support($item_id) {
		if ($this->supportId > 0) {
			foreach ($this->items as $sup_item) {
				if ($sup_item->requestId == $item_id) {
					return true;
				}
			}
		} 

		return false;
	} 

	function getItemCost($item_id) {
		if ($this->supportId > 0) {
			foreach ($this->items as $sup_item) {
				if ($sup_item->requestId == $item_id) {
					return $sup_item->Cost;
				}
			}
		} 

		return -1;
	} 

	function changeStatus() {
		if (strcmp($this->status,"S2001") == 0) {
			$this->status = "S2002";
		} else {
			$this->status = "S2001";
		} 

		$this->Update();
	} 
} 
?>
