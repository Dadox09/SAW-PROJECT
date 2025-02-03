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

    $conn = connectDB();
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        $stmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, password_hash($password, PASSWORD_DEFAULT), $first_name, $last_name);
        $stmt->execute();
        
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