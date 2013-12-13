<?php

require_once(ROOT.'/controllers/app_controller.php');
class CitiesController extends AppController {
    function find() {
        $rows = array();
        $this->post = $this->post['city'];
    
        $conditions = array();
        if(isset($this->post['name'])) {
            $regex = addslashes($this->post['name']);
            $regex = str_replace('%', '.*', $regex);
            $regex = '/^'.$regex.'$/i';
            
            $conditions['name'] = new MongoRegex($regex);
        }
        
        if(isset($this->post['gmina']['id'])) {
            $conditions['gmina_id'] = intval($this->post['gmina']['id']);
        }
        
        $this->loadModel('City');
        if($this->City->find('count', array('conditions' => $conditions)) > 100) {
            return $this->responseError(531);
        }
        $cities = $this->City->find('all', array('conditions' => $conditions));
        
        foreach($cities as $city) {
            $rows[] = array(
                'id' => $city['id'],
                'gmina' => array(
                    'id' => $city['gmina_id'],
                ),
                'name' => $city['name'],
            );
        }
        
        return array('cities' => $rows);
    }

    function get($id) {
        $id = intval($id);
        
        $this->loadModel('City');
        $city = $this->City->find('first', array('conditions' => array('id' => $id)));
        
        $rows = array();
        if($city) {
            $rows = array(
                array(
                    'id' => $city['id'],
                    'gmina' => array(
                        'id' => $city['gmina_id'],
                    ),
                    'name' => $city['name'],
                )
            );
        }
        
        return array('cities' => $rows);
    }
}

?>
