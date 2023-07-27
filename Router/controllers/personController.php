<?php

class personController{
    public static function create(){
        require_once (DIR.'/Api/Person/Create.php');
    }

    public static function read(){
        require_once (DIR.'/Api/Person/Read.php');
    }

    public static function update(){
        require_once (DIR.'/Api/Person/Update.php');
    }

    public static function delete(){
        require_once (DIR.'/Api/Person/Delete.php');
    }

    public static function list(){
        require_once (DIR.'/Api/Person/List.php');
    }
}