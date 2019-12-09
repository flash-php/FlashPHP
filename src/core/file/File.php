<?php


namespace FlashPHP\core\file;

use finfo;



/**
 * Class File
 * @author Ingo Andelhofs
 * @package FlashPHP\core\file
 */
class File {
  private string $name;
  private string $full_name;
  private string $extension;
  private string $temp_path;
  private string $type;
  private string $real_type;
  private int $size;

  private int $max_size;
  private string $dest_name;
  private string $dest_path;
  private string $full_dest_path;

  private string $error;


  /**
   * File constructor.
   * @param string $file_name The name of the file
   * @throws FileException If the file doesn't exist
   */
  public function __construct(string $file_name) {
    $file_name ??= 'filename';

    if (!isset($_FILES[$file_name]))
      throw new FileException("@NoSuchFile: There was no file uploaded with name '$file_name'");

    $upload = $_FILES[$file_name];

    $this->full_name = $upload['name'] ?? null;
    $this->type = $upload['type'] ?? null;
    $this->temp_path = $upload['tmp_name'] ?? null;
    $this->error = $upload['error'] ?? 0;
    $this->size = $upload['size'] ?? 0;

    $path_info = pathinfo($this->full_name);
    $this->name = $path_info['filename'] ?? null;
    $this->extension = $path_info['extension'] ?? null;

    $this->real_type = (new finfo(FILEINFO_MIME_TYPE))->file($this->temp_path);
    $this->max_size =  2 * pow(2, 20); // 2mb
  }
}