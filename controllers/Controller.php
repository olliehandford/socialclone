<?php 

class Controller 
{
    public static function view($name)
    {
        require_once("./views/" . $name . ".php");
    }
}