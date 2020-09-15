<?php

class UserModel extends CI_Model {

    public function users($control)
	{
        try {
            // query : id
            $service    = 'usuario';
            $params     = $control->getServiceParams($service);
            $parameter  = build_query($params);

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/usuario'.$parameter, [ 'headers'  => array( 'Authorization: Bearer '.$token )]);
            
            if(!isset($result) || empty($result) || is_string($result)){
                $control->log($service, $params, 'fail');
            }else{
                $control->saveData($service, $result);
                $control->log($service, $params, 'success');    
            }
            
        }catch(Exception $err){
            $control->log($service, $params, 'fail');
        }
    }

    public function userDetail($control)
	{
        try {
            // query : id
            $service    = 'usuario_detalle';
            $params     = $control->getServiceParams($service);
            $parameter  = build_query($params);

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/usuario'.$parameter, [ 'headers'  => array( 'Authorization: Bearer '.$token )]);

            if(!isset($result) || empty($result) || is_string($result)){
                $control->log($service, $params, 'fail');
            }else{
                $control->saveData($service, $result);
                $control->log($service, $params, 'success');    
            }
        }catch(Exception $err){
            $control->log($service, $params, 'fail');
        }
    }
    

    public function clients($control)
	{
        try {
            // query : id
            $service    = 'cliente';
            $params     = $control->getServiceParams($service);
            $parameter  = build_query($params);

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/cliente'.$parameter, [ 'headers'  => array( 'Authorization: Bearer '.$token )]);
            
            if(!isset($result) || empty($result) || is_string($result)){
                $control->log($service, $params, 'fail');
            }else{
                $control->saveData($service, $result);
                $control->log($service, $params, 'success');    
            }
        }catch(Exception $err){
            $control->log($service, $params, 'fail');
        }
        
	}

}