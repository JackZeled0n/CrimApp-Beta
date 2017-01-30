<?php
require 'src/facebook-sdk-v5/autoload.php';
session_start();

$fb = new Facebook\Facebook([
  'app_id' => '747595275390955',
  'app_secret' => '4a8b961b4d7afe2a1b4d1ee32c595099',
  'default_graph_version' => 'v2.5',
]);
