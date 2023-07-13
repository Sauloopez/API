<?php


$conn= Conection::conn(
    //Modulo PDO que se va a utilizar, ejm:mysql, postgres, etc
    'mysql', 
    //Host de la base de datos
    'localhost', 
    //Usuario de la base de datos
    'root', 
    //Contraseña de la base de datos
    'Saulopez1.', 
    //Nombre de la base de datos
    'API'
);


class Conection{
    private PDO $CONN;

    public function __construct(PDO $conn = null){
        $this->CONN= $conn;
    }

    public static function conn($module, $host, $user, $password, $database){
        try{
            $conn= new Conection( new PDO($module.':host='.$host.';dbname='.$database, 
            $user, $password));
        }catch(PDOException $exp){
            echo 'Error'.$exp->getMessage();
            return null;
            
        }

        return $conn->getConn();
    }

    private function getConn(){
        return $this->CONN;
    }
}