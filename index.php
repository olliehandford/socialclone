<?php 
session_start();

spl_autoload_register(function ($class_name) {
    if(file_exists('./classes/' . $class_name . '.php'))
    {
        include './classes/' . $class_name . '.php';
    } 
    else if(file_exists('./controllers/' . $class_name . '.php'))
    {
        include './controllers/' . $class_name . '.php';
    }
    
});

define('SMTP_PRIMARY_EMAIL', 'cd2f89acf41290');
define('SMTP_PRIMARY_PASSWORD', '5ba4d79b109fee');

require_once('routes.php');

