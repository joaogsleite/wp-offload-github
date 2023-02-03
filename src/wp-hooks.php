<?php

require_once( __DIR__ . '/github.php');

add_filter( 'big_image_size_threshold', '__return_false' );

add_filter('wp_handle_upload', 'handle_upload_external', 100);
function handle_upload_external(&$file, $overrides = false, $time = null) {
  $new_file = upload_file_to_github($file['file']);
  $result = array(
    'url' => explode('/wp-content/uploads/', $file['url'])[0] . '/wp-content/uploads/' . $new_file,
    'type' => $file['type'],
    'file' => explode('/wp-content/uploads/', $file['file'])[0] . '/wp-content/uploads/' . $new_file,
  );
  rename($file['file'], $result['file']);
  return $result;
}

add_filter('wp_generate_attachment_metadata', 'unlink_file_after_attachment_metadata', 100, 3);
function unlink_file_after_attachment_metadata($metadata, $attachment_id, $action) {
  $path = get_attached_file($attachment_id);
  unlink($path);
  return $metadata;
}

add_action('intermediate_image_sizes_advanced', 'disable_all_image_sizes');
function disable_all_image_sizes($sizes) {
  $size_names = array_keys($sizes);
  foreach($size_names as $name) {
    unset($sizes[$name]);
  }
}