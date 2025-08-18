<?php
class Request {
    public $method;
    public $uri;
    public $query;
    public $body;
    public $headers;

    public function __construct() {
        $this->method  = $_SERVER['REQUEST_METHOD'];
        $this->uri     = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->query   = $_GET;
        $this->headers = function_exists('getallheaders') ? getallheaders() : [];

        // Support both JSON & form-data
        $input = file_get_contents("php://input");
        $decoded = json_decode($input, true);

        if (is_array($decoded)) {
            $this->body = $decoded;
        } else {
            $this->body = $_POST;
        }
    }
}
