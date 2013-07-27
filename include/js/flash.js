//flash 2006.11.17
function callFlash(flashSrc, w, h, isTransparent) {
	var srcTr = "<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,24,0' width='"+w+"' height='"+h+"'>\n";
	srcTr += "		<param name='allowScriptAccess' value='always' />\n";
	srcTr += "		<param name='movie' value='"+flashSrc+"'>\n";
	srcTr += "		<param name='quality' value='high'>\n";
	if(isTransparent == 'transparent') {
		srcTr += "		<param name='wmode' value='transparent'>\n";
		srcTr += "		<embed src='"+flashSrc+"' quality='high' allowScriptAccess='always' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash' width='"+w+"' height='"+h+"' wmode='transparent'></embed>\n";
	} else {
	srcTr += "		<param name='wmode' value='opaque'>\n";
		srcTr += "		<embed src='"+flashSrc+"' quality='high' allowScriptAccess='always' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash' width='"+w+"' height='"+h+"' wmode='opaque'></embed>\n";
	}
	srcTr += "	</object>";
	document.write(srcTr);
}