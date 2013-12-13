<?php

require_once(ROOT.'/models/app_model.php');
class District extends AppModel {
    function init() {
        parent::init();
        
        $this->collection()->ensureIndex(array('id' => 1, 'city_id' => 1));
    }
}

?>
