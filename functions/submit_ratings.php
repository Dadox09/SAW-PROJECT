<?php
session_start();
require_once dirname(__FILE__) . '/../config/db_connect.php';
require_once dirname(__FILE__) . '/ratings.php';

// Funzione per inviare risposta JSON
function sendJsonResponse($success, $message = '') {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'message' => $message
    ]);
    exit();
}

// Verifica se l'utente è loggato
if (!isset($_SESSION['user_id'])) {
    sendJsonResponse(false, 'Devi effettuare il login per lasciare una recensione');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    
    // Validazione
    if ($rating < 1 || $rating > 5 || empty($comment)) {
        sendJsonResponse(false, 'Per favore, inserisci una valutazione valida e un commento');
    }
    
    // Salva la recensione
    if (saveRating($userId, $rating, $comment)) {
        sendJsonResponse(true, 'Grazie per la tua recensione!');
    } else {
        sendJsonResponse(false, 'Si è verificato un errore durante il salvataggio della recensione');
    }
}

// Se non è una richiesta POST
sendJsonResponse(false, 'Richiesta non valida');
?>
