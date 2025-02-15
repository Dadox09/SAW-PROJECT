<?php
// Definizione del BASE_URL
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    $base_url = 'http://localhost/websites/SAW-PROJECT';
} else {
    $base_url = 'https://saw.dibris.unige.it/~s5470839';
}
define('BASE_URL', $base_url);

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);