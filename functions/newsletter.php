<?php
session_start();
require_once '../config/config.php';
require_once '../config/db_connect.php';
header('Content-Type: application/json');

try {
    $conn = connectDB();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Metodo non valido']);
        exit;
    }

    if (!isset($_POST['email']) || empty($_POST['email'])) {
        echo json_encode(['success' => false, 'message' => 'Email mancante']);
        exit;
    }

    $email = trim($_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email non valida']);
        exit;
    }

    // Verifica se l'email esiste già
    $stmt = $conn->prepare("SELECT COUNT(*) FROM newsletter_subscribers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo json_encode(['success' => false, 'message' => 'Questa email è già iscritta alla newsletter']);
        exit;
    }

    // Inserisci la nuova email
    $stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->close();

    $subject = "Benvenuto nella nostra Newsletter!";
    $message = "
    <html>
    <body>
        <h2>Grazie per esserti iscritto alla nostra newsletter!</h2>
        <p>Ciao,</p>
        <p>Grazie per esserti iscritto alla newsletter di Coccole e Croissant B&B. Da ora riceverai aggiornamenti su:</p>
        <ul>
            <li>Nuove offerte speciali</li>
            <li>Eventi esclusivi</li>
            <li>Consigli per il tuo soggiorno</li>
            <li>Novità sul nostro B&B</li>
        </ul>
        <p>Come promesso, ecco il tuo codice sconto di 5€: <strong>WELCOME5</strong></p>
        <p>Puoi utilizzare questo codice per il tuo prossimo soggiorno presso di noi.</p>
        <p>Cordiali saluti,<br>Il team di Coccole e Croissant B&B</p>
    </body>
    </html>
    ";

    require_once __DIR__ . '/newsletter_mailer.php';
    $mailResult = sendNewsletter([$email], $subject, $message);

    echo json_encode([
        'success' => true,
        'message' => $mailResult['success'] 
            ? 'Iscrizione completata con successo! Controlla la tua email per il codice sconto.'
            : 'Iscrizione completata, ma non è stato possibile inviare l\'email di conferma: ' . $mailResult['message']
    ]);
    exit;

} catch (Exception $e) {
    error_log('Newsletter error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Errore durante l\'iscrizione. Per favore riprova più tardi.']);
    exit;
}