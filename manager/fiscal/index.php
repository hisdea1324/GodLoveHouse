<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

checkAuth();
showAdminHeader("관리툴 - 재정관리","","","");
//call showAdminMenu()
body();
showAdminFooter();

function body() {
?>
	<div class="sub">
	<a href="#">일일보고</a> | 
	<a href="#">월간보고</a> | 
	<a href="#">분기보고</a> |
	<a href="#">연간보고</a>
	</div>
	</div>
	<div id="wrap">
		<div class="lSec">
		<ul>
			<li><img src="/images/manager/lm_0500.gif"></li>
		<li><a href="#"><img src="/images/manager/lm_0501.gif"></a></li>
		<li><a href="#"><img src="/images/manager/lm_0502.gif"></a></li>
		<li><a href="#"><img src="/images/manager/lm_0503.gif"></a></li>
		<li><a href="#"><img src="/images/manager/lm_0504.gif"></a></li>
		<li><img src="/images/manager/lm_bot.gif"></li>
		</ul>
	</div>
		
	<div class="rSec">
	<!-- 컨텐츠 들어가는 부분 -->
	</div>
	
	</div>

<?php } ?>

<script type="text/javascript">
//<![CDATA[
	var searchString = '&keyword=<?php echo $keyword;?><%&field=<?php echo $field;?><%';
	
	function clickButton(no, houseId) {
		switch(no) {
<?php 
if (($status=="S2002")) {
?>
			case 0: goShow(houseId); break;
			case 1: goEdit(houseId); break;
			case 2: goConfirm(houseId, 'S2001'); break;
<?php 
} else {
?>
			case 0: goShow(houseId); break;
			case 1: goEdit(houseId); break;
			case 2: goDelete(houseId); break;
			case 3: goConfirm(houseId, 'S2002'); break;
<?php 
} 

?><%
			default: break;
		}
	}

	function goShow(houseId) 
	{
		location.href = 'roomList.php?houseId=' + houseId + searchString;
	}

	function goEdit(houseId) 
	{
		location.href = 'editHouse.php?mode=editHouse&houseId=' + houseId + searchString;
	}

	function goDelete(houseId) 
	{
		if (confirm("정말 삭제 하시겠습니까?")) {
			location.href = 'process.php?mode=deleteHouse&houseId=' + houseId + searchString;
		}
	}
	
	function goConfirm(houseId, value) 
	{
		if (confirm("정말 승인 하시겠습니까?")) {
			location.href = 'process.php?mode=confirmHouse&value=' + value + '&houseId=' + houseId + searchString;
		}
	}
	
	function addHouse() 
	{
		location.href = 'editHouse.php?mode=addHouse' + searchString;
	}
//]]>
</script>	

