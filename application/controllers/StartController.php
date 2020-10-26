<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class StartController extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		$this->load->helper('client');
		$this->load->model('ServiceModel');
		
		$this->load->library('googleanalyticsreporting');
		$this->load->library('services');
		
		ini_set('memory_limit','-1');
		ini_set('max_execution_time', 0);
	}

	public function index(){
		$this->service();
		$this->analytic();
	}

	public function service()
	{
		$this->ServiceModel->cleanDB('evolta');
		$evolta_env = $this->config->item('evolta');

		$this->services->runServices($this->ServiceModel, $evolta_env);

		echo "Evolta Services finished ..... ";
		return;
	}

	// Google Analytics
	public function analytic(){
		$this->ServiceModel->cleanDB('ga');
		$this->googleanalyticsreporting->runGoogleAnalyticsReporting($this->ServiceModel);

		echo "GoogleAnalyticsReporting finished....";
		return;
	}

}
