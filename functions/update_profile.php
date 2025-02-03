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

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Metodo non consentito'
    ]);
    exit;
}

$conn = connectDB();

$user_id = $_SESSION['user_id'];
$email = trim($_POST['email']);
$first_name = trim($_POST['firstname']);
$last_name = trim($_POST['lastname']);

    // Validate inputs
    if (empty($first_name) || !preg_match("/^[A-Za-z\s]+$/", $first_name)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Il nome deve contenere solo lettere e spazi'
        ]);
        exit;
    }

    if (empty($last_name) || !preg_match("/^[A-Za-z\s]+$/", $last_name)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Il cognome deve contenere solo lettere e spazi'
        ]);
        exit;
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Email non valida'
        ]);
        exit;
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->bind_param("si", $email, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Questa email è già in uso'
        ]);
        exit;
    }


            $stmt = $conn->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?");
            $stmt->bind_param("sssi", $first_name, $last_name, $email, $user_id);
            $result = $stmt->execute();

            if ($result) {
                // Update session variables
                $_SESSION['firstname'] = $first_name;
                $_SESSION['lastname'] = $last_name;
                $_SESSION['email'] = $email;

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Profilo aggiornato con successo!',
                    'data' => [
                        'firstname' => $first_name,
                        'lastname' => $last_name,
                        'email' => $email
                    ]
                ]);
                exit;
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => "Errore durante l'aggiornamento del profilo"
                ]);
                exit;
            }
