<?php

$archClassPath = DIRECTORY_SEPARATOR . 'wamp64' . DIRECTORY_SEPARATOR . 'www' . DIRECTORY_SEPARATOR . 'arch' . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR;
include_once($archClassPath . 'initialize2.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/* Testing Constant */
define('WEB_SITE_URL', 'http://192.168.1.10/');

define('SSLCZ_STORE_ID', 'estee5bb43aa1c7137');
define('SSLCZ_STORE_PASSWD', 'estee5bb43aa1c7137@ssl');
define('SSLCZ_IS_SANDBOX', true);
define('SSLCZ_IS_DEBUG', true);
/* End Testing Constant */


$portalFolder = 'pportal/';
define('isProduction', false);
define('PORTAL_URL', constant('WEB_SITE_URL') . $portalFolder);
define('API_URL', constant('SITE_URL') . '/api/pportal/');
