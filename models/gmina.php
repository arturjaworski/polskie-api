<?php

require_once(ROOT.'/models/app_model.php');
class Gmina extends AppModel {
    function init() {
        parent::init();
        
        $this->collection()->ensureIndex(array('id' => 1, 'powiat_id' => 1));
    }
}

?>
