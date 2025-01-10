<?php
session_start();
require_once __DIR__ . '/../config/config.php';
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/errors/errors.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/components/navbar.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/components/main.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/layout/home.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/layout/bookings.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<nav class="navbar" role="navigation" aria-label="Menu principale">
    <div class="navbar-container">
        <a href="<?php echo BASE_URL; ?>" class="navbar-brand" aria-label="Home">
            <i class="fas fa-store" aria-hidden="true"></i>
            <span>Coccole e Croissant</span>
        </a>
        
        <button class="navbar-toggle" id="navbar-toggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="navbar-menu">
            <i class="fas fa-bars" aria-hidden="true"></i>
        </button>

        <ul class="navbar-menu" id="navbar-menu" role="menu">
            <li class="navbar-item" role="none">
                <a href="<?php echo BASE_URL; ?>" class="navbar-link" role="menuitem" <?php echo ($_SERVER['REQUEST_URI'] == BASE_URL || $_SERVER['REQUEST_URI'] == '/') ? 'aria-current="page"' : ''; ?>>
                    <i class="fas fa-home" aria-hidden="true"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="navbar-item" role="none">
                <a href="<?php echo BASE_URL; ?>/products" class="navbar-link" role="menuitem" <?php echo (strpos($_SERVER['REQUEST_URI'], '/products') !== false) ? 'aria-current="page"' : ''; ?>>
                    <i class="fas fa-box" aria-hidden="true"></i>
                    <span>Prodotti</span>
                </a>
            </li>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <li class="navbar-item" role="none">
                    <a href="<?php echo BASE_URL; ?>/pages/formLogin.php" class="navbar-button" role="menuitem">
                        <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
                        <span>Accedi</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="navbar-item" role="none">
                    <a href="<?php echo BASE_URL; ?>/pages/show_profile.php" class="navbar-link" role="menuitem" <?php echo (strpos($_SERVER['REQUEST_URI'], '/show_profile') !== false) ? 'aria-current="page"' : ''; ?>>
                        <i class="fas fa-user" aria-hidden="true"></i>
                        <span>Profilo</span>
                    </a>
                </li>
                <li class="navbar-item" role="none">
                    <a href="<?php echo BASE_URL; ?>/auth/logout.php" class="navbar-link" role="menuitem">
                        <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                        <span>Esci</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const navbarToggle = document.getElementById('navbar-toggle');
    const navbarMenu = document.getElementById('navbar-menu');
    
    navbarToggle.addEventListener('click', function() {
        const isExpanded = navbarToggle.getAttribute('aria-expanded') === 'true';
        navbarToggle.setAttribute('aria-expanded', !isExpanded);
        navbarMenu.classList.toggle('active');
    });

    // Chiudi il menu mobile quando si clicca su un link
    const navLinks = document.querySelectorAll('.navbar-link, .navbar-button');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                navbarMenu.classList.remove('active');
                navbarToggle.setAttribute('aria-expanded', 'false');
            }
        });
    });

    // Gestione scroll navbar
    let lastScroll = 0;
    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        const navbar = document.querySelector('.navbar');
        
        if (currentScroll <= 0) {
            navbar.style.transform = 'translateY(0)';
            return;
        }
        
        if (currentScroll > lastScroll && currentScroll > 100) {
            // Scrolling down
            navbar.style.transform = 'translateY(-100%)';
        } else {
            // Scrolling up
            navbar.style.transform = 'translateY(0)';
        }
        lastScroll = currentScroll;
    });
});
</script>