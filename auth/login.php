<?php
session_start();
require_once '../config/config.php';
require_once '../config/db_connect.php';

if (isset($_POST['submit'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');

    if (!$email || !$password) {
        $_SESSION['error'] = "Tutti i campi sono obbligatori";
        header('Location: ' . BASE_URL . '/pages/formLogin.php');
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
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];
            
            header('Location: ' . BASE_URL);
            exit;
        } else {
            $_SESSION['error'] = "Email o password non validi";
            header('Location: ../pages/formLogin.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Errore durante il login. Riprova più tardi.";
        header('Location: ../pages/formLogin.php');
        exit;
    }
} else {
    header('Location: ../pages/formLogin.php');
    exit;
}