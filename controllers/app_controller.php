<?php

class AppController {
    public $name;
    public $action;
    public $params;
    
    protected $get;
    protected $post;
        
    protected $models = array();
        
    protected $disallowedMethods = array();
        
    public function __get($name) {
        if (array_key_exists($name, $this->models)) {
            return $this->models[$name];
        }
        
        return NULL;
    }
    
    function __construct($action, $params) {
        $this->name = strtolower(str_replace('Controller', '', get_class($this)));
        $this->action = $action;
        $this->params = $params;
    }

    public function run() {
        $this->loadPost();
        $this->loadGet();
    
        if(in_array($this->name.'.'.$this->action, $this->disallowedMethods) && !defined('CRON_DISPATCHER')) {
            throw new Exception();
        }
    
        $response = call_user_func_array(array(&$this, $this->action), $this->params);
        echo json_encode($response);
    }
    
    protected function loadPost() {
        if(!($json = file_get_contents('php://input'))) {
            return;
        }
        
        $this->post = json_decode($json, true);
    }
    
    protected function loadGet() {
        $this->get = $_GET;
    }
    
    protected function loadModel($name) {
        if(array_key_exists($name, $this->models)) {
            return true;
        }
        require_once(ROOT.'/models/'.strtolower($name).'.php');
        $this->models[$name] = new $name();
        $this->models[$name]->init();
    
        return true;
    }
    
    protected function responseError($number) {
        $number = (int)$number;
        $message = false;
        switch($number) {
            case 531:
                $message = 'Too many records';
                break;
        }
        
        if($message === false) {
            return false;
        }
        
        header('HTTP/1.0 '.$number.' '.$message, true, $number);
        header('Status: '.$number);
        die();
    }
}

?>