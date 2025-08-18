<?php

class HomeController
{
    public static function index()
    {
        return View::render('Home');
    }

    public static function profile(Request $request, $id, $param)
    {
        echo "User ID: $id";
    }
    public static function about()
    {
        return View::render('About');
    }
}
