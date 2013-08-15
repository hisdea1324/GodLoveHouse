<?php
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");

$roomId = (isset($_REQUEST["roomId"])) ? trim($_REQUEST["roomId"]) : "";
$houseId = (isset($_REQUEST["houseId"])) ? trim($_REQUEST["houseId"]) : "";
$field = (isset($_REQUEST["field"])) ? trim($_REQUEST["field"]) : "";
$keyword = (isset($_REQUEST["keyword"])) ? trim($_REQUEST["keyword"]) : "";
$gotoPage = (isset($_REQUEST["gotoPage"])) ? trim($_REQUEST["gotoPage"]) : "";


$h_Helper = new HouseHelper();
$room = $h_Helper->getRoomInfoById($roomId);
$house = $h_Helper->getHouseInfoById($houseId);

checkAuth();
showAdminHeader("관리툴 - 방 등록","","","");
body();
showAdminFooter();

function body() {
	global $keyword, $field, $page;
	global $house, $room;
?>
	<div class="sub">
	<a href="editHouse.php?mode=addHouse&keyword=<?php echo $keyword;?>&field=<?php echo $field;?>">선교관추가</a> | 
	<a href="index.php">등록된 선교관</a> | 
	<a href="index.php?status=S2001">대기중 선교관</a> | 
	<a href="editHospital.php??mode=addHospital&keyword=<?php echo $keyword;?>&field=<?php echo $field;?>">병원 추가</a> | 
	<a href="hospital.php">등록된 병원</a> | 
	<a href="hospital.php?status=S2001">대기중 병원</a>
	</div>
	</div>
	<div id="wrap">
		<div class="lSec">
		<ul>
			<li><img src="/images/manager/lm_0200.gif"></li>
		<li><a href="editHouse.php?mode=addHouse&keyword=<?php echo $keyword;?>&field=<?php echo $field;?>"><img src="/images/manager/lm_0201.gif"></a></li>
		<li><a href="index.php"><img src="/images/manager/lm_0202.gif"></a></li>
		<li><a href="index.php?status=S2001"><img src="/images/manager/lm_0203.gif"></a></li>
		<li><img src="/images/manager/lm_bot.gif"></li>
		</ul>
	</div>
	
	<div class="rSec">
		<dl>
		<form name="dataForm" id="dataForm" method="post">
		<input type="hidden" name="field" value="<?php echo $field;?>" />
		<input type="hidden" name="keyword" value="<?php echo $keyword;?>" />
		<input type="hidden" name="page" value="<?php echo $page;?>" />
		<input type="hidden" name="mode" value="editRoom" />
		<input type="hidden" name="roomId" value="<?php echo $room->RoomId;?>" />
			<dt>
				선교관 이름 
			<dd>
				<?php echo $house->HouseName;?>&nbsp;&nbsp;
				<input type="hidden" name="houseId" value="<?php echo $house->HouseID;?>" />
			<dt>
				방 이름	
			<dd>
				<input type="text" name="roomName" size="20" maxlength=20 value="<?php echo $room->RoomName;?>" />&nbsp;&nbsp;
			<dt>
				인터넷 유무
			<dd>
				<input type="text" name="network" size="20" value="<?php echo $room->Network;?>" />
			<dt>
				취사 여부
			<dd>
				<input type="text" name="kitchen" size="20" value="<?php echo $room->Kitchen;?>" />
			<dt>
				세탁여부
			<dd>
				<input type="text" name="laundary" size="20" value="<?php echo $room->Laundary;?>" />
			<dt>
				요금
			<dd>
				<input type="text" name="fee" size="20" value="<?php echo $room->Fee;?>" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" />
			<dt>
				방인원수
			<dd>
				<input type="text" name="limit" size="20" value="<?php echo $room->Limit;?>" onKeyPress="CheckNumber(event);" style="ime-mode:disabled" />
			<dt>
				이미지 1
			<dd>
				<div id="showimage1" style="position:absolute;visibility:hidden;border:1px solid black"></div>
				<input type="button" name="imgUpload" id="imgUpload" value="이미지 업로드" onclick="uploadImage(event, 'RoomImage1', 'room')" style="cursor:pointer" /> (320x220)<br />
				<input type="hidden" name="idRoomImage1" id="idRoomImage1" value="<?php echo $room->ImageID1;?>" />
				<img src="<?php echo $room->Image1;?>" id="imgRoomImage1" width="320" height="220" onclick="showImage(1, this, event)" alt="크게보기" style="cursor:pointer" />
			<dt>
				이미지 2
			<dd>
				<div id="showimage2" style="position:absolute;visibility:hidden;border:1px solid black"></div>
				<input type="button" name="imgUpload" id="imgUpload" value="이미지 업로드" onclick="uploadImage(event, 'RoomImage2', 'room')" style="cursor:pointer" /> (320x220)<br />
				<input type="hidden" name="idRoomImage2" id="idRoomImage2" value="<?php echo $room->ImageID2;?>" />
				<img src="<?php echo $room->Image2;?>" id="imgRoomImage2" width="320" height="220" onclick="showImage(2, this, event)" alt="크게보기" style="cursor:pointer" />
			<dt>
				이미지 3
			<dd>
				<div id="showimage3" style="position:absolute;visibility:hidden;border:1px solid black"></div>
				<input type="button" name="imgUpload" id="imgUpload" value="이미지 업로드" onclick="uploadImage(event, 'RoomImage3', 'room')" style="cursor:pointer" /> (320x220)<br />
				<input type="hidden" name="idRoomImage3" id="idRoomImage3" value="<?php echo $room->ImageID3;?>" />
				<img src="<?php echo $room->Image3;?>" id="imgRoomImage3" width="320" height="220" onclick="showImage(3, this, event)" alt="크게보기" style="cursor:pointer" />
			<dt> 
				이미지 4
			<dd>
				<div id="showimage4" style="position:absolute;visibility:hidden;border:1px solid black"></div>
				<input type="button" name="imgUpload" id="imgUpload" value="이미지 업로드" onclick="uploadImage(event, 'RoomImage4', 'room')" style="cursor:pointer" /> (320x220)<br />
				<input type="hidden" name="idRoomImage4" id="idRoomImage4" value="<?php echo $room->ImageID4;?>" />
				<img src="<?php echo $room->Image4;?>" id="imgRoomImage4" width="320" height="220" onclick="showImage(4, this, event)" alt="크게보기" style="cursor:pointer" />
			<dt>&nbsp;
			<dd>
				<img src="/images/board/btn_ok.gif" border="0" onclick="check();" style="cursor:hand;">&nbsp;&nbsp;&nbsp;
				<img src="/images/board/btn_cancel.gif" border="0" onclick="history.back(-1);" style="cursor:hand"></a>
		</form>
		</dl>
	</div>
	</div>
<?php } ?>

<script type="text/javascript">
//<![CDATA[
	function check() {
		document.getElementById("dataForm").action="process.php";
		document.getElementById("dataForm").submit();
	}
	
	function showImage(id, obj, e) {
		crossobj = document.getElementById("showimage" + id);
		
		if (crossobj.style.visibility == "hidden") {
			crossobj.style.left = e.clientX;
			crossobj.style.top = e.clientY;
			crossobj.innerHTML = '<img src="' + obj.src + '" style="cursor:pointer" onClick="closepreview()" />';
			crossobj.style.visibility = "visible";
		} else {
			crossobj.style.visibility = "hidden";
		}
	}

	function closepreview(){
		crossobj.style.visibility="hidden"
	}
//]]>
</script>
