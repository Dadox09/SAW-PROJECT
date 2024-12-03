<?php
session_start();
require_once __DIR__ . '/../config/config.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/components/navbar.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/components/main.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<nav class="navbar">
        <div class="navbar-container">
            <a href="/" class="navbar-brand">
                <i class="fas fa-store"></i>
                E-commerce
            </a>
            
            <button class="navbar-toggle" id="navbar-toggle">
                <i class="fas fa-bars"></i>
            </button>

            <ul class="navbar-menu" id="navbar-menu">
                <li class="navbar-item">
                    <a href="/" class="navbar-link">Home</a>
                </li>
                <li class="navbar-item">
                    <a href="/products" class="navbar-link">Prodotti</a>
                </li>
                <li class="navbar-item">
                    <a href="/cart" class="navbar-link">
                        <i class="fas fa-shopping-cart"></i>
                        Carrello
                    </a>
                </li>
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <li class="navbar-item">
                        <a href="/auth/login.php" class="navbar-button">Accedi</a>
                    </li>
                <?php else: ?>
                    <li class="navbar-item">
                        <a href="/auth/profile.php" class="navbar-link">
                            <i class="fas fa-user"></i>
                            Profilo
                        </a>
                    </li>
                    <li class="navbar-item">
                        <a href="/auth/logout.php" class="navbar-link">
                            <i class="fas fa-sign-out-alt"></i>
                            Esci
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <script>
        document.getElementById('navbar-toggle').addEventListener('click', function() {
            document.getElementById('navbar-menu').classList.toggle('active');
        });
    </script>