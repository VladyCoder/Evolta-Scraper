<?php

class AccountModel extends CI_Model {

    public function contact($control)
	{
        try{
            // $available_parameters = ['contacto', 'fechaini', 'fechafin'];
            $service    = 'contacto';
            $parameter = '?contacto=342222&fechaini=&fechafin=';

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/contacto'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
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
    
    public function prospecto($control)
	{
        try{
            // $available_parameters = ['proyecto', 'prospecto', 'responsable', 'estado', 'fechaini', 'fechafin'];
            $service    = 'prospecto';
            $parameter = '?proyecto=228&prospecto=&responsable=&estado=&fechaini=&fechafin';

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/prospecto'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
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
    
    public function payment($control)
	{
        try{
            // $available_parameters = ['proyecto', 'vendedor', 'estado', 'fecpagoini', 'fecpagofin', 'fecmodifini', 'fecmodiffin'];
            $service    = 'pago';
            $parameter = '?proyecto=&vendedor=&estado=&fecpagoini=&fecpagofin=&fecmodifini=&fecmodiffin';

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/pago'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
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
    
    public function accountStatus($control)
	{
        try{
            // $available_parameters = ['proyecto', 'vendedor', 'cronograma', 'activo', 'fechaini', 'fechafin'];
            $service    = 'cuentas';
            $parameter = '?proyecto=228&vendedor=&cronograma=&activo=&fechaini=&fechafin';

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/estadocuenta'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
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
    
    public function reportAnalisis($control)
	{
        try{
            // $available_parameters = ['proyecto', 'tipo', 'mes', 'anio'];
            $service    = 'reporte_analisis';
            $parameter = '?proyecto=178&tipo=1&mes=11&anio=2019';

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/reporte/analisis/formacontacto'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
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