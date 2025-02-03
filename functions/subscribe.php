<?php
require_once '../config/db_connect.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Devi essere loggato per iscriverti alla newsletter']);
    exit;
}

$user_id = $_SESSION['user_id'];
$conn = connectDB();

// Aggiorna lo stato dell'iscrizione alla newsletter
$query = "UPDATE users SET newsletter_subscribed = TRUE WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['newsletter_subscribed'] = true;
    echo json_encode(['success' => true, 'message' => 'Iscrizione alla newsletter completata con successo!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Si è verificato un errore durante l\'iscrizione alla newsletter']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);