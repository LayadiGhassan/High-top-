<?php
header('Content-Type: application/json');
$pdo = new PDO("mysql:host=localhost;dbname=restaurant_db", "root", "");
$stmt = $pdo->query("SELECT * FROM menu_items");
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($items);
?>