<?php

class ProjectModel extends CI_Model {

    public function projects($control)
	{
        try{
            // query : /id
            $service    = 'proyecto';
            $params     = $control->getServiceParams($service);
            $parameter  = build_query($params);

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/proyecto'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
            if(!isset($result) || empty($result) || is_string($result)){
                $control->log($service, $params, 'fail');
            }else{
                $control->saveData($service, $result);
                $control->log($service, $params, 'success');    
            }
            return;

        }catch(Exception $err){
            $control->log($service, $params, 'fail');
            return;
        }
    }

    public function projectDetail($control)
	{
        try{
            // query : id;
            $service    = 'proyecto';
            $params     = $control->getServiceParams($service);
            $parameter  = build_query($params);

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/proyecto'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
            if(!isset($result) || empty($result) || is_string($result)){
                $control->log($service, $params, 'fail');
            }else{
                $control->saveData($service, $result);
                $control->log($service, $params, 'success');    
            }
            return;

        }catch(Exception $err){
            $control->log($service, $params, 'fail');
            return;
        }
    }
    
    public function etapaDetail($control)
	{
        try{
            // query : id
            $service    = 'etapa';
            $params     = $control->getServiceParams($service);
            $parameter  = build_query($params);

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/etapa'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
            if(!isset($result) || empty($result) || is_string($result)){
                $control->log($service, $params, 'fail');
            }else{
                $control->saveData($service, $result);
                $control->log($service, $params, 'success');    
            }
            return;

        }catch(Exception $err){
            $control->log($service, $params, 'fail');
            return;
        }
    }
    
    public function edificioDetail($control)
	{
        try{
            // query : id
            $service    = 'edificio';
            $params     = $control->getServiceParams($service);
            $parameter  = build_query($params);

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/edificio'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
            if(!isset($result) || empty($result) || is_string($result)){
                $control->log($service, $params, 'fail');
            }else{
                $control->saveData($service, $result);
                $control->log($service, $params, 'success');    
            }
            return;
            
        }catch(Exception $err){
            $control->log($service, $params, 'fail');
            return;
        }
	}
}