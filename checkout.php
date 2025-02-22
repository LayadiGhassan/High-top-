<?php
// Session and Header Setup Section
session_start();
header('Content-Type: application/json');

// Checkout Handling Section
if(!isset($_SESSION['user_id']) || !isset($_SESSION['cart'])) {
    echo json_encode(['success' => false, 'message' => 'No cart or not logged in']);
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=restaurant_db", "root", "");
$total = 0;

$stmt = $pdo->prepare("SELECT price FROM menu_items WHERE id = ?");
foreach($_SESSION['cart'] as $itemId => $quantity) {
    $stmt->execute([$itemId]);
    $price = $stmt->fetchColumn();
    $total += $price * $quantity;
}

$order_stmt = $pdo->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
$order_stmt->execute([$_SESSION['user_id'], $total]);
$order_id = $pdo->lastInsertId();

$item_stmt = $pdo->prepare("INSERT INTO order_items (order_id, menu_item_id, quantity, price) VALUES (?, ?, ?, ?)");
foreach($_SESSION['cart'] as $itemId => $quantity) {
    $stmt->execute([$itemId]);
    $price = $stmt->fetchColumn();
    $item_stmt->execute([$order_id, $itemId, $quantity, $price]);
}

unset($_SESSION['cart']);
echo json_encode(['success' => true]);
?>