<?php

require '/var/www/html/API/vendor/autoload.php';

use Carbon\Carbon;

class Validator extends Carbon{
    public static $NON_METHOD_PROVIDED="1";
    public static $NON_CONTROLLER_PROVIDED="2";
    public static $NON_INIT_PROVIDED="3";
    public static $NON_EXP_PROVIDED="4";
    public static $NON_CONTENT_PROVIDED="5";
    public static $NO_JSON_CONTENT="6";
    private static $error_codes = ["1", "2", "3", "4", "5", "6", "7" ];

    public static $THAT_IS_EXPIRED = "7";

    
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
     * Retorna el array asociativo de la request proporcionada la cual debe estar codificada en JSON.
     * Se validan los requisitos que debe tener una request: 
     * method, controller, init, exp y content; si no existe alguno de ellos, se retorna un código de error :).
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
}