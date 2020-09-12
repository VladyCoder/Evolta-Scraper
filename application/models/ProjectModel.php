<?php

class ProjectModel extends CI_Model {

    public function projects($control)
	{
        try{
            // $available_parameters = [];
            $service    = 'proyecto';

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/proyecto', ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
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

    public function projectDetail($control)
	{
        try{
            // $available_parameters = ['id'];
            $service    = 'proyecto_detalle';
            $parameter = '178';

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/proyecto/'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
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
    
    public function etapaDetail($control)
	{
        try{
            // $available_parameters = ['id'];
            $service    = 'etapa_detalle';
            $parameter = '178';

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/etapa/'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
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
    
    public function edificioDetail($control)
	{
        try{
            // $available_parameters = ['id'];
            $service    = 'edificio_detalle';
            $parameter = '178';

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/edificio/'.$parameter, ['headers'  => array( 'Authorization: Bearer '.$token )]);
            
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