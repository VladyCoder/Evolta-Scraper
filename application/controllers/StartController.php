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

		$this->load->dbforge();
		$this->load->database();
		$this->load->helper('client');

		$this->load->model('ProjectModel');
		$this->load->model('UserModel');
		$this->load->model('PropertyModel');
		$this->load->model('AccountModel');
	}

	public function index()
	{
		$this->cleanDB();

		// $projects = $this->ProjectModel->projects($this);
		// $project_detail = $this->ProjectModel->projectDetail($this);
		// $etapa_detail = $this->ProjectModel->etapaDetail($this);
		// $edificio_detail = $this->ProjectModel->edificioDetail($this);

		// $clients = $this->UserModel->clients($this);
		// $users = $this->UserModel->users($this);
		// $user_detail = $this->UserModel->userDetail($this);

		$property_unit = $this->PropertyModel->propertyUnit($this);
		$property_model = $this->PropertyModel->propertyModel($this);
		$operation = $this->PropertyModel->operationComercial($this);
		$stock = $this->PropertyModel->stock($this);

		$contact = $this->AccountModel->contact($this);
		$prospecto = $this->AccountModel->prospecto($this);
		$payment = $this->AccountModel->payment($this);
		$account = $this->AccountModel->accountStatus($this);
		$report_analisis = $this->AccountModel->reportAnalisis($this);

		return 'adfsf';
	}

	private function cleanDB()
	{
		$this->dbforge->drop_table('proyecto',TRUE);
		$this->dbforge->drop_table('proyecto_detalle',TRUE);
		$this->dbforge->drop_table('etapa_detalle',TRUE);
		$this->dbforge->drop_table('edificio_detalle',TRUE);

		$this->dbforge->drop_table('cliente',TRUE);
		$this->dbforge->drop_table('usuario',TRUE);
		$this->dbforge->drop_table('usuario_detalle',TRUE);

		$this->dbforge->drop_table('unidad_inmueble',TRUE);
		$this->dbforge->drop_table('model_inmueble',TRUE);
		$this->dbforge->drop_table('operacion_comercial',TRUE);
		$this->dbforge->drop_table('stocks',TRUE);

		$this->dbforge->drop_table('contacto',TRUE);
		$this->dbforge->drop_table('prospecto',TRUE);
		$this->dbforge->drop_table('pago',TRUE);
		$this->dbforge->drop_table('cuentas',TRUE);
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
}
