<?php
session_start();
include '../config/db_connect.php';

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

$user_id = $_SESSION['user_id'];
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// Validazione input
if (empty($current_password)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'La password attuale è obbligatoria'
    ]);
    exit;
}

if (empty($new_password)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'La nuova password è obbligatoria'
    ]);
    exit;
}

// Verifica requisiti nuova password
if (strlen($new_password) < 8) {
    echo json_encode([
        'status' => 'error',
        'message' => 'La password deve essere di almeno 8 caratteri'
    ]);
    exit;
}

if (!preg_match("/[A-Za-z]/", $new_password)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'La password deve contenere almeno una lettera'
    ]);
    exit;
}

if (!preg_match("/\d/", $new_password)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'La password deve contenere almeno un numero'
    ]);
    exit;
}

// Verifica se la password attuale è corretta
if($new_password === $confirm_password) {
    $conn = connectDB();

    // Verifica password attuale
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($current_password, $user['password'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'La password attuale non è corretta'
        ]);
        exit;
    }

    // Aggiorna la password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hashed_password, $user_id);
    $result = $stmt->execute();

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Password aggiornata con successo'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => "Errore durante l'aggiornamento della password"
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Le due password non coincidono'
    ]);
}
    