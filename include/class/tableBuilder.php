<?php
class tableBuilder {
	var $pageSize;
	var $blockSize;

	var $currentPage;
	var $currentBlock;

	var $fieldList;
	var $fieldCount;

	var $keyList;
	var $keyCount;

	var $columnList;
	var $columnCount;

	var $buttonList;
	var $buttonCount;

	var $order;
	var $orderType;

	function __construct() {
		$pageSize=15;
		$blockSize=10;
		$fieldCount=-1;
		$keyCount=-1;
		$columnCount=-1;
		$buttonCount=2;
		$buttonList = array("보 기","수 정","삭 제");
	} 

	function __destruct() {
		

	} 

	function setPaging($l_pgSize,$l_blkSize) {
		$pageSize = $l_pgSize;
		$blockSize = $l_blkSize;
	} 

	function setOrder($ordString) {
		if ((substr($ordString,strlen($ordString)-(4))=="DESC")) {
			$orderType="DESC";
			$order=substr($ordString,0,strlen($ordString)-5);
		}
			else
		if ((substr($ordString,strlen($ordString)-(3))=="ASC")) {
			$orderType="ASC";
			$order=substr($ordString,0,strlen($ordString)-4);
		} else {
			$orderType="ASC";
			$order = $ordString;
		} 

	} 

	function setGotoPage($l_gotoPage) {
		$currentPage = $l_gotoPage;
		if ($l_gotoPage=="") {
			$currentPage=1;
		} else {
			$currentPage = $l_gotoPage;
		} 

		$currentBlock=intval(($l_gotoPage-1)/$blockSize)*$blockSize+1;
	} 

	function setButton($l_fields) {
		$buttonList = $l_fields;
		$buttonCount=count($l_fields);
	} 

	function setColumn($l_fields) {
		$columnList = $l_fields;
		$columnCount=count($l_fields);
	} 

	function setField($l_fields) {
		$fieldList = $l_fields;
		$fieldCount=count($l_fields);
	} 

	function setKeyValue($l_fields) {
		$keyList = $l_fields;
		$keyCount=count($l_fields);
	} 

	function tableHeader() {
		$pathInfo = $_SERVER["PATH_INFO"];
		if ((strlen($_SERVER["QUERY_STRING"])>0)) {
			if (((strpos($_SERVER["QUERY_STRING"],"order=") ? strpos($_SERVER["QUERY_STRING"],"order=")+1 : 0)>0)) {
				$tempString=substr($_SERVER["QUERY_STRING"],strlen($_SERVER["QUERY_STRING"])-(strlen($_SERVER["QUERY_STRING"])-(strpos($_SERVER["QUERY_STRING"],"order=") ? strpos($_SERVER["QUERY_STRING"],"order=")+1 : 0)+1));
				if (((strpos($tempString,"&") ? strpos($tempString,"&")+1 : 0))) {
					$queryString=substr($_SERVER["QUERY_STRING"],0,(strpos($_SERVER["QUERY_STRING"],"order=") ? strpos($_SERVER["QUERY_STRING"],"order=")+1 : 0)-1).substr($tempString,strlen($tempString)-(strlen($tempString)-(strpos($tempString,"&") ? strpos($tempString,"&")+1 : 0)+1))."&";
				}
					else
				{
					$queryString=substr($_SERVER["QUERY_STRING"],0,(strpos($_SERVER["QUERY_STRING"],"order=") ? strpos($_SERVER["QUERY_STRING"],"order=")+1 : 0)-1);
				} 

			} else {
				$queryString = $_SERVER["QUERY_STRING"]."&";
			} 

		} else {
			$queryString="";
		} 

		$linkUrl = $pathInfo."?".$queryString;

		# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # /
		#  임시코드 : 나중에 수정합시다.
		# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # /
		$linkUrl=str_replace("?&","?",str_replace("?&","?",$linkUrl));
		$linkUrl=str_replace("&&","&",str_replace("&&","&",$linkUrl));

		$retString = $retString."<table id=\"table\" cellpadding='3' cellspacing='1' width='100%' bgcolor='#999999'>".chr(13);
		$retString = $retString."<thead id=\"theader\" bgcolor='#FFFFFF' align='center'>".chr(13);
		# retString = retString & "	<th>No</th>" & chr(13)
		if ($columnCount>=0) {
			for ($i=0; $i <= $columnCount; $i = $i+1) {
				if (($order == $fieldList[$i])) {
					if (($orderType=="ASC")) {
						$retString = $retString."	<th><a href='".$linkUrl."order=".$fieldList[$i]."+DESC'><u>".$columnList[$i]."△ </u></a></th>".chr(13);
					} else if (($order == $fieldList[$i])) {
						$retString = $retString."	<th><a href='".$linkUrl."order=".$fieldList[$i]."+ASC'><u>".$columnList[$i]."▽ </u></a></th>".chr(13);
					} 
				} else {
					$retString = $retString."	<th><a href='".$linkUrl."order=".$fieldList[$i]."'>".$columnList[$i]."</a></th>".chr(13);
				} 
			}
		} else {
			for ($i=0; $i <= $fieldCount; $i = $i+1) {
				$retString = $retString."	<th>".$fieldList[$i]."</th>".chr(13);

			}
		} 

		if (($buttonCount>=0)) {
			$retString = $retString."	<th>action</th>".chr(13);
		} 

		$retString = $retString."</thead>".chr(13);
		$retString = $retString."<tbody id=\"tbody\">".chr(13);

		return $retString;
	} 

