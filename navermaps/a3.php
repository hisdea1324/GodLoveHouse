<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Sample 06</title>
<script type="text/javascript" src="http://map.naver.com/js/naverMap.naver?key=481c0483e27994558af69d54e9d76ee1"></script>
</head>
<body>
<div id='mapContainer' style='width:300px;height:300px'></div>
<script type="text/javascript"> 
<!--
	/*지도 개체 생성 */
	var mapObj = new NMap(document.getElementById('mapContainer'),450,450);
 
	/* 지도 좌표, 축적 수 준 초기화 */
	mapObj.setCenterAndZoom(new NPoint(321198,529730),3);
 
	/* 지도 컨트롤 생성 */
	var zoom  = new NZoomControl();
	zoom.setAlign("left");
	zoom.setValign("bottom");
	mapObj.addControl(zoom);
	mapObj.addControl(new NIndexMap());
//-->
</script>
</body>
</html>

