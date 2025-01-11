<?php
session_start();
require_once '../config/db_connect.php';

if (isset($_POST['submit'])) {
    $email = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
    $first_name = htmlspecialchars(trim($_POST['firstname']), ENT_QUOTES, 'UTF-8');
    $last_name = htmlspecialchars(trim($_POST['lastname']), ENT_QUOTES, 'UTF-8');
    $password = htmlspecialchars($_POST['pass'], ENT_QUOTES, 'UTF-8');
    $confirm_password = htmlspecialchars($_POST['confirm'], ENT_QUOTES, 'UTF-8');

    if (!$email || !$password || !$first_name || !$last_name) {
        $_SESSION['error'] = "Tutti i campi sono obbligatori";
        header('Location: ../pages/formRegistration.php');
        exit;
    }

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Le password non corrispondono";
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