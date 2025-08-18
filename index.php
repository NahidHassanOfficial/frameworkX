<?php
require_once __DIR__ . '/config.php';

require_once __DIR__ . '/services/ErrorHandler.php';
new ErrorHandler(constant('isProduction'));

require_once __DIR__ . '/core/autoload.php';
require_once __DIR__ . '/core/cors.php';

require_once __DIR__ . '/database/database.php';

require_once __DIR__ . '/services/RequestHandler.php';
require_once __DIR__ . '/core/router.php';

require_once __DIR__ . '/helpers/Response.php';
