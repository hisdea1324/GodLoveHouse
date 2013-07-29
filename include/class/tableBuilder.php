<?php
class tableBuilder {
	public $totalPage;
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
		$this->pageSize = 15;
		$this->blockSize = 10;
		$this->fieldCount = -1;
		$this->keyCount = -1;
		$this->columnCount = -1;
		$this->buttonCount = 2;
		$this->buttonList = array (
			"보 기",
			"수 정",
			"삭 제"
		);
	}

	function __destruct() {

	}

	function setPaging($l_pgSize, $l_blkSize) {
		$this->pageSize = $l_pgSize;
		$this->blockSize = $l_blkSize;
	}

	function setOrder($ordString) {
		if (substr($ordString, strlen($ordString) - (4)) == "DESC") {
			$this->orderType = "DESC";
			$this->order = substr($ordString, 0, strlen($ordString) - 5);
		} else
			if ((substr($ordString, strlen($ordString) - (3)) == "ASC")) {
				$this->orderType = "ASC";
				$order = substr($ordString, 0, strlen($ordString) - 4);
			} else {
				$this->orderType = "ASC";
				$order = $ordString;
			}

	}

	function setGotoPage($l_gotoPage) {
		$this->currentPage = $l_gotoPage;
		if ($l_gotoPage == "") {
			$this->currentPage = 1;
		} else {
			$this->currentPage = $l_gotoPage;
		}

		$currentBlock = intval(($l_gotoPage -1) / $this->blockSize) * $this->blockSize +1;
	}

	function setButton($l_fields) {
		$this->buttonList = $l_fields;
		$this->buttonCount = count($l_fields);
	}

	function setColumn($l_fields) {
		$this->columnList = $l_fields;
		$this->columnCount = count($l_fields);
	}

	function setField($l_fields) {
		$this->fieldList = $l_fields;
		$this->fieldCount = count($l_fields);
	}

	function setKeyValue($l_fields) {
		$this->keyList = $l_fields;
		$this->keyCount = count($l_fields);
	}

	function tableHeader() {
		$pathInfo = $_SERVER["PATH_INFO"];
		if ((strlen($_SERVER["QUERY_STRING"]) > 0)) {
			if (((strpos($_SERVER["QUERY_STRING"], "order=") ? strpos($_SERVER["QUERY_STRING"], "order=") + 1 : 0) > 0)) {
				$tempString = substr($_SERVER["QUERY_STRING"], strlen($_SERVER["QUERY_STRING"]) - (strlen($_SERVER["QUERY_STRING"]) - (strpos($_SERVER["QUERY_STRING"], "order=") ? strpos($_SERVER["QUERY_STRING"], "order=") + 1 : 0) + 1));
				if (((strpos($tempString, "&") ? strpos($tempString, "&") + 1 : 0))) {
					$queryString = substr($_SERVER["QUERY_STRING"], 0, (strpos($_SERVER["QUERY_STRING"], "order=") ? strpos($_SERVER["QUERY_STRING"], "order=") + 1 : 0) - 1) . substr($tempString, strlen($tempString) - (strlen($tempString) - (strpos($tempString, "&") ? strpos($tempString, "&") + 1 : 0) + 1)) . "&";
				} else {
					$queryString = substr($_SERVER["QUERY_STRING"], 0, (strpos($_SERVER["QUERY_STRING"], "order=") ? strpos($_SERVER["QUERY_STRING"], "order=") + 1 : 0) - 1);
				}

			} else {
				$queryString = $_SERVER["QUERY_STRING"] . "&";
			}

		} else {
			$queryString = "";
		}

		$linkUrl = $pathInfo . "?" . $queryString;

		# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # /
		#  임시코드 : 나중에 수정합시다.
		# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # /
		$linkUrl = str_replace("?&", "?", str_replace("?&", "?", $linkUrl));
		$linkUrl = str_replace("&&", "&", str_replace("&&", "&", $linkUrl));

		$retString = $retString . "<table id=\"table\" cellpadding='3' cellspacing='1' width='100%' bgcolor='#999999'>" . chr(13);
		$retString = $retString . "<thead id=\"theader\" bgcolor='#FFFFFF' align='center'>" . chr(13);
		# retString = retString & "	<th>No</th>" & chr(13)
		if ($this->columnCount >= 0) {
			for ($i = 0; $i <= $this->columnCount; $i = $i +1) {
				if ($this->order == $this->fieldList[$i]) {
					if (($this->orderType == "ASC")) {
						$retString = $retString . "	<th><a href='" . $linkUrl . "order=" . $this->fieldList[$i] . "+DESC'><u>" . $this->columnList[$i] . "△ </u></a></th>" . chr(13);
					} else
						if (($this->order == $this->fieldList[$i])) {
							$retString = $retString . "	<th><a href='" . $linkUrl . "order=" . $this->fieldList[$i] . "+ASC'><u>" . $this->columnList[$i] . "▽ </u></a></th>" . chr(13);
						}
				} else {
					$retString = $retString . "	<th><a href='" . $linkUrl . "order=" . $this->fieldList[$i] . "'>" . $this->columnList[$i] . "</a></th>" . chr(13);
				}
			}
		} else {
			for ($i = 0; $i <= $this->fieldCount; $i = $i +1) {
				$retString = $retString . "	<th>" . $this->fieldList[$i] . "</th>" . chr(13);

			}
		}

		if (($this->buttonCount >= 0)) {
			$retString = $retString . "	<th>action</th>" . chr(13);
		}

		$retString = $retString . "</thead>" . chr(13);
		$retString = $retString . "<tbody id=\"tbody\">" . chr(13);

		return $retString;
	}

	function tableFooter() {
		$retString = $retString . "</tbody>" . chr(13);
		$retString = $retString . "</table>" . chr(13);

		return $retString;
	}

	function getTable($RS) {
		$startNum = ($RS->RecordCount - ($this->pageSize * ($this->currentPage -1))) + 1;

		if ($RS->RecordCount > 0) {
			$RS->PageSize = $this->pageSize;
			$RS->AbsolutePage = $this->currentPage;
		}

		$num = $startNum +1;
		while (!($RS->EOF || $num > $startNum + $this->pageSize)) {
			$keyString = "";
			for ($i = 0; $i <= $this->keyCount; $i = $i +1) {
				$keyString = $keyString . ", '" . $RS[$this->keyList[$i]] . "'";

			}

			$rowString = $rowString . "<tr bgcolor='#FFFFFF' align='center' height='28' onMouseOver=\"this.style.background='#eaf2f4';\" onMouseOut=\"this.style.background='#FFFFFF';\">" . chr(13);
			# rowString = rowString & "	<td>" & num & "</td>" & chr(13)
			for ($i = 0; $i <= $this->fieldCount; $i = $i +1) {
				$rowString = $rowString . "	<td>" . textFormat($RS[$this->fieldList[$i]], 1) . "</td>" . chr(13);

			}

			if (($this->buttonCount >= 0)) {
				$rowString = $rowString . "<td>";
				for ($i = 0; $i <= $this->buttonCount; $i = $i +1) {
					$rowString = $rowString . " <input type='button' value='" . $this->buttonList[$i] . "' onclick=\"clickButton(" . $i . $keyString . ");\">";

				}

				$rowString = $rowString . "</td>" . chr(13);
			}

			$rowString = $rowString . "</tr>" . chr(13);
			$num = $num +1;
			$RS->MoveNext;
		}

		while (!($num > $startNum + $this->pageSize)) {
			$rowString = $rowString . "<tr bgcolor=\"#FFFFFF\" height='25' onMouseOver=\"this.style.background='#eaf2f4';\" onMouseOut=\"this.style.background='#FFFFFF';\">" . chr(13);
			for ($i = 0; $i <= $this->fieldCount +1; $i = $i +1) {
				$rowString = $rowString . "	<td>&nbsp;</td>" . chr(13);

			}

			$rowString = $rowString . "</tr>" . chr(13);
			$num = $num +1;
		}

		return tableHeader() . $rowString . tableFooter();
	}

	function displayListPage() {
		global $RecordCount, $gotoPage;
		# 여기 수정해야 함
		$this->totalpage = Ceil($RecordCount, $this->pageSize);
		$gb = "1";

		if ($this->totalPage > 0) {
			# ####페이지
			if ($gotoPage / $this->blockSize == intval($gotoPage / $this->blockSize)) {
				# 현재 페이지가 배수이면
				$BlockStart = $gotoPage - $this->blockSize +1;
			} else {
				# 배수가 아니면
				$BlockStart = intval($gotoPage / $this->blockSize) * $this->blockSize +1;
			}

			# 네비게이션 블럭의 끝 페이지 설정
			$BlockEnd = $BlockStart + $this->blockSize -1;
			if ($BlockEnd > $this->totalPage) {
				$BlockEnd = $this->totalPage;
				# response.write BlockStart&"|"&BlockEnd
			}

			$htmContent = "<table cellpadding='0' cellspacing='0' border=0 width='100%'>" . "\r\n";
			$htmContent = $htmContent . "	<tr>" . "\r\n";
			$htmContent = $htmContent . "		<td width='100%' height='20' align='center' valign='middle'>" . "\r\n";
			$htmContent = $htmContent . "			<table cellpadding='0' cellspacing='0' border='0'>" . "\r\n";
			$htmContent = $htmContent . "				<tr>" . "\r\n";
			$htmContent = $htmContent . "							<td align='center'>" . "\r\n";
			if (intval($gotoPage) > 1) {
				$htmContent = $htmContent . "									<a href=\"javascript:goToPage('1');\"><img src='/common/images/page_s.gif' border='0' align='absmiddle'> 처음</a>" . "\r\n";
			} else {
				$htmContent = $htmContent . "									<img src='/common/images/page_s.gif' border='0' align='absmiddle'> 처음" . "\r\n";
			}

			$htmContent = $htmContent . "							&nbsp;</td>" . "\r\n";

			if ($this->totalPage > $this->blockSize) {
				$htmContent = $htmContent . "					<td align='center'>" . "\r\n";
				if ($BlockStart > 1) {
					$htmContent = $htmContent . "							<a href=\"javascript:goToPage('" . ($BlockStart -1) . "');\"><img src='/common/images/page_pre.gif' border='0' align='absmiddle'> 이전</a>" . "\r\n";
				} else {
					$htmContent = $htmContent . "							<img src='/common/images/page_pre.gif' border='0' align='absmiddle'> 이전" . "\r\n";
				}

				$htmContent = $htmContent . "					&nbsp;</td>" . "\r\n";
			}

			$htmContent = $htmContent . "					<td align='center'>" . "\r\n";
			$htmContent = $htmContent . "						<table cellpadding='0' cellspacing='0'>" . "\r\n";
			$htmContent = $htmContent . "									 <tr>" . "\r\n";
			$htmContent = $htmContent . "											 <td align='center'>" . "\r\n";
			$htmContent = $htmContent . "													 &nbsp;";
			for ($j = $BlockStart; $j <= $BlockEnd; $j = $j +1) {
				if ($j == intval($gotoPage)) {
					#  현재 페이지이면 링크하지 않는다.	
					$htmContent = $htmContent . "[<b><font color='#FF8000'>" . $j . "</font></b>]" . "\r\n";
				} else {
					$htmContent = $htmContent . "[<a href=\"javascript:goToPage('" . $j . "');\" title='" . $j . " 페이지'>" . $j . "</a>]" . "\r\n";
				}

				if ($j == intval($this->totalPage)) {
					$htmContent = $htmContent;
				} else {
					$htmContent = $htmContent;
				}
			}

			$htmContent = $htmContent . "									 " . "\r\n";
			$htmContent = $htmContent . "											 </td>" . "\r\n";
			$htmContent = $htmContent . "									 </tr>" . "\r\n";
			$htmContent = $htmContent . "							</table>" . "\r\n";
			$htmContent = $htmContent . "					</td>" . "\r\n";

			if ($this->totalPage > $this->blockSize) {
				$htmContent = $htmContent . "					<td align='center'>&nbsp;" . "\r\n";
				if ($BlockStart + $this->blockSize -1 < $this->totalPage) {
					$htmContent = $htmContent . "							<a href=\"javascript:goToPage('" . ($BlockEnd +1) . "');\">다음 <img src='/common/images/page_next.gif' border='0' align='absmiddle'></a>" . "\r\n";
				} else {
					$htmContent = $htmContent . "							다음 <img src='/common/images/page_next.gif' border='0' align='absmiddle'>" . "\r\n";
				}

				$htmContent = $htmContent . "					</td>" . "\r\n";
			}

			$htmContent = $htmContent . "					<td align='center'>&nbsp;" . "\r\n";
			if (intval($this->totalPage) != intval($gotoPage) && intval($this->totalPage) > 0) {
				$htmContent = $htmContent . "							<a href=\"javascript:goToPage('" . $this->totalPage . "');\">맨끝 <img src='/common/images/page_e.gif' border='0' align='absmiddle'></a>" . "\r\n";
			} else {
				$htmContent = $htmContent . "							맨끝 <img src='/common/images/page_e.gif' border='0' align='absmiddle'>" . "\r\n";
			}

			$htmContent = $htmContent . "					</td>" . "\r\n";
			$htmContent = $htmContent . "				</tr>" . "\r\n";
			$htmContent = $htmContent . "			</table>" . "\r\n";
			$htmContent = $htmContent . "		</td>" . "\r\n";
			$htmContent = $htmContent . " </tr>" . "\r\n";
			$htmContent = $htmContent . "</table>" . "\r\n";
		}

		return $htmContent;
	}
}
?>
