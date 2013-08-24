var calObjdoc;
var calInput;

// 아직도 firefox에서 작동하지 않음 
function calendar_init()
{
	var date = new Date;
	var year = date.getFullYear();
	var month = date.getMonth();
	
	var calStyle = "\
	<style>\
	body {margin:0}\
	select {font:8pt tahoma}\
	a {text-decoration:none;color:#000000}\
	.tahoma {font:8pt tahoma}\
	.white {color:#ffffff}\
	.today {font-weight:bold;color:#ff0000}\
	</style>\
	";

	var calLayout = "\
	<form name=frmCalendar style='display:inline'>\
	<table width=201 cellpadding=0 cellspacing=0>\
	<tr>\
		<td bgcolor=#AAAAAA style='padding:0 9'>\
		<table width=100% cellpadding=0 cellspacing=0 class=tahoma>\
		<tr>\
			<td width=30><font color=#ffffff onClick='parent.calendar_move(-1)' style='cursor:pointer'>◀</font></td>\
			<td align=center>\
			<select name=year onChange=parent.calendar_update()></select>\
			<select name=month onChange=parent.calendar_update()></select>\
			</td>\
			<td width=30 align=right><font color=#ffffff onClick='parent.calendar_move(1)' style='cursor:pointer'>▶</font></td>\
		</tr>\
		</table>\
		</td>\
	</tr>\
	<tr>\
		<td>\
		<table width=100% id=calInner class=tahoma>\
		<tr><th style='color:red'>S</th><th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th style='color:blue'>S</th></tr>\
		<col align=center span=7>\
		</table>\
		</td>\
	</tr>\
	<tr>\
		<td bgcolor=#AAAAAA>\
		<table width=100% class=tahoma>\
		<tr>\
			<td><a href='javascript:parent.calendar_update(" + year + "," + month + ")' onfocus=blur() class=white>\
			<b>now</b> (" + year + "-" + parent.calendar_addZero(month+1) + ")\
			</a></td>\
			<td align=right><a href='javascript:parent.calendar_close()' class=white>close</a></td>\
		</tr>\
		</table>\
		</td>\
	</tr>\
	</table>\
	</form>\
	";
	
	calObj = "<iframe id=calObj width='201' style='position:absolute;left:-999;background:#ffffff;' frameborder=0></iframe>";
	document.write(calObj);

	calObjdoc = document.getElementById('calObj').contentWindow.document;
	calObjdoc.open();
	calObjdoc.write(calStyle);
	calObjdoc.write(calLayout);
	calObjdoc.close();

	calendar_setup();
	document.getElementById('calObj').style.display = 'none';
}

function calendar_setup() {
	var objMonth = calObjdoc.frmCalendar.month;
	for (i=0;i<12;i++) objMonth.options[i] = new Option(i+1+"월",i);

	var date = new Date;
	var year = date.getFullYear();
	var month = date.getMonth();
	calendar_update(year,month);
}

function calendar_update(year,month) {
	if (isNaN(year)){
		year = calObjdoc.frmCalendar.year.value;
		month = calObjdoc.frmCalendar.month.value;
	}

	year = parseInt(year);

	var objYear = calObjdoc.frmCalendar.year;
	var objMonth = calObjdoc.frmCalendar.month;

	for (i=0;i<5;i++) objYear.options[i] = new Option(year+i-2+"년",year+i-2);
	objYear.selectedIndex = 2;
	objMonth.selectedIndex = month;

	calendar_inner(year,month);
}

function calendar_inner(year,month) {
	var date = new Date;
	var Y = date.getFullYear();
	var m = date.getMonth();
	var d = date.getDate();
	
	var firstDay = new Date(year,month);
	firstDay = firstDay.getDay();
	var lastDay = calendar_lastDay(year,month);

	var obj = calObjdoc.getElementById('calInner');

	for (i=obj.rows.length;i>1;i--) obj.deleteRow(i-1);

	oTr = obj.insertRow(-1);
	for (i=0;i<firstDay;i++) oTr.insertCell(-1);
	cnt = i;

	for (i=1;i<=lastDay;i++){
		if (cnt%7==0) oTr = obj.insertRow(-1);
		oTd = oTr.insertCell(-1);
		oTd.style.cursor = "pointer";
		oTd.align = "center";
		oTd.style.backgroundColor = "#f7f7f7";
		oTd.color = "#000000";
		if (Y==year && m==month && d==i){
			oTd.color = "#ff0000";
			oTd.style.fontWeight = "bold";
		}
		oTd.innerHTML = i;
		oTd.style.color = oTd.color;
		oTd.onmouseover = function(){this.style.backgroundColor = "#316AC5"; this.style.color = "#ffffff"}
		oTd.onmouseout = function(){this.style.backgroundColor = "#f7f7f7"; this.style.color = this.color}
		oTd.onclick = function(){parent.calendar_print(this.innerHTML)}
		cnt++;
	}
	document.getElementById('calObj').height = calObjdoc.body.scrollHeight;
}

function calendar_move(idx) {
	var year = calObjdoc.frmCalendar.year.value;
	var month = parseInt(calObjdoc.frmCalendar.month.value) + idx;

	if (month<0){ year--; month=11; }
	if (month==12){ year++; month=0; }

	calendar_update(year,month);
}

function calendar(objName) {
	calInput = document.getElementById(objName);

	var xpos = get_objectLeft(calInput);
	var ypos = get_objectTop(calInput) + calInput.offsetHeight + 2;

	var calObj = document.getElementById('calObj');
	calObj.style.display = "block";
	calObj.style.left = xpos + 'px';
	calObj.style.top = ypos + 'px';
}

function calendar_print(day) {
	var year = calObjdoc.frmCalendar.year.value;
	var month = calObjdoc.frmCalendar.month.value;
	
	calInput.value = calendar_format(year,month,day);
	calendar_close();
}

function calendar_format(year,month,day) {
	month++;
	var format = (calInput.getAttribute("format")!=null) ? calInput.format : "%Y-%m-%d";

	var Y = year;
	var y = year.substr(2,2);
	var m = calendar_addZero(month);
	var d = calendar_addZero(day);

	format = format.replace(/%Y/g, Y);
	format = format.replace(/%y/g, y);
	format = format.replace(/%m/g, m);
	format = format.replace(/%d/g, d);

	return format;
}

function calendar_close() {
	var calObj = document.getElementById('calObj');
	calObj.style.display = "none";
}

function calendar_addZero(str){
	return ((str < 10) ? "0" : "") + str;
}

function calendar_lastDay(year,month){
	var leap;
	var last = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
	if (year%4==0)		leap = true;
	if (year%100==0)	leap = false;
	if (year%400==0)	leap = true;
	if (leap) last[1] = 29;
	return last[month];
}

function get_objectTop(obj){
	if (obj.offsetParent == document.body) return obj.offsetTop;
	else return obj.offsetTop + get_objectTop(obj.offsetParent);
}

function get_objectLeft(obj){
	if (obj.offsetParent == document.body) return obj.offsetLeft;
	else return obj.offsetLeft + get_objectLeft(obj.offsetParent);
}
