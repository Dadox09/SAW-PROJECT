<?php
require_once __DIR__ . '/Exception.php';
require_once __DIR__ . '/SMTP.php';
require_once __DIR__ . '/PHPMailer.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Funzione per inviare la newsletter
function sendNewsletter($recipients, $subject, $message) {
    try {
        $mail = new PHPMailer(true);

        // Configurazione del server SMTP di Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'coccoleecroissant@gmail.com';
        $mail->Password = 'nsgv oszm ferd clue';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        // Impostazioni del mittente
        $mail->setFrom('coccoleecroissant@gmail.com', 'CoccoLe e Croissant');

        foreach ($recipients as $email) {
            $mail->addAddress($email);
        }

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = strip_tags($message);

        $mail->send();
        return [
            'success' => true,
            'message' => 'Email inviata con successo!'
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Errore nell\'invio dell\'email: ' . $e->getMessage()
        ];
    }
}