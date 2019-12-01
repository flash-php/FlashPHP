<?php


namespace FlashPHP\core\http\request;

use FlashPHP\utils\ArrayObject;



/**
 * Class RequestSessionHandler
 * @author Ingo Andelhofs
 * @package FlashPHP\core\http\request
 */
class RequestSessionHandler extends ArrayObject {
  /**
   * RequestSessionHandler constructor.
   */
  public function __construct() {
    parent::__construct($this->get_session_array());
  }

  /**
   * Get a session array reference
   * @return array The session array
   */
  private function &get_session_array() {
    return $_SESSION;
  }
}