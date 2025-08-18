<?php

// ----------------- Parse URI -----------------
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$baseFolder = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($baseFolder !== '/' && strpos($uri, $baseFolder) === 0) {
    $uri = substr($uri, strlen($baseFolder));
}
$uri = rtrim($uri, '/');       // normalize: remove trailing slash
$uri = $uri === '' ? '/' : $uri;


// ----------------- Dispatch -----------------
$method = $_SERVER['REQUEST_METHOD'];

$apiGroup  = require __DIR__ . '/../routes/api.php';
$webGroup  = require __DIR__ . '/../routes/web.php';

$routes = [];

// normalize both groups
foreach ([$apiGroup, $webGroup] as $group) {
    $prefix = rtrim($group['prefix'], '/');
    foreach ($group['routes'] as $route) {
        [$m, $path, $handler, $middleware] = $route + [null, null, null, null];
        $path = '/' . trim($path, '/');
        $fullPath = $prefix === '' || $prefix === '/' ? $path : $prefix . $path;
        $routes[] = [$m, $fullPath, $handler, $middleware];
    }
}

$found = false;

// Create Request object
$request = new Request();

foreach ($routes as $route) {
    [$routeMethod, $routePattern, [$class, $func]] = $route;
    $middleware = $route[3] ?? null;

    if (strtoupper($method) !== strtoupper($routeMethod)) continue;

    $pattern = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\}#', '([^/]+)', $routePattern);
    $pattern = "#^$pattern$#";

    if (preg_match($pattern, $uri, $matches)) {
        array_shift($matches);

        // Run middleware if exists
        if ($middleware) {
            $result = true;

            if (is_callable($middleware)) {
                $result = $middleware();
            } elseif (is_string($middleware) && strpos($middleware, '::') !== false) {
                [$mwClass, $mwMethod] = explode('::', $middleware);
                if (class_exists($mwClass) && method_exists($mwClass, $mwMethod)) {
                    $result = $mwClass::$mwMethod();
                } else {
                    http_response_code(500);
                    echo "Middleware class or method not found!";
                    exit;
                }
            }

            if ($result === false) {
                $found = true; // middleware blocked, consider route handled
                exit; // stop execution
            }
        }

        // Run controller with Request object
        if (class_exists($class) && method_exists($class, $func)) {
            // Pass Request object as first parameter, followed by route parameters
            $class::$func($request, ...$matches);
            $found = true;
            break;
        } else {
            http_response_code(500);
            echo "Controller class or method not found!";
            exit;
        }
    }
}

if (!$found) {
    http_response_code(404);
    echo "404 Not Found";
}
