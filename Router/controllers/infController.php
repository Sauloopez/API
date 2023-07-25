<?php

class infController{

    public static function info(){
        header('Content-Type: application/json');
        echo json_encode(array(
            'Title' => 'Welcome to myAPI RESTful',
            'About of project' =>[
                'Version' => '0.0.1',
                'Birth' => 'First commit at Tue Jul 11 12:06:16 2023 -0500',
                'About of' => 'This API receive encrypted requests via the GET method',
            ],
            'About of creator' => [
                'Name' => 'Saul Lopez',
                'Email' => 'sauleta.selb@gmail.com',
                'GitHub' => 'https://github.com/Sauloopez',
                'Ocupation' => 'Systems engineer student from Lejanías, Meta-Colombia'
            ],
        ));
    }
}