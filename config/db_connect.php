<?php
require_once __DIR__ . '/config.php';

if($_SERVER['HTTP_HOST'] === 'localhost') {
    define('DB_HOST', getEnvValue('DB_HOST_local'));
    define('DB_USER', getEnvValue('DB_USER_local'));
    define('DB_PASS', getEnvValue('DB_PASS_local'));
    define('DB_NAME', getEnvValue('DB_NAME_local'));
} else {
    define('DB_HOST', getEnvValue('DB_HOST_remote'));
    define('DB_USER', getEnvValue('DB_USER_remote'));
    define('DB_PASS', getEnvValue('DB_PASS_remote'));
    define('DB_NAME', getEnvValue('DB_NAME_remote'));
}

function connectDB() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if(!$conn) {
        die("Connessione fallita: " . mysqli_connect_error());
    }
    return $conn;
}
