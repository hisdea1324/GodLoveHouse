<?php // asp2php (vbscript) converted on Fri Apr 12 22:35:58 2013
 ?>
<?php header("Content-type: "."text/html;charset=euc-kr"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />

<title>::: God's LoveHouse :::</title>

</head>

<?php 
$Naddress = $_REQUEST["Naddr"];
//Naddress= server.URLEncode(request("addr"))
//response.write "1 :"&request("addr") & "							 "
//response.write "3 :"& Naddress
//451884 291241 
//Naddress = "경기도 금촌검산 유승아파트 102호 203호"
$queryVal = $Naddress;

//java.net.URLEncoder.encode(address)$queryVal=rawurlencode($Naddress);
//queryVal = server.UrlEncode(Trim(Naddress))
//queryVal = addressy
//response.write "4:" & queryVal
if (!($queryVal=="")) {
//response.write queryVal &"-1-"	
$REQUEST_URL="http://map.naver.com/api/geocode.php?key=481c0483e27994558af69d54e9d76ee1&encoding=utf-8&query=".$queryVal;
//response.write REQUEST_URL	
// $xml is of type "MSXML2.ServerXMLHTTP"
	$xml->Open("GET", $REQUEST_URL, false);
	$xml->SetRequestHeader("GET", $REQUEST_URL."HTTP/1.0");
	$xml->Send();
//	response.write xml.ResponseText	// $dom is of type "Microsoft.XMLDOM"
	$dom->async=false;
	$dom->load();
	$xml->responseBody;
	$xmlroot = $dom->documentElement;
//Set xmlnode1
//Set xmlnode2
//response.write "	"&	xmlroot.childNodes.Length	
	if (($xmlroot->childNodes->length)>2) {
//	response.write xmlroot.childNodes.Length
//response.write xmlroot.childNodes(1).text		
	$xmlnode1 = $xmlroot->SelectSingleNode;
		$x = $xmlnode1->text;

//	response.write xmlnode1.text
		$xmlnode2 = $xmlroot->SelectSingleNode;
		$y = $xmlnode2->text;
	} else {
		$x="319198";
		$y="542490";
	} 

} 

$xml = null;

$dom = null;

//response.write "																 " & x & " " & y?>

<body style="margin:0px 0px 0px 0px">
<script type="text/JavaScript" src="http://maps.naver.com/js/naverMap.naver?key=481c0483e27994558af69d54e9d76ee1">
</script>
<div id='mapContainer' style='width:550px;height:450px'></div> 
<script type="text/javascript">
// 기본 지도 생성
var mapObj = new NMap(document.getElementById('mapContainer'),550,450);
//mapObj.setBound(<?php echo $x;?><%,<?php echo $y;?><%, <?php echo $x;?><%,<?php echo $y;?><%); 
mapObj.setCenterAndZoom(new NPoint(<?php echo $x;?><%,<?php echo $y;?><%),3);
mapObj.zoomOut();

// 확대, 축소를 위한 컨트롤을 생성합니다.
var zoom = new NZoomControl();
zoom.setAlign("right");
zoom.setValign("bottom");
mapObj.addControl(zoom);

var mapBtns = new NMapBtns();
	mapBtns.setAlign("right");
	mapBtns.setValign("top");
	mapObj.addControl(mapBtns);

var iconUrl = "http://www.godlovehouse.net/navermaps/GLH.gif";
var marker = new NMark(NPoint(<?php echo $x;?><%,<?php echo $y;?><%), new NIcon(iconUrl, new NSize(22, 29)));
	mapObj.addOverlay(marker);

</script>

</body>
</html>

