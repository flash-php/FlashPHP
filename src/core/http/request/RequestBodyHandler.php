<?php


namespace FlashPHP\core\http\request;

use FlashPHP\core\http\HttpHandler;
use FlashPHP\utils\ArrayObject;



/**
 * Class RequestBodyHandler
 * @author Ingo Andelhofs
 * @package FlashPHP\core\http\request
 */
class RequestBodyHandler extends ArrayObject {
  /**
   * RequestBodyHandler constructor.
   */
  public function __construct() {
    parent::__construct(HttpHandler::get_request_body());
  }
}