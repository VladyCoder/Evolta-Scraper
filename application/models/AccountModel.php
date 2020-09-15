<?php

class AccountModel extends CI_Model {

    public function contact($control)
	{
        try{
            // query : '?contacto=342222&fechaini=&fechafin=';
            $service    = 'contacto';
            $params     = $control->getServiceParams($service);
            $parameter  = build_query($params);

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/contacto'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
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
    
    public function prospecto($control)
	{
        try{
            // query : '?proyecto=228&prospecto=&responsable=&estado=&fechaini=&fechafin'
            $service    = 'prospecto';
            $params     = $control->getServiceParams($service);
            $parameter  = build_query($params);

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/prospecto'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
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
    
    public function payment($control)
	{
        try{
            // query : '?proyecto=228&vendedor=&estado=&fecpagoini=&fecpagofin=&fecmodifini=&fecmodiffin';
            $service    = 'pago';
            $params     = $control->getServiceParams($service);
            $parameter  = build_query($params);

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/pago'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
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
    
    public function accountStatus($control)
	{
        try{
            // query : ?proyecto=&vendedor=&cronograma=&activo=&fechaini=&fechafin
            $service    = 'estadocuenta';
            $params     = $control->getServiceParams($service);
            $parameter  = build_query($params);

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/estadocuenta'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
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
    
    public function reportAnalisis($control)
	{
        try{
            // query : '?proyecto=178&tipo=1&mes=11&anio=2019'
            $service    = 'reporte_analisis';
            $params     = $control->getServiceParams($service);
            $parameter  = build_query($params);

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/reporte/analisis/formacontacto'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
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