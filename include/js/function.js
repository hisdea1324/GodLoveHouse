/*쎌렉트박스*/
function MM_jumpMenu(targ,selObj,restore){ //v3.0
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) selObj.selectedIndex=0;
}

/* 문서 전체 링크 점선 없애기 */
function bluring(){ 
if(event.srcElement.tagName=="A"||event.srcElement.tagName=="IMG") document.body.focus(); 
} 
document.onfocusin=bluring; 

/*따라다니는 메뉴*/
var scroll_pixel,div_pixel,gtpos,gbpos,loop,moving_spd;
var top_margin = 200;			/// 창의 맨위와의 여백 내려올때
var top_margin2 =260;			/// 창의 맨위와의 여백 올라올때
var speed = 40;					/// 점차 줄어드는 속도를 위한 설정
var speed2 = 15;				/// setTimeout을 위한 속도 설정
var moving_stat = 1;			/// 메뉴의 스크롤을 로딩시 on/off설정 1=움직임 0은 멈춤
 
function check_scrollmove(){
	scroll_pixel = document.body.scrollTop;
	gtpos = document.body.scrollTop+top_margin;
	gbpos = document.body.scrollTop+top_margin2;
	if(div_id.style.pixelTop < gtpos){
		moving_spd = (gbpos-div_id.style.pixelTop)/speed;
		div_id.style.pixelTop += moving_spd;
	}
	if(div_id.style.pixelTop > gtpos){
		moving_spd = (div_id.style.pixelTop-gtpos)/speed;
		div_id.style.pixelTop -= moving_spd;
	}
	loop = setTimeout("check_scrollmove()",speed2);
}

function moving_control(){
	if(!moving_stat){
	 check_scrollmove(); moving_stat = 1;
	} else{
	 clearTimeout(loop); moving_stat = 0; div_id.style.pixelTop = top_margin;
	}
}

//check_scrollmove();


/* 롤오버버튼 */

