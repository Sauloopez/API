<?php
include_once(DIR.'/tk/Tker.php');
include_once(DIR.'/tk/Validator.php');
class Router{
    private $controller;
    private $method;

    public function __construct(){
        $this->matchRoute();
    }

    public function matchRoute(){
        
        $url = explode('.', URL);
        
        
        $data= Validator::reqExT(Tker::sslurldecoder($url[0], Tker::sslurldecoder($url[1])));
        
        if(!in_array($data, Validator::$error_codes)){
            define('data', $data);
            $this->controller= $data['controller'];
            $this->method= $data['method'];
            $this->controller = $this->controller.'Controller';
        
            require_once(__DIR__.'/controllers/'.$this->controller.'.php');
        }else{
            die($data);
        }
        
        
    }


    public function run(){
        $controller = new $this->controller;
        $method = $this->method;
        
        $controller::$method();
    }
}