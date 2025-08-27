<?php

$APP_NAME = 'FrameworkX';
$BASE_URL = '/frameworkX/';

if (session_status() == PHP_SESSION_NONE) {
    session_name($APP_NAME);
    session_start();
}

/* Testing Constant */
define('DB_SERVER', 'localhost');
define('DB_NAME', 'ahms');
define('DB_USER', 'root');
define('DB_PASS', '');
/* End Testing Constant */

define('IS_PRODUCTION', false);
define('IS_MAINTENANCE',  false);