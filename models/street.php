<?php

require_once(ROOT.'/models/app_model.php');
class Street extends AppModel {
    function init() {
        parent::init();
        
        $this->collection()->ensureIndex(array('id' => 1, 'city_id' => 1, 'district_id' => 1, 'subdivision_id' => 1, 'prefix' => 1, 'name_additional_part' => 1, 'name' => 1, 'full_name' => 1));
    }
}

?>
