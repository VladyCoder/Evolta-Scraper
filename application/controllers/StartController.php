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
		header('Content-Type: application/json');

		try{
			$this->clearall();

			$this->googleanalyticsreporting->runGoogleAnalyticsReporting($this->ServiceModel);

			$evolta_env = $this->config->item('evolta');
			$this->services->runServices($this->ServiceModel, $evolta_env);
			
			echo json_encode([
				'execution' => true
			]);
		}catch(Exception $err){
            echo json_encode([
				'execution' => false,
  				'message' => 'execution error'
			]);
        }
	}

	public function service()
	{
		header('Content-Type: application/json');

		try{
			$this->ServiceModel->cleanDB('evolta');

			$evolta_env = $this->config->item('evolta');
			$this->services->runServices($this->ServiceModel, $evolta_env);

			echo json_encode([
				'execution' => true
			]);
		}catch(Exception $err){
			echo json_encode([
				'execution' => false,
  				'message' => 'execution error'
			]);
		}
	}

	// Google Analytics
	public function analytic(){
		header('Content-Type: application/json');

		try{
			$this->ServiceModel->cleanDB('ga');
			$this->googleanalyticsreporting->runGoogleAnalyticsReporting($this->ServiceModel);

			echo json_encode([
				'execution' => true
			]);
		}catch(Exception $err){
			echo json_encode([
				'execution' => false,
  				'message' => 'execution error'
			]);
		}
	}

	public function clearall(){
		$this->ServiceModel->cleanDB('evolta');
		$this->ServiceModel->cleanDB('ga');
	}
}
