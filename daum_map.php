<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Daum 지도 API</title>
<script type="text/javascript" src="http://apis.daum.net/maps/maps.js?apikey=a641ba54f9f3dfcf1be698c474d3c587b965a14e" charset="utf-8"></script>
</head>
<body topmargin="0" leftmargin="0">
	<div id="map" style="width:700px;height:400px;"></div>
	<script type="text/javascript">
		var map = new DMap("map", {point:new DLatLng(37.51629527200998, 127.01976791269156), level:1}); 
		
		//var icon = new DIcon("http://localimg.daum-img.net/localimages/07/2008/map/i_mks_b1.gif", new DSize(127, 33));
		//icon.src = "http://www.servingod.org/yulynmoon/new/img/body/foot_logo.gif";
		//map.addOverlay(new DMark(new DLatLng(37.51549895934866, 127.01395020103005), {mark:icon})); // mark Overlay
		
		var iw = new DInfoWindow("http://www.godlovehouse.net/include/html/daum_banner.html", {width:170, height:80});
		var m = new DMark(new DLatLng(37.51549895934866, 127.01435020103005), {infowindow:iw, draggable:true});
		map.addOverlay(m); // mark Overlay
		map.addControl(new DIndexMapControl());
		map.addControl(new DZoomControl());	
		
		// 맵이동
		window.setTimeout(function() {map.panTo(new DLatLng(37.51549895934866, 127.01435020103005));}, 1000);
		
		//신사 37.51629527200998, 127.01976791269156
		//잠원 37.51276438751054, 127.01108601643457
		//교회 37.51549895934866, 127.01435020103005
	 </script>
</body>
</html>

