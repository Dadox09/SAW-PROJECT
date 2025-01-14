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

$email = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
$first_name = htmlspecialchars(trim($_POST['firstname']), ENT_QUOTES, 'UTF-8');
$last_name = htmlspecialchars(trim($_POST['lastname']), ENT_QUOTES, 'UTF-8');
$password = htmlspecialchars($_POST['pass'], ENT_QUOTES, 'UTF-8');
$confirm_password = htmlspecialchars($_POST['confirm'], ENT_QUOTES, 'UTF-8');

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