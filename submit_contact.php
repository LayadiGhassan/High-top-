<?php
// Contact Form Handling Section
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = new PDO("mysql:host=localhost;dbname=restaurant_db", "root", "");
    $stmt = $pdo->prepare("INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)");
    $stmt->execute([
        ':name' => $_POST['name'],
        ':email' => $_POST['email'],
        ':message' => $_POST['message']
    ]);
    header("Location: index.php#contact");
    exit();
}
?>