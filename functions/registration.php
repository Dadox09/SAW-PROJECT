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

try {
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

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Email non valida'
        ]);
        exit;
    }

    if (strlen($password) < 8) {
        echo json_encode([
            'status' => 'error',
            'message' => 'La password deve essere di almeno 8 caratteri'
        ]);
        exit;
    }

    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/', $password)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'La password deve contenere almeno una lettera, un numero e un carattere speciale (@$!%*#?&)'
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
    
    // Verifica se l'email esiste già
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    if (!$stmt) {
        throw new Exception("Errore nella preparazione della query: " . $conn->error);
    }
    
    $stmt->bind_param("s", $email);
    if (!$stmt->execute()) {
        throw new Exception("Errore nell'esecuzione della query: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Email già registrata'
        ]);
        exit;
    }

    // Inserisci il nuovo utente
    $stmt = $conn->prepare("INSERT INTO users (email, password, first_name, last_name) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Errore nella preparazione della query di inserimento: " . $conn->error);
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt->bind_param("ssss", $email, $hashed_password, $first_name, $last_name);
    
    if (!$stmt->execute()) {
        throw new Exception("Errore nell'inserimento dell'utente: " . $stmt->error);
    }
    
    $stmt->close();
    $conn->close();

    echo json_encode([
        'status' => 'success',
        'message' => 'Utente registrato con successo',
        'redirect' => '../pages/formLogin.php'
    ]);

} catch (Exception $e) {
    error_log("Errore durante la registrazione: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Si è verificato un errore durante la registrazione. Riprova più tardi.'
    ]);
    exit;
}