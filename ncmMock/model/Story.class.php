<?php
	ob_start(); 
	
	require_once 'User.class.php';
	require_once 'Tool.class.php';
	
	class Story {
		private $userIdLower;
		private $userIdUpper;
		private $userTotal;
		
		function __construct() {
			$this->userIdLower = 10000;
			$this->userIdUpper = 10100;
			$this->userTotal = $this->userIdUpper - $this->userIdLower;
		}

		function userStory1() {
			$result = new stdClass;
			$tempResult = $this->createUser();
			$result->Contacts = $tempResult->targetUsers;
			$phoneNumberPool = $tempResult->phoneNumberPool;
			$namePool = $tempResult->namePool;

			//target callLog, 15%频繁通话(平均每月5次)，80%很少(平均每月1次)，5%不联系
			$callLogs = array();
			$freqNumbers = array_slice($phoneNumberPool, 0, 0.15 * $this->userTotal);
			$freqNames = array_slice($namePool, 0, 0.15 * $this->userTotal);
			$rareNumbers = array_slice($phoneNumberPool, 0.15 * $this->userTotal, 0.8 * $this->userTotal);
			$rareNames = array_slice($namePool, 0.15 * $this->userTotal, 0.8 * $this->userTotal);
			$this->createCallLog($callLogs, $freqNumbers, $freqNames, 5);
			$this->createCallLog($callLogs, $rareNumbers, $rareNames, 1);
			$result->CallLogs = $callLogs;
			$result->MergeWeight = $this->calContactMergeWeight($result->Contacts);
			return $result;
		}

		function userStory2() {
			$result = new stdClass;
			$tempResult = $this->createUser();
			$result->Contacts = $tempResult->targetUsers;
			$phoneNumberPool = $tempResult->phoneNumberPool;
			$namePool = $tempResult->namePool;

			//top10 最常联系人（通话时间最长）
			define("TOP", 10);
			define("SP", 9);
			$freqContacts = array_slice($result->Contacts, 0, TOP);
			//特殊频率变化
			$specificFreqContact = $freqContacts[SP];
			$rareContacts = array_slice($result->Contacts, TOP);

			//默认平均每天有10次通话
			$amountOfCallLog = 12*30*10;
			//1年的记录
			$uptillNowDuration = 60*60*24*30*12;
			$now = time();
			
			$callLogs = array();
			$stat = $this->statInit($freqContacts, $specificFreqContact);
			for ($k = 0; $k < $amountOfCallLog; $k++) {
				$type = null; //0-呼出 1-呼进 2-未接
				$time = null;
				$callLogTmp = new stdClass;
				
				$contactId = $this->randomContact($freqContacts, $rareContacts, $type);
				$index = $contactId - $this->userIdLower;
				$callLogTmp->number = $phoneNumberPool[$index];
				$callLogTmp->name = $namePool[$index];

				//特殊通话频率：有一段时间频繁联系，后来频率突然就减少了
				if ($contactId == $specificFreqContact->id) {
					$time = $this->randomTime($now-$uptillNowDuration, $uptillNowDuration);
				}
				//默认起始时间
				$callLogTmp->time = !is_null($time) ? $time : Tool::randomInRange($now-$uptillNowDuration, $now);

				//默认通话时长
				$callLogTmp->duration = $this->randomDuration();

				//默认60%呼出，30%呼进，10%未接
				$callLogTmp->type = !is_null($type) ? $type : Tool::randomThreeSection(0.6, 0.3);
				if ($callLogTmp->type == 2) {
					$callLogTmp->duration = 0;
				}

				//统计
				$stat->totalDuration += $callLogTmp->duration;
				if ($callLogTmp->type != 2) {
					$stat->totalCount++;
				}
				if ($index < TOP) {
					if ($callLogTmp->type == 0) {
						$stat->top10[$index]->callOutTotal +=  $callLogTmp->duration;
					}elseif ($callLogTmp->type == 1) {
						$stat->top10[$index]->callInTotal +=  $callLogTmp->duration;
					}
					if($index == SP) {
						//呼出为正，呼进为负
						$durationTemp = $callLogTmp->type == 1 ? -1*$callLogTmp->duration : $callLogTmp->duration;
						$pair = array($callLogTmp->time, $durationTemp);
						$stat->specificFreqContact->callLog[] = $pair;
					}
				}
				else {
					if ($callLogTmp->type == 0) {
						$stat->top10[TOP]->callOutTotal +=  $callLogTmp->duration;
					}elseif ($callLogTmp->type == 1) {
						$stat->top10[TOP]->callInTotal +=  $callLogTmp->duration;
					}
				}

				$callLogs[] = $callLogTmp;
			}

			$result->CallLogs = $callLogs;
			$result->stat = $stat;
			return $result;
		}


		function statInit($freqContacts, $specificFreqContact) {
			$stat = new stdClass;
			//总通话时长
			$stat->totalDuration = 0;
			//总通话次数，不含未接电话
			$stat->totalCount = 0;
			$stat->top10 = array();

			foreach ($freqContacts as $value) {
				$freqContact = new stdClass;
				$freqContact->id = $this->getId($value);
				$freqContact->name = $this->getName($value);
				$freqContact->callOutTotal = 0;
				$freqContact->callInTotal = 0;
				$stat->top10[] = $freqContact;
			}
			$otherContact = new stdClass;
			$otherContact->id = null;
			$otherContact->name = '其他';
			$otherContact->callOutTotal = 0;
			$otherContact->callInTotal = 0;
			$stat->top10[] = $otherContact;

			$temp = new stdClass;
			$temp->id = $this->getId($specificFreqContact);
			$temp->name = $this->getName($specificFreqContact);
			$temp->callLog = array();
			$stat->specificFreqContact = $temp;

			return $stat;
		}

		function randomContact($freqContacts, $rareContacts, &$type) {
			//80%通话发生在top10联系人，20%在其他联系人
			if (Tool::randomPercentage() < 0.8) {
				//70%通话发生在top1联系人，30%在其他9个联系人，默认top1为freqContacts[0]
				$theMostFreqContact = $freqContacts[0];
				$otherFreqContacts = array_slice($freqContacts, 1);
				if (Tool::randomPercentage() < 0.7) {
					$contactId = $theMostFreqContact->id;
					//top1联系人90%为呼出，10%为呼进
					$type = Tool::randomPercentage() < 0.9 ? 0 : 1;
				}
				else {
					$contactId = Tool::randomInArray($otherFreqContacts)->id;
				}
			}
			else {
				$contactId = Tool::randomInArray($rareContacts)->id;
			}

			return $contactId;
		}

		//特殊通话频率：有一段时间频繁联系，后来频率突然就减少了
		function randomTime($start, $duration) {
			//分为频繁区间（占通话次数70%），缓冲区间(占通话次数20%)，极少联系区间(10%)
			switch (Tool::randomThreeSection(0.9, 0.1)) {
				case 0:
					$time = Tool::randomInRange($start, $start + $duration/5*2);
					break;
				case 1:
					$time = Tool::randomInRange($start + $duration/5*2, $start + $duration/5*3);
					break;	
				case 2:
					$time = Tool::randomInRange($start + $duration/5*3, $start + $duration);
					break;
				default:
					$time = null;
					break;	
			}
			return $time;
		}

		function randomDuration() {
			//分为短区间（10s到60s），中区间(61s到600s)，长区间(601s到3600s)
			switch (Tool::randomThreeSection(0.1, 0.7)) {
				case 0:
					$duration = Tool::randomInRange(10, 60);
					break;
				case 1:
					$duration = Tool::randomInRange(61, 600);
					break;	
				case 2:
					$duration = Tool::randomInRange(601, 3600);
					break;
				default:
					$duration = null;
					break;	
			}
			return $duration;
		}

		function createUser() {
			$user = new User();
			$userPoolIdLower = $user->userIdLower;
			$userPoolIdUpper = $user->userIdUpper;
			//original user pool
			$userPool = array();
			$phoneNumberPool = array();
			$homeNumberPool = array();
			$namePool = array();
			$emailPool = array();
			$userInfos = $user->getUserInfoBatch($userPoolIdLower, $userPoolIdUpper);

			for ($i = $userPoolIdLower; $i <= $userPoolIdUpper; $i++) {
				$userInfoTmp = $userInfos[$i];
				$userPool[] = $userInfoTmp;
				$phoneNumberPool[] = $userInfoTmp->Phone[0]->NUMBER;
				$homeNumberPool[] = $userInfoTmp->Phone[2]->NUMBER;
				$namePool[] = $userInfoTmp->StructuredName->DISPLAY_NAME;
				$emailPool[] = $userInfoTmp->Email[0]->ADDRESS;
			}

			//target user pool
			$targetUsers = array();
			for ($j = 0; $j < $this->userTotal; $j++) {
				$originalUserInfo = $userPool[$j];

				$userInfo = $user->createUserInfo();
				$userInfo->id = $j + $this->userIdLower;
				$userInfo->StructuredName->DISPLAY_NAME = $namePool[$j];
				$userInfo->Photo->PHOTO = stripslashes($originalUserInfo->Photo->PHOTO);
				//20%重复联系人，80%重复号码（70%重复手机，30%重复电话），20%重复email
				$phone = $user->createPhone();
				//MOBILE PHONE
				$phone->TYPE = 2;
				
				if (Tool::randomPercentage() < 0.2*0.8*0.7) {
					$phone->NUMBER = Tool::randomInArray($phoneNumberPool);
				}
				else {
					$phone->NUMBER = $phoneNumberPool[$j];
				}
				$userInfo->Phone[] = $phone;
				
				$homeNumberTemp = '';
				if (Tool::randomPercentage() < 0.2*0.8*0.3) {
					$homeNumberTemp = Tool::randomInArray($homeNumberPool);
				}
				else {
					$homeNumberTemp = $homeNumberPool[$j];
				}

				$email = $user->createEmail();
				$email->TYPE = $originalUserInfo->Email[0]->TYPE;
				if (Tool::randomPercentage() < 0.2*0.2) {
					$email->ADDRESS = Tool::randomInArray($emailPool);
				}
				else {
					$email->ADDRESS= $emailPool[$j];
				}
				$userInfo->Email[] = $email;

				//HOME PHONE 70%有电话
				$phone = $user->createPhone();
				$phone->TYPE = 1;
				$phone->NUMBER = Tool::randomPercentage() < 0.7 ? $homeNumberTemp : null;
				$userInfo->Phone[] = $phone;
				//5%有IM，默认为qq
				$Im = $user->createIm();
				$Im->PROTOCOL = 4;
				$Im->TYPE = 4;
				$Im->DATA = Tool::randomPercentage() < 0.05 ? $originalUserInfo->Im[0]->DATA : null;
				$userInfo->Im[] = $Im;

				$targetUsers[] = $userInfo;
			}
			//伪造一个所有信息都重复的用户
			$repeatUserInfo = clone $userInfo;
			$repeatUserInfo->id++;
			$targetUsers[] = $repeatUserInfo;
			$result = new stdClass;
			$result->targetUsers = $targetUsers;
			$result->phoneNumberPool = $phoneNumberPool;
			$result->namePool = $namePool;

			return $result;
		}
		
		//合并指数
		function calContactMergeWeight($contacts) {
			$targetContacts = $this->mergeFilterResult($contacts);			

			$contactsCount = count($targetContacts);	
			$contactMergeWeightArr = array();
			
			for($i = 0; $i < $contactsCount-1; $i++) {
				for($j = $i+1; $j < $contactsCount; $j++) {
					$contactPair = new stdClass;
					$contactPair->contactId1 = $targetContacts[$i]->id;
					$contactPair->contactId2 = $targetContacts[$j]->id;
					$contactPair->weight = $this->calWeight($targetContacts[$i], $targetContacts[$j]);
					if ($contactPair->weight > 0)
						$contactMergeWeightArr[] = $contactPair;
				}
			}

			usort($contactMergeWeightArr, $this->compareFunction('getWeight'));	

			return $contactMergeWeightArr;
		}

		function mergeFilterResult($contacts) {
			$arr1 = $this->filterContactWithRepeatInfo($contacts, 'getName');
			$arr2 = $this->filterContactWithRepeatInfo($contacts, 'getMobilePhone');
			$arr3 = $this->filterContactWithRepeatInfo($contacts, 'getHomePhone');
			$arr4 = $this->filterContactWithRepeatInfo($contacts, 'getQQ');
			$arr5 = $this->filterContactWithRepeatInfo($contacts, 'getEmail');

			$mergeArr = array_merge($arr1, $arr2, $arr3, $arr4, $arr5);

			usort($mergeArr, $this->compareFunction('getId'));

			return $this->uniqArr($mergeArr, $this->compareFunction('getId'));
		}

		function uniqArr($arr, $compareFunction) {
			$arrResult = array(); 
		    $arrResult[] = $arr[0]; 
		    for ($i = 1, $j = 0; $i < count($arr); $i++) {
		      if ($compareFunction($arr[$i], $arrResult[$j]) != 0) {
		        $arrResult[] = $arr[$i];
		        $j++;
		      }
		    }
		    
		    return $arrResult;
		}

		function filterContactWithRepeatInfo($contacts, $getFuntion) {
			usort($contacts, $this->compareFunction($getFuntion));
			
			return $this->filterSortedArr($contacts,  $this->compareFunction($getFuntion), $getFuntion);
		}

		function compareFunction($getFuntion) {
			return function ($x, $y) use ($getFuntion) {
				if ($this->$getFuntion($x) == $this->$getFuntion($y))
				  return 0;
				else if ($this->$getFuntion($x) > $this->$getFuntion($y))
				  return -1;
				else if ($this->$getFuntion($x) < $this->$getFuntion($y))
				  return 1;
			};
		}

		function filterSortedArr($arr, $compareFunction, $getFuntion) {
			$arrResult = array();
			$preElement = $arr[0];
			$fallBackFlag = true;
			for($i = 1; $i < count($arr); $i++) {
				if($this->$getFuntion($arr[$i]) != '' && $compareFunction($arr[$i], $preElement) == 0) {
					if ($fallBackFlag)
						$arrResult[] = $preElement;
					$arrResult[] = $arr[$i];
					$fallBackFlag = false;
				}
				else {
					$fallBackFlag = true;
				}
				$preElement = $arr[$i];
			}

			return $arrResult;
		}

		//规则引擎
		function calWeight($contact1, $contact2) {
			//0为无关，1为推荐合并，2为需要合并
			$weightType = 0;
			$sign1 = $sign2 = $sign3 = $sign4 = $sign5 = false;
			if($this->getName($contact1) != '' && $this->getName($contact1) == $this->getName($contact2))
				$sign1 = true;
			if($this->getMobilePhone($contact1) != '' && $this->getMobilePhone($contact1) == $this->getMobilePhone($contact2))
				$sign2 = true;
			if($this->getHomePhone($contact1) != '' && $this->getHomePhone($contact1) == $this->getHomePhone($contact2))
				$sign3 = true;
			if($this->getQQ($contact1) != '' && $this->getQQ($contact1) == $this->getQQ($contact2))
				$sign4 = true;
			if($this->getEmail($contact1) != '' && $this->getEmail($contact1) == $this->getEmail($contact2))
				$sign5 = true;
			if($sign1 || $sign2 || $sign3 || $sign4 || $sign5)
				$weightType = 1;
			if($this->getName($contact1) == $this->getName($contact2) && 
			$this->getMobilePhone($contact1) == $this->getMobilePhone($contact2) && 
			$this->getHomePhone($contact1) == $this->getHomePhone($contact2) && 
			$this->getQQ($contact1) == $this->getQQ($contact2) && 
			$this->getEmail($contact1) == $this->getEmail($contact2))
				$weightType = 2;

			return $weightType;
		}

		//加权指数
		function calWeight2($contact1, $contact2) {
			$weight = 0;
			if($this->getName($contact1) != '' && $this->getName($contact1) == $this->getName($contact2))
				$weight += 1;
			if($this->getMobilePhone($contact1) != '' && $this->getMobilePhone($contact1) == $this->getMobilePhone($contact2))
				$weight += 2;
			if($this->getHomePhone($contact1) != '' && $this->getHomePhone($contact1) == $this->getHomePhone($contact2))
				$weight += 2;
			if($this->getQQ($contact1) != '' && $this->getQQ($contact1) == $this->getQQ($contact2))
				$weight += 4;
			if($this->getEmail($contact1) != '' && $this->getEmail($contact1) == $this->getEmail($contact2))
				$weight += 8;

			return $weight;
		}

		function getId($contact) {
			return $contact->id;
		}

		function getWeight($stdClass) {
			return $stdClass->weight;
		}

		function getName($contact) {
			return $contact->StructuredName->DISPLAY_NAME;
		}

		function getMobilePhone($contact) {
			return $contact->Phone[0]->NUMBER;
		}

		function getHomePhone($contact) {
			return $contact->Phone[1]->NUMBER;
		}

		function getQQ($contact) {
			return $contact->Im[0]->DATA;
		}

		function getEmail($contact) {
			return $contact->Email[0]->ADDRESS;
		}

		function createCallLog(&$callLogs, $numberArr, $nameArr, $freq) {
			$amountOfCallLog = count($numberArr)*$freq*12;
			//1年的记录
			$startTime = 60*60*24*30*12;
			//配置，持续时间，默认为10s到10min
			$durationLower = 10;
			$durationUpper = 10*60;
			$now = time();
			$type = 2; //0-呼出 1-呼进 2-未接
			for($k = 0; $k < $amountOfCallLog; $k++) {
				$callLogTmp = new stdClass;
				$r = Tool::randomInRange(0, count($numberArr)-1);
				$callLogTmp->number = $numberArr[$r];
				$callLogTmp->name = $nameArr[$r];		
				$callLogTmp->time = Tool::randomInRange($now-$startTime, $now);
				$callLogTmp->duration = Tool::randomInRange($durationLower, $durationUpper);
				$callLogTmp->type = Tool::randomNumber($type);
				if ($callLogTmp->type == 2) {
					$callLogTmp->duration = 0;
				}

				$callLogs[] = $callLogTmp;
			}
		}
	}
?>