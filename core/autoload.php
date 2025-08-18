<?php

/* ----------------- Auto-load controllers and middleware ----------------- */
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . "/../controllers/$class.php",
        __DIR__ . "/../middlewares/$class.php",
        __DIR__ . "/../helpers/$class.php",
        __DIR__ . "/../services/$class.php",
    ];
    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
