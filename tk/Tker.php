<?php

class Tker
{
    
    private static $secret = 'f1a7a54581b3c9d1727a5395f95579fc2e89989a372c72ac9e189e0b8e5d2599';

    /**
    *Variación del campo $iv:
    *Para desencriptar el contenido, se especifica el IV.
    *Para desencriptar el IV, se deja en null
    *Devuelve un string del contenido desencriptado
    */
    public static function sslurldecoder($text, $iv = null)
    {
        $iv = ($iv == null) ? 'Sondieciseiskeys': $iv;        
        $method = "camellia-128-cfb";
        $encode = str_replace(
            ['-', '_', '*'],
            ['+', '/', '='],
            $text
        );

        return openssl_decrypt($encode, $method, Tker::$secret, null, $iv);
    }


    /**
    *Variación del campo $with_iv:
    *Para encriptar el contenido debe ser true, se construye un IV de manera aleatoria.
    *Para la encriptación del IV se deja en false.
    *Devuelve un array de dos campos, uno con el contenido cifrado y el otro con el IV
    */
    public static function sslurlencoder($text, $with_iv = true)
    {
        $method = "camellia-128-cfb";

        $iv = $with_iv ?
            openssl_random_pseudo_bytes(openssl_cipher_iv_length($method)) :
            'Sondieciseiskeys';

        return [
            'content' => str_replace(
                ['+', '/', '='],
                ['-', '_', '*'],
                openssl_encrypt($text, $method, Tker::$secret, null, $iv)
            ),
            'iv' => $iv
        ];
    }
}
