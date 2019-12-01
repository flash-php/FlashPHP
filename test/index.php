<?php

use FlashPHP\core\http\request\Request;
use FlashPHP\core\http\response\Response;
use FlashPHP\core\http\Router;
use FlashPHP\core\middleware\Middleware;

require_once '../vendor/autoload.php';


// Developing mode
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);



// Routes
$home = new Router("home");
$home->simple_get("index", ["test", "lol"], [function() { return Middleware::$block; }], function (Request $req, Response $res) {
  $res->send_r( $req->params );

});




Router::start();