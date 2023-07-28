<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Method: GET');

include_once (DIR.'/Models/Person.php');
include_once (DIR.'/config/Database/Con.php');
include_once (DIR.'/Modules/Validator.php');

if($_SERVER['REQUEST_METHOD'] == 'GET' && isset(data['content'])){
    $data= data['content'];
    
    if(Validator::valid_content('Person', $data) == Validator::$IS_VALID_CONTENT){
        $person = new Person ($data['name'],
        $data['last_name'], $data['age'], $data['address'], $data['country'],
        $data['state'], $data['mobile'], $data['user']);
        $value = Person::CREATE($person, $conn);
        if($value::class == 'Error'){
            echo json_encode(['message' => 'Se han establecido los datos del usuario']);
        }else{
            echo json_encode(['message' => 'Este usuario ya tiene datos establecidos']);
            $value->__destruct();
        }

        $person->__destruct();
        return true;

    }else{
        echo json_encode(['message' => 'Datos erroneos']);
    }
    
}else{
    echo json_encode(['message' => 'Peticion equivoca']);
    return false;
}