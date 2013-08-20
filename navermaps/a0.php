<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>


<title>GOD Love House</title>
</head>
<?php // asp2php (vbscript) converted on Fri Apr 12 22:35:58 2013
 $CODEPAGE="65001";?>


<?php 

$addressy = $_REQUEST["addr"];
//response.write addressy//451884 291241 //addressy = "경기도 금촌검산 유승아파트 102호 203호"//response.write addressy//queryVal = addressy
$queryVal=rawurlencode($addressy);
//queryVal = server.UrlEncode(Trim(addressy))
//response.write queryVal
if (!($queryVal=="")) {
//response.write queryVal &"-1-"	$REQUEST_URL="http://map.naver.com/api/geocode.php?key=481c0483e27994558af69d54e9d76ee1&query=".$queryVal;
//response.write REQUEST_URL	// $xml is of type "MSXML2.ServerXMLHTTP"
	$xml->Open"GET"	$REQUEST_URL.""	$False;
	$xml->SetRequestHeader"GET"	$REQUEST_URL."HTTP/1.0";
	$xml->Send();
//	response.write xml.ResponseText	// $dom is of type "Microsoft.XMLDOM"
	$dom->async=false;

	$dom->load$xml->responseBody;

	$xmlroot = $dom->documentElement;
//Set xmlnode1//Set xmlnode2//response.write "	"&	xmlroot.childNodes.Length	if (($xmlroot->childNodes$Length)>2) {
//	response.write xmlroot.childNodes.Length//response.write xmlroot.childNodes(1).text		$xmlnode1 = $xmlroot->SelectSingleNode;
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

//var infowin = new NInfoWindow();


	//mapObj.setBound(<?php echo $x;?><%,<?php echo $y;?><%, <?php echo $x;?><%,<?php echo $y;?><%); 
	mapObj.setCenterAndZoom(new NPoint(<?php echo $x;?><%,<?php echo $y;?><%),3);		//현위치
	mapObj.zoomOut();



//response.write "																 " & x & " " & y

// 확대, 축소를 위한 컨트롤을 생성합니다.
var zoom = new NZoomControl();						// 줌...
	zoom.setAlign("right");
	zoom.setValign("bottom");
	mapObj.addControl(zoom);

var mapBtns = new NMapBtns();
	mapBtns.setAlign("right");
	mapBtns.setValign("top");
	mapObj.addControl(mapBtns);

	var iconUrl = "http://www.godlovehouse.net/navermaps/GLH.gif";
	var marker = new NMark(NPoint(<?php echo $x;?><%,<?php echo $y;?><%), new NIcon(iconUrl, new NSize(15, 14)));
		mapObj.addOverlay(marker);


</script>

</body>
</html>

