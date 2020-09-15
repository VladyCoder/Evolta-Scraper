<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StartController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$evolta_config = $this->config->item('evolta');
		$this->evolta_host = $evolta_config['EVOLTA_HOST'];
		$this->evolta_username = $evolta_config['EVOLTA_USERNAME'];
		$this->evolta_password = $evolta_config['EVOLTA_PASSWORD'];
		$this->load_max_count = 5000;

		$this->load->dbforge();
		$this->load->database();
		$this->load->helper('client');

		$this->load->model('ProjectModel');
		$this->load->model('UserModel');
		$this->load->model('PropertyModel');
		$this->load->model('AccountModel');

		ini_set('memory_limit','-1');
		ini_set('max_execution_time', 0);
	}


	public function index()
	{
		$this->cleanDB();

		$this->ProjectModel->projects($this);
		$this->ProjectModel->etapaDetail($this);
		$this->ProjectModel->edificioDetail($this);

		$this->UserModel->clients($this);
		$this->UserModel->users($this);

		$this->PropertyModel->unitProperty($this);
		$this->PropertyModel->modelProperty($this);
		$this->PropertyModel->operationComercial($this);
		$this->PropertyModel->stock($this);

		$this->AccountModel->contact($this);
		$this->AccountModel->prospecto($this);  //error
		$this->AccountModel->payment($this);
		$this->AccountModel->accountStatus($this);  //error
		$this->AccountModel->reportAnalisis($this);

		echo "finished ..... ";
		return 'adfsf';
	}


	private function cleanDB()
	{
		$this->dbforge->drop_table('proyecto',TRUE);
		$this->dbforge->drop_table('etapa',TRUE);
		$this->dbforge->drop_table('edificio',TRUE);

		$this->dbforge->drop_table('cliente',TRUE);
		$this->dbforge->drop_table('usuario',TRUE);

		$this->dbforge->drop_table('unidadInmobiliaria',TRUE);
		$this->dbforge->drop_table('modeloinmueble',TRUE);
		$this->dbforge->drop_table('operacioncomercial',TRUE);
		$this->dbforge->drop_table('stock',TRUE);

		$this->dbforge->drop_table('contacto',TRUE);
		$this->dbforge->drop_table('prospecto',TRUE);
		$this->dbforge->drop_table('pago',TRUE);
		$this->dbforge->drop_table('estadocuenta',TRUE);
		$this->dbforge->drop_table('reporte_analisis',TRUE);
	}

	public function getToken()
	{
		try {
			$params = [
				'username' => $this->evolta_username,
				'password' => $this->evolta_password,
				'grant_type' => 'password'
			];
	
			$res = curl_request('POST', $this->evolta_host.'/oauth2/token', [
				'data' => $params
			]);
			
			return $res->access_token;

		} catch(Exeption $err) {
			throw $err;
		}
	}

	public function getServiceParams($service)
	{
		$this->db->select('parametro, valor');
		$this->db->where(array('servicio' => $service, 'state' => 1));
		$query = $this->db->get('servicio_config');
		return $query->result();		
	}

	public function saveData($service, $result)
	{
		// if(is_array($result)){
		// 	$fields = array();

		// 	for($i = 0; $i < ceil(count($result)/$this->load_max_count); $i++){
		// 		$_result = array_splice($result, 0, $this->load_max_count);
		// 		$data = flatten_request_data($_result);
				
		// 		if($i == 0){
		// 			$fields = generate_db_fields($data);
		// 			$this->dbforge->add_field($fields);
		// 			$this->dbforge->create_table($service, TRUE);
		// 		}
				
		// 		$data = merge_array_key(array_keys($fields), $data);
		// 		$this->db->insert_batch($service, $data);
		// 	}
		// }else{
			$data = flatten_request_data($result);
			$fields = generate_db_fields($data);

			$this->dbforge->add_field($fields);
			$this->dbforge->create_table($service, TRUE);

			$data = merge_array_key(array_keys($fields), $data);
			$this->db->insert_batch($service, $data);
		// }
	}

	public function log($service, $params, $state)
	{

		$data = array(
			'servicio' => $service,
			'parametro' => '',
			'valor' => '',
			'fechaEjecucion' => date('Y-m-d H:i:s'),
			'usuarioEjecucion' => 'SERVERNAME',
			'state' => $state
		);

		$query_data = array();
		if( $params || count($params)){
			foreach($params as $param){
				$query_data[] = array_merge($data, (array)$param);
			}
		}else{
			$query_data[] = $data;
		}

		$this->db->insert_batch('servicio_log', $query_data);
	}
}
