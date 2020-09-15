<?php

class PropertyModel extends CI_Model {

    public function unitProperty($control)
	{
        try{
            // query : id
            $service    = 'unidadInmobiliaria';
            $params     = $control->getServiceParams($service);
            $parameter  = build_query($params);

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/unidadInmobiliaria/'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
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
    
    public function modelProperty($control)
	{
        try{
            // query : '?proyecto=178&etapa=';
            $service    = 'modeloinmueble';
            $params     = $control->getServiceParams($service);
            $parameter  = build_query($params);

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/modeloinmueble'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
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

    public function operationComercial($control)
	{
        try{
            // query : '?proyecto=&proforma=&etapa=&vendedor=&opercomerc=1&fechaocini=&fechaocfin=&fechaeditini=&fechaeditfin=&fechaprofini=01-01-2020&fechaproffin=31-01-2020&fechasepini&fechasepfin';
            $service    = 'operacioncomercial';
            $params     = $control->getServiceParams($service);
            $parameter  = build_query($params);

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/operacioncomercial'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
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

    public function stock($control)
	{
        try{
            // query : '?proyecto=&etapa=&edificio=&tipo=&estado=&anulado=0&fechaini=&fechafin=&fechaentregaini&fechaentregafin'
            $service    = 'stock';
            $params     = $control->getServiceParams($service);
            $parameter  = build_query($params);

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/stock'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
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