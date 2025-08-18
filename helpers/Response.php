<?php

//Response class with static methods to dynamically send response with status code and data
class Response
{
    private static function sendResponse($data, int $code)
    {
        header('Content-Type: application/json'); // Ensure proper JSON header
        http_response_code($code); // Set HTTP response status code
        echo json_encode($data, JSON_PRETTY_PRINT);
    }


    public static function success($msg = "Request successful", $data = [], $code = 200)
    {
        self::sendResponse([
            'status'  => 'success',
            'message' => $msg,
            'data'    => $data,
        ], $code);
    }

    public static function failed($msg = "Request failed", $code = 400)
    {
        self::sendResponse([
            'status'  => 'failed',
            'message' => $msg,
        ], $code);
    }

    public static function error($data = [], $code = 400)
    {
        self::sendResponse([
            'status'  => 'error',
            'message' => 'Something went wrong',
            'data'    => $data,
        ], $code);
    }
}
