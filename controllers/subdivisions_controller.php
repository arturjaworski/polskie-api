<?php

require_once(ROOT.'/controllers/app_controller.php');
class SubdivisionsController extends AppController {
    function find() {
        $rows = array();
        $this->post = $this->post['subdivision'];
    
        $conditions = array();
        if(isset($this->post['name'])) {
            $regex = addslashes($this->post['name']);
            $regex = str_replace('%', '.*', $regex);
            $regex = '/^'.$regex.'$/i';
            
            $conditions['name'] = new MongoRegex($regex);
        }
        
        if(isset($this->post['city']['id'])) {
            $conditions['city_id'] = intval($this->post['city']['id']);
        }
        
        if(isset($this->post['district']['id'])) {
            $conditions['district_id'] = intval($this->post['district']['id']);
        }
        
        $this->loadModel('Subdivision');
        if($this->Subdivision->find('count', array('conditions' => $conditions)) > 100) {
            return $this->responseError(531);
        }
        $subdivisions = $this->Subdivision->find('all', array('conditions' => $conditions));
        
        foreach($subdivisions as $subdivision) {
            $district = NULL;
            if($subdivision['district_id']) {
                $district = array(
                    'id' => $subdivision['district_id'],
                );
            }
        
            $rows[] = array(
                'id' => $subdivision['id'],
                'city' => array(
                    'id' => $subdivision['city_id'],
                ),
                'district' => $district,
                'name' => $subdivision['name'],
            );
        }
        
        return array('subdivisions' => $rows);
    }

    function get($id) {
        $id = intval($id);
        
        $this->loadModel('Subdivision');
        $subdivision = $this->Subdivision->find('first', array('conditions' => array('id' => $id)));
        
        $rows = array();
        if($subdivision) {
            $district = NULL;
            if($subdivision['district_id']) {
                $district = array(
                    'id' => $subdivision['district_id'],
                );
            }
            
            $rows = array(
                array(
                    'id' => $subdivision['id'],
                    'city' => array(
                        'id' => $subdivision['city_id'],
                    ),
                    'district' => $district,
                    'name' => $subdivision['name'],
                )
            );
        }
        
        return array('subdivisions' => $rows);
    }
}

?>
