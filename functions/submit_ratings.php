<?php
session_start();
require_once dirname(__FILE__) . '/../config/db_connect.php';
require_once dirname(__FILE__) . '/ratings.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Devi effettuare il login per lasciare una recensione'
    ]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    

    if ($rating < 1 || $rating > 5 || empty($comment)) {
        echo json_encode([
            'success' => false,
            'message' => 'Per favore, inserisci una valutazione valida e un commento'
        ]);
        exit();
    }
    
    // Salva la recensione
    if (saveRating($userId, $rating, $comment)) {
        echo json_encode([
            'success' => true,
            'message' => 'Grazie per la tua recensione!'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Si è verificato un errore durante il salvataggio della recensione'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Richiesta non valida'
    ]);
}
?>
