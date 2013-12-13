<?php

require_once(ROOT.'/controllers/app_controller.php');
class DistrictsController extends AppController {
    function find() {
        $rows = array();
        $this->post = $this->post['district'];
    
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
        
        $this->loadModel('District');
        if($this->District->find('count', array('conditions' => $conditions)) > 100) {
            return $this->responseError(531);
        }
        $districts = $this->District->find('all', array('conditions' => $conditions));
        
        foreach($districts as $district) {
            $rows[] = array(
                'id' => $district['id'],
                'city' => array(
                    'id' => $district['city_id'],
                ),
                'name' => $district['name'],
            );
        }
        
        return array('districts' => $rows);
    }

    function get($id) {
        $id = intval($id);
        
        $this->loadModel('District');
        $district = $this->District->find('first', array('conditions' => array('id' => $id)));
        
        $rows = array();
        if($district) {
            $rows = array(
                array(
                    'id' => $district['id'],
                    'city' => array(
                        'id' => $district['city_id'],
                    ),
                    'name' => $district['name'],
                )
            );
        }
        
        return array('districts' => $rows);
    }
}

?>
