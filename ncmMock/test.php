<?php
//文件重命名脚本
// $a=array("a"=>"Dog","b"=>"Cat","c"=>"Horse");
echo stripslashes("http:\/\/172.18.43.151\/ncmMock\/img\/avatar\/m51.gif");
// $a1=array(0=>"Cat",1=>"Dog",2=>"Horse");
// $a2=array(1=>"Bird",3=>"Rat",4=>"Fish");
// $a3=array(5=>"Horse",6=>"Dog",7=>"Bird");
// print_r(array_diff_key($a1,$a2,$a3));

//分隔字符脚本
// $filePath = "100Name2.txt";
// $fp = fopen($filePath,"r ");
// $string = read_content_to_array($fp);   // 取得文本文件中的内容
    
// echo($string);
// function read_content_to_array($fp){
//     $i=0;
//     $a = array();
//     $str = "";
//     while (!feof ($fp)) {    
//         $nameArr = array();
//         while ($buffer = fgets($fp)) {
// 			$row = explode(" ",$buffer); 
// 			for ($j = 0; $j < count($row); $j++) {
// 				$tmp = trim($row[$j]);
// 				if ($tmp != "") {
// 					$nameArr[] = '"'.$tmp.'"';
// 					$i++;
// 				}
// 			}
//         }  
		
//         $str .= implode(",", $nameArr);
//     }
//     echo("total: ".$i."<br>");
//     return $str;
// }
// $filePath2 = "lastName.json";
// $handle = fopen($filePath2, "w");
// fwrite($handle, $string);

// fclose($fp); 
// fclose($handle);


// $json = array('{"a":1}', '{"b":2}');
// //echo $foo = serialize($json);
// $json = '{"a":1}';
// $contactsArr = '[{"dfd":1},{"qa":2}]';
// print_r(json_decode($contactsArr));

// $foo = '[{"uid":1000,"name":"abc"},{"uid":1002,"name":"def"},{"uid":1003,"name":"ghi"}]';
// $bar = json_decode($foo);
// print_r($bar);
// $jsonArr = new stdClass();
// $contacts = array();
// $contact = new stdClass();

// $contact -> userId = 12;

// $contact -> name = "adfd";

// $contacts[] = $contact;

// $jsonArr -> contacts = $contacts;
// $jsonArr -> totalNumber = 1;
// echo  (json_encode($jsonArr));

// //echo(toUTF8("*_@-sdff则"));
//  function toUTF8($str) {
//         $encoding = mb_detect_encoding($str, array('ASCII','UTF-8','GB2312','GBK','BIG5'));
//         return mb_convert_encoding($str, 'utf-8', $encoding);
//     }


?>