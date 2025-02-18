<?php
require_once __DIR__ . '/config.php';

if($_SERVER['HTTP_HOST'] === 'localhost') {    
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'sawproject');
} else {
    define('DB_HOST', 'localhost');
    define('DB_USER', 's5470839');
    define('DB_PASS', 'SawPieDaddo');
    define('DB_NAME', 's5470839');
}

function connectDB() {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if(!$conn) {
        die("Connessione fallita: " . mysqli_connect_error());
    }
    return $conn;
}
