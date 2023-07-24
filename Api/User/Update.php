<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Method: GET');
header('Access-Control-Allow-Origin: *');

include_once(DIR . '/Models/User.php');
include_once(DIR . '/config/Database/Con.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $data = data['content'];
    //Se evalúan si los datos no están vacíos y a su vez, no sean nulos...
    $antiguo = array(
        'user' => 
            isset($data['user']) && strlen($data['user']) > 0 ?
            $data['user'] : 
            die(false),
        'password' => 
            isset($data['old_password']) && strlen($data['old_password']) > 0 ? 
            $data['old_password'] : 
            die(false)
    );

    $nuevo = array(
        'user' => 
            isset($data['user']) && strlen($data['user']) > 0 ?
            $data['user'] :
            die(false),
        'password' =>
            isset($data['new_password']) && strlen($data['new_password']) > 0 ?
            $data['new_password'] :
            die(false)
    );

    $userA= new User($antiguo['user'], $antiguo['password']);
    $userN= new User($nuevo['user'], $nuevo['password']);

    $update = User::UPDATE($userN, $userA, $conn);

    if($update != null){
        if($update::class == 'User'){
            echo json_encode(['message' => 'Usuario: '.$update->getUser(). ', actualizado']);
            return true;
        }else{
            echo json_encode(['message' => 'Datos inválidos']);
            return false;
        }
    }

}