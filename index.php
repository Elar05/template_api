<?php

date_default_timezone_set('America/Lima');
error_reporting(E_ALL);
ini_set('ignore_repeated_errors', TRUE);
ini_set('display_errors', FALSE);
ini_set('log_errors', TRUE);
ini_set("error_log", 'debug.log');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, Content-Type, X-Requested-With, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=UTF-8");

require_once 'vendor/autoload.php';

require_once 'config/config.php';
require_once 'libs/app.php';

spl_autoload_register(function ($class) {
  list($folder, $file) = explode("\\", $class);
  $class = lcfirst($folder) . '/' . lcfirst($file);
  $fileClass = __DIR__ . "/$class.php";
  // error_log($class);
  // error_log($fileClass);

  if (file_exists($fileClass)) {
    require_once $fileClass;
  }
});

new App();
