<?php

class View
{
    public static function render($view, $data = [])
    {
        // make variables from associative array; will be accessible in the view
        extract($data);

        // build file path
        $file = __DIR__ . "/../views/$view.php";

        if (file_exists($file)) {
            include $file;
        } else {
            throw new Exception("View file not found: $view");
        }
    }
}
