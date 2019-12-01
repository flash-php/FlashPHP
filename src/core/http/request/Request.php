<?php


namespace FlashPHP\core\http\request;

use FlashPHP\utils\ArrayObject;



/**
 * Class Request
 * @author Ingo Andelhofs
 * @package FlashPHP\core\http\request
 */
class Request {
  public RequestBodyHandler $body;
  public RequestSessionHandler $session;
  public RequestCookieHandler $cookie;
  public RequestFileHandler $files;
  public ArrayObject $parameters;


  /**
   * Request constructor.
   * @param array $parameters The parameters from the current route/url
   */
  public function __construct(array &$parameters) {
    $this->body = new RequestBodyHandler();
    $this->session = new RequestSessionHandler();
    $this->cookie = new RequestCookieHandler();
    $this->files = new RequestFileHandler();
    $this->parameters = new ArrayObject($parameters);
  }
}