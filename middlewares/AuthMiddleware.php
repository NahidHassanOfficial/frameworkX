<?php
class AuthMiddleware
{
    public static function check()
    {
        $allowed_origin =  $_SERVER['HTTP_ORIGIN'] ?? '';

        header("Access-Control-Allow-Origin: $allowed_origin");
        header("Access-Control-Allow-Credentials: true");
        header("Content-Type: application/json; charset=UTF-8");

        if (isset($_SESSION['uhid']) && isset($_SESSION['pportal'])) {
            return true;
        } else {
            Response::failed('You are not logged in', 401);
            exit; // prevent further script execution
        }
    }
}
