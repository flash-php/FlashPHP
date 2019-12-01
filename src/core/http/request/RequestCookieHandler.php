<?php


namespace FlashPHP\core\http\request;

use FlashPHP\utils\ArrayObject;



/**
 * Class RequestCookieHandler
 * @author Ingo Andelhofs
 * @package FlashPHP\core\http\request
 */
class RequestCookieHandler extends ArrayObject {
  /**
   * RequestCookieHandler constructor.
   */
  public function __construct() {
    parent::__construct($this->get_cookie_array());
  }

  /**
   * Get a cookie array reference
   * @return array The cookie array
   */
  private function &get_cookie_array() : array {
    return $_COOKIE;
  }


  /**
   * Set the cookie value for one month
   * @param string $key The cookie array key
   * @param mixed $value The new cookie value
   */
  public function __set($key, $value) {
    setcookie($key, $value, strtotime("+1 month"));
  }
}