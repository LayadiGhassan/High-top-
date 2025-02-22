<?php
// Session and Header Setup Section
session_start();
header('Content-Type: application/json');

// Reservation Handling Section
if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$pdo = new PDO("mysql:host=localhost;dbname=restaurant_db", "root", "");
$stmt = $pdo->prepare("INSERT INTO reservations (user_id, date_time, guests) VALUES (?, ?, ?)");
$stmt->execute([$_SESSION['user_id'], $data['date'], $data['guests']]);

echo json_encode(['success' => true]);
?>