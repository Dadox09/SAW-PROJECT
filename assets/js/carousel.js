document.addEventListener('DOMContentLoaded', function() {
    const track = document.querySelector('.carousel-track');
    const slides = Array.from(track.children);
    const nextButton = document.querySelector('.carousel-button.next');
    const prevButton = document.querySelector('.carousel-button.prev');
    const dotsNav = document.querySelector('.carousel-dots');
    const dots = Array.from(dotsNav.children);

    // Imposta la larghezza delle slide
    const slideWidth = slides[0].getBoundingClientRect().width;
    slides.forEach((slide, index) => {
        slide.style.left = slideWidth * index + 'px';
    });

    let currentSlide = 0;
    const totalSlides = slides.length;
    let autoplayInterval;

    // Funzione per muovere il carosello
    function moveToSlide(targetIndex) {
        if (targetIndex < 0) {
            targetIndex = totalSlides - 1;
        } else if (targetIndex >= totalSlides) {
            targetIndex = 0;
        }

        const targetSlide = slides[targetIndex];
        track.style.transform = 'translateX(-' + targetSlide.style.left + ')';
        
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

    // Touch events per il mobile
    let touchStartX = 0;
    let touchEndX = 0;

    track.addEventListener('touchstart', e => {
        touchStartX = e.touches[0].clientX;
    });

    track.addEventListener('touchmove', e => {
        touchEndX = e.touches[0].clientX;
    });

    track.addEventListener('touchend', () => {
        const difference = touchStartX - touchEndX;
        if (Math.abs(difference) > 50) { // Minima distanza per considerarlo uno swipe
            if (difference > 0) {
                // Swipe verso sinistra
                moveToSlide(currentSlide + 1);
            } else {
                // Swipe verso destra
                moveToSlide(currentSlide - 1);
            }
            resetAutoplay();
        }
    });

    // Pausa l'autoplay quando il mouse è sopra il carosello
    track.addEventListener('mouseenter', () => {
        clearInterval(autoplayInterval);
    });

    track.addEventListener('mouseleave', () => {
        startAutoplay();
    });

    // Avvia l'autoplay
    startAutoplay();
});