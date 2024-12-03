<?php
// Definizione del BASE_URL
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/websites/SAW-PROJECT';
define('BASE_URL', $base_url);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ecommerce_db');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);