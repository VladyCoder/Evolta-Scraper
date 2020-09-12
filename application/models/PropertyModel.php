<?php

class PropertyModel extends CI_Model {

    public function propertyUnit($control)
	{
        try{
            // $available_parameters = ['id'];
            $service    = 'unidad_inmueble';
            $parameter = '178';

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/unidadInmobiliaria/'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
            $data = flatten_request_data($result);
            $fields = generate_db_fields($data);
            
            $this->dbforge->add_field($fields);
            $this->dbforge->create_table($service, TRUE);

            $this->db->insert_batch($service, $data);
            
            return $data;

        }catch(Exception $err){
            return 'failed';
        }
    }
    
    public function propertyModel($control)
	{
        try{
            // $available_parameters = ['proyecto', 'etapa'];
            $service    = 'model_inmueble';
            $parameter = '?proyect=178&etapa=';

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/modeloinmueble'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
            $data = flatten_request_data($result);
            $fields = generate_db_fields($data);
            
            $this->dbforge->add_field($fields);
            $this->dbforge->create_table($service, TRUE);

            $this->db->insert_batch($service, $data);
            
            return $data;

        }catch(Exception $err){
            return 'failed';
        }
    }

    public function operationComercial($control)
	{
        try{
            // $available_parameters = ['proyecto', 'proforma', 'etapa', 'vendedor', 'opercomerc', 'fechaocini', 'fechaocfin', 'fechaeditini', 'fechaeditfin', 'fechaprofini', 'fechaproffin', 'fechasepini', 'fechasepfin'];
            $service    = 'operacion_comercial';
            $parameter = '?proyecto=&proforma=&etapa=&vendedor=&opercomerc=1&fechaocini=&fechaocfin=&fechaeditini=&fechaeditfin=&fechaprofini=01-01-2020&fechaproffin=31-01-2020&fechasepini&fechasepfin';

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/operacioncomercial'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
            $data = flatten_request_data($result);
            $fields = generate_db_fields($data);
            
            $this->dbforge->add_field($fields);
            $this->dbforge->create_table($service, TRUE);

            $this->db->insert_batch($service, $data);
            
            return $data;

        }catch(Exception $err){
            return 'failed';
        }
    }

    public function stock($control)
	{
        try{
            // $available_parameters = ['proyecto', 'etapa', 'edificio', 'tipo', 'estado', 'anulado', 'fechaini', 'fechafin', 'fechaentregaini', 'fechaentregafin'];
            $service    = 'stocks';
            $parameter = '?proyecto=&etapa=&edificio=&tipo=&estado=&anulado=0&fechaini=&fechafin=&fechaentregaini&fechaentregafin';

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/stock'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
            $data = flatten_request_data($result);
            $fields = generate_db_fields($data);
            
            $this->dbforge->add_field($fields);
            $this->dbforge->create_table($service, TRUE);

            $this->db->insert_batch($service, $data);
            
            return $data;

        }catch(Exception $err){
            return 'failed';
        }
    }
    
}