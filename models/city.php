<?php

require_once(ROOT.'/models/app_model.php');
class City extends AppModel {
    function init() {
        parent::init();
        
        $this->collection()->ensureIndex(array('id' => 1, 'gmina_id' => 1));
    }
}

?>
