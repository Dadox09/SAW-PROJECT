<?php
require_once '../config/db_connect.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

header('Content-Type: application/json');

// Verifica che l'utente sia admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    echo json_encode(['success' => false, 'message' => 'Accesso non autorizzato. Solo gli amministratori possono inviare newsletter.']);
    exit;
}

// Verifica che siano stati inviati i dati necessari
if (!isset($_POST['subject']) || !isset($_POST['content'])) {
    echo json_encode(['success' => false, 'message' => 'Soggetto e contenuto della newsletter sono obbligatori']);
    exit;
}

$subject = $_POST['subject'];
$content = $_POST['content'];

// Connessione al database
$conn = connectDB();

// Recupera tutti gli utenti iscritti alla newsletter
$query = "SELECT email, first_name, last_name FROM users WHERE newsletter_subscribed = TRUE";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Errore nel recupero degli iscritti alla newsletter']);
    mysqli_close($conn);
    exit;
}

// Configura PHPMailer
$mail = new PHPMailer(true);

try {
    // Configurazione del server
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';  // Sostituisci con il tuo server SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'your-email@gmail.com'; // Sostituisci con la tua email
    $mail->Password = 'your-password';        // Sostituisci con la tua password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    // Impostazioni del mittente
    $mail->setFrom('your-email@gmail.com', 'Nome Hotel'); // Sostituisci con i tuoi dati
    $mail->isHTML(true);
    $mail->Subject = $subject;

    // Contatori
    $success_count = 0;
    $error_count = 0;
    $error_emails = [];

    // Invia email a tutti gli iscritti
    while ($subscriber = mysqli_fetch_assoc($result)) {
        $personalizedContent = $content;
        if (!empty($subscriber['first_name'])) {
            $personalizedContent = "Gentile " . htmlspecialchars($subscriber['first_name']) . 
                                 (!empty($subscriber['last_name']) ? " " . htmlspecialchars($subscriber['last_name']) : "") . 
                                 ",<br><br>" . $content;
        }

        $mail->Body = $personalizedContent;
        $mail->clearAddresses();
        $mail->addAddress($subscriber['email']);

        try {
            $mail->send();
            $success_count++;
        } catch (Exception $e) {
            $error_count++;
            $error_emails[] = $subscriber['email'];
        }
    }

    // Prepara il messaggio di risposta
    $response = [
        'success' => true,
        'message' => "Newsletter inviata con successo a $success_count destinatari."
    ];

    if ($error_count > 0) {
        $response['warning'] = "Non è stato possibile inviare la newsletter a $error_count indirizzi email.";
        $response['failed_emails'] = $error_emails;
    }

    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Errore durante l\'invio della newsletter: ' . $e->getMessage()
    ]);
}

mysqli_free_result($result);
mysqli_close($conn);