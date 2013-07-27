<script language="JavaScript">
if (window.XMLHttpRequest) {xmlhttp = new XMLHttpRequest();
}
else //internet Explore 5/6
{
xmlhttp = new ActiveXObjext("Microsoft.XMLHTTP");
}
xmlhttp.open("GET","http://map.naver.com/api/geocode.php?key=481c0483e27994558af69d54e9d76ee1&query=경남마산시회원2동500-5","false");
xmlhttp.send();
var xml = xmlhttp.responseXML;
var x = xml.getElementsByTagName("x");
var y = xml.getElementsByTagName("y");
//alert(x.item(0));
if (document.form1.getcode.value) {if (x.item(0) != null ) {opener.document.go_write.x.value = x.iterm(0).firstChild.nodeValue;
opener.document.go_write.y.value = y.iterm(0).firstChild.nodeValue;
alert('죄표를 구했습니다.');
self.colse();
}
else alert('좌표를 구하지 못했습니다. 올바른 주소를 입력하세요.');
}
</script>

