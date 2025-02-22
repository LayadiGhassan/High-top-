<?php
// Session and Header Setup Section
session_start();
header('Content-Type: application/json');

// Cart Addition Section
if(!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$_SESSION['cart'][$data['itemId']] = $data['quantity'];

echo json_encode(['success' => true]);
?>