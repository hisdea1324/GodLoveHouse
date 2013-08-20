	var geoCodingUrl="http://map.naver.com/api/geocode.php?key=481c0483e27994558af69d54e9d76ee1&query=경남마산시회원2동500-5";
	var xmlhttp =	new NXmlhttp();
	xmlhttp.setType(1);
	xmlhttp.loadhttp(geoCodingUrl.saveXml)

function saveXml(xml) {	var xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
	xmlDoc.load(xml);
	var x = xmlDoc.getElementsByTagName('x');
	var y = xmlDoc.getElementsByTagName('y');

	if (x.length > 0 ) {
		var xV = x[0].firstChild.nodeValue;
		var yV = y[0].firstChild.nodeValue;
		displayCurrentLoc(xV,yV);
	} else {
	alert ("검색된 결과가 없습니다.");
	}
}

