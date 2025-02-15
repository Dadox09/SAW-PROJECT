<?php
// Definizione del BASE_URL
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    $base_url = 'http://localhost/websites/SAW-PROJECT';
} else {
    $base_url = 'https://saw.dibris.unige.it/~s5470839';
}
define('BASE_URL', $base_url);

//estraggo le varibili dall'env
function getEnvValue($key) {
    $path = __DIR__ . '/../.env';
    if (file_exists($path)) {
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0 || empty($line)) continue;
            
            list($envKey, $envValue) = explode('=', $line, 2);
            if (trim($envKey) === $key) {
                return trim($envValue);
            }
        }
    }
    return null;
}

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);