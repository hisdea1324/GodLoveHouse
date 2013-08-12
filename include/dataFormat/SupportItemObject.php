<?php 
# ************************************************************
#  Object : SupportItemObject
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************
class SupportItemObject {
	var $m_supportItemId;
	var $m_supportId;
	var $m_requestId;
	var $m_cost;

	#  Get property
	# ***********************************************
	function SupportItemID() {
		$SupportItemId = $m_supportItemId;
	} 

	function SupportID() {
		$SupportId = $m_supportId;
	} 

	function RequestID() {
		$RequestId = $m_requestId;
	} 

	function Cost() {
		$Cost = $m_cost;
	} 

	#  Set property
	# ***********************************************
	function RequestID($value) {
		$m_requestId=intval($value);
	} 

	function SupportID($value) {
		$m_supportId=intval($value);
	} 

	function Cost($value) {
		$m_cost = trim($value);
	} 

	#  class initialize
	# ***********************************************
	function __construct() {
		$m_supportItemId=-1;
		$m_supportId=-1;
		$m_requestId=-1;
		$m_cost=0;
	} 

	function __destruct() {
	} 

	#  class method
	# ***********************************************
	function OpenByQuery($query) {
		$supportRS = $objDB->execute_query($query);
		if ((!$supportRS->eof && !$supportRS->bof)) {
			$m_supportItemId=intval($supportRS["supItemId"]);
			$m_supportId=intval($supportRS["supId"]);
			$m_requestId=intval($supportRS["reqId"]);
			$m_cost = $supportRS["cost"];
		} 

		$supportRS = null;
	} 

	function Open($supItemId) {
		$query = "SELECT supItemId, supId, reqId, cost FROM supportItem WHERE supItemId = '".$mssqlEscapeString[$supItemId]."'";
		OpenByQuery($query);
	} 

	function OpenWithIndex($supId,$reqId) {
		$m_supportId = $supId;
		$m_requestId = $reqId;
		$query = "SELECT supItemId, supId, reqId, cost FROM supportItem ";
		$query = $query."WHERE supId = '".$mssqlEscapeString[$supId]."' AND reqId = '".$mssqlEscapeString[$reqId]."'";
		OpenByQuery($query);
	} 

	function Update() {
		if (($m_supportItemId==-1)) {
			# New Data
			$query = "INSERT INTO supportItem (supId, reqId, cost) VALUES ";
			$insertData="'".$m_supportId."', ";
			$insertData = $insertData."'".$m_requestId."', ";
			$insertData = $insertData."'".$m_cost."'";
			$query = $query."(".$insertData.")";
			$objDB->execute_command($query);

			$query = "SELECT MAX(supItemId) AS maxSupItemId FROM supportItem WHERE supId = '".$m_supportId."' AND reqId = '".$m_requestId."'";
			$supportRS = $objDB->execute_query($query);
			if ((!$supportRS->eof && $supportRS->bof)) {
				$m_supportItemId=intval($supportRS["maxSupItemId"]);
			} 
		} else {
			$query = "UPDATE supportItem SET ";
			$updateData="supId = '".$m_supportId."', ";
			$updateData = $updateData."reqId = ".$m_requestId.", ";
			$updateData = $updateData."cost = ".$m_cost." ";
			$query = $query.$updateData." WHERE supItemId = ".$m_supportItemId;
			$objDB->execute_command($query);
		} 

		$supportRS = null;
	} 

	function Delete($supId,$reqId) {
		$query = "DELETE FROM supportItem WHERE supId = '".$supId."' AND reqId = '".$reqId."'";
		$objDB->execute_command($query);
	} 

	function showPrice() {
		return priceFormat($m_cost, 1)." / 월";
	} 
} 
?>
