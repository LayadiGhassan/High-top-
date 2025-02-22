<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$pdo = new PDO("mysql:host=localhost;dbname=restaurant_db", "root", "");
$fav_stmt = $pdo->prepare("SELECT m.* FROM menu_items m JOIN favorites f ON m.id = f.menu_item_id WHERE f.user_id = ?");
$fav_stmt->execute([$_SESSION['user_id']]);
$favorites = $fav_stmt->fetchAll();

$order_stmt = $pdo->prepare("SELECT o.*, oi.*, m.name FROM orders o 
    JOIN order_items oi ON o.id = oi.order_id 
    JOIN menu_items m ON oi.menu_item_id = m.id 
    WHERE o.user_id = ? ORDER BY o.created_at DESC LIMIT 5");
$order_stmt->execute([$_SESSION['user_id']]);
$orders = $order_stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profile - High Top</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/logo.png" alt="High Top Logo" class="logo-img">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#menu">Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#reservation">Reservation</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php#contact">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                    <?php
                    $stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
                    $stmt->execute([$_SESSION['user_id']]);
                    $is_admin = $stmt->fetchColumn();
                    if($is_admin): ?>
                        <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Your Profile</h2>
            <div class="row">
                <div class="col-md-6">
                    <h3>Your Favorites</h3>
                    <?php foreach($favorites as $item): ?>
                        <div class="menu-item mb-3 p-3">
                            <h4><?php echo $item['name']; ?></h4>
                            <p><?php echo $item['description']; ?></p>
                            <p>$<?php echo number_format($item['price'], 2); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="col-md-6">
                    <h3>Last Orders</h3>
                    <?php foreach($orders as $order): ?>
                        <div class="menu-item mb-3 p-3">
                            <p><?php echo $order['name']; ?> - Quantity: <?php echo $order['quantity']; ?> - $<?php echo number_format($order['price'], 2); ?></p>
                            <p>Order Date: <?php echo $order['created_at']; ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-3 text-center">
        <p>© 2025 High Top. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>