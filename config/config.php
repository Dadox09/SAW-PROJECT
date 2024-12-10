<?php
// Definizione del BASE_URL
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/websites/SAW-PROJECT';
define('BASE_URL', $base_url);

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);