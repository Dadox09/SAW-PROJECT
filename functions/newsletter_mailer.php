<?php
// Verifica se il file autoload.php esiste
$vendorDir = dirname(dirname(__FILE__));
$autoloadPath = $vendorDir . '/vendor/autoload.php';

if (!file_exists($autoloadPath)) {
    throw new Exception('PHPMailer non è installato. Esegui: composer require phpmailer/phpmailer');
}

require_once $autoloadPath;

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
        $mail->Username = 'pietroravera23@gmail.com';
        $mail->Password = 'cvrh eugj ylns yqwb';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        // Impostazioni del mittente
        $mail->setFrom('pietroravera23@gmail.com', 'Pietro Ravera');

        // Aggiunta dei destinatari
        foreach ($recipients as $email) {
            $mail->addAddress($email);
        }

        // Contenuto dell'email
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
            'message' => "Errore nell'invio della mail: " . $e->getMessage()
        ];
    }
}