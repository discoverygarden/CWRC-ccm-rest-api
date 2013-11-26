<?php
getRoute()->get('/person/search', array('PersonController', 'search'));
getRoute()->get('/person/(.+)', array('PersonController', 'view'));
getRoute()->post('/person', array('PersonController', 'create'));
getRoute()->post('/person/(.+)', array('PersonController', 'create'));
getRoute()->put('/person/(.+)', array('PersonController', 'update'));
getRoute()->delete('/person/(.+)', array('PersonController', 'delete'));
include_once './controllers/entity.php';

class PersonController extends EntityController {
	
	public static function search(){
		$query = $_GET['query'];
		$start = $_GET['start'];
		$rows = $_GET['rows'];
		
		$result = EntityController::searchEntities('PERSON', $query, $start, $rows);
		
		echo($result);
	}
	
	public static function view($id){
		$result = EntityController::getEntity($id, 'PERSON');
		
		if(get_class($result) == "Entity"){
			echo $result->getContent();
		}else{
			echo $result;
		}
	}
	
	public static function createNew($data){
		$result = EntityController::uploadNewEntity('cwrc', 'PERSON', $data['data']);
		$object = array();
		
		if(get_class($result) == "Entity"){
			$object["pid"] = $result->getPID();
		}else{
			$object["error"] = $result;
		}
		
		echo json_encode($object);
	}
	
	public static function update($id){
		$result = EntityController::modifyEntity('PERSON', $id, $_POST['data']);
		$object = array();
		
		if(get_class($result) == "Entity"){
			$object["pid"] = $result->getPID();
		}else{
			$object["pid"] = $id;
			$object["error"] = $result;
		}
		
		echo json_encode($object);
	}
	
	public static function delete($id){
		$result = EntityController::deleteEntity($id);
		$object = array();
		
		if($result == null){
			$object['isDeleted'] = true;
		}else{
			$object['isDeleted'] = false;
			$object['error'] = $result;
		}
		
		echo json_encode($object);
	}
}
