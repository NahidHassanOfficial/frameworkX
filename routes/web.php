<?php
return [
    // [METHOD, ROUTE, [Controller, Method], [Middleware::method:params]]
    'prefix' => '/',
    'routes' => [
        ['GET', '/', ['HomeController', 'index']],
        ['GET', '/profile/{id}/{param}', ['HomeController', 'profile'], 'AuthMiddleware::check'],
        ['GET', '/about', ['HomeController', 'about'], ['AuthMiddleware::check', 'RBACMiddleware::allow:[123,133]']],
        ['GET', '/404', function () {
            return View::render('error/404');
        }],
    ],
];
