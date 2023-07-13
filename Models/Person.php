<?php

include_once "User.php";
include_once "/var/www/html/API/config/Database/Con.php";



class Person
{
    public $name;
    public $last_name;
    public $age;
    public $adress;
    public $country;
    public $state;
    public $mobile;

    public $user;

    //Constructor
    public function __construct(
        $NAME = "",
        $LAST_NAME = "",
        $AGE = "",
        $ADRESS = "",
        $COUNTRY = "",
        $STATE = "",
        $MOBILE = "",
        $USER = ""
    ) {
        $this->name = $NAME;
        $this->last_name = $LAST_NAME;
        $this->age = $AGE;
        $this->adress = $ADRESS;
        $this->country = $COUNTRY;
        $this->state = $STATE;
        $this->mobile = $MOBILE;
        $this->user = $USER;
    }

    /**
     * @return bool
     * Retorna true si se ejecuta y false si no.
     * Se debe especificar todo el objeto persona.
     */
    public static function CREATE(Person $PERSON, PDO $conn)
    {
        $query =
            "INSERT INTO Persons
        (name, last_name, age, adress, country, state, mobile, user) " .
            "VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($query);

        $value = $PERSON::READ($PERSON->getUser(), $conn);
        if ($value != User::$DOESNT_EXISTS) {
            return User::$EXISTS;
        }

        return $stmt->execute(
            array(
                $PERSON->name,
                $PERSON->last_name,
                $PERSON->age,
                $PERSON->adress,
                $PERSON->country,
                $PERSON->state,
                $PERSON->mobile,
                $PERSON->user
            )
        );
    }

    /**
     * @return Person | string
     * Retorna la persona a leer.
     * Se debe especificar todo el objeto persona.
     * Salida de '0' si no existe, '1' si existe.
     */
    public static function READ($USER, PDO $conn)
    {
        $query =
            "SELECT
        name, last_name, age, adress, country, state, mobile, user " .
            "FROM Persons WHERE user = ? ";

        $stmt = $conn->prepare($query);

        $stmt->execute(array($USER));

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        //Si existe la fila usuario
        if (isset($row['user']))
            //Si usuario es igual al usuario proporcionado
            if ($row['user'] == $USER) {
                return new Person(
                    $row['name'],
                    $row['last_name'],
                    $row['age'],
                    $row['adress'],
                    $row['country'],
                    $row['state'],
                    $row['mobile'],
                    $row['user']
                );
            }
        //Si el usuario no existe
        return User::$DOESNT_EXISTS;
    }
    /**
     * @return array
     * Retorna un array asociativo con la lista de las personas.
     */
    public static function LIST(PDO $conn){
        $query= "SELECT name, last_name, age, adress, country, state, mobile, user FROM Persons";

        $stmt= $conn->prepare($query);

        $stmt->execute();

        $persons = array();
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $person = array(
                'name' =>$name,
                'last_name' => $last_name,
                'age' => $age,
                'adress' => $adress,
                'country' => $country,
                'state' => $state,
                'mobile' => $mobile,
                'user' => $user
            );

            array_push($persons, $person);
        }

        return $persons;
    }

    /**
     * @return bool
     * Retorna true si se ejecuta y false si no.
     * Se debe especificar todo el objeto persona, incluso el usuario y contraseña.
     */
    public static function UPDATE(Person $PERSON, PDO $conn)
    {
        $query =
            "UPDATE Persons SET
        name= ?, last_name= ?, age= ?, adress= ?, country= ?, state= ?, mobile= ?" .
            " WHERE user = ? ";

        $stmt = $conn->prepare($query);

        $value = PERSON::READ($PERSON->getUser(), $conn);
        if ($value == User::$DOESNT_EXISTS) {
            return User::$DOESNT_EXISTS;
        } elseif ($value == User::$EXISTS) {
            return false;
        }

        return $stmt->execute(
            array(
                $PERSON->name,
                $PERSON->last_name,
                $PERSON->age,
                $PERSON->adress,
                $PERSON->country,
                $PERSON->state,
                $PERSON->mobile,
                $PERSON->getUser()
            )
        );
    }

    /**
     * @return bool
     * Retorna true si se ejecuta, false si no.
     */
    public static function DELETE($USER, PDO $conn)
    {
        $query = "DELETE FROM Persons WHERE user= ?";

        $stmt = $conn->prepare($query);

        $value = Person::READ($USER, $conn);
        if ($value == User::$DOESNT_EXISTS) {
            return User::$DOESNT_EXISTS;
        }

        return $stmt->execute(array($USER));
    }


    /**
     * @return mixed
     * Retorna el usuario de la persona
     */
    public function getUser()
    {
        return $this->user;
    }
}