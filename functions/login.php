<?php
session_start();
require_once '../config/config.php';
require_once '../config/db_connect.php';

// Imposta l'header per indicare che la risposta sarà in JSON
header('Content-Type: application/json');

// Verifica che la richiesta sia POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metodo non consentito'
    ]);
    exit;
}

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = isset($_POST['pass']) ? htmlspecialchars($_POST['pass'], ENT_QUOTES, 'UTF-8') : '';

if (!$email || !$password) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Tutti i campi sono obbligatori'
    ]);
    exit;
}

try {
    $pdo = connectDB();
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = $user['is_admin'];
        $_SESSION['firstname'] = $user['first_name'];
        $_SESSION['lastname'] = $user['last_name'];
        $_SESSION['email'] = $user['email'];
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Login effettuato con successo',
            'redirect' => BASE_URL,
            'user' => [
                'firstname' => $user['first_name'],
                'lastname' => $user['last_name'],
                'is_admin' => $user['is_admin']
            ]
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Email o password non validi'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Errore durante il login. Riprova più tardi.'
    ]);
}