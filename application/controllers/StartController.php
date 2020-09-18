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

		ini_set('memory_limit','-1');
		ini_set('max_execution_time', 0);

		$this->service_list = array(
			'proyecto' => '/api/proyecto',

			'etapa' => '/api/etapa',
			'edificio' => '/api/edificio',
			'cliente' => '/api/usuario',
			'usuario' => '/api/cliente',
			'unidadInmobiliaria' => '/api/unidadInmobiliaria',

			'modeloinmueble' => '/api/modeloinmueble',
			'operacioncomercial' => '/api/operacioncomercial',
			'stock' => '/api/stock',
			'contacto' => '/api/contacto',
			'prospecto' => '/api/prospecto',
			'pago' => '/api/pago',
			'estadocuenta' => '/api/estadocuenta',
			'reporte_analisis' => '/api/reporte/analisis/formacontacto'
		);
	}


	public function index()
	{
		$this->ServiceModel->cleanDB();
		$this->project_ids = array();

		foreach ($this->service_list as $key => $url) {
			$table_names = $this->ServiceModel->tableNames($key);

			foreach ($table_names as $table) {
				$this->createTable($table->table_name, $key, $url);	
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
			$result = curl_request('GET', $this->evolta_host.$url.$query, ['headers'  => array( 'Authorization: Bearer '.$token )]);
			
			if($service == 'proyecto') $this->registerProjectId($result);
            
            if(!isset($result) || empty($result) || is_string($result)){
                $this->ServiceModel->log($tableName, $service, $query, 'fail');
            }else{
                $this->ServiceModel->saveData($tableName, $service, $result);
                $this->ServiceModel->log($tableName, $service, $query, 'success');    
			}
        }catch(Exception $err){
            $this->log($tableName, $service, $query, 'fail');
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
