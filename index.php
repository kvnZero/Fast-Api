<?php 

define('APP_PATH', __DIR__ . '/');

require(APP_PATH . 'fastapi/FastApi.php');

$config = require(APP_PATH . 'config/config.php');

spl_autoload_register(function ($class_name) {
    require_once str_replace('\\','/',$class_name) . '.php';
});

(new fastapi\FastApi($config))->run();