<?php

require_once(ROOT.'/models/app_model.php');
class Powiat extends AppModel {
    function init() {
        parent::init();
        
        $this->collection()->ensureIndex(array('id' => 1, 'voivodeship_id' => 1));
    }
}

?>
