<?php


namespace FlashPHP\core\http;



/**
 * Class HttpHandler
 * @author Ingo Andelhofs
 * @package FlashPHP\core\http
 */
class HttpHandler {
  /**
   * Get the request method of the current request
   * @return string The method in upper case (GET|POST|PUT|DELETE)
   */
  public static function get_request_method() : string {
    $request_method = strtoupper($_SERVER['REQUEST_METHOD']);

    if ($request_method !== 'POST')
      return $request_method;

    return $_POST['REQUEST_METHOD'] ?? 'POST';
  }
}