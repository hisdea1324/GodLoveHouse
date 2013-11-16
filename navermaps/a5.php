<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />

<title>::: God's LoveHouse :::</title>

</head>

<?php 
$x = 0;
$y = 0;
$queryVal = isset($_REQUEST["Naddr"]) ? rawurlencode($_REQUEST["Naddr"]) : "";

$REQUEST_URL = "http://map.naver.com/api/geocode.php?key=481c0483e27994558af69d54e9d76ee1&encoding=utf-8&query=".$queryVal;

$curl_handle = curl_init();
curl_setopt($curl_handle, CURLOPT_URL, $REQUEST_URL);
curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_handle, CURLOPT_USERAGENT, 'http://www.godlovehouse.net');
$content = curl_exec($curl_handle);
$response = curl_getinfo($curl_handle);
curl_close($curl_handle);


$obj_xml = simplexml_load_string($content); 
$x = $obj_xml->item->point->x; 
$y = $obj_xml->item->point->y; 


//$budget74 = simplexml_load_file($REQUEST_URL);
//if ($budget74->status!='OK') die("네이버맵API에서 주소에 대한 좌표를 받아 오는데 실패하였습니다!");
/*
$lat = $budget74->result->geometry->location->lat;
$lng = $budget74->result->geometry->location->lng;
echo "lat:{$lat}, lng:{$lng}";
[출처] [geocoding api] php 에서 google maps api 사용하여 좌표 정보 얻어오기|작성자 얼룩푸우

//response.write REQUEST_URL	
// $xml is of type "MSXML2.ServerXMLHTTP"
	$xml->Open("GET", $REQUEST_URL, false);
	$xml->SetRequestHeader("GET", $REQUEST_URL."HTTP/1.0");
	$xml->Send();
//	response.write xml.ResponseText	
// $dom is of type "Microsoft.XMLDOM"
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
*/
?>

<body style="margin:0px 0px 0px 0px">
<script type="text/JavaScript" src="http://maps.naver.com/js/naverMap.naver?key=481c0483e27994558af69d54e9d76ee1">
</script>
<div id='mapContainer' style='width:550px;height:450px'></div> 
<script type="text/javascript">
// 기본 지도 생성
var mapObj = new NMap(document.getElementById('mapContainer'),550,450);
//mapObj.setBound(<?php echo $x;?><%,<?php echo $y;?><%, <?php echo $x;?><%,<?php echo $y;?><%); 
mapObj.setCenterAndZoom(new NPoint(<?=$x?>,<?=$y?>),3);
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
var marker = new NMark(NPoint(<?=$x?>,<?=$y?>), new NIcon(iconUrl, new NSize(22, 29)));
	mapObj.addOverlay(marker);

</script>

</body>
</html>

