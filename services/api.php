<?php
 	require_once("Rest.inc.php");

	class API extends REST {

		public $data = "";

		const DB_SERVER = "127.0.0.1";
		const DB_USER = "root";
		const DB_PASSWORD = "";
		const DB = "dbagendatelefonica";

		private $db = NULL;
		private $mysqli = NULL;
		public function __construct(){
			parent::__construct();
			$this->dbConnect();
		}


		private function dbConnect(){
			$this->mysqli = new mysqli(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD, self::DB);
		}


		public function processApi(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['x'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404);
		}

			private function contactos(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$query="SELECT * FROM contactos order by Nombres";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0){
				$result = array();
				while($row = $r->fetch_assoc()){
					$result[] = $row;
				}
				$this->response($this->json($result), 200);
			}
			$this->response('',204);
		}
		private function contacto(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			if($id > 0){
				$query="SELECT * FROM contactos where intId=$id";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = $r->fetch_assoc();
					$this->response($this->json($result), 200);
				}
			}
			$this->response('',204);
		}

		private function insertcontacto(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$contacto = json_decode(file_get_contents("php://input"),true);
			$column_names = array('Nombres','Apellidos','Correo','Telefono','Celular');
			$keys = array_keys($contacto);
			$columns = '';
			$values = '';
			foreach($column_names as $desired_key){
			   if(!in_array($desired_key, $keys)) {
			   		$$desired_key = '';
				}else{
					$$desired_key = $contacto[$desired_key];
				}
				$columns = $columns.$desired_key.',';
				$values = $values."'".$$desired_key."',";
			}
			$query = "INSERT INTO contactos(".trim($columns,',').") VALUES(".trim($values,',').")";
			if(!empty($contacto)){
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				$success = array('status' => "Success", "msg" => "Contacto creado.", "data" => $contacto);
				$this->response($this->json($success),200);
			}else
				$this->response('',204);
		}
		private function updatecontacto(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$contacto = json_decode(file_get_contents("php://input"),true);
			$id = (int)$contacto['id'];
			$column_names = array('Nombres','Apellidos','Correo','Telefono','Celular');
			$keys = array_keys($contacto['contacto']);
			$columns = '';
			$values = '';
			foreach($column_names as $desired_key){
			   if(!in_array($desired_key, $keys)) {
			   		$$desired_key = '';
				}else{
					$$desired_key = $contacto['contacto'][$desired_key];
				}
				$columns = $columns.$desired_key."='".$$desired_key."',";
			}
			$query = "UPDATE contactos SET ".trim($columns,',')." WHERE intId=$id";
			if(!empty($contacto)){
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				$success = array('status' => "Success", "msg" => "contacto ".$id." Contacto actualizado.", "data" => $contacto);
				$this->response($this->json($success),200);
			}else
				$this->response('',204);
		}

		private function deletecontacto(){
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			if($id > 0){
				$query="DELETE FROM contactos WHERE intId = $id";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				$success = array('status' => "Success", "msg" => "Contacto eliminado.");
				$this->response($this->json($success),200);
			}else
				$this->response('',204);
		}


		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}



	$api = new API;
	$api->processApi();
?>
