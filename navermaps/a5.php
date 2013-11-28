<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<? 
$x = 0;
$y = 0;
$queryVal = isset($_REQUEST["Naddr"]) ? urlencode($_REQUEST["Naddr"]) : "";

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
?>
<html lang="ko">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
	<title>::: God's LoveHouse :::</title>
</head>

<body style="margin:0px 0px 0px 0px">
<? #script type="text/JavaScript" src="http://maps.naver.com/js/naverMap.naver?key=97c064093c192ebb34eb5763117f3cb8" ?>
<script type="text/JavaScript" src="http://maps.naver.com/js/naverMap.naver?key=481c0483e27994558af69d54e9d76ee1">
</script>
<div id='mapContainer' style='width:550px;height:450px'></div> 
<script type="text/javascript">
// 기본 지도 생성
var mapObj = new NMap(document.getElementById('mapContainer'),550,450);
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

