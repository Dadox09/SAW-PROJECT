<?php
session_start();
require_once '../config/db_connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Utente non autenticato'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metodo non consentito'
    ]);
    exit;
}

try {
    $user_id = $_SESSION['user_id'];
    $current_password = trim($_POST['current_password'] ?? '');
    $new_password = trim($_POST['new_password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Validazione input
    if (!$current_password || !$new_password || !$confirm_password) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Tutti i campi sono obbligatori'
        ]);
        exit;
    }

    if (strlen($new_password) < 8) {
        echo json_encode([
            'status' => 'error',
            'message' => 'La password deve essere di almeno 8 caratteri'
        ]);
        exit;
    }
    
    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/', $new_password)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'La password deve contenere almeno una lettera, un numero e un carattere speciale (@$!%*#?&)'
        ]);
        exit;
    }

    if ($new_password !== $confirm_password) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Le nuove password non corrispondono'
        ]);
        exit;
    }

    $conn = connectDB();

    // Verifica password attuale
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    if (!$stmt) {
        throw new Exception("Errore nella preparazione della query: " . $conn->error);
    }

    $stmt->bind_param("i", $user_id);
    if (!$stmt->execute()) {
        throw new Exception("Errore nell'esecuzione della query: " . $stmt->error);
    }

    $stmt->bind_result($stored_password);
    $stmt->fetch();
    $stmt->close();

    if (!password_verify($current_password, $stored_password)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'La password attuale non è corretta'
        ]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt->bind_param("si", $hashed_password, $user_id);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    echo json_encode([
        'status' => 'success',
        'message' => 'Password aggiornata con successo'
    ]);

} catch (Exception $e) {
    error_log("Errore durante l'aggiornamento della password: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => "Si è verificato un errore durante l'aggiornamento della password. Riprova più tardi."
    ]);
    exit;
}