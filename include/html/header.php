<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=[CHARSET]">
	<title>[TITLE]</title>
	<link href="[WEBROOT]include/css/style.css" rel="stylesheet" type="text/css">
	<script language='javascript' src='[WEBROOT]include/js/flash.js'></script>
	<script language='javascript' src='[WEBROOT]include/js/function.js'></script>
	<script language='javascript' src='[WEBROOT]include/js/interface.js'></script>
	<script language='javascript' src='[WEBROOT]include/js/prototype.js'></script>
	<script language='javascript' src='[WEBROOT]include/js/calendar.js'></script>
	<script type="text/javascript">

	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-30054839-1']);
	_gaq.push(['_trackPageview']);

	(function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
	</script>
</head>

<body onLoad="MM_preloadImages('[WEBROOT]images/sub/left_0101_on.gif')">
<div id="top"><a href="[WEBROOT]index.php"><img src="[WEBROOT]images/common/logo.gif" class="logo"></a>
	<ul class="gm">
		<li><img src="[WEBROOT]images/common/[LOGIN_STATUS1].gif" onclick="clickTopNavi([LOGIN_VALUE1]);" style="cursor:pointer"><img src="[WEBROOT]images/common/gm_bar.gif" class="m5"></li>
		<li><img src="[WEBROOT]images/common/[LOGIN_STATUS2].gif" onclick="clickTopNavi([LOGIN_VALUE2]);" style="cursor:pointer"><img src="[WEBROOT]images/common/gm_bar.gif" class="m5"></li>
		<li><img src="[WEBROOT]images/common/gm_03.gif" onclick="clickTopNavi(3);" style="cursor:pointer"></li>
	</ul>
	<!-- //topmenu -->
	<div id="topmenu">
		<ul>
			<li><a onmouseover="subMenu(0); this.className='topover';" onmouseout="this.className='top';" style="cursor: pointer;"><img src="[WEBROOT]images/common/topmenu_01.gif"></a></li>
			<li class="dot"><img src="[WEBROOT]images/common/topmenu_02.gif" onmouseover="subMenu(1); this.className='topover';" onmouseout="this.className='top';" style="cursor: pointer;"></li>
			<li class="dot"><img src="[WEBROOT]images/common/topmenu_09.gif" onmouseover="subMenu(6); this.className='topover';" onmouseout="this.className='top';" style="cursor: pointer;"></li>
			<li class="dot"><img src="[WEBROOT]images/common/topmenu_03.gif" onmouseover="subMenu(2); this.className='topover';" onmouseout="this.className='top';" style="cursor: pointer;"></li>
			<!--li class="dot"><img src="[WEBROOT]images/common/topmenu_05.gif" onmouseover="subMenu(4); this.className='topover';" onmouseout="this.className='top';" style="cursor: pointer;"></li>
			<li class="dot"><img src="[WEBROOT]images/common/topmenu_04.gif" onmouseover="subMenu(3); this.className='topover';" onmouseout="this.className='top';" style="cursor: pointer;"></li-->
			<li class="dot"><img src="[WEBROOT]images/common/topmenu_06.gif" onmouseover="subMenu(5); this.className='topover';" onmouseout="this.className='top';" style="cursor: pointer;"></li>
			<!--li class="dot"><img src="[WEBROOT]images/common/topmenu_04.gif" onclick="javascript:window.open('http://shoulder2shoulder.org/');" style="cursor: pointer;"></li-->
		</ul>
	</div>
	<div id="sub_menu0" onpropertychange="selectbox_hidden('sub_menu0');" style="position: absolute; z-index: 999; position: absolute; top:58px; left:200px; display:none;">
		<table width="451" border="0" cellspacing="0" cellpadding="0" class="submenu">
			<tr>
				<td class="list3"><img src="[WEBROOT]images/common/submenu_l.gif"></td>
				<td class="list2"><img src="[WEBROOT]images/common/submenu_0101.gif" onclick="clickTopMenu01(1);" style="cursor:pointer"></td>
				<td class="list"><img src="[WEBROOT]images/common/submenu_0102.gif" onclick="clickTopMenu01(2);" style="cursor:pointer"></td>
				<td class="list"><img src="[WEBROOT]images/common/submenu_0103.gif" onclick="clickTopMenu01(3);" style="cursor:pointer"></td>
				<td class="list"><img src="[WEBROOT]images/common/submenu_0104.gif" onclick="clickTopMenu01(4);" style="cursor:pointer"></td>
				<td class="list"><img src="[WEBROOT]images/common/submenu_0105.gif" onclick="clickTopMenu01(5);" style="cursor:pointer"></td>
				<td class="list3"><img src="[WEBROOT]images/common/submenu_r.gif"></td>
			</tr>
		</table>
	</div>
	<div id="sub_menu1" onpropertychange="selectbox_hidden('sub_menu1');" style="position: absolute; z-index: 999; position: absolute; top:58px; left:300px; display:none;">
		<table width="331" border="0" cellspacing="0" cellpadding="0" class="submenu">
			<tr>
				<td class="list3"><img src="[WEBROOT]images/common/submenu_l.gif"></td>
				<td class="list2"><img src="[WEBROOT]images/common/submenu_0202.gif" onclick="clickTopMenu02(2);" style="cursor:pointer"></td>
				<td class="list"><img src="[WEBROOT]images/common/submenu_0201.gif" onclick="clickTopMenu02(1);" style="cursor:pointer"></td>
				<td class="list"><img src="[WEBROOT]images/common/submenu_0203.gif" onclick="clickTopMenu02(3);" style="cursor:pointer"></td>
				<td class="list3"><img src="[WEBROOT]images/common/submenu_r.gif"></td>
			</tr>
		</table>
	</div>
	<div id="sub_menu2" onpropertychange="selectbox_hidden('sub_menu2');" style="position: absolute; z-index: 999; position: absolute; top:58px; left:390px; display:none;">
		<table width="422" border="0" cellspacing="0" cellpadding="0" class="submenu">
			<tr>
				<td class="list3"><img src="[WEBROOT]images/common/submenu_l.gif"></td>
				<td class="list2"><img src="[WEBROOT]images/common/submenu_0401.gif" onclick="clickTopMenu03(4);" style="cursor:pointer"></td>
				<td class="list"><img src="[WEBROOT]images/common/submenu_0301.gif" onclick="clickTopMenu03(1);" style="cursor:pointer"></td>
				<td class="list"><img src="[WEBROOT]images/common/submenu_0302.gif" onclick="clickTopMenu03(2);" style="cursor:pointer"></td>
				<td class="list"><img src="[WEBROOT]images/common/submenu_0303.gif" onclick="clickTopMenu03(3);" style="cursor:pointer"></td>
				<td class="list3"><img src="[WEBROOT]images/common/submenu_r.gif"></td>
			</tr>
		</table>
	</div>
	<!--div id="sub_menu3" onpropertychange="selectbox_hidden('sub_menu3');" style="position: absolute; z-index: 999; position: absolute; top:58px; left:730px; display:none;">
		<table width="165" border="0" cellspacing="0" cellpadding="0" class="submenu">
			<tr>
				<td class="list3"><img src="[WEBROOT]images/common/submenu_l.gif"></td>
				<td class="list2"><img src="[WEBROOT]images/common/submenu_0701.gif" onclick="clickTopMenu04(1);" style="cursor:pointer"></td>
				<td class="list"><img src="[WEBROOT]images/common/submenu_0702.gif" onclick="clickTopMenu04(2);" style="cursor:pointer"></td>
				<td class="list3"><img src="[WEBROOT]images/common/submenu_r.gif"></td>
			</tr>
		</table>
	</div-->
	<!--div id="sub_menu4" onpropertychange="selectbox_hidden('sub_menu4');" style="position: absolute; z-index: 999; position: absolute; top:58px; left:700px; display:none;"-->
	<!--div id="sub_menu4" onpropertychange="selectbox_hidden('sub_menu4');" style="position: absolute; z-index: 999; position: absolute; top:58px; left:600px; display:none;">
		<table width="245" border="0" cellspacing="0" cellpadding="0" class="submenu">
			<tr>
				<td class="list3"><img src="[WEBROOT]images/common/submenu_l.gif"></td>
				<td class="list2"><img src="[WEBROOT]images/common/submenu_0501.gif" onclick="clickTopMenu05(1);" style="cursor:pointer"></td>
				<td class="list"><img src="[WEBROOT]images/common/submenu_0503.gif" onclick="clickTopMenu05(3);" style="cursor:pointer"></td>
				<td class="list"><img src="[WEBROOT]images/common/submenu_0502.gif" onclick="clickTopMenu05(2);" style="cursor:pointer"></td>
				<td class="list3"><img src="[WEBROOT]images/common/submenu_r.gif"></td>
			</tr>
		</table>
	</div-->
	<div id="sub_menu5" onpropertychange="selectbox_hidden('sub_menu5');" style="position: absolute; z-index: 999; position: absolute; top:58px; left:580px; display:none;">
		<table width="384" border="0" cellspacing="0" cellpadding="0" class="submenu">
			<tr>
				<td class="list3"><img src="[WEBROOT]images/common/submenu_l.gif"></td>
				<td class="list2"><img src="[WEBROOT]images/common/submenu_0601.gif" onclick="clickTopMenu06(1);" style="cursor:pointer"></td>
				<td class="list"><img src="[WEBROOT]images/common/submenu_0602.gif" onclick="clickTopMenu06(2);" style="cursor:pointer"></td>
				<!--td class="list"><img src="[WEBROOT]images/common/submenu_0604.gif" onclick="clickTopMenu06(3);" style="cursor:pointer"></td-->
				<td class="list"><img src="[WEBROOT]images/common/submenu_0605.gif" onclick="clickTopMenu06(4);" style="cursor:pointer"></td>
				<td class="list"><img src="[WEBROOT]images/common/submenu_0603.gif" onclick="clickTopMenu06(5);" style="cursor:pointer"></td>
				<td class="list3"><img src="[WEBROOT]images/common/submenu_r.gif"></td>
			</tr>
		</table>
	</div>
	<div id="sub_menu6" onpropertychange="selectbox_hidden('sub_menu6');" style="position: absolute; z-index: 999; position: absolute; top:58px; left:440px; display:none;">
		<table width="308" border="0" cellspacing="0" cellpadding="0" class="submenu">
			<tr>
				<td class="list3"><img src="[WEBROOT]images/common/submenu_l.gif"></td>
				<!--td class="list2"><img src="[WEBROOT]images/common/submenu_0901.gif" onclick="clickTopMenu07(1);" style="cursor:pointer"></td-->
				<td class="list"><img src="[WEBROOT]images/common/submenu_0902.gif" onclick="clickTopMenu07(2);" style="cursor:pointer"></td>
				<td class="list"><img src="[WEBROOT]images/common/submenu_0903.gif" onclick="clickTopMenu07(3);" style="cursor:pointer"></td>
				<td class="list3"><img src="[WEBROOT]images/common/submenu_r.gif"></td>
			</tr>
		</table>
	</div>
	<!-- topmenu// -->
</div>

