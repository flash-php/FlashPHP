<?php


namespace FlashPHP\core\database;



/**
 * Class SqlQueryBuilder
 * @author Ingo Andelhofs
 * @package FlashPHP\core\database
 */
class SqlQueryBuilder {
  private array $query_parts;
  private int $prepare_counter;

  private string $query;


  /**
   * SqlQueryBuilder constructor.
   */
  public function __construct() {
    $this->query_parts = [];
    $this->prepare_counter = 0;
  }


  /**
   * Get the table or throw an error when table was not set
   * @return string The table for the current query
   * @throws QueryBuilderException If the table is not found
   */
  private function getTable() : string {
    if (!isset($this->query_parts['table']))
      throw new QueryBuilderException('@SqlQueryBuilder Please define a table to insert in.');

    return $this->query_parts['table'];
  }


  /**
   * Get the where for the current query
   * @return string The where for the current query
   */
  private function getWhere() : string {
    $where = $this->query_parts['generated_where'] ?? [];
    return join(' OR ', $where);
  }


  /**
   * Get the prepared data
   * @return array The prepared data
   */
  private function getPreparedData() : array {
    return $this->query_parts['prepared_data'] ?? [];
  }


  /**
   * Get the query and the prepared data
   * @return array The array with @0 the query and @1 the prepared data
   */
  private function getQueryAndData() : array {
    $query_and_data = [$this->query, $this->getPreparedData()];
    $this->reset();
    return $query_and_data;
  }


  /**
   * Create a unique prepare label for the current query
   * @param string $id The id/attribute you want to make unique
   * @return string The unique id/attribute
   */
  private function createSafeId(string $id) : string {
    return ':' . $id . $this->prepare_counter++;
  }


  /**
   * Generate the attribute list for the given data
   * @param array $data The data
   * @return string The attribute list
   */
  private function genAttributes(array $data) : string {
    return join(', ', array_keys($data));
  }


  /**
   * Generate the prepared attribute list for the given data
   * @post The prepared data is added to the query_parts list
   * @param array $data The data
   * @return string The prepared attribute list
   */
  private function genPreparedAttributes(array $data) : string {
    $prepared_data = [];
    foreach ($data as $key => $value) {
      $safe_id = $this->createSafeId($key);
      $this->query_parts['prepared_data'][$safe_id] = $value;
      $prepared_data[$safe_id] = $value;
    }

    return join(', ', array_keys($prepared_data));
  }


  /**
   * Generate the select for the current query
   * @param array $select_data The attributes you want to select
   * @return string The selection list
   */
  private function genSelect(array $select_data = []) : string {
    return empty($select_data) ? '*' : join(', ', $select_data);
  }


  /**
   * Generate the update list
   * @param array $update_data The update data
   * @return string The update list
   */
  private function genUpdate(array $update_data) : string {
    $update = [];
    foreach ($update_data as $key => $value)
      $update[] = $this->generateWhereValue($key, $value);
    return join(', ', $update);
  }


  /**
   * Generate the where query
   * @param array $where_data The data where you want to update the query
   */
  public function generateWhere(array $where_data) {
    $generated_where_parts = [];

    foreach ($where_data as $attribute => $value) {
      if (is_array($value))
        $generated_where_parts[] = $this->generateWhereArray($attribute, $value);
      else
        $generated_where_parts[] = $this->generateWhereValue($attribute, $value);

      // todo: operators, between, ... (new func genAssocWhere)
    }

    $this->query_parts['generated_where'] ??= [];
    $this->query_parts['generated_where'][] = '(' . join(' AND ', $generated_where_parts) . ')';
  }


  /**
   * Generate the where query from a single value
   * @post The prepared data is added to the query_parts list
   * @param string $id The id/attribute
   * @param mixed $value The value
   * @return string The where query
   */
  public function generateWhereValue(string $id, $value) : string {
    $safe_id = $this->createSafeId($id);
    $this->query_parts['prepared_data'] ??= [];
    $this->query_parts['prepared_data'][$safe_id] = $value;

    return $id . ' = ' . $safe_id;
  }


  /**
   * Generate the where query from an array of values
   * @post The prepared data is added to the query_parts list
   * @param string $id The id/attribute
   * @param array $values The array of values
   * @return string The where query
   */
  public function generateWhereArray(string $id, array $values) : string {
    $where_values = [];

    for ($i = 0; $i < count($values); ++$i) {
      $value = $values[$i];
      $where_values[] = $this->generateWhereValue($id, $value);
    }

    return join(' OR ', $where_values);
  }


  /**
   * Insert a record into a database
   * @param array $insert_data The data you want to insert into the database
   * @return array The array with @0 the query and @1 the prepared data
   * @throws QueryBuilderException
   */
  public function insert(array $insert_data) {
    $table = $this->getTable();
    $attributes = $this->genAttributes($insert_data);
    $values = $this->genPreparedAttributes($insert_data);

    $this->query = "INSERT INTO $table ($attributes) VALUES ($values);";

    return $this->getQueryAndData();
  }


  /**
   * Select a record from a database
   * @param array $select_data The data you want to select
   * @return array The array with @0 the query and @1 the prepared data
   * @throws QueryBuilderException
   */
  public function select(array $select_data = []) {
    $table = $this->getTable();
    $where = $this->getWhere();
    $select = $this->genSelect($select_data);

    $this->query = "SELECT $select FROM $table WHERE $where;";

    return $this->getQueryAndData();
  }


  /**
   * Update a record in the database
   * @param array $update_data The data you want to replace the old data with
   * @return array The array with @0 the query and @1 the prepared data
   * @throws QueryBuilderException
   */
  public function update(array $update_data) {
    $table = $this->getTable();
    $where = $this->getWhere();
    $update = $this->genUpdate($update_data);

    $this->query = "UPDATE $table SET $update WHERE $where;";

    return $this->getQueryAndData();
  }


  /**
   * Delete a record from the database
   * @return array The array with @0 the query and @1 the prepared data
   * @throws QueryBuilderException
   */
  public function delete() {
    $table = $this->getTable();
    $where = $this->getWhere();

    $this->query = "DELETE FROM $table WHERE $where;";

    return $this->getQueryAndData();
  }


  /**
   * Set the table name for the current query
   * @param string $name The name of the database table
   * @return SqlQueryBuilder
   */
  public function table(string $name) : SqlQueryBuilder {
    $this->query_parts['table'] = $name;
    return $this;
  }


  /**
   * Add a where query to the query list
   * @param array $where_data The where data
   * @return SqlQueryBuilder
   */
  public function where(array $where_data) : SqlQueryBuilder {
    $this->query_parts['where'] ??= [];
    $this->query_parts['where'][] = $where_data;

    $this->generateWhere($where_data);

    return $this;
  }


  /**
   * Reset the helper variables for the next query
   */
  private function reset() {
    $this->query_parts = [];
    $this->prepare_counter = 0;
  }
}