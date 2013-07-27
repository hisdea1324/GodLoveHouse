<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
$reqId = trim($_REQUEST["reqId"]);

$sessions = new __construct();
$s_Helper = new SupportHelper();
$reqInfo = $s_Helper->getRequestInfoByReqId($reqId);
$reqAddInfo = $s_Helper->getRequestAddInfoByReqId($reqId);
$reqItems = $reqAddInfo->RequestItem;

showHeader("HOME > 선교사후원 > 특별후원","sponsor","tit_0301.gif");
body();
showFooter();

function body() {
?>
	<!-- //content -->
	<div id="content">

		<!-- //view -->
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_con2 b20">
			<col width="15%" />
			<col />
			<col width="15%" />
			<col />
			<tr>
				<th>이름</th>
				<td colspan="3" class="ltd"><?php echo $reqAddInfo->Nick;?></td>
			</tr>
			<tr>
				<th>선교지</th>
				<td><?php echo $reqAddInfo->Nation;?></td>
				<th>E-mail</th>
				<td><?php echo $reqAddInfo->Email;?></td>
			</tr>
			<tr>
				<th>제목</th>
				<td colspan="3"> <?php echo $reqInfo->Title;?> </td>
			</tr>
			<tr>
				<th>내용</th>
				<td colspan="3"><?php echo $reqInfo->Explain;?></td>
			</tr>
			<tr>
				<th>후원마감일</th>
				<td colspan="3"><?php echo $dateFormat[$reqAddInfo->Due][1];?></td>
			</tr>
		</table>

		<!-- view// -->
		<p class="b5"><img src="../images/sub/support_txt_01.gif"></p>
		<!-- //list -->

		<div class="bg_list">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="board_list">
		<form action="specialConfirm.php" method="post" name="dataFrm" id="dataFrm">
		<input type="hidden" name="reqId" value="<?php echo $reqId;?>">
			<col width="5%" />
			<col width="20%" />
			<col />
			<col width="15%" />
			<col width="20%" />
			<tr>
				<th>
<?php 
	if (($reqAddInfo->SupportRatio<100)) {
?>
					<input type="checkbox" name="allcheck" id="allcheck" value="checkbox" class="chk" onclick="checkAll()"> 
<?php 
	} 

?>
				</th>
				<th class="th01">항목</th>
				<th class="th01">내용</th>
				<th class="th01">예상경비</th>
				<th class="th01">현재후원상태</th>
			</tr>
<?php 
	if ((count($reqItems)==0)) {
?>
			<tr>
				<td colspan="5"> 리스트가 없습니다 </td>
			</tr>
<?php 
	} else {
		for ($i=0; $i<=count($reqItems); $i = $i+1) {
			$reqItem = $reqItems[$i];
?>
			<tr>
<?php 
			if (($reqItem->HasSupport && $reqItem->SendUser != $sessions->UserID)) {
?>
				<td>
					<font color="#56AA56"><strong>v</strong></font>
				</td>
<?php 
			} else {
?>
				<td>
					<input type="checkbox" name="check" id="check" value="<?php echo $reqItem->RequestItemID;?>" class="chk" onclick="sum()"<?				 if ((strlen($reqItem->SendUser)>0 && $reqItem->SendUser == $sessions->UserID)) {
?> checked<?				 } ?> />
				</td>
<?php 
			} 

?>
				<td class="ltd"> <?php echo $ReqItem->RequestItem;?> </td>
				<td class="ltd"><?php echo $ReqItem->Descript;?> </td>
				<td class="rtd"><?php echo $priceFormat[$ReqItem->Cost][1];?></td>
				<td><?php echo $reqItem->Status;?></td>
			</tr>
			<input type="hidden" name="cost<?php echo $reqItem->RequestItemID;?>" id="cost<?php echo $reqItem->RequestItemID;?>" value="<?php echo $reqItem->Cost;?>" />
<?php 

		}

	} 


	$currentStatus = $reqAddInfo->SupportRatio;
	if (($currentStatus==100)) {
?>
			<tr>
				<td colspan="2" class="total"><br>
				<td colspan="3" class="total">마감되었습니다.</td>
			</tr>
<?php 
	} else {
?>
			<tr>
				<td colspan="2" class="total">예상총계
				<td colspan="3" class="total2" name="sumTd" id="sumTd">0 원</td>
			</tr>
			<tr>
				<td colspan="2" class="total">현재후원
				<td colspan="3" class="total2" name="current"><?php echo $reqAddInfo->currentCost;?> 원</td>
			</tr>
			<tr>
				<td colspan="2" class="total">후원상황
				<td colspan="3" class="total2" name="status"><?php echo $currentStatus;?> %</td>
			</tr>
<?php 
	} 

?>
		</form>
		</table>

		</div>

		<!-- list// -->
<?php 
	if (($currentStatus<100)) {
?>
		<p class="btn_right"><img src="../images/board/btn_support2.gif" border="0" onclick="frmSubmit()" style="cursor:pointer;" /></p>
<?php 
	} 

?>
	</div>
	<!-- content// -->
<?php 
} 
?>

<script type="text/javascript">
//<![CDATA[
	sum();
	
	function frmSubmit() {
		var getObj = document.getElementsByTagName("input");
		var count = 0;

		for(var i=0; i < getObj.length; i++) {
			if( getObj[i].checked && getObj[i].type.toLowerCase() == "checkbox" && getObj[i].name.substr(0,5) == "check") {
				count++;
			}
		}

		document.getElementById("dataFrm").submit();
	}
	
	function checkAll() {
		var sw;
		var sumPrice = 0;
		var getObj = document.getElementsByTagName("input"); 
		
		if( document.getElementById("allcheck").checked == true ) { 
			sw = true; 
		} else { 
			sw = false; 
			sumPrice = 0;
		}
		
		for(var i=0; i < getObj.length; i++) {
			if(getObj[i].type.toLowerCase() == "checkbox" && getObj[i].name.substr(0,5) == "check") {
				getObj[i].checked = sw;
			} 
		}
		
		sum();
	}
	
	function sum() {
		var sumPrice = 0;
		var getObj = document.getElementsByTagName("input"); 
		
		for(var i=0; i < getObj.length; i++) {
			if(getObj[i].type.toLowerCase() == "checkbox" && getObj[i].name.substr(0,5) == "check") {
				if (getObj[i].checked) {
					sumPrice += parseInt(document.getElementById("cost" + getObj[i].value).value);
				}
			} 
		}
		
		document.getElementById("sumTd").innerHTML = sumPrice + ' 원';
	}
//]]>
</script>
