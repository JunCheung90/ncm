<?php		
	ob_start(); 
	
	require_once("model/Rest.inc.php");
	require_once("model/Tool.class.php");
	require_once("model/User.class.php");
	require_once("model/Db.class.php");
	require_once("model/Story.class.php");
	
	class API extends REST {
		public function __construct() {
			parent::__construct();				// Init parent contructor
		}
		
		/*
		 * Public method for access api.
		 * This method dynmically call the method based on the query string
		 *
		 */
		public function processApi(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404);				// If the method not exist with in this class, response would be "Page not found".
		}
		
		private function manage() {
			Header("Location: manage.php");
		}

		private function show() {
			Header("Location: show.php");
		}
		
		private function us1_old() {
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$story = new Story();
			$result = $story->userStory1();
			$this->response(Tool::json($result), 200);
		}

		private function us1() {
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$story = new Story();
			$result = $story->us1New();
			$this->response(Tool::json($result), 200);
		}

		private function us2() {
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$story = new Story();
			$result = $story->userStory2();
			$this->response(Tool::json($result), 200);
		}

		private function usercreate() {
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$user = new User();
			$result = $user->create();
			$this->response(Tool::json($result), 200);
		}

		private function usershow() {	
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$userId = $this->_request['userId'];
			$user = new User();
			$result = $user->getUserInfo($userId);
			if (!$result) {
				$this->response('',204);
			}
			$this->response(Tool::json($result), 200);
		}

		private function usercontact() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$userId = $this->_request['userId'];
			$user = new User();
			$result = $user->getContact($userId);
			$this->response(Tool::json($result), 200);
		}

		private function usercallLog() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$userId = $this->_request['userId'];
			$user = new User();
			$result = $user->getCallLog($userId);
			$this->response(Tool::json($result), 200);
		}

		private function androidapicontact() {
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$userId = $this->_request['userId'];
			$user = new User();
			$result = $user->getAndroidContact($userId);
			$this->response(Tool::json($result), 200);
		}

		//显示html
		private function androiducontact() {
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}

			$userId = $this->_request['userId'];
			//...
		}

		//renren/api/user,请求json数据
		private function renrenapiuser() {
			//...
		}
		
		//renren/u/user, 请求html
		private function renrenuuser() {
			//...
		}
	}
	
	// Initiiate Library
	$api = new API;
	$api->processApi();
?>