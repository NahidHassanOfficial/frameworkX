<?php

class HomeController
{
    public static function index()
    {
       return include_once(__DIR__ .'/../views/Home.php');
    }

    public static function profile($id)
    {
        echo "User ID: $id";
    }
    public static function about()
    {
       return include_once(__DIR__ .'/../views/About.php');
    }
}
