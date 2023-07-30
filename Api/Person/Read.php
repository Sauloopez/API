<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Method: GET');

include_once (DIR.'/Models/Person.php');
include_once (DIR.'/config/Database/Con.php');
include_once (DIR.'/Modules/Validator.php');

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $data= data['content'];
    
    $person= new Person();
    $person->withUser(
        isset($data['user']) && count(explode('@', $data['user']))>1 ? $data['user'] : die('no valid e-mail')
    );

    $read = Person::READ($person, $conn);
    $person->__destruct();
    if($read::class == 'Person'){
        echo json_encode([
            'name' => $read->name,
            'last_name' => $read->last_name,
            'age' => $read->age,
            'address' => $read->address,
            'country' => $read->country,
            'state' => $read->state,
            'mobile' => $read->mobile,
            'user' => $read->user
        ]);
        $read->__destruct();
        return true;
    }else{
        echo json_encode(['message' => 'No hay datos del usuario: '.$data['user']]);
        return false;
    }
}else{
    echo json_encode(['message' => 'Petición equívoca']);
    die();
}