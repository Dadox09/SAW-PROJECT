<?php
require_once '../config/db_connect.php';
header('Content-Type: application/json');

try {
    $conn = connectDB();
    $query = "SELECT * FROM products";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $products = $stmt->get_result();
    $products = $products->fetch_all(MYSQLI_ASSOC);
    
    echo json_encode(['success' => true, 'products' => $products]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Errore nel recupero dei prodotti']);
}
?>
