<?php

require_once(ROOT.'/models/app_model.php');
class Voivodeship extends AppModel {
    function init() {
        parent::init();
        
        $this->collection()->ensureIndex(array('id' => 1));
    }
}

?>
