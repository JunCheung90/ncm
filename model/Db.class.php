<?php
	ob_start(); 
	
	class mysql {
		private $host;
		private $name;
		private $pass;
		private $conn;			//当前连接	
		private $database;		//当前数据库名称
		
		//构造方法初始化
		function __construct() {
			$this->host='localhost';
			$this->name='root';
			$this->pass='root';
			$this->database='ncmmock';
			$this->connect();
			$this->utf8();
			$this->database();
		}
		
		//数据库连接方法
		private function connect() {
			try {
				$conn=@mysql_connect($this->host,$this->name,$this->pass);
				if ($conn) {
					//数据库连接成功
					$this->conn=$conn;
				}
				else {
					throw new Exception('数据库连接失败');
				}
			}
			catch (Exception $e) {
				echo $e->getMessage();
				exit;
			}
		}
		
		//设定字符编码
		private function utf8() {
			mysql_query("SET NAMES 'utf8'");
		}
		
		//引入一个数据库
		private function database() {
			mysql_select_db($this->database,$this->conn);
		}
		
		//获取执行SQL语句句柄
		function query($sql) {
			if (!$_result = mysql_query($sql)) {
				echo('SQL查询失败'.mysql_error());//测试用的报错显示
				return false;
			}
			return $_result;
		}
		
	 	function insert($sql) {
			if (!$_result = mysql_query($sql)) {
				echo('插入失败'.mysql_error());//测试用的报错显示
				return false;
			}
			return $_result;
		}
		
	 	function delete($sql) {
			if (!$_result = mysql_query($sql)) {
				echo('删除失败'.mysql_error());
				return false;
			}
			return $_result;
		}
		
		//筛选数据，关联数组
		function fetch_array($_result) {
			return mysql_fetch_array($_result,MYSQL_ASSOC);
		}

		//筛选数据，数字数组
		function fetch_array_num($_result) {
			return mysql_fetch_array($_result,MYSQL_NUM);
		}
		
		//筛选数据
		function fetch_array2($sql) {
			return mysql_fetch_array(self::query($sql));
		}
		
		function _num_rows($_result) {
			return mysql_num_rows($_result);
		}

		function _insert_id() {
			return mysql_insert_id();
		}
		
		function _free_result($_result) {
			mysql_free_result($_result);
		}
		
		function close() {
			mysql_close();
		}
		
	}
?>