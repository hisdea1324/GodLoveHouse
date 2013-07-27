document.writeln("<div id='quick'><a href='#'><img src='../files/images/common/img_quick.gif' alt='' /></a></div>");




window.onerror = ErrorSetting 

var e_msg=""; 
var e_file=""; 
var e_line=""; 


function ErrorSetting(msg, file_loc, line_no) { 
	e_msg=msg; 
	e_file=file_loc; 
	e_line=line_no; 
	return true; 
}
///////////////////////////////

var isDOM = (document.getElementById ? true : false); 
var isIE4 = ((document.all && !isDOM) ? true : false); 
var isNS4 = (document.layers ? true : false); 
 
function getRef(id) { 
if (isDOM) return document.getElementById(id); 
if (isIE4) return document.all[id]; 
if (isNS4) return document.layers[id]; 
} 
 
var isNS = navigator.appName == "Netscape"; 
function moveRightEdge() { 
		var yMenuFrom, yMenuTo, yOffset, timeoutNextCheck; 
		if (isNS4) { 
				yMenuFrom = divMenu.topBottom; 
				yMenuTo = windows.pageYOffset + 0 ; 
		} else if (isDOM) { 
				yMenuFrom = parseInt (divMenu.style.top, 0); 
				//yMenuTo = (isNS ? window.pageYOffset : document.body.scrollTop) + 260; // 위쪽 위치 
				//yMenuTo		 = document.body.scrollTop +26; // 위쪽 위치 
				//yMenuTo = (isNS ? window.pageYOffset : document.body.scrollTop) + 600; // 위쪽 위치 
				// 여기 수정
				yMenuTo = (isNS ? window.pageYOffset : document.documentElement.scrollTop) + 500; // 위쪽 위치 
		} 
		timeoutNextCheck = 100; 
		if (yMenuFrom != yMenuTo) { 
				yOffset = Math.ceil(Math.abs(yMenuTo - yMenuFrom) / 10); //스크롤속도
				if (yMenuTo < yMenuFrom) 
						yOffset = -yOffset; 
				if (isNS4) 
						divMenu.top += yOffset; 
				else if (isDOM) 
						divMenu.style.top = parseInt (divMenu.style.top, 10) + yOffset; 
						timeoutNextCheck = 10; 
		} 
		setTimeout ("moveRightEdge()", timeoutNextCheck); 
} 
 
if (isNS4) { 
		var divMenu = document["quick"]; 
		divMenu.top = top.pageYOffset +10; 

		divMenu.visibility = "visible"; 
		moveRightEdge(); 
} else if (isDOM) { 
		var divMenu = getRef('quick'); 
		//divMenu.style.top = (isNS ? window.pageYOffset : document.body.scrollTop) +10; 
//						divMenu.style.top = document.body.scrollTop +10; 
		
		// 여기 수정
		divMenu.style.top = (isNS ? window.pageYOffset : document.documentElement.scrollTop) + 230; 
		divMenu.style.visibility = "visible"; 
		moveRightEdge(); 
} 

//-->
<!--// Quick Menu //-->