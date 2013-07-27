<?php 
# ************************************************************
#  Object : __construct
# 
#  editor : Sookbun Lee 
#  last update date : 2010.03.04
# ************************************************************
class __construct {
	var $m_type;
	var $m_userLevel;
	var $m_userid;
	var $m_nick;
	var $m_name;
	var $m_readList;

	function MemberType() {
		$MemberType = $m_type;
	} 

	function UserID() {
		$UserID = $m_userid;
	} 

	function Nick() {
		$Nick = $m_nick;
	} 

	function Name() {
		$Name = $m_name;
	} 

	function UserLevel() {
		$UserLevel = $m_userLevel;
	} 

	function __construct() {
		if (strlen($_SESSION['userId'])>0) {
			setVariable($_SESSION['userId'],$_SESSION['userName'],$_SESSION['userNick'],$_SESSION['userLv']);
		} 

		$m_readList = $_SESSION['readList'];
	} 

	function __destruct() {
	} 

	function addReadList($idBoard) {
		$m_readList = $m_readList."&".$idBoard;
		$_SESSION['readList'] = $m_readList;
	} 

	function checkReadList($idBoard) {
		if (((strpos($m_readList,"&".$idBoard) ? strpos($m_readList,"&".$idBoard)+1 : 0)>0)) {
			return true;
		} else {
			addReadList($idBoard);
			return false;
		} 
	} 

	function setVariable($l_userid,$l_name,$l_nick,$l_level) {
		$m_userid = $l_userid;
		$m_name = $l_name;
		$m_nick = $l_nick;
		$m_userLevel = $l_level;
		$m_type=makeType($l_level);
	} 

	function makeType($l_level) {
		switch ($l_level) {
			case 9:
				$retValue="Super";
				break;
			case 7:
				$retValue="Manager";
				break;
			case 3:
				$retValue="Missionary";
				break;
			default:
				$retValue="Normal";
				break;
		} 
	} 

	function isSessionAlived() {
		if (($count>0 && 
			 strlen($_SESSION['userid'])>0 && strlen($_SESSION['userName'])>0 && 
			 strlen($_SESSION['userLv'])>0 && strlen($_SESSION['userNick'])>0)) {
			$retValue=true;
		} else {
			$retValue=false;
		} 

		return $retValue;
	} 

	function expireSession() {

	} 

	#  return Value 성공: 0, 인증실패: 1, InputData오류: 2
	function setBasicInfo($userid,$password) {
		if (strlen($userid)==0 || strlen($password)==0) {
			$retValue=2;
		} 

		$query = "SELECT nick, name, userLv FROM users WHERE userid = '".$mssqlEscapeString[$userid]."' AND password = '".$mssqlEscapeString[$Encrypt[$password]]."'";
		$rs = $objDB->execute_query($query);

		if (!$Rs->eof || !$Rs->bof) {
			$_SESSION['userId'] = $userid;
			$_SESSION['userNick'] = $Rs["nick"];
			$_SESSION['userName'] = $Rs["name"];
			$_SESSION['userLv'] = $Rs["userLv"];
			setVariable($_SESSION['userId'],$_SESSION['userType'],$_SESSION['userNick'],$_SESSION['userLv']);
			$retValue=0;
		} else {
			$retValue=1;
		} 

		return $retValue;
	} 

	function getBasicInfo($userid,$password) {

	} 

	function authority($limitLevel) {
		if (($m_userLevel<$limitLevel)) {
			$retValue=false;
		} else {
			$retValue=true;
		} 

		return $retValue;
	} 
} 
?>
