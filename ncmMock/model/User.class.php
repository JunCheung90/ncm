<?php
	ob_start(); 
	
	require_once 'Db.class.php';
	
	class User {
		public $userIdLower;
		public $userIdUpper;
		
		function __construct() {
			$this->userIdLower = 1;
			$this->userIdUpper = 2000;
		}

		function createUserInfo() {
			$userInfo = new stdClass;
			$userInfo->id = null;
			$structuredName = new stdClass;
			$structuredName->DISPLAY_NAME = null;
			$userInfo->StructuredName = $structuredName;
			$photo = new stdClass;
			$photo->PHOTO = null;
			$userInfo->Photo = $photo;
			$userInfo->Phone = array();
			$userInfo->Email = array();
			$userInfo->Im = array();
			$userInfo->StructuredPostal = array();
			
			return $userInfo;
		}

		function createPhone() {
			$phone = new stdClass;
			$phone->NUMBER = null;
			$phone->TYPE = null;

			return $phone;
		}

		function createEmail() {
			$email = new stdClass;
			$email->ADDRESS = null;
			$email->TYPE = null;

			return $email;
		}

		function createIm() {
			$Im = new stdClass;
			$Im->DATA = null;
			$Im->PROTOCOL = null;
			$Im->TYPE = null;

			return $Im;
		}

		function createStructuredPostal() {
			$structuredPostal = new stdClass;
			$structuredPostal->FORMATTED_ADDRESS = null;
			$structuredPostal->POSTCODE = null;
			$structuredPostal->TYPE = null;

			return $structuredPostal;
		}

		function create() {
			$mysql = new mysql();
			do {
				$userId = rand($this->userIdLower, $this->userIdUpper);	
				$queryResult = $mysql->query("SELECT * FROM user WHERE id = '".$userId."'");
				$row = $mysql->fetch_array($queryResult);
			} while (!$row);
			
			return $this->getUserInfo($row['id']);
		}

		function getUserInfo($userId) {
			$userId = intval($userId);
			$userArr = $this->getUserInfoBatch($userId, $userId);
			return $userArr[$userId];
		}

		function getUserInfoBatch($userIdLower, $userIdUpper) {
			$mysql = new mysql();
			$queryResult = $mysql->query("SELECT * FROM user WHERE id >= '".$userIdLower."' AND id <= '".$userIdUpper."'");
			$users = array();
			while ($userRow = $mysql->fetch_array($queryResult)) {
				$user = $this->createUserInfo();
				$user->id = $userRow['id'];	
				$user->StructuredName->DISPLAY_NAME = $userRow['display_name'];
				$user->Photo->PHOTO = stripslashes($userRow['photo_url']);
				$users[$user->id] = $user;
			}
			$queryResult = $mysql->query("SELECT * FROM user_data WHERE user_id >= '".$userIdLower."' AND user_id <= '".$userIdUpper."'");
			while ($userDataRow = $mysql->fetch_array($queryResult)) {
				$user = $users[$userDataRow['user_id']];
				switch ($userDataRow['mimetype']) {
					case 'Phone':
						$phone = $this->createPhone();
						$phone->NUMBER = $userDataRow['data1'];
						$phone->TYPE = $userDataRow['data2'];
						$user->Phone[] = $phone;
						break;
					case 'Email':
						$email = $this->createEmail();
						$email->ADDRESS = $userDataRow['data1'];
						$email->TYPE = $userDataRow['data2'];
						$user->Email[] = $email;
						break;
					case 'Im':
						$Im = $this->createIm();
						$Im->DATA = $userDataRow['data1'];
						$Im->PROTOCOL = $userDataRow['data2'];
						$Im->TYPE = $userDataRow['data3'];
						$user->Im[] = $Im;
						break;
					case 'StructuredPostal':
						$structuredPostal = $this->createStructuredPostal();
						$structuredPostal->FORMATTED_ADDRESS = $userDataRow['data1'];
						$structuredPostal->POSTCODE = $userDataRow['data2'];
						$structuredPostal->TYPE = $userDataRow['data3'];
						$user->StructuredPostal[] = $structuredPostal;
						break;		
					default:
						break;
				}
			}

			return $users;
		}

		function getContact($userId) {
			$mysql = new mysql();
			$userId = intval($userId);
			$queryResult = $mysql->query("SELECT * FROM contact WHERE user_id1 = '".$userId."'");
			$contacts = array();
			$i = 0;
			while($row = $mysql->fetch_array($queryResult)) {
				$i++;
				$contact = new stdClass;
				$contact->userId = $row['user_id2']; 
				$contacts[] = $contact;
			}
			$result = new stdClass;
			$result->contacts = $contacts;
			$result->totalNumber = $i;

			return $result;
		}

		function getAndroidContact($userId) {
			$mysql = new mysql();
			$tmp = $this->getContact($userId);
			$contactIds = $tmp->contacts;
			$totalContact = $tmp->totalNumber;
			$contacts = array();

			for ($i = 0; $i < $totalContact; $i++) {
				$tmpId = $contactIds[$i]->userId;
				$contacts[] = $this->getUserInfo($tmpId);
			}
			$result = new stdClass;
			$result->contacts = $contacts;
			$result->totalNumber = $totalContact;

			return $result;
		}

		function getCallLog($userId) {
			$mysql = new mysql();
			$userId = intval($userId);
			$queryResult = $mysql->query("SELECT * FROM call_log WHERE user_id = '".$userId."'");
			$callLogs = array();
			$i = 0;
			while($row = $mysql->fetch_array($queryResult)) {
				$i++;
				$callLog = new stdClass;
				$callLog->number = $row['number']; 
				$callLog->name = $row['name']; 
				$callLog->time = $row['time'];
				$callLog->duration = $row['duration'];
				$callLog->type = $row['type'];
				$callLogs[] = $callLog;
			}
			$result = new stdClass;
			$result->callLogs = $callLogs;
			$result->totalNumber = $i;

			return $result;
		}
	
	}
?>