<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Method: POST');
header('Access-Control-Allow-Origin: *');

include_once(__DIR__ . '/../../Models/User.php');
include_once(__DIR__ . '/../../config/Database/Con.php');
include_once(__DIR__ . '/../../tk/Tker.php');



if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    
    //Se evalúan si los datos no están vacíos y a su vez, no sean nulos...
    
    $user = User::READ(
        new User(
            isset(data['content']['user']) && strlen(data['content']['user']) > 0 
            ? 
                data['content']['user'] 
                : 
                    die(false)
            ,isset(data['content']['password']) && strlen(data['content']['password']) > 0 
            ?
                data['content']['password'] 
                :
                    die(false)
        ), $conn);
    if($user == User::$DOESNT_EXISTS || $user == User::$EXISTS){
        echo json_encode(array(
            'message' => 'Datos incorrectos '.$data['user']
        ));
    }else{
        echo json_encode(array(
            'user' => $user->getUser(),
            'password' => $user->getPassword()
        ));
    }
}else{
    echo json_encode(array('message' => 'petición equívoca'));
}