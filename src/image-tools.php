<?php

function resize_image($params) {
  list($width, $height) = getimagesizefromstring($params['content']);
  if ($params['crop']) {
    $img = imagecreatefromstring($params['content']);
    //find the size of the borders
    $b_top = 0;
    $b_btm = 0;
    $b_lft = 0;
    $b_rt = 0;
    //top
    for(; $b_top < imagesy($img); ++$b_top) {
      for($x = 0; $x < imagesx($img); ++$x) {
        if(imagecolorat($img, $x, $b_top) != 0xFFFFFF) {
          break 2; //out of the 'top' loop
        }
      }
    }
    //bottom
    for(; $b_btm < imagesy($img); ++$b_btm) {
      for($x = 0; $x < imagesx($img); ++$x) {
        if(imagecolorat($img, $x, imagesy($img) - $b_btm-1) != 0xFFFFFF) {
          break 2; //out of the 'bottom' loop
        }
      }
    }
    //left
    for(; $b_lft < imagesx($img); ++$b_lft) {
      for($y = 0; $y < imagesy($img); ++$y) {
        if(imagecolorat($img, $b_lft, $y) != 0xFFFFFF) {
          break 2; //out of the 'left' loop
        }
      }
    }
    //right
    for(; $b_rt < imagesx($img); ++$b_rt) {
      for($y = 0; $y < imagesy($img); ++$y) {
        if(imagecolorat($img, imagesx($img) - $b_rt-1, $y) != 0xFFFFFF) {
          break 2; //out of the 'right' loop
        }
      }
    }
  }
  $ratio = ($width-$b_lft-$b_rt) / ($height-$b_top-$b_btm);
  if ($params['width']) {
    $new_width = $params['width'];
    $new_height = $new_width / $ratio;
  } else if ($params['height']) {
    $new_height = $params['height'];
    $new_width = $new_height * $ratio;
  }
  if (strpos($params['type'], 'jpeg') !== false) { 
    $src = imagecreatefromstring($params['content']);
    $dst = imagecreatetruecolor($new_width, $new_height);
    imagecopyresampled($dst, $src, 0, 0, $b_lft, $b_top, $new_width, $new_height, $width, $height);
    ob_start();
    imagejpeg($dst);
    $output = ob_get_contents();
    ob_end_clean();
  } else if (strpos($params['type'], 'png') !== false) { 
    $src = imagecreatefromstring($params['content']); 
    $dst = imagecreatetruecolor($new_width, $new_height);
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    imagecopyresampled($dst, $src, 0, 0, $b_lft, $b_top, $new_width, $new_height, $width-$b_lft-$b_rt, $height-$b_top-$b_btm);
    ob_start();
    imagepng($dst);
    $output = ob_get_contents();
    ob_end_clean();
  } else {
    $output = $params['content'];
  }
  return $output;
}