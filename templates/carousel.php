<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<section class="carousel-section">
    <div class="carousel-container">
        <div class="carousel-track">
            <div class="carousel-slide">
                <img src="<?php echo BASE_URL; ?>/assets/carousel_images/room1.jpg" alt="Camera di lusso con vista">
                <div class="carousel-caption">
                    <h3>Suite di Lusso</h3>
                    <p>Goditi il comfort in un ambiente raffinato</p>
                </div>
            </div>
            <div class="carousel-slide">
                <img src="<?php echo BASE_URL; ?>/assets/carousel_images/room2.jpg" alt="Area relax con cioccolata">
                <div class="carousel-caption">
                    <h3>Area Relax</h3>
                    <p>Rilassati con la nostra cioccolata calda artigianale</p>
                </div>
            </div>
            <div class="carousel-slide">
                <img src="<?php echo BASE_URL; ?>/assets/carousel_images/room3.jpg" alt="Colazione gourmet">
                <div class="carousel-caption">
                    <h3>Colazione Gourmet</h3>
                    <p>Inizia la giornata con i nostri dolci fatti in casa</p>
                </div>
            </div>
        </div>

        <button class="carousel-button prev" aria-label="Immagine precedente">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="carousel-button next" aria-label="Immagine successiva">
            <i class="fas fa-chevron-right"></i>
        </button>

        <div class="carousel-dots">
            <button class="dot active" aria-label="Vai alla slide 1"></button>
            <button class="dot" aria-label="Vai alla slide 2"></button>
            <button class="dot" aria-label="Vai alla slide 3"></button>
        </div>
    </div>
</section>

<script src="<?php echo BASE_URL; ?>/assets/js/carousel.js" defer></script>