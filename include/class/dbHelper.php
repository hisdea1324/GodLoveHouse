<?php
class dbHelper {
	private $mysqli;

	private $m_host;
	private $m_name;
	private $m_user;
	private $m_pass;

	#클래스 생성자	
	function __construct() {
		global $Application;
		$this->mysqli = null;
		$this->m_host = $Application["DBSource"];
		$this->m_name = $Application["DBName"];
		$this->m_user = $Application["DBUser"];
		$this->m_pass = $Application["DBPass"];
	} 

	#클래스 소멸자
	function __destruct() {
		$this->mysqli = null;
	} 

	# Connection 프로퍼티 Get	
	function Connection() {
		$this->mysqli = new mysqli($this->m_host, $this->m_user, $this->m_pass, $this->m_name);
		if ($this->mysqli->connect_errno) {
			echo "Failed to connect to MySQL: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error;
		}
	} 

	#레코드셋 질의
	function execute_query($sQuery) {
		if ($result = $this->mysqli->query($sQuery)) {
				printf("Select returned %d rows.\n", $result->num_rows);

				/* free result set */
				$result->close();
		}
		return $result;
	} 

	#업데이트 질의
	function execute_command($sQuery) {
		if ($result = $this->mysqli->query($sQuery)) {
				printf("Select returned %d rows.\n", $result->num_rows);

				/* free result set */
				$result->close();
		}
		return $result;
	} 

	#데이터베이스 연결 닫기
	function Close() {
		$this->mysqli = null;
	} 
} 
?> 
