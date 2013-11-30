var ROOT = "/";

function CheckNumber(event) {
	if ((event.keyCode < 48) || (event.keyCode > 57)) 
		event.returnValue = false;
}

function PostPopup() {
	centerWinOpen(450,500,ROOT + "common/findPost.php","FindPost");
}

function uploadImage(event, tag, path) {
	centerWinOpen(400,250,ROOT + "common/uploadImage.php?tag=" + tag + "&path=" + path, "uploadImage");
}

function uploadFile(event, tag, path) {
	centerWinOpen(400,250,ROOT + "common/uploadFile.php?tag=" + tag + "&path=" + path, "uploadFile");
}

function searchUser() {
	centerWinOpen(400,250,ROOT + "common/searchUser.php","idCheck");
}

function checkId() {
	centerWinOpen(400,250,ROOT + "common/searchId.php","idCheck");
}

function checkName() {
	centerWinOpen(400,250,ROOT + "common/searchName.php","nameCheck");
}

function centerWinOpen(ww, wh, winUrl, winName) {
	sw = screen.width;
	sh = screen.height;
	cw = (sw-ww)/2;
	ch = (sh-wh)/2;
	features = "scrollbars=yes, top="+ch+","+"left="+cw+",width="+ww+",height="+wh;
	window.open(winUrl,winName,features);
}
