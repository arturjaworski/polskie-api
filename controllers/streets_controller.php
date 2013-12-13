<?php

require_once(ROOT.'/controllers/app_controller.php');
class StreetsController extends AppController {
    function find() {
        $rows = array();
        $this->post = $this->post['street'];
    
        $columns = array(
            'prefix' => 'string',
            'name_additional_part' => 'string',
            'name' => 'string',
            'full_name' => 'string',
            'city_id' => 'number',
            'district_id' => 'number',
            'subdivision_id' => 'number',
        );
    
        $conditions = array();
        foreach($columns as $column => $type) {
            if(isset($this->post[$column])) {
                if($type == 'string') {
                    $conditions[$column] = new MongoRegex('/^'.str_replace('%', '.*', addslashes($this->post[$column])).'$/i');
                }
                elseif($type == 'number' && strlen($column)-3 == strpos($column, '_id')) {
                    $model = substr($column, 0, -3);
                    $conditions[$column] = intval($this->post[$model]['id']);
                }
            }
        }
                
        $this->loadModel('Street');
        if($this->Street->find('count', array('conditions' => $conditions)) > 100) {
            return $this->responseError(531);
        }
        $streets = $this->Street->find('all', array('conditions' => $conditions));
        
        foreach($streets as $street) {
            $district = NULL;
            if($street['district_id']) {
                $district = array(
                    'id' => $street['district_id'],
                );
            }
                        
            $subdivision = NULL;
            if($street['subdivision_id']) {
                $subdivision = array(
                    'id' => $street['subdivision_id'],
                );
            }
        
            $rows[] = array(
                'id' => $street['id'],
                'city' => array(
                    'id' => $street['city_id'],
                ),
                'district' => $district,
                'subdivision' => $subdivision,
                'prefix' => $street['prefix'],
                'name_additional_part' => $street['name_additional_part'],
                'name' => $street['name'],
            );
        }
        
        return array('streets' => $rows);
    }

    function get($id) {
        if(!is_numeric($id)) {
            return array('streets' => array());
        }
        
        $id = ltrim($id, '0');
        
        $this->loadModel('Street');
        $street = $this->Street->find('first', array('conditions' => array('id' => $id)));
        
        $rows = array();
        if($street) {
            $district = NULL;
            if($street['district_id']) {
                $district = array(
                    'id' => $street['district_id'],
                );
            }
                        
            $subdivision = NULL;
            if($street['subdivision_id']) {
                $subdivision = array(
                    'id' => $street['subdivision_id'],
                );
            }
            
            $rows = array(
                array(
                    'id' => $street['id'],
                    'city' => array(
                        'id' => $street['city_id'],
                    ),
                    'district' => $district,
                    'subdivision' => $subdivision,
                    'prefix' => $street['prefix'],
                    'name_additional_part' => $street['name_additional_part'],
                    'name' => $street['name'],
                )
            );
        }
        
        return array('streets' => $rows);
    }
}

?>