	function tableFooter() {
		$retString = $retString."</tbody>".chr(13);
		$retString = $retString."</table>".chr(13);

		return $retString;
	} 

	function getTable($RS) {
		$startNum=($RS->RecordCount-($pageSize*($currentPage-1)))+1;

		if ($RS->RecordCount>0) {
			$RS->PageSize = $PageSize;
			$RS->AbsolutePage = $currentPage;
		} 

		$num = $startNum+1;
		while(!($RS->EOF || $num>$startNum+$pageSize)) {
			$keyString="";
			for ($i=0; $i <= $keyCount; $i = $i+1) {
				$keyString = $keyString.", '".$RS[$keyList[$i]]."'";

			}

			$rowString = $rowString."<tr bgcolor='#FFFFFF' align='center' height='28' onMouseOver=\"this.style.background='#eaf2f4';\" onMouseOut=\"this.style.background='#FFFFFF';\">".chr(13);
			# rowString = rowString & "	<td>" & num & "</td>" & chr(13)
			for ($i=0; $i <= $fieldCount; $i = $i+1) {
				$rowString = $rowString."	<td>".$textFormat[$RS[$fieldList[$i]]][1]."</td>".chr(13);

			}

			if (($buttonCount>=0)) {
				$rowString = $rowString."<td>";
				for ($i=0; $i <= $buttonCount; $i = $i+1) {
					$rowString = $rowString." <input type='button' value='".$buttonList[$i]."' onclick=\"clickButton(".$i.$keyString.");\">";

				}

				$rowString = $rowString."</td>".chr(13);
			} 


			$rowString = $rowString."</tr>".chr(13);
			$num = $num+1;
			$RS->MoveNext;
		} 

		while(!($num>$startNum+$pageSize)) {
			$rowString = $rowString."<tr bgcolor=\"#FFFFFF\" height='25' onMouseOver=\"this.style.background='#eaf2f4';\" onMouseOut=\"this.style.background='#FFFFFF';\">".chr(13);
			for ($i=0; $i <= $fieldCount+1; $i = $i+1) {
				$rowString = $rowString."	<td>&nbsp;</td>".chr(13);

			}

			$rowString = $rowString."</tr>".chr(13);
			$num = $num+1;
		} 

		return tableHeader().$rowString.tableFooter();
	} 

