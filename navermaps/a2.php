<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Sample 04 - 지도 상 마커 및 정보창 이용</title>
</head>
<body>
<?php 
$channel_list="http://maps.naver.com/api/geocode.php?key=481c0483e27994558af69d54e9d76ee1&query=경남마산시회원2동500-5";
$map_point = $map_point["channerl_list"];

?>

<script type="text/javascript" src="http://map.naver.com/js/naverMap.naver?key=481c0483e27994558af69d54e9d76ee1&query=경남마산시회원2동500-5"></script>
<div id="mapContainer" style="width:300px; height:300px;"></div>
<script type="text/javascript"> 
<!--
var cnt = 0;
 
function createMarker(pos, count, content) {
	var iconUrl = 'http://static.naver.com/local/map_img/set/icos_free_'+String.fromCharCode(96+count)+'.gif';
	var marker = new NMark(pos, new NIcon(iconUrl, new NSize(15, 14)));
	NEvent.addListener(marker, "mouseover", function(pos) {
			infowin.set(pos, '<div style="width:100px; height:50px; background-color:#ffffff; border:solid 1px #666666;">' + content + '</div>');
			infowin.showWindow()
	]);
	NEvent.addListener(marker, "mouseout", function() {
			infowin.hideWindow();
	]);
 
	return marker;
}
 
function clickMap(pos) {
	if (cnt>=10) {
		alert('이 예제에서는 10개까지만 추가 가능합니다.');
		return;
	}
	
	cnt++;
	mapObj.addOverlay(createMarker(pos,cnt,"마커" + cnt));
}
 
var mapObj = new NMap(document.getElementById('mapContainer'),300,300);
var infowin = new NInfoWindow();
 
mapObj.setCenterAndZoom(new NPoint(321198,529730),3);
mapObj.addOverlay(infowin);
NEvent.addListener(mapObj,"click",clickMap);
//-->
</script>
</body>
</html>


