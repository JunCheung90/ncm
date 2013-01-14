<?php
	class Tool {			
		//非法登录
		static function send() {
			if (!$_POST['send']) {
				echo '<script>alert(\'非法登录！\');history.back();</script>';
				exit;
			}
		}
		
		//错误执行方法
		static function error($error) {
			echo "<script>alert('$error');history.back();</script>";
			exit;
		}
		
		//正确提示方法
		static function right($right) {
			echo "<script>alert('$right');</script>";
		}
		
		//正确提示方法2
		static function right2($right,$url) {
			echo "<script>alert('$right');location.href='$url'</script>";
		}	

		//关联数组转为json
		static function json($data){
			if(is_array($data) || is_object($data)){
				return json_encode($data, JSON_UNESCAPED_UNICODE);
			}
		}	

		//转换字符串编码
		static function toUTF8($str) {
	        $encoding = mb_detect_encoding($str, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
	        return mb_convert_encoding($str, 'utf-8', $encoding);
	    }

		static function randomNumber($range) {
			return rand(0, $range); 
		}

		static function randomInRange($min, $max) {
			return rand($min, $max); 
		}

		static function randomInArray($arr) {
			$r = rand(0, count($arr)-1);
			return $arr[$r]; 
		}

		static function randomMultiInArray($sourceArr, $count) {
			$indexArr = array_rand($sourceArr, $count);
			$resultArr = array();
			foreach ($indexArr as $key => $value) {
				$resultArr[$value] = $sourceArr[$value];
			}
			return $resultArr;
		}

		static function randomPercentage() {
			return lcg_value(); 
		}

		static function randomThreeSection($firstSectionProbability, $secondSectionProbability) {
			$r = Tool::randomPercentage();
			if ($r <= $firstSectionProbability) {
				$sectionIndex = 0;
			}
			elseif ($r > $firstSectionProbability && $r <= $firstSectionProbability + $secondSectionProbability) {
				$sectionIndex = 1;
			}
			else {
				$sectionIndex = 2;
			}

			return $sectionIndex;
		}
	}
?>