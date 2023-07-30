<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Method: GET');

include_once (DIR.'/Models/Person.php');
include_once (DIR.'/config/Database/Con.php');
include_once (DIR.'/Modules/Validator.php');

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $data = data['content'];

    $person = new Person();
    $person->withUser(isset($data['user'])&& count(explode('@', $data['user']))>1 
    ? 
      $data['user'] 
    : 
      die("Usuario no valido"));
    $person = Person::READ($person, $conn);
    if($person::class != 'Person') die('No existe el usuario');
    $a=0;
    foreach(get_class_vars('Person') as $k => $v){
        if(key_exists($k, $data) && $person->$k != $data[$k]){
            $person->$k = $data[$k];
            $a++;
        }
    }
    if($a >0){
        $update = Person::UPDATE($person, $conn);
        if($update::class != 'Error'){
            echo json_encode(['message' => 'Se ha actualizado con exito']);
        }
        return true;
    }

    die('No hay nada para actualizar');
}else{
    echo json_encode(['message' => 'Peticion equivoca']);
    return false;
}