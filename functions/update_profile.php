<?php
session_start();
include '../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Utente non autenticato']);
    exit;
}

$pdo = connectDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $user_id = $_SESSION['user_id'];
    $email = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
    $first_name = htmlspecialchars(trim($_POST['firstname']), ENT_QUOTES, 'UTF-8');
    $last_name = htmlspecialchars(trim($_POST['lastname']), ENT_QUOTES, 'UTF-8');
    $errors = [];

    // Validate inputs
    if (empty($first_name) || !preg_match("/^[A-Za-z\s]+$/", $first_name)) {
        $errors[] = "Il nome deve contenere solo lettere e spazi";
    }

    if (empty($last_name) || !preg_match("/^[A-Za-z\s]+$/", $last_name)) {
        $errors[] = "Il cognome deve contenere solo lettere e spazi";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email non valida";
    }

    // Check if email already exists for another user
    if (!empty($email)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $user_id]);
        if ($stmt->rowCount() > 0) {
            $errors[] = "Questa email è già in uso";
        }
    }

    // If no errors, proceed with update
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?");
            $result = $stmt->execute([$first_name, $last_name, $email, $user_id]);

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
        } catch (PDOException $e) {
            echo json_encode([
                'status' => 'error',
                'message' => "Errore del database: " . $e->getMessage()
            ]);
            exit;
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => implode("<br>", $errors)
        ]);
        exit;
    }
} else {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Metodo non consentito'
    ]);
    exit;
}