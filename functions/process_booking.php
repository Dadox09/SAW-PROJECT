<?php
session_start();
require_once '../config/config.php';
require_once '../config/db_connect.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metodo non consentito'
    ]);
    exit;
}

$check_in = $_POST['check_in'] ?? '';
$check_out = $_POST['check_out'] ?? '';
$guests = $_POST['guests'] ?? '';

// Validate inputs
if (!$check_in || !$check_out || !$guests) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Tutti i campi sono obbligatori'
    ]);
    exit;
}

// Validate dates
$check_in_date = new DateTime($check_in);
$check_out_date = new DateTime($check_out);
$today = new DateTime();

if ($check_in_date < $today) {
    echo json_encode([
        'status' => 'error',
        'message' => "La data di check-in non può essere nel passato"
    ]);
    exit;
}

if ($check_out_date <= $check_in_date) {
    echo json_encode([
        'status' => 'error',
        'message' => "La data di check-out deve essere successiva al check-in"
    ]);
    exit;
}

try {

    if (!isset($_SESSION['user_id'])) {
        echo json_encode([
            'status' => 'error',
            'message' => "Devi effettuare l'accesso per prenotare" . "<a href='" . BASE_URL . "/pages/formLogin.php'>Accedi ora</a>"
        ]);
        exit;
    }

    $user_id = $_SESSION['user_id'];

    $conn = connectDB();
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, check_in_date, check_out_date, guests) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $check_in, $check_out, $guests);
    $result = $stmt->execute();
    
    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Prenotazione effettuata con successo!',
            'redirect' => BASE_URL
        ]);
        exit;
    } else {
        throw new Exception("Errore durante la prenotazione");
    }
    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => "Si è verificato un errore: " . $e->getMessage()
    ]);
    exit;
}
