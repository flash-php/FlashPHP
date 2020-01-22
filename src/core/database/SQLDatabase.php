<?php


namespace FlashPHP\core\database;

use FlashPHP\config\Config;
use PDO;



/**
 * Class SQLDatabase
 * @author Ingo Andelhofs
 * @package FlashPHP\core\database
 */
class SQLDatabase {
  protected static PDO $instance;

  protected function __construct() {}
  protected function __clone() {}

  public static function instance() {
    if (self::$instance === null) {
      $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => true,
        PDO::ATTR_PERSISTENT => true,
      ];

      $dsn = Config::$DB_DRIVER.':host='.Config::$DB_HOST.';dbname='.Config::$DB_NAME.';charset='.Config::$DB_CHAR;
      self::$instance = new PDO($dsn, Config::$DB_USERNAME, Config::$DB_PASSWORD, $options);
    }

    return self::$instance;
  }

  public static function __callStatic($method, $args) {
    return call_user_func_array(array(self::instance(), $method), $args);
  }


  public static function query(string $query, array $prepared_data = []) {
    //    $dbh = $this->dbh;
    //    $dbh->beginTransaction(); // IMPORTANT FOR lastInsertedId()
    //
    //    $stmt = $dbh->prepare($statement);
    //
    //    foreach(array_keys($parameters) as $name) {
    //      $data_type = $this->schema[$name] ?? PDO::PARAM_STR;
    //      $stmt->bindParam(":$name", $parameters[$name], $data_type);
    //    }
    //
    //    $stmt->execute();
    //
    //    if (strpos($statement, 'SELECT') !== false) {
    //      $results = $stmt->fetchAll();
    //      $dbh->commit(); // IMPORTANT (end the transaction)
    //      return $results;
    //    }
    //
    //    if (strpos($statement, 'INSERT') !== false) {
    //      $id = $dbh->lastInsertId('id');
    //      $dbh->commit(); // IMPORTANT FOR lastInsertedId()
    //      return $id;
    //    }
    //
    //    $dbh->commit(); // IMPORTANT (end the transaction)
    //    return true;

    return $prepared_data;
  }

  public static function get(string $table, array $where_data, array $select = []) : array {
    $builder = new SqlQueryBuilder();
    $result = $builder
      ->table($table)
      ->where($where_data)
      ->select($select);

    return self::query($result[0], $result[1]);
  }



}