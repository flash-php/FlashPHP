<?php


namespace FlashPHP\core\http;

use Closure;
use FlashPHP\config\Config;



/**
 * Class Router
 * @author Ingo Andelhofs
 * @package FlashPHP\core\http
 */
class Router {
  private string $main_route;
  private static array $routes;


  /**
   * Router constructor.
   * @param string $main_route The name of the router
   */
  public function __construct(string $main_route) {
    $this->main_route = trim($main_route, '/');
  }


  /**
   * Create a route for a specific request type.
   * @param string $sub_route
   * @param array $parameters
   * @param string $request_method
   * @param array $middleware
   * @param Closure $callback
   */
  public function simple_request(string $sub_route, string $request_method, array $parameters, array $middleware, Closure $callback) {
    $sub_route = trim($sub_route, '/');
    $route = new Route($this->main_route, $sub_route, $request_method, $parameters, $middleware, $callback);
    self::$routes[$this->main_route][$sub_route][$request_method] = $route;
  }

  /**
   * Create a get route for the current router.
   * @param string $sub_route
   * @param array $parameters
   * @param array $middleware
   * @param Closure $callback
   */
  public function simple_get(string $sub_route, array $parameters, array $middleware, Closure $callback) {
    $this->simple_request($sub_route, 'GET', $parameters, $middleware, $callback);
  }

  /**
   * Create a post route for the current router.
   * @param string $sub_route
   * @param array $parameters
   * @param array $middleware
   * @param Closure $callback
   */
  public function simple_post(string $sub_route, array $parameters, array $middleware, Closure $callback) {
    $this->simple_request($sub_route, 'POST', $parameters, $middleware, $callback);
  }

  /**
   * Create a put route for the current router.
   * @param string $sub_route
   * @param array $parameters
   * @param array $middleware
   * @param Closure $callback
   */
  public function simple_put(string $sub_route, array $parameters, array $middleware, Closure $callback) {
    $this->simple_request($sub_route, 'PUT', $parameters, $middleware, $callback);
  }

  /**
   * Create a delete route for the current router.
   * @param string $sub_route
   * @param array $parameters
   * @param array $middleware
   * @param Closure $callback
   */
  public function simple_delete(string $sub_route, array $parameters, array $middleware, Closure $callback) {
    $this->simple_request($sub_route, 'DELETE', $parameters, $middleware, $callback);
  }


  /**
   * Start listening for route changes.
   * @throws RouterException
   */
  public static function start() {
    if (!isset($_GET[Config::$ROUTER_URL_PARAMETER]))
      throw new RouterException('@InvalidUrl: U forgot to add the \'?' . Config::$ROUTER_URL_PARAMETER . '=\' at the end of your url');

    self::load();
  }


  /**
   * Load a the route for a given url and a given request method
   * @param string|null $url The url given or the current url
   * @param string|null $request_method The given request method or the current request method
   * @throws RouterException
   */
  public static function load(string $url = null, string $request_method = null) {
    $parsed_url = self::parse_url($url);

    $main_route = $parsed_url[Config::$MAIN_ROUTE_INDEX] ?? Config::$DEFAULT_MAIN_ROUTE;
    $sub_route = $parsed_url[Config::$SUB_ROUTE_INDEX] ?? Config::$DEFAULT_SUB_ROUTE;
    $parameters = array_slice($parsed_url, Config::$ROUTE_PARAMETER_INDEX);
    $request_method = $request_method ?? HttpHandler::get_request_method();


    if (!isset(self::$routes[$main_route][$sub_route][$request_method]))
      throw new RouterException('@NoSuchRoute: There is no Route called \'' . $main_route . '/' . $sub_route . '\' for this Router');

    self::$routes[$main_route][$sub_route][$request_method]->run($parameters);
  }


  /**
   * Parse the url into its main_route, sub_route and parameters
   * @param string|null $url The given url, if null the $_GET parameter from Config is used
   * @return array The array of routes and parameters
   */
  private static function parse_url(string $url = null) : array {
    $url ??= $_GET[Config::$ROUTER_URL_PARAMETER];

    $url = trim($url, '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $url = explode('/', $url);
    $url = array_filter($url);

    return $url;
  }
}
