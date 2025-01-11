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