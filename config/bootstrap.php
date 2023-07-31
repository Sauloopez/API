<?php
define('DIR', '/var/www/html/API');

require DIR.'/vendor/autoload.php';

use Dotenv\Dotenv;

$env = new Dotenv(__DIR__);
$env->load();

define('PDO', getenv('PDO'));
define('DB_HOST', getenv('DB_HOST'));
define('DB_PASSWORD', getenv('DB_PASSWORD'));
define('DB_USER', getenv('DB_USER'));
define('DB', getenv('DB'));

define('SECRET', getenv('SECRET'));

include_once DIR.'/Modules/Tker.php';
include_once DIR.'/Modules/Validator.php';


