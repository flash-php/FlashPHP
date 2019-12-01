<?php


namespace FlashPHP\utils;



/**
 * Class ArrayUtil
 * @author Ingo Andelhofs
 * @package FlashPHP\utils
 */
class ArrayUtil {
  /**
   * Combines an array of keys with an array of values to create a key => value array
   * @param array $keys The keys for the key => value array
   * @param array $values The values for the key => value array
   * @return array The key => value array
   */
  public static function combine_key_value(array $keys, array $values) : array {
    $keys_len = count($keys);
    $values_len = count($values);
    $combined = [];

    for($i = 0; $i < $keys_len && $i < $values_len; ++$i)
      $combined[$keys[$i]] = $values[$i];

    return $combined;
  }


  /**
   * Prefixes all the keys of an associative array with a given prefix
   * @param array $assoc_array The array you want to prefix
   * @param string $prefix The prefix you want to use
   * @return array The prefixed array
   */
  public static function prefix_keys(array $assoc_array, string $prefix = '') : array {
    $prefixed = [];

    foreach ($assoc_array as $key => $value)
      $prefixed[$prefix . $key] = $value;

    return $prefixed;
  }
}