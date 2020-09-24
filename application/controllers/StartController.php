<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StartController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$evolta_env = $this->config->item('evolta');
		$this->evolta_host = $evolta_env['EVOLTA_HOST'];
		$this->evolta_username = $evolta_env['EVOLTA_USERNAME'];
		$this->evolta_password = $evolta_env['EVOLTA_PASSWORD'];

		$this->load->helper('client');
		$this->load->model('ServiceModel');
		
		$this->load_max_count = 500;

		ini_set('memory_limit','-1');
		ini_set('max_execution_time', 0);
	}


	public function index()
	{
		$this->ServiceModel->cleanDB();
		$this->project_ids = array();

		$services = $this->ServiceModel->services();

		foreach ($services as $service) {
			$table_names = $this->ServiceModel->tableNames($service->servicio);

			foreach ($table_names as $table) {
				$this->createTable($table->table_name, $service->servicio, $service->url);
			}
		}
		echo "finished ..... ";
		return;
	}

	public function createTable($name, $service, $url)
	{
		$params = $this->ServiceModel->getParamsInTable($name);

		if(checkLoopByProject($params)) {
			foreach($this->project_ids as $id) {
				$query = build_query($params, $id);
				$this->queryService($name, $service, $url, $query);	
			}
		} else {
			$query = build_query($params, null);
			$this->queryService($name, $service, $url, $query);
		}
	}

	public function queryService($tableName, $service, $url, $query){
		try{
			$token = $this->getToken();
			if(substr($url, 0, 5) !== 'http:' && substr($url, 0, 6) !== 'https:') $url = $this->evolta_host.$url;
			$result = curl_request('GET', $url.$query, ['headers'  => array( 'Authorization: Bearer '.$token )]);
			
			if($service == 'proyecto') $this->registerProjectId($result);
            
            if(!isset($result) || empty($result) || is_string($result)){
                $this->ServiceModel->log($tableName, $service, $query, 'fail');
            }else{
                $this->saveServiceData($tableName, $service, $result);
                $this->ServiceModel->log($tableName, $service, $query, 'success');    
			}
        }catch(Exception $err){
            $this->log($tableName, $service, $query, 'fail');
        }
	}

	public function saveServiceData($tableName, $service, $result){
		if(is_array($result)){
			for($i = 0; $i < ceil(count($result)/$this->load_max_count); $i++){
				$_result = array_splice($result, 0, $this->load_max_count);
				$this->ServiceModel->saveData($tableName, $service, $_result);
			}
		} else {
			$this->ServiceModel->saveData($tableName, $service, $result);
		}
	}

	public function registerProjectId($projects)
	{
		$id_list = array();
		foreach($projects as $project){
			$id_list[] = $project->idProyecto;
		}

		$this->project_ids = array_merge($this->project_ids, $id_list);
	}

	public function getToken()
	{
		try {
			$params = [
				'username' => $this->evolta_username,
				'password' => $this->evolta_password,
				'grant_type' => 'password'
			];
	
			$res = curl_request('POST', $this->evolta_host.'/oauth2/token', ['data' => $params]);
			
			return $res->access_token;

		} catch(Exeption $err) {
			throw $err;
		}
	}
}
