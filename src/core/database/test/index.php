<?php


use FlashPHP\core\database\SqlQueryBuilder;

include '../SqlQueryBuilder.php';
include '../../../utils/ArrayUtil.php';

ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);


$builder = new SqlQueryBuilder();




print('<pre>');

$query_and_data = $builder
  ->table('User')
  ->insert(['firstname' => 'Ingo', 'lastname' => 'Andelhofs']);

print_r($query_and_data);


print('<br>');

$builder
  ->table('User')
  ->where(['id' => 4])
  ->where(['id' => [5, 4, 9]])
  ->where(['id' => 5, 'firstname' => 'ingo'])
  ->select(['id', 'firstname']);

print('<br>');

$builder
  ->table('User')
  ->where(['id' => 5, 'firstname' => 'ingo'])
  ->update(['id' => 6, 'firstname' => 'test']);

print('<br>');

$builder
  ->table('User')
  ->where(['id' => 5, 'firstname' => 'ingo'])
  ->delete();

print('</pre>');