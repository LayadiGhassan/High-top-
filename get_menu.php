<?php
// Header Setup Section
header('Content-Type: application/json');

// Menu Fetching Section
$pdo = new PDO("mysql:host=localhost;dbname=restaurant_db", "root", "");
$stmt = $pdo->query("SELECT * FROM menu_items");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($items);
?>