<?php
include_once(DIR . '/Modules/Tker.php');
include_once(DIR . '/Modules/Validator.php');
class Router
{
    private $controller;
    private $method;

    public function __construct()
    {
        $this->matchRoute();
    }

    public function matchRoute()
    {
        //Si se establece el tk por el método get
        if (isset($_GET['tk'])) {
            //Se divide el tk en el contenido y el iv, cifrados
            $url = explode('.', $_GET['tk']);
            //Se descifran los datos, o en dado caso, se obtiene un código de error
            //en la validación
            $data = Validator::reqExT(Tker::sslurldecoder($url[0], Tker::sslurldecoder($url[1])));
            //Si no hay ningún error de la validación
            if (!in_array($data, Validator::$error_codes)) {
                //Se definen los datos para ser accedidos desde la API
                define('data', $data);
                //y además, se define el método y controlador a ejecutar
                //el controlador se cocatena con la cadena 'Controller'
                $this->controller = $data['controller'].'Controller';
                $this->method = $data['method'];
                
            } else {
                //en caso de tener un error se obtiene el código
                die("Error code: $data");
            }
        }else{
            //En tal caso que no exista el tk, se obtiene el controlador infController
            $this->controller ='infController';
            $this->method = 'info';
        }
        //Se requiere el controller
        require_once(__DIR__ . '/controllers/' . $this->controller . '.php');

    }


    public function run()
    {
        //Se crea un objeto del controller dado
        $controller = new $this->controller;
        //Y se establece de igual manera el método
        $method = $this->method;
        //Se hace llamado a el método del controlador dado
        $controller::$method();
    }
}