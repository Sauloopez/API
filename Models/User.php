<?php

class User{
    private $user;
    private $password;

    public Error $EXISTS;
    public Error $DOESNT_EXISTS;

    public function __construct($USER= "", $PASSWORD=""){
        $this->user=$USER;
        $this->password=$PASSWORD;
        $this->EXISTS = new Error('El usuario existe', 1);
        $this->DOESNT_EXISTS = new Error('El usuario no existe', 0);
    }

    public function __destruct(){

    }

    //Getters
    public function getUser(){
        return $this->user;
    }

    public function getPassword(){
        return $this->password;
    }

    public static function CREATE(User $USER, PDO $conn){
        $query = "INSERT INTO Users (user, password) ".
        "VALUES (?, ?)";

        $stmt = $conn->prepare($query);

        $value = User::READ($USER, $conn);
        if($value == $USER->DOESNT_EXISTS){
            $stmt->execute(array($USER->getUser(), $USER->getPassword()));
            return $USER->DOESNT_EXISTS;
        }

        return $USER->EXISTS;
    }

    public static function READ(User $USER, PDO $conn){
        $query="SELECT user, password FROM Users WHERE user = ? LIMIT 0,1";

        $stmt = $conn->prepare($query);

        $stmt->execute(array($USER->getUser()));

        $row= $stmt->fetch(PDO::FETCH_ASSOC);
        //Si existe la fila usuario
        if(isset($row['user']))
        //Si usuario es igual al usuario proporcionado
        if($row['user'] == $USER->getUser()){
            //Si la contraseña es igual a la proporcionada
            if($row['password']== $USER->getPassword()){
                //Retorna el usuario
                return new User($row['user'], $row['password']);
            }
            //Si el usuario existe
            return $USER->EXISTS;
        }
        //Si el usuario no existe
        return $USER->DOESNT_EXISTS;
    }

    /**
     * Actualiza un usuario
     * @param User $USER Los parámetros nuevos del usuario.
     * @param User $AUTH Los parámetros viejos del usuario
     * @param PDO $conn El PDO conexion a la DB.
     * @return Error|null|User Retorna nulo, si no hay autenticación.
     */
    public static function UPDATE(User $USER, User $AUTH, PDO $conn){
        $query="UPDATE Users SET password= ? WHERE user = ?";
        //Si los dos usuarios son iguales
        if($USER->getUser() == $AUTH->getUser()){
            //Se hace autenticacion del usuario y contraseña
            $userA = User::READ($USER, $conn);
            //Si no se devuelve un objeto usuario, hay error en la autenticacion...
            if($userA::class == 'Error'){
                //y por lo tanto ha de retornar nulo
                return null;
            }
            $userA->__destruct();
        }else{
            //En caso de que los usuarios no sean iguales, falla la autenticacion
            return null;
        }
        $stmt= $conn->prepare($query);

        $value = User::READ($AUTH, $conn);
        if($value::class != 'User'){
            $stmt->execute(array($USER->getPassword(), $USER->getUser()));
        }
        return $value;
    }

    public static function DELETE(User $USER, PDO $conn){
        $query="DELETE FROM Users WHERE user= ? AND password = ?";

        $stmt= $conn->prepare($query);

        $value = User::READ($USER, $conn);
        if($value::class == 'Error'){
            return $USER->DOESNT_EXISTS;
        }

        $stmt->execute(array($USER->getUser(), $USER->getPassword()));
        return $USER;
    }
}