<?php

class Tker{
    private static $secret= 'f1a7a54581b3c9d1727a5395f95579fc2e89989a372c72ac9e189e0b8e5d2599';

    public static function sslurldecoder($text){
        
        $method = "camellia-128-cfb";
        $encode= str_replace(
            ['-', '_', '*'],
            ['+', '/', '='],
            $text
        );
    
        return openssl_decrypt($encode, $method, Tker::$secret, null, 'Sondieciseiskeys');
    }

    public static function sslurlencoder($text){
        
        $method = "camellia-128-cfb";
        return str_replace(
            ['+', '/', '='],
            ['-', '_', '*'],
            openssl_encrypt($text, $method, Tker::$secret, null, 'Sondieciseiskeys')
        );
    }
}
