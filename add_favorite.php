<?php
// Session and Header Setup Section
session_start();
header('Content-Type: application/json');

// Favorite Addition Section
if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$pdo = new PDO("mysql:host=localhost;dbname=restaurant_db", "root", "");
$stmt = $pdo->prepare("INSERT INTO favorites (user_id, menu_item_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE id=id");
$stmt->execute([$_SESSION['user_id'], $data['itemId']]);

echo json_encode(['success' => true]);
?>