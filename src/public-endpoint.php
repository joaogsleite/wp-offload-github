<?php

require_once( __DIR__ . '/github.php');
require_once( __DIR__ . '/image-tools.php');

if (strpos($_SERVER['REQUEST_URI'], '/wp-content/uploads/') === 0) {
  $filename = explode('/wp-content/uploads/', $_SERVER['REQUEST_URI'])[1];
  if (strpos($filename, '-crop') !== false) {
    $crop = true;
  } else {
    $crop = false;
  }
  if (strpos($filename, '-w') !== false) {
    $width = explode('.', explode('-w', $filename)[1])[0];
    $id = explode('-w', $filename)[0];
    $ext = explode('.', $filename)[1];
    ['content' => $content, 'type' => $type] = get_file_from_github("$id.$ext");
    $output = resize_image(array('content' => $content, 'width' => $width, 'type' => $type, 'crop' => $crop));
  } else if (strpos($filename, '-h') !== false) { 
    $height = explode('.', explode('-h', $filename)[1])[0];
    $id = explode('-h', $filename)[0];
    $ext = explode('.', $filename)[1];
    ['content' => $content, 'type' => $type] = get_file_from_github("$id.$ext");
    $output = resize_image(array('content' => $content, 'height' => $height, 'type' => $type, 'crop' => $crop));
  } else {
    ['content' => $content, 'type' => $type] = get_file_from_github($filename);
    $output = $content;
  }
  header("Content-Type: " . $type);
  header("Cache-Control: max-age=31536000");
  echo $output;
  exit(0);
}


