<?php
return [
    // [METHOD, ROUTE, [Controller, Method], Middleware]
    'prefix' => '/api/v1/',
    'routes' => [
        ['POST', '/login', ['AuthenticationController', 'login']],
        ['POST', '/logout', ['AuthenticationController', 'logout']],

        ['GET', '/user/{id}/profile/{param}', ['HomeController', 'profile']],
    ]
];
