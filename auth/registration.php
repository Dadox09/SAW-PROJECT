<?php
session_start();
require_once '../config/config.php';
require_once '../config/db_connect.php';

if (isset($_POST['submit'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    if (!$email || !$password || !$first_name || !$last_name) {
        $_SESSION['error'] = "Tutti i campi sono obbligatori";
        header('Location: ../pages/formRegistration.php');
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
            $_SESSION['success'] = "Utente registrato con successo";
            header('Location: ../pages/formRegistration.php');
            exit;
        } else {
            $_SESSION['error'] = "Email già registrata";
            header('Location: ../pages/formRegistration.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Errore durante la registrazione. Riprova più tardi.";
        header('Location: ../pages/formRegistration.php');
        exit;
    }
} else {
    header('Location: ../pages/formRegistration.php');
    exit;
}