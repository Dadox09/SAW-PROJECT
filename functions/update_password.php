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
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$errors = [];

// Validazione input
if (empty($current_password)) {
    $errors[] = "La password attuale è obbligatoria";
}

if (empty($new_password)) {
    $errors[] = "La nuova password è obbligatoria";
}

// Verifica requisiti nuova password
if (strlen($new_password) < 8) {
    $errors[] = "La password deve essere di almeno 8 caratteri";
}

if (!preg_match("/[A-Za-z]/", $new_password)) {
    $errors[] = "La password deve contenere almeno una lettera";
}

if (!preg_match("/\d/", $new_password)) {
    $errors[] = "La password deve contenere almeno un numero";
}

if (!empty($errors)) {
    echo json_encode([
        'status' => 'error',
        'message' => implode("<br>", $errors)
    ]);
    exit;
}

try {
    $pdo = connectDB();
    
    // Verifica password attuale
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($current_password, $user['password'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'La password attuale non è corretta'
        ]);
        exit;
    }

    // Aggiorna la password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $result = $stmt->execute([$hashed_password, $user_id]);

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
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => "Errore del database: " . $e->getMessage()
    ]);
}