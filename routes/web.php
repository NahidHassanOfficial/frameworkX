<?php
return [
    // [METHOD, ROUTE, [Controller, Method], Middleware]
    'prefix' => '/',
    'routes' => [
        ['GET', '/', ['HomeController', 'index']],
        ['GET', '/about', ['HomeController', 'about']],
    ]
];
