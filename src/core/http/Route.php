<?php


namespace FlashPHP\core\http;

use Closure;
use FlashPHP\core\http\request\Request;
use FlashPHP\core\http\response\Response;
use FlashPHP\utils\ArrayUtil;



/**
 * Class Route
 * @author Ingo Andelhofs
 * @package FlashPHP\core\http
 */
class Route {
  private string $main_route;
  private string $sub_route;
  private string $path;

  private string $request_method;
  private array $params;
  private array $middleware;
  private Closure $callback;


  /**
   * Route constructor.
   *
   * @pre $main_route must be trimmed
   * @pre $sub_route must be trimmed
   *
   * @param string $main_route
   * @param string $sub_route
   * @param string $request_method
   * @param array $params
   * @param array $middleware
   * @param Closure $callback
   */
  public function __construct(string $main_route, string $sub_route, string $request_method, array $params, array $middleware, Closure $callback) {
    $this->main_route = $main_route;
    $this->sub_route = $sub_route;
    $this->request_method = $request_method;
    $this->params = $params;
    $this->middleware = $middleware;
    $this->callback = $callback;

    $this->path = $main_route . '/' . $sub_route;
  }


  /**
   * Activate the current route (send it to the user)
   * @param array $parameters The url parameters for the current route
   */
  public function run(array $parameters) {
    $parameters = ArrayUtil::combine_key_value($this->params, $parameters);

    // Handle middleware
    foreach($this->middleware as $middleware) {
      if (!$middleware())
        (new Response())->end("Middleware blocks you from this route");
    }

    ($this->callback)(new Request($parameters), new Response());
  }
}