function MM_swapImgRestore() { //v3.0
	var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
	var d=document; if(d.images){ if(!d.MM_p) d.MM_p = new Array();
		var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
		if (a[i].indexOf("#")!=0){ d.MM_p[j] = new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
	var p,i,x;	if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
		d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
	if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
	for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
	if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
	var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr = new Array; for(i=0;i<(a.length-2);i+=3)
	 if ((x=MM_findObj(a[i])) != null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

<!--
	// 서브메뉴
	function subMenu(arg) {
		try {
			if (document.getElementById('sub_menu0')) {
				if (arg == 0)	 document.getElementById('sub_menu0').style.display="inline";
				else						document.getElementById('sub_menu0').style.display="none";
			}
			if (document.getElementById('sub_menu1')) {
				if (arg == 1)	 document.getElementById('sub_menu1').style.display="inline";
				else						document.getElementById('sub_menu1').style.display="none";
			}
			if (document.getElementById('sub_menu2')) {
				if (arg == 2)	 document.getElementById('sub_menu2').style.display="inline";
				else						document.getElementById('sub_menu2').style.display="none";
			}
			if (document.getElementById('sub_menu3')) {
				if (arg == 3)	 document.getElementById('sub_menu3').style.display="inline";
				else						document.getElementById('sub_menu3').style.display="none";
			}
			if (document.getElementById('sub_menu4')) {
				if (arg == 4)	 document.getElementById('sub_menu4').style.display="inline";
				else						document.getElementById('sub_menu4').style.display="none";
			}
			if (document.getElementById('sub_menu5')) {
				if (arg == 5)	 document.getElementById('sub_menu5').style.display="inline";
				else						document.getElementById('sub_menu5').style.display="none";
			}
		if (document.getElementById('sub_menu6')) {
				if (arg == 6)	 document.getElementById('sub_menu6').style.display="inline";
				else						document.getElementById('sub_menu6').style.display="none";
			}
		} catch(e) {}
	}
	// Internet Explorer에서 셀렉트박스와 레이어가 겹칠시 레이어가 셀렉트 박스 뒤로 숨는 현상을 해결하는 함수
	// 레이어가 셀렉트 박스를 침범하면 셀렉트 박스를 hidden 시킴
	// 사용법 :
	// <div id=LayerID style="display:none; position:absolute;" onpropertychange="selectbox_hidden('LayerID')">
	function selectbox_hidden(layer_id) {
		// 메뉴가 많아질 경우 너무 오랜 시간이 걸림. 일단 주석처리
	/*
		selectbox_visible() ;
		var ly = eval(layer_id);

		// 레이어 좌표
		var ly_left	= ly.offsetLeft;
		var ly_top		= ly.offsetTop;
		var ly_right	= ly.offsetLeft + ly.offsetWidth;
		var ly_bottom = ly.offsetTop + ly.offsetHeight;

		// 셀렉트박스의 좌표
		var el;

		for (i=0; i<document.forms.length; i++) {
			for (k=0; k<document.forms[i].length; k++) {
				el = document.forms[i].elements[k];

				if (el.type == "select-one") {
					var el_left = el_top = 0;
					var obj = el;
					if (obj.offsetParent) {
						while (obj.offsetParent) {
							el_left += obj.offsetLeft;
							el_top	+= obj.offsetTop;
							obj = obj.offsetParent;
						}
					}
					el_left	+= el.clientLeft;
					el_top		+= el.clientTop;
					el_right	= el_left + el.clientWidth;
					el_bottom = el_top + el.clientHeight;

					// 좌표를 따져 레이어가 셀렉트 박스를 침범했으면 셀렉트 박스를 hidden 시킴
					if ( (el_left >= ly_left && el_top >= ly_top && el_left <= ly_right && el_top <= ly_bottom) ||
	//						(el_right >= ly_left && el_right <= ly_right && el_top >= ly_top && el_top <= ly_bottom) ||
							(el_right >= ly_left && el_left <= ly_right && el_top >= ly_top && el_top <= ly_bottom) ||
							(el_left >= ly_left && el_bottom >= ly_top && el_right <= ly_right && el_bottom <= ly_bottom) ||
							(el_left >= ly_left && el_left <= ly_right && el_bottom >= ly_top && el_bottom <= ly_bottom) ) {
						el.style.visibility = 'hidden';
	//alert(el.name+"V:"+el.style.visibility+"\n"+"left:"+el_left+"top:"+el_top+"right:"+el_right+"bottom:"+el_bottom+"\n"+"left:"+ly_left+"top:"+ly_top+"right:"+ly_right+"bottom:"+ly_bottom);
					}
				}
			}
		}
	*/
	}
	// 감추어진 셀렉트 박스를 모두 보이게 함
	function selectbox_visible() {
		for (i=0; i<document.forms.length; i++) {
			for (k=0; k<document.forms[i].length; k++) {
				el = document.forms[i].elements[k];
				if (el.type == "select-one" && el.style.visibility == 'hidden')
					el.style.visibility = 'visible';
			}
		}
	}
//-->

/*FAQ*/
function clickblock(num)	
{	
	for (i=1;i<12;i++)	{			//5라는 수는 줄수보다 1 더한값을 적어주세요//
		var left_menu=eval("block"+i+".style");																										
		if (num==i) {	
			if (left_menu.display=="block") { 
				left_menu.display="none";	 
			}else{	
				left_menu.display="block";
			}	
		}else {
			left_menu.display="none";
		}	
	}	
}

var WebRoot = "/";
function clickTopNavi(num) {
	switch (num) {
		case 1:
			location.href = WebRoot + 'member/login.php'; 
			break;
		case 2: 
			location.href = WebRoot + 'member/join.php'; 
			break;
		case 3: 
			location.href = WebRoot + 'common/sitemap.php'; 
			break;
		case 4:
			location.href = WebRoot + 'member/process.php?mode=logout';
			break;
		case 5:
			location.href = WebRoot + 'member/mypage_member.php';
			break;
		case 6:
			location.href = WebRoot + 'member/mypage_support.php';
			break;
		case 7:
			location.href = WebRoot + 'member/mypage_cert.php';
			break;
		case 8:
			location.href = WebRoot + 'member/mypage_houseInfo.php';
			break;
		case 9:
			location.href = WebRoot + 'member/mypage_houseReserv.php';
			break;
		case 10:
			if (confirm("정말로 탈퇴하시겠습니까\n탈퇴 시 회원님의 개인 정보는 삭제됩니다.")) {
				location.href = WebRoot + 'member/process.php?mode=withdrawal';
			}
			break;
		case 11:
			location.href = WebRoot + 'member/mypage_missionary.php';
			break;
		case 12:
			location.href = WebRoot + 'living/registHouse.php';
			break;
		case 13:
			centerWinOpen(1200, 680, WebRoot + 'house_manager/index.php', "house_manager");
			break;
		default : location.href = webRoot;
	}
}

function clickTopMenu01(num) {
	switch (num) {
		case 1:
			location.href = WebRoot + 'doorkeeper/welcome.php'; 
			break;
		case 2: 
			location.href = WebRoot + 'doorkeeper/introduce.php'; 
			break;
		case 3: 
			location.href = WebRoot + 'doorkeeper/company.php'; 
			break;
		case 4: 
			location.href = WebRoot + 'doorkeeper/guide.php'; 
			break;
		case 5: 
			location.href = WebRoot + 'doorkeeper/map.php'; 
			break;
		case 6: 
			location.href = WebRoot + 'doorkeeper/welcome2.php'; 
			break;
		case 7: 
			location.href = WebRoot + 'doorkeeper/business.php'; 
			break;
		case 8: 
			location.href = WebRoot + 'doorkeeper/manage.php'; 
			break;
		default : location.href = webRoot;
	}
}

function clickTopMenu02(num) {
	switch (num) {
		case 1:
			location.href = WebRoot + 'living/etc.php'; 
			break;
		case 2: 
			location.href = WebRoot + 'living/reservation.php'; 
			break;
		case 3: 
			location.href = WebRoot + 'living/registHouse.php'; 
			break;
		default : location.href = webRoot;
	}
}

function clickTopMenu03(num) {
	switch (num) {
		case 1:
			location.href = WebRoot + 'sponsor/special.php'; 
			break;
		case 2: 
			location.href = WebRoot + 'sponsor/center.php'; 
			break;
		case 3: 
			location.href = WebRoot + 'sponsor/service.php'; 
			break;
		case 4: 
			location.href = WebRoot + 'cooperate/family.php'; 
			break;
		default : location.href = webRoot;
	}
}

function clickTopMenu04(num) {
	switch (num) {
		case 1:
			location.href = WebRoot + 'community/need.php'; 
			break;
		case 2: 
			location.href = WebRoot + 'community/share.php'; 
			break;
		default : location.href = webRoot;
	}
}

function clickTopMenu05(num) {
	switch (num) {
		case 1:
			location.href = WebRoot + 'community/mission_news.php'; 
			break;
		case 2:
			location.href = WebRoot + 'community/support_news.php'; 
			break;
		case 3: 
			location.href = WebRoot + 'fiscal/outgoings.php'; 
			break;
		default : location.href = webRoot;
	}
}

function clickTopMenu06(num) {
	switch (num) {
		case 1:
			location.href = WebRoot + 'community/notice.php'; 
			break;
		case 2: 
			location.href = WebRoot + 'community/event.php'; 
			break;
		case 3: 
			location.href = WebRoot + 'community/column.php'; 
			break;
		case 4: 
			location.href = WebRoot + 'community/impression.php'; 
			break;
		case 5: 
			location.href = WebRoot + 'community/free.php'; 
			break;
		default : location.href = webRoot;
	}
}

function clickTopMenu07(num) {
	switch (num) {
		case 1:
			location.href = WebRoot + 'hospital/reservation.php'; 
			break;
		case 2: 
			location.href = WebRoot + 'hospital/etc.php'; 
			break;
		case 3: 
			location.href = WebRoot + 'hospital/registHouse.php'; 
			break;
		default : location.href = webRoot;
	}
}
