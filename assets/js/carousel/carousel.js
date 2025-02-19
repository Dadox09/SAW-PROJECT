document.addEventListener('DOMContentLoaded', function() {
    const track = document.querySelector('.carousel-track');
    const slides = Array.from(track.children);
    const nextButton = document.querySelector('.carousel-button.next');
    const prevButton = document.querySelector('.carousel-button.prev');
    const dotsNav = document.querySelector('.carousel-dots');
    const dots = Array.from(dotsNav.children);

    let currentSlide = 0;
    const totalSlides = slides.length;
    let autoplayInterval;

    // Inizializza le posizioni delle slide
    slides.forEach((slide, index) => {
        slide.style.left = `${index * 100}%`;
    });

    // Funzione per muovere il carosello
    function moveToSlide(targetIndex) {
        if (targetIndex < 0) {
            targetIndex = totalSlides - 1;
        } else if (targetIndex >= totalSlides) {
            targetIndex = 0;
        }

        track.style.transform = `translateX(-${targetIndex * 100}%)`;
        
        // Aggiorna il dot attivo
        dots.forEach(dot => dot.classList.remove('active'));
        dots[targetIndex].classList.add('active');

        currentSlide = targetIndex;
    }

    // Event listeners per i pulsanti
    nextButton.addEventListener('click', () => {
        moveToSlide(currentSlide + 1);
        resetAutoplay();
    });

    prevButton.addEventListener('click', () => {
        moveToSlide(currentSlide - 1);
        resetAutoplay();
    });

    // Event listeners per i dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            moveToSlide(index);
            resetAutoplay();
        });
    });

    // Autoplay
    function startAutoplay() {
        autoplayInterval = setInterval(() => {
            moveToSlide(currentSlide + 1);
        }, 5000); // Cambia slide ogni 5 secondi
    }

    function resetAutoplay() {
        clearInterval(autoplayInterval);
        startAutoplay();
    }

    // Pausa l'autoplay quando il mouse è sopra il carosello
    track.addEventListener('mouseenter', () => {
        clearInterval(autoplayInterval);
    });

    track.addEventListener('mouseleave', () => {
        startAutoplay();
    });

    // Avvia l'autoplay iniziale
    startAutoplay();
});