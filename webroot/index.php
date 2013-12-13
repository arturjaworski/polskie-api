<?php
    ini_set('display_errors', 'On');
    header('Content-Type: application/json; charset=utf-8');

    if(!defined('ROOT')) {
        define('ROOT', '../');
    }
    require_once(ROOT.'/utils.php');

    $tab = explode('/', $_GET['url'], 3);
      
    $name = strtolower($tab[0]);
    $action = isset($tab[1]) ? $tab[1] : 'index';
    $params = isset($tab[2]) ? explode('/', $tab[2]) : array();
    
    $controllerFile = ROOT.'controllers/'.$name.'_controller.php';
    
    try {
        if(empty($name) || $name == 'app') {
            throw new Exception();
        }
        require_once($controllerFile);
        $controllerClassName = ucfirst($name).'Controller';
        $controller = new $controllerClassName($action, $params);
        $controller->run();
    }
    catch(Exception $exception) {
        header("Status: 404 Not Found");
        exit();
    }
?>
