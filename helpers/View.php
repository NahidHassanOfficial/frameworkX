<?php

class View
{
    public static function render($view, $data = [], $layout = 'layout')
    {
        extract($data);

        // build file path; $view contains the filename with path
        $renderViewFile = __DIR__ . "/../views/$view.php";
        if (!file_exists($renderViewFile)) {
            throw new Exception("View file not found: $view");
        }

        // Capture view output into $content
        ob_start();
        include $renderViewFile;
        $content = ob_get_clean();

        // If a layout is specified, render it with $content
        if ($layout) {
            $layoutFile = __DIR__ . "/../views/$layout.php";
            if (!file_exists($layoutFile)) {
                throw new Exception("Layout file not found: $layout");
            }
            include $layoutFile;
        } else {
            echo $content; // no layout, just raw view
        }
    }
}
