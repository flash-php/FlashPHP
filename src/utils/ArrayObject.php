<?php


namespace FlashPHP\utils;



/**
 * Class ArrayObject
 * @author Ingo Andelhofs
 * @package FlashPHP\utils
 */
class ArrayObject {
  protected $assoc_array;


  /**
   * ArrayObject constructor.
   * @param array|null $assoc_array The array you want to objectify
   */
  public function __construct(array &$assoc_array = null) {
    $this->assoc_array = &$assoc_array;
  }


  /**
   * Shows the debug info
   * @return array|null The array with the debug info
   */
  public function __debugInfo() : ?array {
    return $this->assoc_array;
  }


  /**
   * Allows object like getting on the internal array, and returns null if the value does not exist
   * @param string $key The array key
   * @return array|ArrayObject|mixed|null The array value or a new ArrayObject
   */
  public function __get(string $key) {
    $value = &$this->assoc_array[$key] ?? null;
    return (is_array($value) && array_values($value) !== $value) ? new ArrayObject($value) : $value;
  }


  /**
   * Allows object like setting on the internal array
   * @param string $key The array key
   * @param mixed $value The new array value
   */
  public function __set(string $key, $value) {
    $this->assoc_array[$key] = $value;
  }


  /**
   * Get a reference of the internal array
   * @return array|null The internal array if set
   */
  public function &get_array() : ?array {
    return $this->assoc_array;
  }
}