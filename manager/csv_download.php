<?
require_once($_SERVER['DOCUMENT_ROOT']."/include/include.php");
require_once($_SERVER['DOCUMENT_ROOT']."/include/manageMenu.php");
checkAuth();

// DOWNLOAD
if (!isset($_REQUEST["table"])) {
	exit();
}

global $mysqli;

if (isset($_REQUEST["action"]) && $_REQUEST["action"] == "download") {
	$table =  $_REQUEST["table"];
	
	$key = array(
		"users" => "userid, name, nick, userlv, email, address1, address2, zipcode, phone, mobile",
		"missionary" => "userid, missionname, church, churchcontact, ngo, ngocontact, nationcode, approval, manager, managercontact, manageremail, homepage, bank, accountno, accountname",
		"house" => "houseid, houseName, status, assocName, address1, address2, zipcode, regionCode, buildingType, userid, manager1, contact1, manager2, contact2, price, personLimit, personLimit1, roomCount, roomLimit, price1, homepage",
		"room" => "roomId, roomName, houseId, `limit`, network, kitchen, laundary, bed, fee, hide",
		"reservation" => "reservationNo, userid, hospitalId, roomId, reservStatus, FROM_UNIXTIME(startDate,'%Y.%m.%d'), FROM_UNIXTIME(endDate,'%Y.%m.%d'), FROM_UNIXTIME(regDate,'%Y.%m.%d'), arrival_time, people_number, purpose, memo, resv_name, resv_phone, resv_nation, resv_nation"
	);
	
	$query = "SELECT {$key[$table]} FROM {$table}";
	$result = $mysqli->query($query);
	
	$data_set = array(explode(", ", $key[$table]));
	while ($row = $result->fetch_assoc()) {
		array_push($data_set, $row);
	}
	
	array_to_csv_download($data_set, "export.csv");
}
?>