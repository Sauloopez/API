<?php
include_once(__DIR__.'/config/bootstrap.php');
require_once (DIR.'/Router/router.php');

$router = new Router();
$router->run();
