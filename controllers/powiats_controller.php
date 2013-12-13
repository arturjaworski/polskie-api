<?php

require_once(ROOT.'/controllers/app_controller.php');
class PowiatsController extends AppController {
    function find() {
        $rows = array();
        $this->post = $this->post['powiat'];
    
        $conditions = array();
        if(isset($this->post['name'])) {
            $regex = addslashes($this->post['name']);
            $regex = str_replace('%', '.*', $regex);
            $regex = '/^'.$regex.'$/i';
            
            $conditions['name'] = new MongoRegex($regex);
        }
        
        if(isset($this->post['voivodeship']['id'])) {
            $conditions['voivodeship_id'] = intval($this->post['voivodeship']['id']);
        }
        
        $this->loadModel('Powiat');
        if($this->Powiat->find('count', array('conditions' => $conditions)) > 100) {
            return $this->responseError(531);
        }
        $powiats = $this->Powiat->find('all', array('conditions' => $conditions));
        
        foreach($powiats as $powiat) {
            $rows[] = array(
                'id' => $powiat['id'],
                'voivodeship' => array(
                    'id' => $powiat['voivodeship_id']
                ),
                'name' => $powiat['name'],
            );
        }
        
        return array('powiats' => $rows);
    }

    function get($id) {
        $id = intval($id);
        
        $this->loadModel('Powiat');
        $powiat = $this->Powiat->find('first', array('conditions' => array('id' => $id)));
        
        $rows = array();
        if($powiat) {
            $rows = array(
                array(
                    'id' => $powiat['id'],
                    'voivodeship' => array(
                        'id' => $powiat['voivodeship_id']
                    ),
                    'name' => $powiat['name'],
                )
            );
        }
        
        return array('powiats' => $rows);
    }
}

?>
