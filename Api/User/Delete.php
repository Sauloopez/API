<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Method: GET');

include_once (DIR.'/Models/User.php');
include_once (DIR.'/config/Database/Con.php');

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $data = data['content'];
    //Se evalúan si los datos no están vacíos y a su vez, no sean nulos...
    $user = isset($data['user']) && strlen($data['user']) > 0 ? $data['user'] : die(false);
    $password = isset($data['password']) && strlen($data['password']) > 0 ? $data['password'] : die(false);
    
    $User = new User($user, $password);
    $delete = User::DELETE($User, $conn) ;
    if($delete::class == 'Error'){
        echo json_encode(['message' => 'Datos inválidos']);
        $User->__destruct();
        return false;
    }else{
        echo json_encode(['message' => 'Usuario: '.$delete->getUser().' eliminado']);
        $User->__destruct();
        $delete->__destruct();
        return true;
    }
}