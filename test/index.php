<?php

use FlashPHP\core\http\Router;
require_once '../vendor/autoload.php';


// Developing mode
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);



// Routes
$home = new Router("home");
$home->simple_get("index", ["test", "lol"], [], function () {
  echo "test";
});




Router::start();