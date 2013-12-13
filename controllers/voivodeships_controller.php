<?php

require_once(ROOT.'/controllers/app_controller.php');
class VoivodeshipsController extends AppController {
    function find() {
        $rows = array();
        $this->post = $this->post['voivodeship'];
    
        $conditions = array();
        if(isset($this->post['name'])) {
            $regex = addslashes($this->post['name']);
            $regex = str_replace('%', '.*', $regex);
            $regex = '/^'.$regex.'$/i';
            
            $conditions['name'] = new MongoRegex($regex);
        }
        
        $this->loadModel('Voivodeship');
        // w sumie niepotrzebne
        if($this->Voivodeship->find('count', array('conditions' => $conditions)) > 100) {
            return $this->responseError(531);
        }
        $voivodeships = $this->Voivodeship->find('all', array('conditions' => $conditions));
        
        foreach($voivodeships as $voivodeship) {
            $rows[] = array(
                'id' => $voivodeship['id'],
                'name' => $voivodeship['name'],
            );
        }
        
        return array('voivodeships' => $rows);
    }

    function get($id) {
        $id = intval($id);
        
        $this->loadModel('Voivodeship');
        $voivodeship = $this->Voivodeship->find('first', array('conditions' => array('id' => $id)));
        
        $rows = array();
        if($voivodeship) {
            $rows = array(
                array(
                    'id' => $voivodeship['id'],
                    'name' => $voivodeship['name'],
                )
            );
        }
        
        return array('voivodeships' => $rows);
    }
}

?>
