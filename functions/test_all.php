<?php
header('Content-Type: application/json');

$results = [];

// Test Database
try {
    $pdo = new PDO("mysql:host=localhost;dbname=sawproject", 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    
    $stmt = $pdo->query("SELECT COUNT(*) FROM newsletter_subscribers");
    $count = $stmt->fetchColumn();
    
    $results['database'] = [
        'success' => true,
        'message' => 'Connessione al database riuscita',
        'subscribers_count' => $count
    ];
} catch (PDOException $e) {
    $results['database'] = [
        'success' => false,
        'message' => 'Errore database: ' . $e->getMessage()
    ];
}

// Test Email
try {
    require_once __DIR__ . '/newsletter_mailer.php';
    
    $testResult = sendNewsletter(
        ['rizzodavidege@gmail.com'], 
        'Test Newsletter System', 
        '<h1>Test Email</h1><p>Questo è un test del sistema newsletter.</p>'
    );
    
    $results['email'] = $testResult;
} catch (Exception $e) {
    $results['email'] = [
        'success' => false,
        'message' => 'Errore email: ' . $e->getMessage()
    ];
}

echo json_encode($results, JSON_PRETTY_PRINT);
