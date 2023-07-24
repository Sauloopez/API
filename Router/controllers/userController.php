<?php

class userController{
    public static function create(){
        require_once (DIR.'/Api/User/Create.php');
    }

    public static function read(){
        require_once(DIR.'/Api/User/Read.php');
    }

    public static function update(){
        require_once(DIR.'/Api/User/Update.php');
    }

    public static function delete(){
        require_once(DIR.'/Api/User/Delete.php');
    }
}