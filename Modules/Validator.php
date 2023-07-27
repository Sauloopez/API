<?php

require '/var/www/html/API/vendor/autoload.php';

use Carbon\Carbon;

class Validator extends Carbon{
    //Se establece los mensajes de invalidacion de los datos
    public static $NON_METHOD_PROVIDED="1";
    public static $NON_CONTROLLER_PROVIDED="2";
    public static $NON_INIT_PROVIDED="3";
    public static $NON_EXP_PROVIDED="4";
    public static $NON_CONTENT_PROVIDED="5";
    public static $NO_JSON_CONTENT="6";
    public static $NO_VALID_CONTENT= "7";

    //Se establece un mensaje de validacion de los datos
    public static $IS_VALID_CONTENT = "A";
    //Se establece un mensaje de invalidacion del tk
    public static $THAT_IS_EXPIRED = "8";
    //Se establece el array publico de codigos de error
    public static $error_codes = ["1", "2", "3", "4", "5", "6", "7", "8" ];
    
    //Valida el contenido del tk o request
    private static function reqValidator($json_request_encoded){
        $req = json_decode($json_request_encoded, true);
        if($req != null){
            if(!isset($req['method'])){
                return Validator::$NON_METHOD_PROVIDED;
            }
            if(!isset($req['controller'])){
                return Validator::$NON_CONTROLLER_PROVIDED;
            }
            if(!isset($req['init'])){
                return Validator::$NON_INIT_PROVIDED;
            }
            if(!isset($req['exp'])){
                return Validator::$NON_EXP_PROVIDED;
            }
            if(!isset($req['content'])){
                return Validator::$NON_CONTENT_PROVIDED;
            }
            return $req;
        }

        return Validator::$NO_JSON_CONTENT;
    }
    /**
     * Retorna el array asociativo de la request proporcionada la cual debe estar codificada en JSON: `$json_request_encoded`.
     * Se validan los requisitos que debe tener una request: 
     * `method`, `controller`, `init`, `exp` y `content`; si no existe alguno de ellos, devuelve un código de error.
     * 
     * @param mixed $json_request_encoded
     * @return mixed
     */
    public static function reqExT($json_request_encoded){
        $req = Validator::reqValidator($json_request_encoded);

        if(!in_array($req, Validator::$error_codes)){
            if(Carbon::now()->getTimestamp() > $req['exp']){
                return Validator::$THAT_IS_EXPIRED;
            }
        }
        return $req;
    }
    /**
     * Retorna `Validator::$NO_VALID_CONTENT` si no es válido el `$content` proporcionado para los atributos de la clase `$class_model` proporcionada.
     * En caso de que sea válido, devuelve `Validator::$IS_VALID_CONTENT`
     * @param string $class_model La clase de los atributos a validar
     * @param array $content El contenido proporcionado para validar
     * @return mixed `Validator::$NO_VALID_CONTENT` | `Validator::$IS_VALID_CONTENT`
     */
    public static function valid_content($class_model, $content){
        $vars = get_class_vars($class_model);
        foreach($vars as $k => $v){
            if(array_key_exists($k, $content)){
                if($content[$k] == '' || $content[$k] == ' '){
                    return Validator::$NO_VALID_CONTENT;
                }
            }else{
                return Validator::$NO_VALID_CONTENT;
            }
        }
        return Validator::$IS_VALID_CONTENT;
    }
}