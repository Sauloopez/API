<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Method: GET');

include_once (__DIR__.'/../../Models/User.php');
include_once (__DIR__.'/../../Config/Database/Con.php');

if($_SERVER['METHOD']= 'POST'){
    $data = json_decode(file_get_contents('php://input'), true);
    //Se evalúan si los datos no están vacíos y a su vez, no sean nulos...
    $user = isset($data['user']) && strlen($data['user']) > 0 ? $data['user'] : die(false);
    $password = isset($data['password']) && strlen($data['password']) > 0 ? $data['password'] : die(false);
    
    $User = new User($user, $password);
    if(User::CREATE($User, $conn)){
        return true;
    }else{
        return false;
    }
}