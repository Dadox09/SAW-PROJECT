<?php
session_start();
require_once __DIR__ . '/../config/config.php';
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/components/navbar.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/components/main.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/layout/home.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<nav class="navbar">
        <div class="navbar-container">
            <a href="<?php echo BASE_URL; ?>" class="navbar-brand">
                <i class="fas fa-store"></i>
                E-commerce
            </a>
            
            <button class="navbar-toggle" id="navbar-toggle">
                <i class="fas fa-bars"></i>
            </button>

            <ul class="navbar-menu" id="navbar-menu">
                <li class="navbar-item">
                    <a href="/" class="navbar-link">
                        <i class="fas fa-home"></i>
                        Home
                    </a>
                </li>
                <li class="navbar-item">
                    <a href="/products" class="navbar-link">
                        <i class="fas fa-box"></i>
                        Prodotti
                    </a>
                </li>
                <li class="navbar-item">
                    <a href="/cart" class="navbar-link">
                        <i class="fas fa-shopping-cart"></i>
                        Carrello
                    </a>
                </li>
                <?php if (!isset($_SESSION['username'])): ?>
                    <li class="navbar-item">
                        <a href="<?php echo BASE_URL; ?>/pages/formLogin.php" class="navbar-button">Accedi</a>
                    </li>
                <?php else: ?>
                    <li class="navbar-item">
                        <a href="<?php echo BASE_URL; ?>/pages/show_profile.php" class="navbar-link">
                            <i class="fas fa-user"></i>
                            Profilo
                        </a>
                    </li>
                    <li class="navbar-item">
                        <a href="<?php echo BASE_URL; ?>/auth/logout.php" class="navbar-link">
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