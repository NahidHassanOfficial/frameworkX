<?php

// ----------------- Parse URI -----------------
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$baseFolder = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
if ($baseFolder !== '/' && strpos($uri, $baseFolder) === 0) {
    $uri = substr($uri, strlen($baseFolder));
}
$uri = rtrim($uri, '/'); // normalize: remove trailing slash
$uri = $uri === '' ? '/' : $uri;

// ----------------- Dispatch -----------------

$apiGroup = require __DIR__ . '/../routes/api.php';
$webGroup = require __DIR__ . '/../routes/web.php';

$routes = [];

// normalize both groups
foreach ([$apiGroup, $webGroup] as $group) {
    $prefix = rtrim($group['prefix'], '/');
    foreach ($group['routes'] as $route) {
        [$method, $path, $handler, $middleware] = $route + [null, null, null, null];
        $path = '/' . trim($path, '/');
        $fullPath = $prefix === '' || $prefix === '/' ? $path : $prefix . $path;
        $routes[] = [$method, $fullPath, $handler, $middleware];
    }
}

$found = false;

// Create Request object
$request = new Request();

$method = $_SERVER['REQUEST_METHOD'];
foreach ($routes as $route) {
    [$routeMethod, $routePattern, $handler] = $route;
    $middleware = $route[3] ?? null;

    if (strtoupper($method) !== strtoupper($routeMethod)) {
        continue;
    }

    $pattern = preg_replace('#\{[a-zA-Z_][a-zA-Z0-9_]*\}#', '([^/]+)', $routePattern);
    $pattern = "#^$pattern$#";

    if (preg_match($pattern, $uri, $matches)) {
        array_shift($matches);

        // Run middleware if exists (support multiple, with params)
        if ($middleware) {
            $middlewares = is_array($middleware) ? $middleware : [$middleware];
            foreach ($middlewares as $mw) {
                $result = true;
                if (is_callable($mw)) {
                    $result = $mw($request);
                } elseif (is_string($mw) && strpos($mw, '::') !== false) {
                    // Support 'Class::method:param1,param2' format
                    $mwParts = explode('::', $mw, 2);
                    $mwClass = $mwParts[0];
                    $methodAndParams = $mwParts[1];
                    $mwMethod = $methodAndParams;
                    $params = [];
                    if (strpos($methodAndParams, ':') !== false) {
                        [$mwMethod, $paramStr] = explode(':', $methodAndParams, 2);
                        $params = array_map('trim', explode(',', $paramStr));
                    }
                    if (class_exists($mwClass) && method_exists($mwClass, $mwMethod)) {
                        $result = $mwClass::$mwMethod($request, ...$params);
                    } else {
                        http_response_code(500);
                        echo "Middleware class or method not found!";
                        exit;
                    }
                }
                if ($result === false) {
                    $found = true; // middleware blocked, consider route handled
                    exit;          // stop execution
                }
            }
        }

        // Run controller or closure with Request object
        if (is_callable($handler)) {
            $handler($request, ...$matches);
            $found = true;
            break;
        } elseif (is_array($handler) && count($handler) === 2) {
            [$class, $func] = $handler;
            if (class_exists($class) && method_exists($class, $func)) {
                $class::$func($request, ...$matches);
                $found = true;
                break;
            } else {
                http_response_code(500);
                echo "Controller class or method not found!";
                exit;
            }
        } else {
            http_response_code(500);
            echo "Invalid route handler!";
            exit;
        }
    }
}

function handleNotFound($requestUri)
{
    if (strpos($requestUri, '/api') === 0) {
        // API route: return JSON
        header('Content-Type: application/json', true, 404);
        echo json_encode(['status' => 'error', 'message' => 'Not Found'], JSON_PRETTY_PRINT);
    } else {
        // Web route: render 404 page
        http_response_code(404);
        echo View::render('error/404');
    }
    exit;
}


if (! $found) {
    handleNotFound($uri);
}
