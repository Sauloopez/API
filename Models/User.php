<?php

class User{
    private $user;
    private $password;

    public static $EXISTS = '1';
    public static $DOESNT_EXISTS = '0';

    public function __construct($USER= "", $PASSWORD=""){
        $this->user=$USER;
        $this->password=$PASSWORD;
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
        if($value != User::$DOESNT_EXISTS){
            return User::$EXISTS;
        }

        return $stmt->execute(array($USER->getUser(), $USER->getPassword()));
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
            return User::$EXISTS;
        }
        //Si el usuario no existe
        return User::$DOESNT_EXISTS;
    }

    public static function UPDATE(User $USER, User $AUTH, PDO $conn){
        $query="UPDATE Users SET password= ? WHERE user = ?";

        if($USER->getUser() != $AUTH->getUser()){
            return null;
        }
        $stmt= $conn->prepare($query);

        $value = User::READ($AUTH, $conn);
        if($value == User::$DOESNT_EXISTS){
            return User::$DOESNT_EXISTS;
        }elseif($value == User::$EXISTS ){
            return false;
        }

        return $stmt->execute(array($USER->getPassword(), $USER->getUser()));
    }

    public static function DELETE(User $USER, PDO $conn){
        $query="DELETE FROM Users WHERE user= ? AND password = ?";

        $stmt= $conn->prepare($query);

        $value = User::READ($USER, $conn);
        if($value == User::$DOESNT_EXISTS){
            return User::$DOESNT_EXISTS;
        }

        if($value == User::$EXISTS){
            return User::$EXISTS;
        }

        return $stmt->execute(array($USER->getUser(), $USER->getPassword()));
    }
}