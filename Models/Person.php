<?php

include_once "User.php";
include_once "/var/www/html/API/config/Database/Con.php";



class Person
{
    public $name;
    public $last_name;
    public $age;
    public $address;
    public $country;
    public $state;
    public $mobile;

    public $user;

    private Error $EXISTS;
    private Error $DOESNT_EXISTS;
    private Error $USER_DOESNT_EXISTS;

    //Constructor
    public function __construct(
        $NAME = "",
        $LAST_NAME = "",
        $AGE = "",
        $ADDRESS = "",
        $COUNTRY = "",
        $STATE = "",
        $MOBILE = "",
        $USER = ""
    ) {
        $this->name = $NAME;
        $this->last_name = $LAST_NAME;
        $this->age = $AGE;
        $this->address = $ADDRESS;
        $this->country = $COUNTRY;
        $this->state = $STATE;
        $this->mobile = $MOBILE;
        $this->user = $USER;
        $this->EXISTS = new Error('La persona existe', 1);
        $this->DOESNT_EXISTS = new Error('La persona no existe', 0);
        $this->USER_DOESNT_EXISTS = new Error('El usuario asociado no existe', 2);
    }

    public function __destruct()
    {

    }

    /**
     * @return Error|Person
     * Retorna Error o Persona, si es la persona, es por que existe.
     * Se debe especificar todo el objeto persona.
     */
    public static function CREATE(Person $PERSON, PDO $conn)
    {
        $query =
            "INSERT INTO Persons
        (name, last_name, age, address, country, state, mobile, user) " .
            "VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($query);
        $user = new User($PERSON->getUser());
        $value = $PERSON::READ($PERSON, $conn);
        $valid_user = User::READ($user, $conn);
        if ($valid_user == $user->EXISTS) {
            if ($value::class == 'Error') {
                $stmt->execute(
                    array(
                        $PERSON->name,
                        $PERSON->last_name,
                        $PERSON->age,
                        $PERSON->address,
                        $PERSON->country,
                        $PERSON->state,
                        $PERSON->mobile,
                        $PERSON->user
                    )
                );
                return $PERSON->DOESNT_EXISTS;
            }
        } else {
            return $PERSON->USER_DOESNT_EXISTS;
        }

        return $value;
    }

    /**
     * @return Person | Error
     * Retorna la persona a leer.
     * Se debe especificar al menos el id (user)
     * Salida de '0' si no existe, '1' si existe.
     */
    public static function READ(Person $PERSON, PDO $conn)
    {
        $query =
            "SELECT
        name, last_name, age, address, country, state, mobile, user " .
            "FROM Persons WHERE user = ? ";

        $stmt = $conn->prepare($query);

        $stmt->execute(array($PERSON->getUser()));

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        //Si existe la fila usuario
        if (isset($row['user']))
            //Si usuario es igual al usuario proporcionado
            if ($row['user'] == $PERSON->getUser()) {
                return new Person(
                    $row['name'],
                    $row['last_name'],
                    $row['age'],
                    $row['address'],
                    $row['country'],
                    $row['state'],
                    $row['mobile'],
                    $row['user']
                );
            }
        //Si la persona con ese usuario no existe...
        return $PERSON->DOESNT_EXISTS;
    }
    /**
     * @return array
     * Retorna un array asociativo con la lista de las personas.
     */
    public static function LIST(PDO $conn)
    {
        $query = "SELECT name, last_name, age, address, country, state, mobile, user FROM Persons";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $persons = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $person = array(
                'name' => $name,
                'last_name' => $last_name,
                'age' => $age,
                'address' => $address,
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
     * @return Error | Person
     * Retorna true si se ejecuta y false si no.
     * Se debe especificar todo el objeto persona, incluso el usuario y contraseña.
     */
    public static function UPDATE(Person $PERSON, PDO $conn)
    {
        $query =
            "UPDATE Persons SET
        name= ?, last_name= ?, age= ?, address= ?, country= ?, state= ?, mobile= ?" .
            " WHERE user = ? ";

        $stmt = $conn->prepare($query);

        $value = PERSON::READ($PERSON->getUser(), $conn);
        if ($value::class != 'Error') {
            $stmt->execute(
                array(
                    $PERSON->name,
                    $PERSON->last_name,
                    $PERSON->age,
                    $PERSON->address,
                    $PERSON->country,
                    $PERSON->state,
                    $PERSON->mobile,
                    $PERSON->getUser()
                )
            );
        }
        return $value;
    }



    /**
     * @return Error | Person
     * Retorna true si se ejecuta, false si no.
     */
    public static function DELETE(Person $PERSON, PDO $conn)
    {
        $query = "DELETE FROM Persons WHERE user= ?";

        $stmt = $conn->prepare($query);

        $value = Person::READ($PERSON, $conn);
        if ($value::class == 'Error') {
            return $PERSON->DOESNT_EXISTS;
        }

        $stmt->execute(array($PERSON->getUser()));
        return $value;
    }

    public function withUser($user)
    {
        $this->user = $user;
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