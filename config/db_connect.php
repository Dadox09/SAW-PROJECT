<?php

// Definisce le costanti del database usando le variabili d'ambiente
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
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    } catch(PDOException $e) {
        die("Connessione fallita: " . $e->getMessage());
    }
}