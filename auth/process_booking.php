<?php
session_start();
require_once '../config/config.php';
require_once '../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL);
    exit;
}

$check_in = $_POST['check_in'] ?? '';
$check_out = $_POST['check_out'] ?? '';
$guests = $_POST['guests'] ?? '';

// Validate inputs
if (!$check_in || !$check_out || !$guests) {
    $_SESSION['error'] = "Tutti i campi sono obbligatori";
    header('Location: ' . BASE_URL);
    exit;
}

// Validate dates
$check_in_date = new DateTime($check_in);
$check_out_date = new DateTime($check_out);
$today = new DateTime();

if ($check_in_date < $today) {
    $_SESSION['error'] = "La data di check-in non può essere nel passato";
    header('Location: ' . BASE_URL);
    exit;
}

if ($check_out_date <= $check_in_date) {
    $_SESSION['error'] = "La data di check-out deve essere successiva al check-in";
    header('Location: ' . BASE_URL);
    exit;
}

try {

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Devi effettuare l'accesso per prenotare";
        header('Location: ' . BASE_URL . '/pages/formLogin.php');
        exit;
    }

    $user_id = $_SESSION['user_id'];

    $pdo = connectDB();
    // Insert the booking
    $stmt = $pdo->prepare("INSERT INTO bookings (user_id, check_in_date, check_out_date, guests) VALUES (?, ?, ?, ?)");
    $result = $stmt->execute([$user_id, $check_in, $check_out, $guests]);
    
    if ($result) {
        $_SESSION['success'] = "Prenotazione effettuata con successo!";
    } else {
        throw new Exception("Errore durante la prenotazione");
    }
    
    header('Location: ' . BASE_URL);
    exit;

} catch (Exception $e) {
    $_SESSION['error'] = "Si è verificato un errore: " . $e->getMessage();
    header('Location: ' . BASE_URL);
    exit;
}
