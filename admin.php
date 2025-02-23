<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=restaurant_db", "root", "");

// Authentication Section
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$is_admin = $stmt->fetchColumn();
if(!$is_admin) {
    header("Location: index.php");
    exit();
}

// Pagination Settings Section
$items_per_page = 15;

// Menu Item Addition Section
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $target_dir = "images/";
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $image_name;
    if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $stmt = $pdo->prepare("INSERT INTO menu_items (name, description, price, category, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $price, $category, $target_file]);
    }
}

// Menu Item Deletion Section
if(isset($_GET['delete_item'])) {
    $id = $_GET['delete_item'];
    $stmt = $pdo->prepare("DELETE FROM menu_items WHERE id = ?");
    $stmt->execute([$id]);
}

// Pagination for Users Section
$user_page = isset($_GET['user_page']) ? (int)$_GET['user_page'] : 1;
$user_offset = ($user_page - 1) * $items_per_page;
$user_total = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$user_pages = ceil($user_total / $items_per_page);
$users = $pdo->query("SELECT * FROM users LIMIT $user_offset, $items_per_page")->fetchAll();

// Pagination for Menu Items Section
$menu_page = isset($_GET['menu_page']) ? (int)$_GET['menu_page'] : 1;
$menu_offset = ($menu_page - 1) * $items_per_page;
$menu_total = $pdo->query("SELECT COUNT(*) FROM menu_items")->fetchColumn();
$menu_pages = ceil($menu_total / $items_per_page);
$menu_items = $pdo->query("SELECT * FROM menu_items LIMIT $menu_offset, $items_per_page")->fetchAll();

// Pagination for Orders Section
$order_page = isset($_GET['order_page']) ? (int)$_GET['order_page'] : 1;
$order_offset = ($order_page - 1) * $items_per_page;
$order_total = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$order_pages = ceil($order_total / $items_per_page);
$orders = $pdo->query("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id LIMIT $order_offset, $items_per_page")->fetchAll();

// Pagination for Reservations Section
$reservation_page = isset($_GET['reservation_page']) ? (int)$_GET['reservation_page'] : 1;
$reservation_offset = ($reservation_page - 1) * $items_per_page;
$reservation_total = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
$reservation_pages = ceil($reservation_total / $items_per_page);
$reservations = $pdo->query("SELECT r.*, u.username FROM reservations r JOIN users u ON r.user_id = u.id LIMIT $reservation_offset, $items_per_page")->fetchAll();

