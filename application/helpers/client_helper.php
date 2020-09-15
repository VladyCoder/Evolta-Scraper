<?php
    function curl_request($method, $url, $payload){
            
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        
        if($method && strtolower($method) == 'post'){
            curl_setopt($ch, CURLOPT_POST, true);
            
            $urlencoded_data = '';
            foreach($payload['data'] as $key => $value){
                if(strlen($urlencoded_data) > 0) $urlencoded_data .= "&";
                $urlencoded_data .= $key."=".$value;
            }
            
            curl_setopt($ch, CURLOPT_POSTFIELDS, $urlencoded_data);
        }

        if($payload && isset($payload['headers'])){
            // curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $payload['headers']);
        }
        
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);

        $response = curl_exec($ch);

        if(curl_errno($ch)){
            $result = null;
        }else{
            $result = json_decode($response);
        }

        curl_close($ch);

        return $result;
    }

    function flatten_request_data($data, $parentKey = null)
    {
        $flatten = array();

        if(is_array($data)) {
            foreach ($data as $value) {
                $_list = flatten_request_data($value);
                $flatten = join_array($flatten, $_list);
            }
        }else if(is_object($data)) {
            $data_array = (array)$data;
            foreach ( $data_array as $key => $value) {
                if(count((array)$value) > 0) {
                    $_list = flatten_request_data($value, $key);
                    $flatten = join_data($key, $flatten, $_list, $parentKey);
                }
            }
        }else{
            return strval($data);
        }
        
        return $flatten;
    }

    function join_array($arr1, $arr2) {
        if(!is_array($arr2) || count($arr2) == 0) return $arr1;
        if(count($arr1) == 0) return $arr2;

        foreach($arr2 as $arr){
            $arr1[] = $arr;
        }
        
        return $arr1;
    }

    function join_data($key, $arr1, $arr2, $parentKey = null) {
        if(is_array($arr2)){
            if(count($arr1) == 0) return $arr2;
            if(count($arr2) == 0) return $arr1;

            $joined = array();
            foreach($arr1 as $origiArr){
                foreach($arr2 as $arr){
                    $joined[] = array_merge($origiArr, $arr);
                }
            }

            return $joined;
        }else{
            if($parentKey){
                if($key == 'value') {
                    $key = $parentKey;
                } else if($key == 'href'){
                    $key = str_replace('id', 'Des', $parentKey);
                }
            }
            
            $arr2 = array($key => $arr2);
            $joined = array();

            if(count($arr1) == 0) $joined[] = $arr2;
            else {
                foreach($arr1 as $origiArr){
                    $joined[] = array_merge($origiArr, $arr2);
                }
            }
            return $joined;
        }
    }

    function generate_db_fields($rows){
        if(!$rows || !is_array($rows)) return null;

        $fields = array();
        foreach($rows as $row){
            $columns = (array)$row;
            foreach($columns as $key => $column){
                $fields[$key] = array(
                    'type' => 'VARCHAR',
                    'constraint' => 'MAX',
                    'null' => TRUE
                );
            }
        }

        return $fields;
    }

    function merge_array_key($key_list, $data){
        
        $default = array_fill_keys($key_list, '');
        $new_data = array();

        foreach($data as $row){
            $new_data[] = array_merge($default, $row);
        }

        return $new_data;
    }

    function build_query($params){
        $query = '';
        if($params){
			foreach($params as $param){
				if($param->parametro == 'id') return '/'.$param->valor;
				else if($query == '') $query .= '?'.$param->parametro.'='.$param->valor;
				else $query .= '&'.$param->parametro.'='.$param->valor;
			}
        }

        return $query;
    }