<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Method: GET');

include_once (DIR.'/Models/Person.php');
include_once (DIR.'/config/Database/Con.php');
include_once (DIR.'/Modules/Validator.php');

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    echo json_encode(Person::LIST($conn));
}