	function displayListPage() {
		# 여기 수정해야 함
		$totalpage = $Ceil[$RecordCount][$PageSize];
		$gb="1";

		if ($totalpage>0) {
			# ####페이지
			if ($gotoPage/$blockSize==intval($gotoPage/$blockSize)) {
				# 현재 페이지가 배수이면
				$BlockStart = $gotoPage-$blockSize+1;
			} else {
				# 배수가 아니면
				$BlockStart=intval($gotoPage/$blockSize)*$blockSize+1;
			} 

			# 네비게이션 블럭의 끝 페이지 설정
			$BlockEnd = $BlockStart+$blockSize-1;
			if ($BlockEnd>$totalpage) {
				$BlockEnd = $totalpage;
				# response.write BlockStart&"|"&BlockEnd
			} 

			$htmContent="<table cellpadding='0' cellspacing='0' border=0 width='100%'>"."\r\n";
			$htmContent = $htmContent."	<tr>"."\r\n";
			$htmContent = $htmContent."		<td width='100%' height='20' align='center' valign='middle'>"."\r\n";
			$htmContent = $htmContent."			<table cellpadding='0' cellspacing='0' border='0'>"."\r\n";
			$htmContent = $htmContent."				<tr>"."\r\n";
			$htmContent = $htmContent."							<td align='center'>"."\r\n";
			if (intval($gotoPage)>1) {
				$htmContent = $htmContent."									<a href=\"javascript:goToPage('1');\"><img src='/common/images/page_s.gif' border='0' align='absmiddle'> 처음</a>"."\r\n";
			} else {
				$htmContent = $htmContent."									<img src='/common/images/page_s.gif' border='0' align='absmiddle'> 처음"."\r\n";
			} 

			$htmContent = $htmContent."							&nbsp;</td>"."\r\n";

			if ($totalpage>$blockSize) {
				$htmContent = $htmContent."					<td align='center'>"."\r\n";
				if ($BlockStart>1) {
					$htmContent = $htmContent."							<a href=\"javascript:goToPage('".($BlockStart-1)."');\"><img src='/common/images/page_pre.gif' border='0' align='absmiddle'> 이전</a>"."\r\n";
				} else {
					$htmContent = $htmContent."							<img src='/common/images/page_pre.gif' border='0' align='absmiddle'> 이전"."\r\n";
				} 

				$htmContent = $htmContent."					&nbsp;</td>"."\r\n";
			} 

			$htmContent = $htmContent."					<td align='center'>"."\r\n";
			$htmContent = $htmContent."						<table cellpadding='0' cellspacing='0'>"."\r\n";
			$htmContent = $htmContent."									 <tr>"."\r\n";
			$htmContent = $htmContent."											 <td align='center'>"."\r\n";
			$htmContent = $htmContent."													 &nbsp;";
			for ($j = $BlockStart; $j <= $BlockEnd; $j = $j+1) {
				if ($j==intval($gotoPage)) {
					#  현재 페이지이면 링크하지 않는다.	
					$htmContent = $htmContent."[<b><font color='#FF8000'>".$j."</font></b>]"."\r\n";
				} else {
					$htmContent = $htmContent."[<a href=\"javascript:goToPage('".$j."');\" title='".$j." 페이지'>".$j."</a>]"."\r\n";
				} 

				if ($j==intval($totalPage)) {
					$htmContent = $htmContent;
				} else {
					$htmContent = $htmContent;
				} 
			}

			$htmContent = $htmContent."									 "."\r\n";
			$htmContent = $htmContent."											 </td>"."\r\n";
			$htmContent = $htmContent."									 </tr>"."\r\n";
			$htmContent = $htmContent."							</table>"."\r\n";
			$htmContent = $htmContent."					</td>"."\r\n";

			if ($totalpage>$blockSize) {
				$htmContent = $htmContent."					<td align='center'>&nbsp;"."\r\n";
				if ($BlockStart+$blockSize-1<$totalpage) {
					$htmContent = $htmContent."							<a href=\"javascript:goToPage('".($BlockEnd + 1)."');\">다음 <img src='/common/images/page_next.gif' border='0' align='absmiddle'></a>"."\r\n";
				} else {
					$htmContent = $htmContent."							다음 <img src='/common/images/page_next.gif' border='0' align='absmiddle'>"."\r\n";
				} 

				$htmContent = $htmContent."					</td>"."\r\n";
			} 

			$htmContent = $htmContent."					<td align='center'>&nbsp;"."\r\n";
			if (intval($totalpage)!=intval($gotoPage) && intval($totalpage)>0) {
				$htmContent = $htmContent."							<a href=\"javascript:goToPage('".$totalpage."');\">맨끝 <img src='/common/images/page_e.gif' border='0' align='absmiddle'></a>"."\r\n";
			} else {
				$htmContent = $htmContent."							맨끝 <img src='/common/images/page_e.gif' border='0' align='absmiddle'>"."\r\n";
			} 

			$htmContent = $htmContent."					</td>"."\r\n";
			$htmContent = $htmContent."				</tr>"."\r\n";
			$htmContent = $htmContent."			</table>"."\r\n";
			$htmContent = $htmContent."		</td>"."\r\n";
			$htmContent = $htmContent." </tr>"."\r\n";
			$htmContent = $htmContent."</table>"."\r\n";
		} 

		return $htmContent;
	} 
} 
?>
