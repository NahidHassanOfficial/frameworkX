<?php

class AuthenticationController
{
    public static function login(Request $request)
    {
        $req = $request->body;
        return Response::success('Request successful', $req->name);
    }

    public static function logout()
    {
        return Response::success('Request successful');
    }
}