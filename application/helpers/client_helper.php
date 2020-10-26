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

    function get_db_fields($rows){
        $fields = array();
        foreach($rows as $row){
            $columns = array_keys((array)$row);
            $fields = array_merge($fields, $columns);
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

    function build_query($params, $projectId = null) {
        $query = '';
        foreach($params as $param){
            if($param->parametro == 'id') return '/'.$param->valor;

            if($query == '') $query .= '?';
            else $query .= '&';

            if($param->parametro == 'proyecto' && $projectId !== null) $query .= 'proyecto='.$projectId;
            else if(strpos($param->valor, 'getdate') !== FALSE) {
                $differ = substr($param->valor, 7);
                if(empty($differ)) $query .= $param->parametro.'='.date('d-m-Y');
                else $query .= $param->parametro.'='.date('d-m-Y', strtotime($differ.' days'));
            }else $query .= $param->parametro.'='.$param->valor;
        }

        return $query;
    }

    function checkLoopByProject($params) {
        if(!$params[0]->forproyect) return false;

        foreach($params as $param){
            if($param->parametro == 'proyecto') return true;
        }
        return false;
    }


    // 
    function build_ga_query($params) {
        $query = [];
        $matrix_maxium = 10;
        $dimension_maxium = 6;
        $matrix_count = 1;
        $dimension_count = 1;

        foreach($params as $param){
            $key = $param->parametro;

            if($key == 'start-date' || $key == 'end-date'){
                $date = 'today';
                if(strpos($param->valor, 'getdate') !== FALSE && !empty(substr($param->valor, 7))){
                    $date = date('Y-m-d', strtotime(substr($param->valor, 7).' days'));
                }

                $query[$key] = $date;
            }else if(isset($query[$key])){
                if($key == 'metrics' && $matrix_count < $matrix_maxium) {
                    $matrix_count += 1;
                    $query[$key] .= ','.$param->valor;
                }else if($key == 'dimensions' && $dimension_count < $dimension_maxium){
                    $dimension_count += 1;
                    $query[$key] .= ','.$param->valor;
                }
            }else{
                $query[$key] = $param->valor;
            }
        }

        if(isset($query['time'])){
            if(isset($query['dimensions'])) $query['dimensions'] .= ",".$query['time'];
            else $query['dimensions'] = $query['time'];
        }

        if(!isset($query['dimensions'])) $query['dimensions'] = array();
        
        return $query;
    }

    function apply_keys($keys, $data){
        if(!$data || !is_array($data)) return;

        $_data = array();
        foreach($data as $item){
            $_data[] = array_combine($keys, $item);
        }

        return $_data;
    }