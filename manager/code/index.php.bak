<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");
global $mysqli;

/* 나중에 주석을 풀어야함 - 관리자권한 체크 
checkAuth();
*/


$mode = (isset($_REQUEST["mode"])) ? trim($_REQUEST["mode"]) : "";

switch ($mode) {
	case "1":
		$query = "SELECT * FROM code where type = '상태코드' OR type = '상태코드2' order by type, code";
		break;
	case "2":
		$query = "SELECT * FROM code where type = '선교지' order by type, code";
		break;
	case "3":
		$query = "SELECT * FROM code where type = '지역코드' order by type, code";
		break;
	case "4":
		$query = "SELECT * FROM code where type = '후원타입' order by type, code";
		break;
	case "5":
		$query = "SELECT * FROM code where type = 'BANK' order by type, code";
		break;
	default:
		$query = "SELECT * FROM code order by type, code";
		break;
} 

$result = $mysqli->query($query);

showAdminHeader("관리툴 - 코드관리","","","");
body($result);
showAdminFooter();

function body($result) {
?>
		<div class="sub">
			<a href="index.php?mode=0">전체</a> | 
			<a href="index.php?mode=1">상태코드</a> | 
			<a href="index.php?mode=2">선교지</a> | 
			<a href="index.php?mode=3">지역코드</a> | 
			<a href="index.php?mode=4">후원타입</a> | 
			<a href="index.php?mode=5">은행코드</a>	
		</div>
	</div>
	<div id="wrap">
		<div class="lSec">
			<ul>
				<li><img src="/images/manager/lm_0700.gif"></li>
				<li><a href="index.php?mode=1"><img src="/images/manager/lm_0701.gif"></a></li>
				<li><a href="index.php?mode=2"><img src="/images/manager/lm_0702.gif"></a></li>
				<li><a href="index.php?mode=3"><img src="/images/manager/lm_0703.gif"></a></li>
				<li><a href="index.php?mode=4"><img src="/images/manager/lm_0704.gif"></a></li>
				<li><a href="index.php?mode=5"><img src="/images/manager/lm_0705.gif"></a></li>
				<li><img src="/images/manager/lm_bot.gif"></li>
			</ul>
		</div>

		<div class="rSec">
		<!-- 컨텐츠 들어가는 부분 -->
			<!-- S login -->
			<div class="codeList">
			<table>
			<thead>
				<tr><th>코드번호</th><th>코드값</th><th>타입</th><th> </th><tr>
			<tbody>
<?php 
	while ($row = $result->fetch_array()) {
?>
				<tr>
					<td><?php echo $row["code"];?></td>
					<td><?php echo $row["name"];?></td>
					<td><?php echo $row["type"];?></td>
					<td><a href="process.php?mode=delete&id=<?php echo $row["id"];?>">[삭제]</a></td>
				</tr>
<?php 
	} 
?>
			<form action="process.php" method="post" id="frmCode" name="frmCode">
				<input type="hidden" name="mode" value="add">
				<tr>
					<td><input type="text" name="newCode" id="newCode" size="5" maxlength="5" /></td>
					<td><input type="text" name="newName" id="newName" size="10" maxlength="20" /></td>
					<td>
						<select name="newType" id="newType">
							<option value="선교지">선교지</option>
							<option value="지역코드">지역코드</option>
							<option value="후원타입">후원타입</option>
							<option value="상태코드">상태코드</option>
							<option value="상태코드2">상태코드2</option>
							<option value="BANK">은행코드</option>
						</select>
					</td>
					<td><a href="#" onclick="frmSubmit()">[코드 추가]</a></td>
				</tr>
			</form>
			</tbody>
			</table>
			</div>

		</div>
	</div>
<?php 
} 
?>
<script type="text/javascript">
//<![CDATA[
	function frmSubmit() {
		document.getElementById("frmCode").submit();
	}
//]]>
</script>
