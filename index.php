<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head Section: Metadata and Styles -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>High Top</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#menu">Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="#reservation">Reservation</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>
                        <?php
                        $pdo = new PDO("mysql:host=localhost;dbname=restaurant_db", "root", "");
                        $stmt = $pdo->prepare("SELECT is_admin FROM users WHERE id = ?");
                        $stmt->execute([$_SESSION['user_id']]);
                        $is_admin = $stmt->fetchColumn();
                        if($is_admin): ?>
                            <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Home Section: Swiper Carousel -->
    <section id="home">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="images/dish1.jpg" alt="Grilled Salmon">
                    <div class="carousel-caption">
                        <h1>Syrian Shawarma</h1>
                        <p>yami yami</p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <img src="images/dish2.jpg" alt="Steak Supreme">
                    <div class="carousel-caption">
                        <h1>Saudi Mandi</h1>
                        <p>super delicious</p>
                    </div>
                </div>
                <div class="swiper-slide">
                    <img src="images/dish3.jpg" alt="Pasta Primavera">
                    <div class="carousel-caption">
                        <h1>Algerian Mhajeb</h1>
                        <p>so hot and tasty</p>
                    </div>
                </div>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next custom-arrow"></div>
            <div class="swiper-button-prev custom-arrow"></div>
        </div>
    </section>

    <!-- Menu Section -->
    <section id="menu" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Our Menu</h2>
            <div class="row" id="menu-container"></div>
        </div>
    </section>

    <!-- Reservation Section -->
    <section id="reservation" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Make a Reservation</h2>
            <?php if(isset($_SESSION['user_id'])): ?>
                <!-- Reservation Form for Logged-in Users -->
                <form id="reservation-form" class="reservation-form mx-auto">
                    <div class="mb-3">
                        <input type="datetime-local" class="form-control" name="date_time" required>
                    </div>
                    <div class="mb-3">
                        <input type="number" class="form-control" name="guests" min="1" max="20" placeholder="Number of Guests" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Book Now</button>
                </form>
            <?php else: ?>
                <!-- Login Prompt for Non-Logged-in Users -->
                <div class="login-prompt mx-auto text-center">
                    <i class="fas fa-sign-in-alt fa-2x mb-3"></i>
                    <p>Please log in to make a reservation</p>
                    <form method="POST" action="login.php" class="mx-auto" style="max-width: 300px;">
                        <div class="mb-3">
                            <input type="text" class="form-control" name="username" placeholder="Username" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Contact Us</h2>
            <form id="contact-form" method="POST" action="submit_contact.php" class="mx-auto">
                <div class="mb-3">
                    <input type="text" class="form-control" name="name" placeholder="Your Name" required>
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" name="email" placeholder="Your Email" required>
                </div>
                <div class="mb-3">
                    <textarea class="form-control" name="message" placeholder="Your Message" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">Send Message</button>
            </form>
        </div>
    </section>

    <!-- Cart Section -->
    <div class="cart-container" id="cart-container">
        <h3>Cart</h3>
        <div id="cart-items"></div>
        <button id="checkout" class="btn btn-primary w-100 mt-3">Checkout</button>
    </div>

    <!-- Footer Section -->
    <footer class="py-3 text-center">
        <p>© 2025 High Top. All rights reserved.</p>
    </footer>

    <!-- Scripts Section -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>