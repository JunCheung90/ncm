<?php
require_once 'Db.class.php';

$tableType = $_POST["table"]; 
$serialData = $_POST["data"];
$userIdBase = $_POST["userBase"];

$mysql = new mysql();

define('USER_ID_BASE', 2001);
//通话圈子大小
define('CALL_LOG_BASE', 10);
define('CALL_LOG_SIZE', 30);
//联系人圈子大小
define('CONTACT_BASE', 10);
define('CONTACT_SIZE', 20);

if ($tableType == "user") {
	$users = json_decode(stripslashes($serialData), true);
	$userCount = count($users);
	$userValues = array();
	$userDataValues = array();
	for ($i = 0; $i < $userCount; $i++) {
		$displayName = $users[$i]['StructuredName']['DISPLAY_NAME'];
		$tmpUrl = "/ncmMock/img/avatar/";
		$photoUrl = $tmpUrl.$users[$i]['Photo']['PHOTO'];
		// $userId = $i + USER_ID_BASE;
		$userId = $i + $userIdBase;
		$userValues[] =  "('{$userId}', '{$displayName}', '{$photoUrl}')";
		$mimetypeArr = array("Phone", "Email", "Im", "StructuredPostal");
		$mimetypeCount = count($mimetypeArr);
		for ($j = 0; $j < $mimetypeCount; $j++) { 
			$mimetype = $mimetypeArr[$j];
			$dataArr = $users[$i][$mimetype];
			$dataArrCount = count($dataArr);
			for ($k = 0; $k < $dataArrCount; $k++) { 
				$data = $dataArr[$k];
				$values = array();
				foreach ($data as $key => $value) {
					$values[] = $value;
				}
				for ($t = count($values); $t < 4; $t++) { 
					$values[] = null;
				}
				$userDataValues[] = "('{$userId}','{$mimetype}','{$values[0]}','{$values[1]}','{$values[2]}','{$values[3]}')";
			}
		}
	}

	$mysql->insert("INSERT INTO user (id, display_name, photo_url) VALUES ".implode(',', $userValues));
	$mysql->insert("INSERT INTO user_data (user_id, mimetype, data1, data2, data3, data4) VALUES ".implode(',', $userDataValues));	
}elseif ($tableType == "call_log") {
	$callLogs = json_decode(stripslashes($serialData), true);
	$callLogsCount = count($callLogs);
	$callLogValues = array();
	//number pool
	$callLogBase = CALL_LOG_BASE;
	$callLogUpper = CALL_LOG_SIZE + CALL_LOG_BASE - 1; 
	$sql = "SELECT user_data.data1 FROM user_data WHERE mimetype='Phone'and data2='2' and user_id >= '$callLogBase' and  user_id <= '$callLogUpper' ORDER BY user_id";
	$queryResult = $mysql->query($sql);
	$numberArr = array();
	while ($row = $mysql->fetch_array_num($queryResult)) {
		$numberArr[] = $row[0];
	}	
	for ($i = 0; $i < $callLogsCount; $i++) {
		$callLog = $callLogs[$i];
		$userId = $callLog['u_id1'];
		$userId2 = $callLog['u_id2'];
		$number = $numberArr[$userId2 - CALL_LOG_BASE];
		$name = null;
		$callLogValues[] =  "('{$userId}', '{$number}', '{$name}', '{$callLog['time']}', '{$callLog['duration']}', '{$callLog['type']}')";
	}

	$mysql->insert("INSERT INTO call_log (user_id, number, name, time, duration, type) VALUES ".implode(',', $callLogValues));	
}elseif ($tableType == "contact") {
	$contacts = json_decode(stripslashes($serialData), true);
	$contactsCount = count($contacts);
	$contactValues = array();
	for ($i = 0; $i < $contactsCount; $i++) {
		$userId = $contacts[$i]['u_id1'];
		$userId2 = $contacts[$i]['u_id2'];
		$contactValues[] = "('{$userId}', '{$userId2}')";
	}
	$mysql->insert("INSERT INTO contact (user_id1, user_id2) VALUES ".implode(',', $contactValues));
}

echo "Done :)";
$mysql->close();
?>