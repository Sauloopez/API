<?php
//Se define el paquete en donde está ubicado
$folderPath = dirname($_SERVER['SCRIPT_NAME']);
//Esta es la request uri del usuario
$urlPath = $_SERVER['REQUEST_URI'];
//esta es la url sin el paquete
$url = substr($urlPath, strlen($folderPath.'/'));

//se define la url a asignar, ejm path/method
define('URL', $url);
