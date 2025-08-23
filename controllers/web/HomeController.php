<?php

class HomeController
{
    public static function index()
    {
        global $DB;
        $user1 = $DB->result_one("SELECT fname, lname FROM user WHERE id = 1");
        return View::render('pages/Home', ['user' => $user1]);
    }

    public static function profile(Request $request, $id, $param)
    {
        echo "User ID: $id";
    }
    public static function about()
    {
        return View::render('pages/About');
    }
}
