<?php
// Carica le variabili d'ambiente dal file .env
$env = parse_ini_file(__DIR__ . '/../.env');

// Definisce le costanti del database usando le variabili d'ambiente
define('DB_HOST', $env['DB_HOST']);
define('DB_USER', $env['DB_USER']);
define('DB_PASS', $env['DB_PASS']);
define('DB_NAME', $env['DB_NAME']);

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