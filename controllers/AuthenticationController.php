<?php

class AuthenticationController
{
    public static function login(Request $request)
    {
       return Response::success('Request successful', $request);
    }

    public static function logout()
    {
       
    }
}
