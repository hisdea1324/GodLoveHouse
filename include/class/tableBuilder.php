<?php
class tableBuilder {
	public $totalPage;
	public $pageSize;
	public $blockSize;

	public $currentPage;
	public $currentBlock;

	public $fieldList;
	public $fieldCount;

	public $keyList;
	public $keyCount;

	public $columnList;
	public $columnCount;

	public $buttonList;
	public $buttonCount;

	public $order;
	public $orderType;

	public function __construct() {
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
		if (substr($ordString, strlen($ordString) - 4) == "DESC") {
			$this->orderType = "DESC";
			$this->order = substr($ordString, 0, strlen($ordString) - 5);
		} else
			if (substr($ordString, strlen($ordString) - 3) == "ASC") {
				$this->orderType = "ASC";
				$order = substr($ordString, 0, strlen($ordString) - 4);
			} else {
				$this->orderType = "ASC";
				$order = $ordString;
			}

	}

	function setGotoPage($value) {
		$this->currentPage = $value;
		if ($value == "") {
			$this->currentPage = 1;
		} else {
			$this->currentPage = $value;
		}

		$currentBlock = intval(($value -1) / $this->blockSize) * $this->blockSize +1;
	}

	function setButton($value) {
		$this->buttonList = $value;
		$this->buttonCount = count($value);
	}

	function setColumn($value) {
		$this->columnList = $value;
		$this->columnCount = count($value);
	}

	function setField($value) {
		$this->fieldList = $value;
		$this->fieldCount = count($value);
	}

	function setKeyValue($value) {
		$this->keyList = $value;
		$this->keyCount = count($value);
	}

	private function tableHeader() {
		$retString = "";
		
		$pathInfo = get_path_info();
		if (isset($_SERVER["QUERY_STRING"])) {
			$queryString = preg_replace('/(&*)page=(\d+)/i', '', $_SERVER["QUERY_STRING"]);
			$queryString = preg_replace('/(&*)order=(\w+)/i', '', $_SERVER["QUERY_STRING"]);
			$queryString = preg_replace('/^&/i', '?', $queryString);
		} else {
			$queryString = "";
		}

		$linkUrl = $pathInfo . "?" . $queryString;

		# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # /
		#  임시코드 : 나중에 수정합시다.
		# # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # # /
		$linkUrl = str_replace("?&", "?", str_replace("?&", "?", $linkUrl));
		$linkUrl = str_replace("&&", "&", str_replace("&&", "&", $linkUrl));

		$retString = $retString . "<table id=\"table\" cellpadding='3' cellspacing='1' width='100%' bgcolor='#999999'>\r\n" . chr(13);
		$retString = $retString . "<thead id=\"theader\" bgcolor='#FFFFFF' align='center'>\r\n" . chr(13);
		if ($this->columnCount >= 0) {
			for ($i = 0; $i < $this->columnCount; $i++) {
				if ($this->order == $this->fieldList[$i]) {
					if ($this->orderType == "ASC") {
						$retString = $retString . "	<th><a href='" . $linkUrl . "&order=" . $this->fieldList[$i] . "+DESC'><u>" . $this->columnList[$i] . "△ </u></a></th>\r\n" . chr(13);
					} else
						if ($this->order == $this->fieldList[$i]) {
							$retString = $retString . "	<th><a href='" . $linkUrl . "&order=" . $this->fieldList[$i] . "+ASC'><u>" . $this->columnList[$i] . "▽ </u></a></th>\r\n" . chr(13);
						}
				} else {
					$retString = $retString . "	<th><a href='" . $linkUrl . "&order=" . $this->fieldList[$i] . "'>" . $this->columnList[$i] . "</a></th>\r\n" . chr(13);
				}
			}
		} else {
			foreach ($this->fieldList as $field) {
				$retString = $retString . "	<th>" . $field . "</th>\r\n" . chr(13);
			}
		}

		if ($this->buttonCount >= 0) {
			$retString = $retString . "	<th>action</th>\r\n" . chr(13);
		}

		$retString = $retString . "</thead>\r\n" . chr(13);
		$retString = $retString . "<tbody id=\"tbody\">" . chr(13);

		return $retString;
	}

	private function tableFooter() {
		$retString = "</tbody>\r\n" . chr(13);
		$retString = $retString . "</table>\r\n" . chr(13);

		return $retString;
	}

	function getTable($query) {
		global $mysqli;
		$rowString = "";
		$pagingString = "";
		$htmContent = "";
		
		if ($result = $mysqli->query($query)) {
			while ($row = $result->fetch_array()) {
				$keyString = "";
				foreach ($this->keyList as $key) {
					$keyString = $keyString . ", '" . $row[$key] . "'";
				}
	
				$rowString = $rowString . "<tr bgcolor='#FFFFFF' align='center' height='28' onMouseOver=\"this.style.background='#eaf2f4';\" onMouseOut=\"this.style.background='#FFFFFF';\">\r\n" . chr(13);
				foreach ($this->fieldList as $field) {
					if (is_numeric($row[$field]) && strlen($row[$field]) == 10) {
						# 날짜 표기임
						$rowString = $rowString . "	<td>" . date("Y-m-d",$row[$field]) . "</td>\r\n" . chr(13);
					} else {
						$rowString = $rowString . "	<td>" . textFormat($row[$field], 1) . "</td>\r\n" . chr(13);
					}
				}
	
				if ($this->buttonCount >= 0) {
					$rowString = $rowString . "<td>\r\n";
					for ($i = 0; $i < $this->buttonCount; $i++) {
						$rowString = $rowString . " <input type='button' value='" . $this->buttonList[$i] . "' onclick=\"clickButton(" . $i . $keyString . ");\">\r\n";
					}
	
					$rowString = $rowString . "</td>\r\n" . chr(13);
				}
				// $pagingString = $pagingString . "</tr>\r\n" . chr(13);
				// $pagingString = $pagingString . "<tr bgcolor=\"#FFFFFF\" height='25' onMouseOver=\"this.style.background='#eaf2f4';\" onMouseOut=\"this.style.background='#FFFFFF';\">\r\n" . chr(13);
				// for ($i = 0; $i < $this->fieldCount + 1; $i++) {
				// 	$pagingString = $pagingString . "	<td>&nbsp;</td>\r\n" . chr(13);
				// }
				// $pagingString = $pagingString . "</tr>\r\n" . chr(13);
			}
		}

		return $this->tableHeader() . $rowString . $this->tableFooter();
	}

	function displayListPage() {
		global $RecordCount, $gotoPage;
		
		# 여기 수정해야 함
		$htmContent = "";
		$this->totalpage = Ceil($RecordCount / $this->pageSize);
		$gb = "1";

		if ($this->totalPage > 0) {
			# ####페이지
			if ($gotoPage / $this->blockSize == intval($gotoPage / $this->blockSize)) {
				# 현재 페이지가 배수이면
				$BlockStart = $gotoPage - $this->blockSize +1;
			} else {
				# 배수가 아니면
				$BlockStart = intval($gotoPage / $this->blockSize) * $this->blockSize + 1;
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
