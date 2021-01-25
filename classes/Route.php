<?php 
class Route {

    public static $validRoutes = [];

    public static function add($route, $callback) 
    {
        self::$validRoutes[] = $route;

        if($_GET['url'] == $route) 
        {
            $callback->__invoke();
        }
    }
}