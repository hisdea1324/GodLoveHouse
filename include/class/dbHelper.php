<?php
class dbHelper {
	private $mysqli;

	private $m_host;
	private $m_name;
	private $m_user;
	private $m_pass;

	#클래스 생성자	
	function __construct() {
		$mysqli = null;
		$m_host = $Application["DBSource"];
		$m_name = $Application["DBName"];
		$m_user = $Application["DBUser"];
		$m_pass = $Application["DBPass"];
	} 

	#클래스 소멸자
	function __destruct() {
		$mysqli = null;
	} 

	# Connection 프로퍼티 Get	
	function Connection() {
		$mysqli = new mysqli($m_host, $m_user, $m_pass, $m_name);
		if ($mysqli->connect_errno) {
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}
	} 

	#레코드셋 질의
	function execute_query($sQuery) {
		if ($result = $$mysqli->query($sQuery)) {
				printf("Select returned %d rows.\n", $result->num_rows);

				/* free result set */
				$result->close();
		}
		return $result;
	} 

	#업데이트 질의
	function execute_command($sQuery) {
		if ($result = $$mysqli->query($sQuery)) {
				printf("Select returned %d rows.\n", $result->num_rows);

				/* free result set */
				$result->close();
		}
		return $result;
	} 

	#데이터베이스 연결 닫기
	function Close() {
		$m_conn = null;
	} 
} 
?> 
