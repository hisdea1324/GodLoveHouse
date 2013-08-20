<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE> Sample 07 </TITLE>
</HEAD>
 
<BODY>
<SCRIPT LANGUAGE="JavaScript" src="http://map.naver.com/js/naverMap.naver?key=481c0483e27994558af69d54e9d76ee1"></SCRIPT>
<div id='mapContainer' style='width:300px;height:300px'></div>
<SCRIPT LANGUAGE="JavaScript"> 
<!--
	/*지도 개체 생성 */
	var opts = {width:500, height:300, mapMode:1};
 
	var mapObj = new NMap(document.getElementById('mapContainer'),opts);
 
	/* 지도 좌표, 축적 수 준 초기화 */
	mapObj.setCenterAndZoom(new NPoint(321198,529730),3);
 
	/* 지도 컨트롤 생성 */
	var zoom = new NZoomControl();
 
	zoom.setAlign("right");
	zoom.setValign("top");
	mapObj.addControl(zoom);
 
	/* 지도 모드 변경 버튼 생성 */
	var mapBtns = new NMapBtns();
	mapBtns.setAlign("right");
	mapBtns.setValign("top");
	mapObj.addControl(mapBtns);
 
//-->
</SCRIPT>
</BODY>
</HTML>

