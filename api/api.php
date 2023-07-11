<?php

require_once("Rest.inc.php");

	class API extends REST {

		public $data = "";
		const demo_version = false;

		private $db 	= NULL;
		private $mysqli = NULL;
		public function __construct() {
			// Init parent contructor
			parent::__construct();
			// Initiate Database connection
			$this->dbConnect();
			date_default_timezone_set('Asia/Kolkata');
		}

		/*
		 *  Connect to Database
		*/
		private function dbConnect() {
			include "../includes/config.php";
			$this->mysqli = new mysqli($host, $user, $pass, $database);
			$this->mysqli->query('SET CHARACTER SET utf8');
		}

		/*
		 * Dynmically call the method based on the query string
		 */
		public function processApi() {
			$func = strtolower(trim(str_replace("/","",$_REQUEST['x'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else{
				$respone = array('status' => FAIL, 'message' => MSG_NO_METHOD_FOUND);
				$this->response($this->json($respone), 404);	// If the method not exist with in this class "Page not found".
			}
		}

		/* Api Checker */
		private function checkConnection() {
			if (mysqli_ping($this->mysqli)) {
				$respone = array( 'status' => SUCCESS, 'database' => 'connected' );
                $this->response($this->json($respone), 200);
			} else {
                $respone = array( 'status' => FAIL, 'database' => 'not connected' );
                $this->response($this->json($respone), 404);
			}
		}

		private function getAPIValid(){
			if(isset($_GET['api_key'])) {
				include "../includes/config.php";
				$access_key_received = $_GET['api_key'];
				$setting_qry    = "SELECT * FROM tbl_settings where api_key = '$access_key_received'";
				$setting_result = mysqli_query($connect, $setting_qry);
				$settings_row   = mysqli_fetch_assoc($setting_result);
				$api_key    = $settings_row['api_key'];
				return $api_key;
			} else {
				$respone = array('status' => FAIL, 'message' => MSG_API_KEY_REQUIRED);
				$this->response($this->json($respone), 200);	
			}
		
		}

		private function login() {

			$api_key = $this->getAPIValid();

			$date = date('Y-m-d H:i:s', time());

			if (isset($api_key)) {
				include "../includes/config.php";
				if($this->get_request_method() != "GET") $this->response('',406);
				if(isset($this->_request['username']) && isset($this->_request['password'])) {
					$username = $this->_request['username'];
					$password = $this->_request['password'];
					$device_type = isset($this->_request['device_type']) ? $this->_request['device_type']: 'Android';

					$password = hash('sha256',$username.$password);
					
					$query = "SELECT 
								id, 
								username, 
								email,
								device_type, 
								active, 
								createdAt, 
								updatedAt 
								FROM 
									tbl_user 
								WHERE 
									(username = '$username' AND password='$password') OR (email = '$username' AND e_password='$password') ";

					$userData = $this->get_list_result($query);
					$count = count($userData);
					
					if($count > 0) {
						$sql_query = "UPDATE tbl_user SET device_type=?, updatedAt=?  WHERE id=?";
						$userData[0]['device_type'] = $device_type;
						$userData[0]['updatedAt'] = $date;
						$stmt = $connect->stmt_init();
						if($stmt->prepare($sql_query)) {
							$stmt->bind_param('ssi', $device_type, $date, $userData[0]['id']);
							$stmt->execute();
							$update_result = $stmt->store_result();
							$stmt->close();
						} 
						$respone = array( 'status' => SUCCESS, 'message' => MSG_LOGIN_SUCCESS, 'data' => $userData[0] );
						$this->response($this->json($respone), 200);
					} else {
						$respone = array( 'status' => FAIL, 'message' => MSG_INVALID_USER_PASS);
						$this->response($this->json($respone), 200);
					}
				} else {
					$respone = array('status' => FAIL, 'message' => MSG_REQUIRED_PARAMS);
					$this->response($this->json($respone), 200);	
				}
				
			} else {
				$respone = array('status' => FAIL, 'message' => MSG_API_KEY_INCORRECT);
				$this->response($this->json($respone), 200);	
			}
		}

		private function register() {

			$api_key = $this->getAPIValid();

			$date = date('Y-m-d H:i:s', time());

			if (isset($api_key)) {
				include "../includes/config.php";
				if($this->get_request_method() != "GET") $this->response('',406);
				if(isset($this->_request['username']) && isset($this->_request['password']) && isset($this->_request['email'])) {
					$username = $this->_request['username'];
					$password = $this->_request['password'];
					$email = $this->_request['email'];
					$user_role = 102;
					$active = '1';

					$query = "SELECT 
									id, 
									username, 
									email, 
									device_type, 
									active, 
									createdAt, 
									updatedAt 
								FROM 
									tbl_user 
								WHERE 
									email='$email' OR username='$username' ";

					$user = $this->get_list_result($query);
					$count = count($user);
					if($count > 0){
						$respone = array('status' => FAIL, 'message' => MSG_RECORD_ALREADY_EXIST);
						$this->response($this->json($respone), 200);	
					}else{
						$password = hash('sha256',$username.$password);
						$e_password = hash('sha256',$email.$password);

						$sql = "INSERT INTO tbl_user (username, password, e_password, email, user_role, active, createdAt, updatedAt) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

						$insert = $connect->prepare($sql);
						$insert->bind_param('ssssssss', $username, $password, $e_password, $email, $user_role, $active, $date, $date);
						$insert->execute();

						$query = "SELECT id, username, email, device_type, active, createdAt, updatedAt FROM tbl_user WHERE username='$username' AND email='$email'";
						$userData = $this->get_list_result($query);						
						$respone = array('status' => SUCCESS, 'message' => MSG_REGISTER_SUCCESS, 'data' => $userData[0]);
						$this->response($this->json($respone), 200);	
					}
				} else {
					$respone = array('status' => FAIL, 'message' => MSG_REQUIRED_PARAMS);
					$this->response($this->json($respone), 200);	
				}
			} else {
				$respone = array('status' => FAIL, 'message' => MSG_API_KEY_INCORRECT);
				$this->response($this->json($respone), 200);	
			}

		}

		private function changePassword(){
			
			$api_key = $this->getAPIValid();

			$date = date('Y-m-d H:i:s', time());

			if (isset($api_key)) {
				include "../includes/config.php";
				if($this->get_request_method() != "GET") $this->response('',406);
				if(isset($this->_request['username']) && isset($this->_request['email']) && isset($this->_request['old_password']) && isset($this->_request['new_password'])) {
					$username = $this->_request['username'];
					$email = $this->_request['email'];
					$old_password = $this->_request['old_password'];
					$new_password = $this->_request['new_password'];
					
					if($username === "admin" || $email === "bytesbee@gmail.com"){
						$respone = array( 'status' => FAIL, 'message' => 'Sorry! You are not authorised user to change password');
						$this->response($this->json($respone), 200);
					} else {
						$password = hash('sha256',$username.$old_password);
						$e_password = hash('sha256',$email.$old_password);
						
						$query = "SELECT * FROM tbl_user WHERE 
									(username = '$username' AND password='$password') OR 
									(email = '$email' AND e_password='$e_password') ";
						$userData = $this->get_list_result($query);
						$count = count($userData);
	
						if($count > 0) {
							$new_u_password = hash('sha256',$username.$new_password);
							$new_e_password = hash('sha256',$email.$new_password);
						
							$sql_query = "UPDATE tbl_user SET updatedAt=? ,password=?, e_password=? WHERE id=?";
							$stmt = $connect->stmt_init();
							if($stmt->prepare($sql_query)) {
								$stmt->bind_param('sssi', $date, $new_u_password, $new_e_password, $userData[0]['id']);
								$stmt->execute();
								$update_result = $stmt->store_result();
								$stmt->close();
							} 
							$userData[0]['updatedAt'] = $date;
							$userData[0]['password'] = $new_u_password;
							$userData[0]['e_password'] = $new_e_password;
							$respone = array( 'status' => SUCCESS, 'message' => MSG_CHANGE_PASSWORD, 'data' => $userData[0] );
							$this->response($this->json($respone), 200);
						} else {
							$respone = array( 'status' => FAIL, 'message' => MSG_INVALID_PASSWORD);
							$this->response($this->json($respone), 200);
						}
	
					}

				} else {
					$respone = array('status' => FAIL, 'message' => MSG_REQUIRED_PARAMS);
					$this->response($this->json($respone), 200);	
				}
			} else {
				$respone = array('status' => FAIL, 'message' => MSG_API_KEY_INCORRECT);
				$this->response($this->json($respone), 200);	
			}
		}

		private function getAllCategories() {
			$api_key = $this->getAPIValid();

			if (isset($api_key)) {
				include "../includes/config.php";
				if($this->get_request_method() != "GET") $this->response('',406);
				$count_total = $this->get_count_result("SELECT COUNT(DISTINCT cid) FROM tbl_category where active = 1");
				
				$query = "SELECT 
							cid, 
							category_name, 
							category_image, 
							concat(COUNT(category_id), ' ', 'ITEMS') AS item_count 
						FROM 
							tbl_category 
						LEFT JOIN 
							tbl_channel ON cid=category_id 
						AND 
							tbl_channel.active = 1 
						WHERE 
							tbl_category.active = 1 
						GROUP BY cid 
						order by cid DESC";

				$categories = $this->get_list_result($query);
				$count = count($categories);
				$respone = array('status' => SUCCESS, 'count' => $count, 'categories' => $categories);
				$this->response($this->json($respone), 200);
			} else {
				$respone = array('status' => FAIL, 'message' => MSG_API_KEY_INCORRECT);
				$this->response($this->json($respone), 200);	
			}
		}
		
		private function getRecentChannels() {
			$api_key = $this->getAPIValid();

			if (isset($api_key)) {
				include "../includes/config.php";
				
				if($this->get_request_method() != "GET") $this->response('',406);
				$limit = isset($this->_request['count']) ? ((int)$this->_request['count']) : 10;
				$page = isset($this->_request['page']) ? ((int)$this->_request['page']) : 1;
				if($page == 0){
					$page = 1;
				}

				$offset = ($page * $limit) - $limit;
				$count_total = $this->get_count_result("SELECT COUNT(DISTINCT n.id) FROM  tbl_category c, tbl_channel n 
														WHERE c.active = 1 AND n.active = 1 AND n.category_id = c.cid");
				$allQueryFields = $this->getQueryFields();
				$query = "SELECT distinct 
							$allQueryFields
						WHERE 
							c.active = 1 AND n.active = 1 AND n.category_id = c.cid ORDER BY n.id DESC LIMIT $limit OFFSET $offset";

				$post = $this->get_list_result($query);
				$count = count($post);
				$respone = array(
					'status' => SUCCESS, 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'posts' => $post
				);
				$this->response($this->json($respone), 200);
			} else {
				$respone = array('status' => FAIL, 'message' => MSG_API_KEY_INCORRECT);
				$this->response($this->json($respone), 200);	
			}
		}

		private function getPopularChannels() {
			$api_key = $this->getAPIValid();

			if (isset($api_key)) {
				include "../includes/config.php";
				
				if($this->get_request_method() != "GET") $this->response('',406);
				$limit = isset($this->_request['count']) ? ((int)$this->_request['count']) : 10;
				$page = isset($this->_request['page']) ? ((int)$this->_request['page']) : 1;
				if($page == 0){
					$page = 1;
				}

				$offset = ($page * $limit) - $limit;
				$count_total = $this->get_count_result("SELECT COUNT(DISTINCT n.id) FROM  tbl_category c, tbl_channel n 
														WHERE c.active = 1 AND n.active = 1 AND n.category_id = c.cid");
				$allQueryFields = $this->getQueryFields();
				$query = "SELECT distinct
							$allQueryFields
						WHERE
							c.active = 1 AND n.active = 1 AND n.category_id = c.cid 
						GROUP BY 
							id 
						ORDER BY 
							total_count DESC 
						LIMIT
							$limit OFFSET $offset";

				$post = $this->get_list_result($query);
				$count = count($post);
				$respone = array(
					'status' => SUCCESS, 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'posts' => $post
				);
				$this->response($this->json($respone), 200);
			} else {
				$respone = array('status' => FAIL, 'message' => MSG_API_KEY_INCORRECT);
				$this->response($this->json($respone), 200);	
			}
		}

		private function getCategoryDetailsByPage() {
			$api_key = $this->getAPIValid();

			if (isset($api_key)) {
				include "../includes/config.php";
				if(isset($_GET['id'])) {
					$id = $_GET['id'];
					if($this->get_request_method() != "GET") $this->response('',406);
					$limit = isset($this->_request['count']) ? ((int)$this->_request['count']) : 10;
					$page = isset($this->_request['page']) ? ((int)$this->_request['page']) : 1;
					if($page == 0){
						$page = 1;
					}

					$offset = ($page * $limit) - $limit;
					$count_total = $this->get_count_result("SELECT COUNT(DISTINCT id) FROM tbl_channel WHERE category_id = '$id' AND active = 1");

					$query = "SELECT distinct cid, category_name, category_image FROM tbl_category WHERE cid = '$id' AND active = 1
							ORDER BY cid DESC";
					$allQueryFields = $this->getQueryFields();
					$query2 = "SELECT distinct 
								$allQueryFields
							WHERE 
								n.category_id = c.cid AND c.cid = '$id' AND c.active = 1 AND n.active = 1 
							ORDER BY 
								n.id DESC LIMIT $limit OFFSET $offset"; 

					$category = $this->get_category_result($query);
					$post = $this->get_list_result($query2);
					$count = count($post);
					if($count>0) {
						$respone = array(
							'status' => SUCCESS, 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'category' => $category, 'posts' => $post
						);
						$this->response($this->json($respone), 200);
					} else {
						$respone = array( 'status' => FAIL, 'message' => MSG_RECORD_NOT_FOUND );
						$this->response($this->json($respone), 200);
					}
				} else {
					$respone = array('status' => FAIL, 'message' => MSG_REQUIRED_PARAMS);
					$this->response($this->json($respone), 200);		
				}
			} else {
				$respone = array('status' => FAIL, 'message' => MSG_API_KEY_INCORRECT);
				$this->response($this->json($respone), 200);	
			}

		}

		private function getSearchByChannel() {
			$api_key = $this->getAPIValid();

			if (isset($api_key)) {
				include "../includes/config.php";
				if(isset($_GET['search'])) {
					$search = $_GET['search'];

					if($this->get_request_method() != "GET") $this->response('',406);
					$limit = isset($this->_request['count']) ? ((int)$this->_request['count']) : 10;
					$page = isset($this->_request['page']) ? ((int)$this->_request['page']) : 1;
					if($page == 0){
						$page = 1;
					}

					$offset = ($page * $limit) - $limit;
					$count_total = $this->get_count_result("SELECT COUNT(DISTINCT n.id) FROM tbl_channel n, tbl_category c WHERE n.category_id = c.cid AND 
					(n.channel_name LIKE '%$search%' OR n.channel_description LIKE '%$search%')");

					$allQueryFields = $this->getQueryFields();
					$query = "SELECT distinct 
								$allQueryFields
							WHERE 
								n.category_id = c.cid AND (n.channel_name LIKE '%$search%' OR n.channel_description LIKE '%$search%')
							LIMIT 
								$limit OFFSET $offset";
							
					$post = $this->get_list_result($query);
					$count = count($post);
					$respone = array('status' => SUCCESS, 'count' => $count, 'count_total' => $count_total, 'pages' => $page, 'posts' => $post);
					$this->response($this->json($respone), 200);
				} else {
					$respone = array('status' => FAIL, 'message' => MSG_REQUIRED_PARAMS);
					$this->response($this->json($respone), 200);		
				}
			} else {
				$respone = array('status' => FAIL, 'message' => MSG_API_KEY_INCORRECT);
				$this->response($this->json($respone), 200);
			}
		}

		private function getQueryFields(){
			$data = " 
					n.id AS 'channel_id', 
					n.category_id, 
					n.channel_name, 
					n.channel_type, 
					n.channel_image,
					n.channel_description,
					n.view_count, 
					c.category_name, 
					n.createdAt, 
					n.updatedAt 
				FROM
					tbl_channel n, tbl_category c ";
			return $data;
		}

		private function updateViewCount() {
			$api_key = $this->getAPIValid();

			$date = date('Y-m-d H:i:s', time());

			if (isset($api_key)) {
				include "../includes/config.php";
				if($this->get_request_method() != "GET") $this->response('',406);
				if(isset($this->_request['channel_id'])) {
					$view_count = isset($this->_request['view_count']) ? $this->_request['view_count'] : 1;
					$channel_id = $this->_request['channel_id'];
					
					$query = "SELECT * from tbl_channel  WHERE id = '$channel_id'";
					$user = $this->get_list_result($query);
					$count = count($user);
					if($count <= 0) {
						$respone = array('status' => FAIL, 'message' => MSG_RECORD_NOT_FOUND);
						$this->response($this->json($respone), 200);	
					} else {

						$sql_query = "UPDATE tbl_channel SET view_count=?, updatedAt=?  WHERE id=?";
						$view_count = ((int)$user[0]['view_count']) + ((int)$view_count);
						$stmt = $connect->stmt_init();
						if($stmt->prepare($sql_query)) {
							$stmt->bind_param('isi', $view_count, $date, $channel_id);
							$stmt->execute();
							$update_result = $stmt->store_result();
							$stmt->close();

							$message = "Link1 count updated successfully.";
							
							$query = "SELECT 
											id, 
											id AS 'channel_id',
											category_id, 
											channel_name, 
											channel_image, 
									
											channel_description, 
									
											view_count
										FROM 
											tbl_channel 
										WHERE
											id='$channel_id'";

							$userData = $this->get_list_result($query);						
							$respone = array('status' => SUCCESS, 'message' => $message, 'data' => $userData[0]);
							$this->response($this->json($respone), 200);
						} else {
							$respone = array( 'status' => SUCCESS, 'data' => 'Oops, Something is missing!' );
							$this->response($this->json($respone), 200);	
						}

					}
				} else {
					$respone = array('status' => FAIL, 'message' => MSG_REQUIRED_PARAMS);
					$this->response($this->json($respone), 200);	
				}
			} else {
				$respone = array('status' => FAIL, 'message' => MSG_API_KEY_INCORRECT);
				$this->response($this->json($respone), 200);	
			}
		}

	

		private function getAppSettings() {
			$api_key = $this->getAPIValid();

			if (isset($api_key)) {
				include "../includes/config.php";
				if($this->get_request_method() != "GET") $this->response('',406);

				$query = "SELECT *, CONCAT(app_version, ' ')  as app_version FROM tbl_settings where id = '1'";
				$data = $this->get_list_result($query);
				$respone = array('status' => SUCCESS, 'message' => MSG_RECORD_FOUND, 'data' => $data[0]);
				$this->response($this->json($respone), 200);
				
			} else {
				$respone = array('status' => FAIL, 'message' => MSG_API_KEY_INCORRECT);
				$this->response($this->json($respone), 200);	
			}

		}
		
		/**
		 * =========================================================================================================
		 *	COMMON METHODS  
		 * =========================================================================================================
		 */

		//don't edit all the code below
		private function get_list($query) {
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0) {
				$result = array();
				while($row = $r->fetch_assoc()) {
					$result[] = $row;
				}
				$this->response($this->json($result), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function get_list_result($query) {
			$result = array();
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0) {
				while($row = $r->fetch_assoc()) {
					$result[] = $row;
				}
			}
			return $result;
		}

		private function get_object_result($query) {
			$result = array();
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0) {
				while($row = $r->fetch_assoc()) {
					$result = $row;
				}
			}
			return $result;
		}

		private function get_category_result($query) {
			$result = array();
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0) {
				while($row = $r->fetch_assoc()) {
					$result = $row;
				}
			}
			return $result;
		}

		private function get_one($query) {
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0) {
				$result = $r->fetch_assoc();
				$this->response($this->json($result), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function get_count($query) {
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0) {
				$result = $r->fetch_row();
				$this->response($result[0], 200);
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function get_count_result($query) {
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			if($r->num_rows > 0) {
				$result = $r->fetch_row();
				return $result[0];
			}
			return 0;
		}

		private function post_one($obj, $column_names, $table_name) {
			$keys 		= array_keys($obj);
			$columns 	= '';
			$values 	= '';
			foreach($column_names as $desired_key) {
			  if(!in_array($desired_key, $keys)) {
			   	$$desired_key = '';
				} else {
					$$desired_key = $obj[$desired_key];
				}
				$columns 	= $columns.$desired_key.',';
				$values 	= $values."'".$this->real_escape($$desired_key)."',";
			}
			$query = "INSERT INTO ".$table_name."(".trim($columns,',').") VALUES(".trim($values,',').")";
			
			if(!empty($obj)) {
				
				if ($this->mysqli->query($query)) {
					$status = "success";
			    $msg 		= $table_name." created successfully";
				} else {
					$status = "failed";
			    $msg 		= $this->mysqli->error.__LINE__;
				}
				$resp = array('status' => $status, "msg" => $msg, "data" => $obj);
				$this->response($this->json($resp),200);
			} else {
				$this->response('',204);	//"No Content" status
			}
		}

		private function post_update($id, $obj, $column_names, $table_name) {
			$keys = array_keys($obj[$table_name]);
			$columns = '';
			$values = '';
			foreach($column_names as $desired_key){
			  if(!in_array($desired_key, $keys)) {
			   	$$desired_key = '';
				} else {
					$$desired_key = $obj[$table_name][$desired_key];
				}
				$columns = $columns.$desired_key."='".$this->real_escape($$desired_key)."',";
			}

			$query = "UPDATE ".$table_name." SET ".trim($columns,',')." WHERE id=$id";
			if(!empty($obj)) {
				if ($this->mysqli->query($query)) {
					$status = "success";
					$msg 	= $table_name." update successfully";
				} else {
					$status = "failed";
					$msg 	= $this->mysqli->error.__LINE__;
				}
				$resp = array('status' => $status, "msg" => $msg, "data" => $obj);
				$this->response($this->json($resp),200);
			} else {
				$this->response('',204);	// "No Content" status
			}
		}

		private function delete_one($id, $table_name) {
			if($id > 0) {
				$query="DELETE FROM ".$table_name." WHERE id = $id";
				if ($this->mysqli->query($query)) {
					$status = "success";
			    $msg 		= "One record " .$table_name." successfully deleted";
				} else {
					$status = "failed";
			    $msg 		= $this->mysqli->error.__LINE__;
				}
				$resp = array('status' => $status, "msg" => $msg);
				$this->response($this->json($resp),200);
			} else {
				$this->response('',204);	// If no records "No Content" status
			}
		}

		private function responseInvalidParam() {
			$resp = array("status" => 'Failed', "msg" => 'Invalid Parameter' );
			$this->response($this->json($resp), 200);
		}

		/* ==================================== End of API utilities ==========================================
		 * ====================================================================================================
		 */

		/* Encode array into JSON */
		private function json($data) {
			if(is_array($data)) {
				return json_encode($data, JSON_NUMERIC_CHECK);
			}
		}

		/* String mysqli_real_escape_string */
		private function real_escape($s) {
			return mysqli_real_escape_string($this->mysqli, $s);
		}
	}

	// Initiate Library
	$api = new API;
	$api->processApi();
?>
