<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('DIR', '/var/www/html/API');

require_once (DIR.'/Router/define.php');
require_once (DIR.'/Router/router.php');

$router = new Router();
$router->run();
