<?php
require_once __DIR__ . '/config.php';

require_once __DIR__ . '/services/ErrorHandler.php';
new ErrorHandler(constant('IS_PRODUCTION'));

require_once __DIR__ . '/core/autoload.php';
require_once __DIR__ . '/core/cors.php';

if (constant('IS_MAINTENANCE')) {
    View::render('error/503');
    exit;
}

require_once __DIR__ . '/helpers/function.php';
require_once __DIR__ . '/database/database.php';

require_once __DIR__ . '/services/RequestHandler.php';
require_once __DIR__ . '/core/router.php';

require_once __DIR__ . '/helpers/Response.php';
