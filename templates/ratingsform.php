<div class="rating-form">
    <h3>Lascia una recensione</h3>
    <form action="<?php echo BASE_URL; ?>/functions/submit_ratings.php" method="POST" class="rating-stars">
        <div class="star-rating">
            <?php for($i = 5; $i >= 1; $i--): ?>
                <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" />
                <label for="star<?php echo $i; ?>">&#9733;</label>
            <?php endfor; ?>
        </div>
        
        <div class="form-group">
            <textarea name="comment" rows="4" placeholder="Scrivi qui il tuo commento..." required></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Invia Recensione</button>
    </form>
</div>

<script src="<?php echo BASE_URL; ?>/assets/js/ratings/ratings.js" defer></script>