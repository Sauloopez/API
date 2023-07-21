<?php
include_once(DIR.'/tk/Tker.php');
class Router{
    private $controller;
    private $method;

    public function __construct(){
        $this->matchRoute();
    }

    public function matchRoute(){
        
        $params = explode('?', URL);
        $url = explode('/', $params[0]);
        
        $data= json_decode(Tker::sslurldecoder($url[1]), true);
        define('data', $data);
        $this->controller= $data['controller'];
        $this->method= $data['method'];
        
        $this->controller = $this->controller.'Controller';
        
        require_once(__DIR__.'/controllers/'.$this->controller.'.php');
    }


    public function run(){
        $controller = new $this->controller;
        $method = $this->method;
        
        $controller::$method();
    }
}