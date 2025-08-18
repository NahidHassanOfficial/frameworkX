<?php
return [
    // [METHOD, ROUTE, [Controller, Method], Middleware]

    ['POST', '/login', ['AuthenticationController', 'login']],
    ['POST', '/logout', ['AuthenticationController', 'logout']],

    ['GET', '/about', ['HomeController', 'about']],
    ['GET', '/user/{id}', ['HomeController', 'profile'], 'AuthMiddleware::check'],
];
