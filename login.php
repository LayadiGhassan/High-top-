<?php
session_start();
if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Login Handling Section
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = new PDO("mysql:host=localhost;dbname=restaurant_db", "root", "");
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();

    if($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid credentials";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Head Section: Metadata and Styles -->
    <title>Login - High Top</title>
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
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Login Form Section -->
    <section id="login" class="d-flex align-items-center justify-content-center min-vh-100">
        <div class="container text-center">
            <h2 class="mb-4">Login</h2>
            <?php if(isset($error)) echo "<p class='alert alert-danger'>$error</p>"; ?>
            <form method="POST" class="mx-auto" style="max-width: 400px;">
                <div class="mb-3">
                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
                <p class="mt-3">Not registered? <a href="register.php" class="text-primary">Register here</a></p>
            </form>
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