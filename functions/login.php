<?php
session_start();
require_once '../config/config.php';
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
$password = trim($_POST['pass']);

if (!$email || !$password) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Tutti i campi sono obbligatori'
    ]);
    exit;
}

    $conn = connectDB();
    
    if ($conn->connect_error) {
        die(json_encode([
            'status' => 'error',
            'message' => 'Errore di connessione: ' . $conn->connect_error
        ]));
    }
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

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