// Pagination for Contacts Section
$contact_page = isset($_GET['contact_page']) ? (int)$_GET['contact_page'] : 1;
$contact_offset = ($contact_page - 1) * $items_per_page;
$contact_total = $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
$contact_pages = ceil($contact_total / $items_per_page);
$contacts = $pdo->query("SELECT * FROM contacts LIMIT $contact_offset, $items_per_page")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Head Section: Metadata and Styles -->
    <title>Admin - High Top</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation Bar Section -->
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
                    <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Admin Dashboard Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Admin Dashboard</h2>

            <!-- Users Subsection -->
            <h3>Users</h3>
            <table class="table table-striped mb-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Admin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['is_admin'] ? 'Yes' : 'No'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Users Pagination -->
            <nav aria-label="User Pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php if($user_page <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?user_page=<?php echo $user_page - 1; ?>&menu_page=<?php echo $menu_page; ?>&order_page=<?php echo $order_page; ?>&reservation_page=<?php echo $reservation_page; ?>&contact_page=<?php echo $contact_page; ?>">Previous</a>
                    </li>
                    <li class="page-item disabled"><span class="page-link">Page <?php echo $user_page; ?> of <?php echo $user_pages; ?></span></li>
                    <li class="page-item <?php if($user_page >= $user_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="?user_page=<?php echo $user_page + 1; ?>&menu_page=<?php echo $menu_page; ?>&order_page=<?php echo $order_page; ?>&reservation_page=<?php echo $reservation_page; ?>&contact_page=<?php echo $contact_page; ?>">Next</a>
                    </li>
                </ul>
            </nav>

            <!-- Menu Items Subsection -->
            <h3>Menu Items</h3>
            <form method="POST" enctype="multipart/form-data" class="mb-4">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <input type="text" class="form-control" name="name" placeholder="Name" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <input type="text" class="form-control" name="description" placeholder="Description" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <input type="number" step="0.01" class="form-control" name="price" placeholder="Price" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <input type="text" class="form-control" name="category" placeholder="Category" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <input type="file" class="form-control" name="image" accept="image/*" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Add Item</button>
            </form>
            <table class="table table-striped mb-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($menu_items as $item): ?>
                        <tr>
                            <td><?php echo $item['id']; ?></td>
                            <td><?php echo $item['name']; ?></td>
                            <td><?php echo $item['description']; ?></td>
                            <td><?php echo number_format($item['price'], 2); ?> DA</td>
                            <td><?php echo $item['category']; ?></td>
                            <td><img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" style="max-height: 50px;"></td>
                            <td><a href="admin.php?delete_item=<?php echo $item['id']; ?>&user_page=<?php echo $user_page; ?>&menu_page=<?php echo $menu_page; ?>&order_page=<?php echo $order_page; ?>&reservation_page=<?php echo $reservation_page; ?>&contact_page=<?php echo $contact_page; ?>" class="btn btn-danger btn-sm">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Menu Items Pagination -->
            <nav aria-label="Menu Pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php if($menu_page <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?user_page=<?php echo $user_page; ?>&menu_page=<?php echo $menu_page - 1; ?>&order_page=<?php echo $order_page; ?>&reservation_page=<?php echo $reservation_page; ?>&contact_page=<?php echo $contact_page; ?>">Previous</a>
                    </li>
                    <li class="page-item disabled"><span class="page-link">Page <?php echo $menu_page; ?> of <?php echo $menu_pages; ?></span></li>
                    <li class="page-item <?php if($menu_page >= $menu_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="?user_page=<?php echo $user_page; ?>&menu_page=<?php echo $menu_page + 1; ?>&order_page=<?php echo $order_page; ?>&reservation_page=<?php echo $reservation_page; ?>&contact_page=<?php echo $contact_page; ?>">Next</a>
                    </li>
                </ul>
            </nav>

            <!-- Orders Subsection -->
            <h3>Orders</h3>
            <table class="table table-striped mb-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($orders as $order): ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo $order['username']; ?></td>
                            <td><?php echo number_format($order['total'], 2); ?> DA</td>
                            <td><?php echo $order['status']; ?></td>
                            <td><?php echo $order['created_at']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Orders Pagination -->
            <nav aria-label="Order Pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php if($order_page <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?user_page=<?php echo $user_page; ?>&menu_page=<?php echo $menu_page; ?>&order_page=<?php echo $order_page - 1; ?>&reservation_page=<?php echo $reservation_page; ?>&contact_page=<?php echo $contact_page; ?>">Previous</a>
                    </li>
                    <li class="page-item disabled"><span class="page-link">Page <?php echo $order_page; ?> of <?php echo $order_pages; ?></span></li>
                    <li class="page-item <?php if($order_page >= $order_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="?user_page=<?php echo $user_page; ?>&menu_page=<?php echo $menu_page; ?>&order_page=<?php echo $order_page + 1; ?>&reservation_page=<?php echo $reservation_page; ?>&contact_page=<?php echo $contact_page; ?>">Next</a>
                    </li>
                </ul>
            </nav>

            <!-- Reservations Subsection -->
            <h3>Reservations</h3>
            <table class="table table-striped mb-4">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Date/Time</th>
                        <th>Guests</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($reservations as $reservation): ?>
                        <tr>
                            <td><?php echo $reservation['id']; ?></td>
                            <td><?php echo $reservation['username']; ?></td>
                            <td><?php echo $reservation['date_time']; ?></td>
                            <td><?php echo $reservation['guests']; ?></td>
                            <td><?php echo $reservation['status']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Reservations Pagination -->
            <nav aria-label="Reservation Pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php if($reservation_page <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?user_page=<?php echo $user_page; ?>&menu_page=<?php echo $menu_page; ?>&order_page=<?php echo $order_page; ?>&reservation_page=<?php echo $reservation_page - 1; ?>&contact_page=<?php echo $contact_page; ?>">Previous</a>
                    </li>
                    <li class="page-item disabled"><span class="page-link">Page <?php echo $reservation_page; ?> of <?php echo $reservation_pages; ?></span></li>
                    <li class="page-item <?php if($reservation_page >= $reservation_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="?user_page=<?php echo $user_page; ?>&menu_page=<?php echo $menu_page; ?>&order_page=<?php echo $order_page; ?>&reservation_page=<?php echo $reservation_page + 1; ?>&contact_page=<?php echo $contact_page; ?>">Next</a>
                    </li>
                </ul>
            </nav>

            <!-- Contacts Subsection -->
            <h3>Contact Messages</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($contacts as $contact): ?>
                        <tr>
                            <td><?php echo $contact['id']; ?></td>
                            <td><?php echo $contact['name']; ?></td>
                            <td><?php echo $contact['email']; ?></td>
                            <td><?php echo $contact['message']; ?></td>
                            <td><?php echo $contact['created_at']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Contacts Pagination -->
            <nav aria-label="Contact Pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php if($contact_page <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="?user_page=<?php echo $user_page; ?>&menu_page=<?php echo $menu_page; ?>&order_page=<?php echo $order_page; ?>&reservation_page=<?php echo $reservation_page; ?>&contact_page=<?php echo $contact_page - 1; ?>">Previous</a>
                    </li>
                    <li class="page-item disabled"><span class="page-link">Page <?php echo $contact_page; ?> of <?php echo $contact_pages; ?></span></li>
                    <li class="page-item <?php if($contact_page >= $contact_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="?user_page=<?php echo $user_page; ?>&menu_page=<?php echo $menu_page; ?>&order_page=<?php echo $order_page; ?>&reservation_page=<?php echo $reservation_page; ?>&contact_page=<?php echo $contact_page + 1; ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="py-3 text-center fixed-bottom">
        <p>© 2025 High Top. All rights reserved.</p>
    </footer>

    <!-- Scripts Section -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>