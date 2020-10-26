<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GoogleAnalyticsReporting {

    public function __construct()
	{
        // 
    }

    public function runGoogleAnalyticsReporting($serviceModel){
        $this->ServiceModel = $serviceModel;
        $analytics = $this->configAnalyticsReporting();
        $profiles = $this->getGAProfiles($analytics);
        
		$gaTableNames = $this->ServiceModel->tableNames('ga_config', 'ga_reporting');
		foreach($gaTableNames as $tableName){
			$this->createGATable($tableName->table_name, $profiles, $analytics);
		}
    }

	public function configAnalyticsReporting(){
		$KEY_FILE_PATH = APPPATH.'config/ga-api-extraccion-6c0fa5cf97a0.json';

		$client = new Google_Client();
		$client->setApplicationName("GA API EXTRACCION");
		$client->setAuthConfig($KEY_FILE_PATH);
        $client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
        $analytics = new Google_Service_Analytics($client);

		return $analytics;
    }
    
    public function getGAProfiles($analytics) {
		$_profiles = array();
		$accounts = $analytics->management_accounts->listManagementAccounts();
	  
		if (count($accounts->getItems()) > 0) {

			foreach($accounts->getItems() as $account_item){
				$accountId = $account_item->getId();
				$properties = $analytics->management_webproperties->listManagementWebproperties($accountId);
				
				if (count($properties->getItems()) > 0) {
					
					foreach($properties->getItems() as $propery_item){
						$profiles = $analytics->management_profiles->listManagementProfiles($accountId, $propery_item->getId());

						if(count($profiles->getItems()) > 0){
							foreach($profiles->getItems() as $profile){
								$_profiles[] = ['id' => $profile->getId(), 'name' => $profile->getName()];
							}
						}
					}
				}
			}
		} else {
		  print_r('No accounts found for this user.');
		}

		return $_profiles;
	}

	public function createGATable($tableName, $profiles, $analytics){
		$headers = array('profile_id', 'profile_name');
		$rows = array();

		$params = $this->ServiceModel->getGAParamsInTable('ga_config', $tableName);
		$query = build_ga_query($params);

		if(!isset($query['metrics'])) {
			echo '____'.$tableName.": ERROR(bad config) ______". "\n";

			$this->ServiceModel->log($tableName, 'ga_reporting', '', 'fail');
			return;
        }
        
		try{
			foreach($profiles as $profile){
				$data = $analytics->data_ga->get(
					'ga:'.$profile['id'],
					$query['start-date'],
					$query['end-date'],
					$query['metrics'],
					['dimensions' => $query['dimensions']]
                );
                
				$_rows = $data->getRows();
	
				if(count($headers) == 2) {
					$_headers = $data->getColumnHeaders();
					$headers = array_merge($headers, $this->filterKeys($_headers));
				}

				$rows = $this->pushGAData($rows, $_rows, [$profile['id'], $profile['name']]);
			}
		}catch(Google_Service_Exception $error){
			echo '____'.$tableName.": ERROR (bad request) ______". "\n";

			$this->ServiceModel->log($tableName, 'ga_reporting', '', 'fail');
			return;
		}

		$this->ServiceModel->saveGAData($tableName, $headers, $rows);
		$this->ServiceModel->log($tableName, 'ga_reporting', '', 'success');
	}

	public function filterKeys($data){
		$_data = array();
		foreach($data as $item){
			if(isset($item->name)) $_data[] = $item->name;
		}
		return $_data;
	}

	public function pushGAData($old, $newItems, $preItems){
		if(!$newItems) return $old;

		foreach($newItems as $item){
			$old[] = array_merge($preItems, $item);
		}

		return $old;
    }
    







    // Analytics Reporting V4
    // public function getReports($client, $profiles){
    //     $analytics = new Google_Service_AnalyticsReporting($client);
    //     // Replace with your view ID, for example XXXX.
    //     $VIEW_ID = $profiles[0]['id'];
    //     echo $VIEW_ID;

    //     // Create the DateRange object.
    //     $dateRange = new Google_Service_AnalyticsReporting_DateRange();
    //     $dateRange->setStartDate("2020-08-01");
    //     $dateRange->setEndDate("today");

    //     // Create the Metrics object.
    //     $sessions = new Google_Service_AnalyticsReporting_Metric();
    //     $sessions->setExpression("ga:sessions");
    //     $sessions->setAlias("sessions");

    //     // Create the Dimensions object.
    //     $date = new Google_Service_AnalyticsReporting_Dimension();
    //     $date->setName("ga:date,ga:browser");


    //     // Create the ReportRequest object.
    //     $request = new Google_Service_AnalyticsReporting_ReportRequest();
    //     $request->setViewId($VIEW_ID);
    //     $request->setDateRanges($dateRange);
    //     $request->setMetrics(array($sessions));
    //     $request->setDimensions(array($date));

    //     $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
    //     $body->setReportRequests( array( $request) );
    //     return $analytics->reports->batchGet( $body );
    // }
}
?>