<?php


namespace FlashPHP\config;



/**
 * Class Config
 * @author Ingo Andelhofs
 * @package FlashPHP\config
 */
class Config {
  // Root Path
  public static string $DIR = __DIR__;
  public static string $HOME = __DIR__;
  public static string $HOME_PATH = __DIR__;

  // Router
  public static string $ROUTER_URL_PARAMETER = 'route';
  public static int $MAIN_ROUTE_INDEX = 0;
  public static int $SUB_ROUTE_INDEX = 1;
  public static int $ROUTE_PARAMETER_INDEX = 2;
  public static string $DEFAULT_MAIN_ROUTE = 'home';
  public static string $DEFAULT_SUB_ROUTE = 'index';

  // Database
  public static string $DB_DRIVER = 'mysql';
  public static string $DB_PORT = '3306';
  public static string $DB_HOST = 'localhost';
  public static string $DB_NAME = 'dbname';
  public static string $DB_USERNAME = 'root';
  public static string $DB_PASSWORD = 'root';
  public static string $DB_CHAR = 'UTF8';

}