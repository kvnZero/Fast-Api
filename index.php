<?php 

define('APP_PATH', __DIR__ . '/');

require(APP_PATH . 'fastapi/FastApi.php');

$config = require(APP_PATH . 'config/config.php');

(new fastapi\FastApi($config))->run();