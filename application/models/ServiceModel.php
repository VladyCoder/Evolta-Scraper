<?php

class ServiceModel extends CI_Model {

    public function __construct()
	{
		parent::__construct();
		
		$this->load->dbforge();
        $this->load->database();
    }

    public function services()
    {
		$query = $this->db->get('servicios');
		return $query->result();
    }

    public function tableNames($config_table, $service)
    {
        $this->db->select('table_name');
        $this->db->where('servicio', $service);
        $this->db->where('state', 1);
        $this->db->group_by('table_name');
		$query = $this->db->get($config_table);
		return $query->result();
    }

    public function getParamsInTable($config_table, $name)
    {   
        $this->db->select('parametro, valor, forproyect');
        $this->db->where('table_name', $name);
        // $this->db->where('state', 1);
        $query = $this->db->get($config_table);

		return $query->result();
    }

    public function saveData($tableName, $service, $result)
	{
        $data = flatten_request_data($result);
        $fields = get_db_fields($data);

        $cur_table = $this->db->where('table_name', $tableName)->where('type', 'evolta')->get('table_names')->row();
        if($cur_table){
            $curColumns = explode(';', $cur_table->fields);

            $newFields = array_diff($fields, $curColumns);
            $fields = array_merge($curColumns, $newFields);

            $newFields = array_fill_keys($newFields, array(
                'type' => 'VARCHAR',
                'constraint' => 'MAX',
                'null' => TRUE
            ));

            $this->dbforge->add_column($tableName, $newFields);

            $this->db->set('fields', implode(';', $fields));
            $this->db->where('table_name', $tableName);
            $this->db->update('table_names');
        }else{
            $newFields = array_fill_keys($fields, array(
                'type' => 'VARCHAR',
                'constraint' => 'MAX',
                'null' => TRUE
            ));
            $this->dbforge->add_field($newFields);
            $this->dbforge->create_table($tableName, TRUE);
            $this->db->insert('table_names', array('table_name' => $tableName, 'fields' => implode(';', $fields), 'type'=>'evolta'));
        }

        $this->insertServiceData($tableName, $fields, $data);
        unset($data, $result, $fields);
    }

    public function insertServiceData($tableName, $fields, $data)
    {
        $data = merge_array_key($fields, $data);
        $this->db->insert_batch($tableName, $data);
    }
    
    public function log($tableName, $service, $params, $state)
	{
		$data = array(
            'table_name' => $tableName,
			'servicio' => $service,
			'query' => $params,
			'fechaEjecucion' => date('Y-m-d H:i:s'),
			'usuarioEjecucion' => 'SERVERNAME',
			'state' => $state
		);

        $this->db->insert('servicio_log', $data);
    }
    
    public function cleanDB($type)
	{
        $this->db->where('type', $type);
        $this->db->set('fields', '');
        $this->db->update('table_names');

        $tables = $this->db->where('type', $type)->get('table_names')->result();
        foreach ($tables as $tb) {
            $this->dbforge->drop_table($tb->table_name, TRUE);
        }
        
        $this->db->where('type', $type);
        $this->db->delete('table_names');
        // $this->db->from('table_names')->truncate();
    }
    


    // 
    public function getGAParamsInTable($config_table, $name)
    {   
        $this->db->select('parametro, valor, forproyect');
        $this->db->where('table_name', $name);
        $this->db->where('state', 1);
        $query = $this->db->get($config_table);

		return $query->result();
    }
    public function saveGAData($tableName, $headers, $rows)
	{
        $_headers = array_fill_keys($headers, array(
            'type' => 'VARCHAR',
            'constraint' => 'MAX',
            'null' => TRUE
        ));
        $this->dbforge->add_field($_headers);
        $this->dbforge->create_table($tableName, TRUE);
        $this->db->insert('table_names', array('table_name' => $tableName, 'fields' => implode(';', $headers), 'type' => 'ga'));

        $data = apply_keys($headers, $rows);
        $this->db->insert_batch($tableName, $data);
    }
}
