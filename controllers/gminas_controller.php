<?php

require_once(ROOT.'/controllers/app_controller.php');
class GminasController extends AppController {
    function find() {
        $rows = array();
        $this->post = $this->post['gmina'];
    
        $conditions = array();
        if(isset($this->post['name'])) {
            $regex = addslashes($this->post['name']);
            $regex = str_replace('%', '.*', $regex);
            $regex = '/^'.$regex.'$/i';
            
            $conditions['name'] = new MongoRegex($regex);
        }
        
        if(isset($this->post['powiat']['id'])) {
            $conditions['powiat_id'] = intval($this->post['powiat']['id']);
        }
        
        $this->loadModel('Gmina');
        if($this->Gmina->find('count', array('conditions' => $conditions)) > 100) {
            return $this->responseError(531);
        }
        $gminas = $this->Gmina->find('all', array('conditions' => $conditions));
        
        foreach($gminas as $gmina) {
            $rows[] = array(
                'id' => $gmina['id'],
                'powiat' => array(
                    'id' => $gmina['powiat_id'],
                ),
                'name' => $gmina['name'],
            );
        }
        
        return array('gminas' => $rows);
    }

    function get($id) {
        $id = intval($id);
        
        $this->loadModel('Gmina');
        $gmina = $this->Gmina->find('first', array('conditions' => array('id' => $id)));
        
        $rows = array();
        if($gmina) {
            $rows = array(
                array(
                    'id' => $gmina['id'],
                    'powiat' => array(
                        'id' => $gmina['powiat_id'],
                    ),
                    'name' => $gmina['name'],
                )
            );
        }
        
        return array('gminas' => $rows);
    }
}

?>
