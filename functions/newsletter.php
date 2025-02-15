<?php
header('Content-Type: application/json');
header('Cache-Control: no-cache, must-revalidate');

// Gestione degli errori
error_reporting(E_ALL);
ini_set('display_errors', 0);
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});


try {
    // Verifica se è una richiesta POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Metodo non valido']);
        exit;
    }

    // Verifica se l'email è stata inviata
    if (!isset($_POST['email']) || empty($_POST['email'])) {
        echo json_encode(['success' => false, 'message' => 'Email mancante']);
        exit;
    }

    $email = trim($_POST['email']);

    // Valida l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email non valida']);
        exit;
    }

    // Connessione al database
    $pdo = new PDO("mysql:host=localhost;dbname=sawproject", 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Verifica se l'email esiste già
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM newsletter_subscribers WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Questa email è già iscritta alla newsletter']);
        exit;
    }

    // Inserisci l'email nel database
    $stmt = $pdo->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
    $stmt->bindParam(1, $email);
    $stmt->execute();

    // Prepara l'email di benvenuto
    $subject = "Benvenuto nella nostra Newsletter!";
    $message = "
    <html>
    <head>
        <title>Benvenuto nella Newsletter</title>
    </head>
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

    // Invia l'email
    require_once __DIR__ . '/newsletter_mailer.php';
    $mailResult = sendNewsletter([$email], $subject, $message);


    // Invia la risposta
    echo json_encode([
        'success' => true,
        'message' => $mailResult['success'] 
            ? 'Iscrizione completata con successo! Controlla la tua email per il codice sconto.'
            : 'Iscrizione completata, ma non è stato possibile inviare l\'email di conferma: ' . $mailResult['message']

    ]);
    exit;

} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Errore del database. Per favore riprova più tardi.']);
    exit;
} catch (Exception $e) {
    error_log('Application error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
} catch (Throwable $e) {
    error_log('Unexpected error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Errore: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine()
    ]);
    exit;
}