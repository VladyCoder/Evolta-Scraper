<?php

class UserModel extends CI_Model {

    public function users($control)
	{
        try {
            // $available_parameters = [];
            $service    = 'usuario';
            // $parameter = '178';

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/usuario', [ 'headers'  => array( 'Authorization: Bearer '.$token )]);
            
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

    public function userDetail($control)
	{
        try {
            // $available_parameters = [];
            $service    = 'usuario_detalle';
            $parameter = '1345';

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/usuario/'.$parameter, [ 'headers'  => array( 'Authorization: Bearer '.$token )]);
            
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
    
    public function clients($control)
	{
        try {
            // $available_parameters = [];
            $service    = 'cliente';
            // $parameter = '178';

            $token = $control->getToken();
            $result = curl_request('GET', $control->evolta_host.'/api/cliente', [ 'headers'  => array( 'Authorization: Bearer '.$token )]);
            $result = array_slice($result, 0, 10); /// temparay code  * memory limit

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