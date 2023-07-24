<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Method: GET');
header('Access-Control-Allow-Origin: *');

include_once(__DIR__ . '/../../Models/User.php');
include_once(__DIR__ . '/../../config/Database/Con.php');



if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    
    //Se evalúan si los datos no están vacíos y a su vez, no sean nulos...
    
    $user =new User(
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
    );

    $read = User::READ($user, $conn);

    if($read == $user->DOESNT_EXISTS || $read == $user->EXISTS){
        echo json_encode(array(
            'message' => 'Datos incorrectos '
        ));
    }else{
        echo json_encode(array(
            'user' => $read->getUser(),
            'password' => $read->getPassword()
        ));
    }
}else{
    echo json_encode(array('message' => 'petición equívoca'));
}