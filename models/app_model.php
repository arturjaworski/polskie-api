<?php

class AppModel {
    private $mongo = NULL;
    private $db = NULL;
    private $collectionName = NULL;
    
    function __construct() {
        $this->mongo = new MongoClient();
        $this->db = $this->mongo->selectDB('polskie-api');
        $this->collectionName = strtolower(get_class($this));
    }
    
    function __set($name, $value) {
        if($name == 'collectionName') {
            if($value === NULL) {
                $this->collectionName = strtolower(get_class($this));
            }
            else {
                $this->collectionName = $value;
            }
        }
    }
    
    public function init() { }
    
    protected function collection() {
        if($this->collectionName === NULL) {
            return false;
        }
        return $this->db->selectCollection($this->collectionName);
    }
    
    public function find($type = 'all', $options) {
        if($type === 'first') {
            $options['limit'] = 1;
        }
        
        $query = array();
        if(isset($options['conditions'])) {
            $query = $options['conditions'];
        }
        
        $fields = array();
        if(isset($options['fields'])) {
            $fields = array_fill_keys($options['fields'], true);
        }
        $cursor = $this->collection()->find($query, $fields);
        if($type === 'count') {
            return $cursor->count();
        }
        
        if(isset($options['order'])) {
            $order = $options['order'];
            foreach($order as $k => $v) {
                $order[$k] = intval(str_replace(array('asc', 'desc'), array(1, -1), strtolower($v)));
            }
        
            $cursor->sort($order);
        }
        
        if(isset($options['limit'])) {
            $cursor->limit(intval($options['limit']));
        }
        
        $response = array();
        foreach($cursor as $row) {
            $response[] = $row;
        }

        $cursor->reset();

        if($type === 'first') {
            if(isset($response[0])) {
                return $response[0];
            }
            else {
                return false;
            }
        }

        return $response;
    }
    
    // $id must be MongoID
    public function save($id = null, $data) {
        $data['modified'] = microtime(true);
        unset($data['_id']);
        if($id !== null) {
            if(!($id instanceof MongoId)) {
                return false;
            }
            
            return $this->collection()->update(array('_id' => $id), array('$set' => $data));
        }
        
        $data['created'] = $data['modified'];
        return $this->collection()->insert($data);
    }
    
    protected function deleteDirect($query) {
        return $this->collection()->remove($query);
    }
    
    // $id must be MongoID
    protected function delete($id = null) {
        if($id !== null && $id instanceof MongoId) {
            return $this->deleteDirect(array('_id' => $id));
        }
        
        return false;
    }
}

?>