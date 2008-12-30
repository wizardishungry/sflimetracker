<?php

// code inspired by drupal 

/**
 * Determine the maximum file upload size by querying the PHP settings.
 *
 * @return
 *   A file size limit in bytes based on the PHP upload_max_filesize and
 *   post_max_size
 */
function file_upload_max_size() {
  static $max_size;
  if(isset($max_size))
    return $max_size;
  $upload_max = parse_size(ini_get('upload_max_filesize'));
  $post_max = parse_size(ini_get('post_max_size'));
  $max_size = min($upload_max, $post_max);
  return $max_size;
}

function parse_size($size) {
  $suffixes = array(
    '' => 0,
    'k' => 1,
    'm' => 2, // 1024 * 1024
    'g' => 3, // 1024 * 1024 * 1024
  );
  if (preg_match('/([0-9]+)\s*(k|m|g)?(b?(ytes?)?)/i', $size, $matches)) {
    return $matches[1] * pow(1024,$suffixes[strtolower($matches[2])]);
  }
}

