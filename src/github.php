<?php

// Define this values on wp-config.php
//define('GITHUB_REPO', 'owner/repo');
//define('GITHUB_BRANCH', 'uploads');
//define('GITHUB_TOKEN', '....');

function get_file_from_github($filename) {
  $ctx = stream_context_create([
    "http" => [
      "method" => "GET",
      "header" => "Authorization: token ".GITHUB_TOKEN
    ]
  ]);
  $content = file_get_contents("https://raw.githubusercontent.com/".GITHUB_REPO."/".GITHUB_BRANCH."/$filename" , false, $ctx);
  foreach ($http_response_header as $header) {
    if (strpos($header, 'Content-Type: ') !== false) {
      $type = explode(': ', $header)[1];
    }
  }
  return array(
    'content' => $content,
    'type' => $type,
  );
}

function upload_file_to_github($path) {
  $content = file_get_contents($path);
  $pathinfo = pathinfo($path);
  $filename = uniqid() . '.' . $pathinfo['extension'];
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0');
  curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/".GITHUB_REPO."/contents/$filename");
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: token '.GITHUB_TOKEN,
    'Accept: application/vnd.github.v3+json'
  ));
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array(
    'branch' => GITHUB_BRANCH,
    'message' => 'Uploading file',
    'content' => base64_encode($content)
  )));
  $reply = curl_exec($ch);
  curl_close($ch);
  $reply = json_decode($reply);
  if ($reply->data->error) {
    print_r($reply->data->error);
  }
  return $filename;
}
