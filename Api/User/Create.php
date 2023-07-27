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
    $create = User::CREATE($User, $conn) ;
    if($create == $User->DOESNT_EXISTS){
        echo json_encode(['message' => 'Usuario creado']);
        $User->__destruct();
        return true;
    }else{
        echo json_encode(['message' => 'El usuario ya existe']);
        $User->__destruct();
        return false;
    }
}