<?php


namespace FlashPHP\core\http\response;

use FlashPHP\config\Config;



/**
 * Class Response
 * @author Ingo Andelhofs
 * @package FlashPHP\core\http\response
 */
class Response {
  /**
   * Print a (xss safe) message to the screen
   * @param string $message The message you want to print to the screen
   */
  public function send(string $message = '') {
    print(htmlspecialchars($message));
  }


  /**
   * Print a (xss safe) errormessage to the screen
   * @param string $message The errormessage you want to print to the screen
   */
  public function error(string $message = '') {
    $this->send("<span class='error'>$message</span>");
  }


  /**
   * Print a human readable version of data to the screen
   * @param mixed $data The data you want to print to the screen
   */
  public function send_r($data = '') {
    print("<pre style='font-family:inherit;'>");
    print_r($data);
    print('</pre>');
  }


  /**
   * Print json to the screen
   * @param array $data The json data you want to print
   */
  public function json(array $data = []) {
    $this->send_r(json_encode($data));
  }


  /**
   * Log a js message to the js console
   * @param string $message The message you want to log to the js console
   */
  public function js_log(string $message = '') {
    print('<script>console.log("');
    print($message);
    print('");</script>');
  }


  /**
   * End the php script with a message
   * @param string $message The message u want to send before ending the script
   */
  public function end(string $message = '') {
    die(htmlspecialchars($message));
  }


  /**
   * Render a view with the given data
   * @param string $name The name/path of the view you want to render
   * @param array $data The data you want to render the view with
   */
  public function view(string $name = '', array $data = []) {
    $engine = Config::$ENGINE ?? Config::$DEFAULT_ENGINE;
    $engine->render($name, $data);
    $this->end();
  }


  /**
   * Redirect to a given url
   * @param string $to The url you want to redirect to
   */
  public function redirect(string $to = '') {
    header("Location: $to");
    $this->end("Redirecting to: $to...");
  }


  /**
   * Redirect to the previous url
   */
  public function redirect_back() {
    // ALTERNATIVE: $this->redirect('javascript://history.go(-1)');
    $this->redirect($_SERVER['HTTP_REFERER']);
  }


  /**
   * Read a file from a given path
   * @param string $path The path to the file you want to read
   *
   * @todo: Check for Security
   */
  public function read_file(string $path = '') {
    if (file_exists($path))
      readfile($path);

    $this->end();
  }


  /**
   * Force a file download from a given file path
   * @param string $path The path to the file you want to download
   */
  public function download(string $path = '') {
    if (file_exists($path)) {
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename="' . basename($path) . '"');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: ' . filesize($path));
      readfile($path);
    }

    $this->end();
  }
}