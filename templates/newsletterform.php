<div class="newsletter-card">
    <form method="post" id="newsletterForm">
        <h1>Iscriviti al Newsletter</h1>
        <p>Inserisci la tua email per ricevere informazioni su nuovi prodotti e offerte. Per te in omaggio anche un buono sconto da 5 euro da utilizzare nel prossimo acquisto!</p>
        <div class="newsletter-group">
            <div class="input-with-icon">
                <i class="fas fa-envelope"></i>
                <input type="email" 
                       id="email" 
                       name="email" 
                       placeholder="Inserisci la tua email" 
                       required>
            </div>
            <button type="submit" name="submit" id="iscrivitiBtn">Iscriviti ora!</button>
        </div>
    </form>
</div>
<script src="<?php echo BASE_URL; ?>/assets/js/newsletter/subscribe.js"></script>