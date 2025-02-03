<?php
session_start();
require_once '../config/db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metodo non consentito'
    ]);
    exit;
}

$email = trim($_POST['email']);
$first_name = trim($_POST['firstname']);
$last_name = trim($_POST['lastname']);
$password = trim($_POST['pass']);
$confirm_password = trim($_POST['confirm']);

if (!$email || !$password || !$first_name || !$last_name) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Tutti i campi sono obbligatori'
    ]);
    exit;
}

if ($password !== $confirm_password) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Le password non corrispondono'
    ]);
    exit;
}

try {
    $pdo = connectDB();
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user === false) {
        $stmt = $pdo->prepare("INSERT INTO users (email, password, first_name, last_name) VALUES (?, ?, ?, ?)");
        $stmt->execute([$email, password_hash($password, PASSWORD_DEFAULT), $first_name, $last_name]);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Utente registrato con successo',
            'redirect' => '../pages/formLogin.php'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Email già registrata'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Errore durante la registrazione. Riprova più tardi.'
    ]);
}