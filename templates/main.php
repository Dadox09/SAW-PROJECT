<div class="main-content">
        <section class="welcome-section">
            <h1>Prenota Ora</h1>
            <p>Scopri il lusso del comfort nel cuore della città. Prenota ora il tuo soggiorno indimenticabile.</p>

            <div id="booking-message"></div>


            <form action="<?php echo BASE_URL; ?>/functions/process_booking.php" method="POST" class="booking-form" id="booking-form">


                <div class="booking-form-group">
                    <label for="check_in">Check-in</label>
                    <input type="date" id="check_in" name="check_in"  placeholder="Seleziona data" required>
                </div>
                <div class="booking-form-group">
                    <label for="check_out">Check-out</label>
                    <input type="date" id="check_out" name="check_out"  placeholder="Seleziona data" required>
                </div>
                <div class="booking-form-group">
                    <label for="guests">Ospiti</label>
                    <select id="guests" name="guests" required>
                        <option value="" disabled selected>Seleziona numero ospiti</option>
                        <?php for($i = 1; $i <= 10; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?> <?php echo $i == 1 ? 'ospite' : 'ospiti'; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="submit" class="submit-btn">Prenota</button>
            </form>
        </section>
</div>

<div class="features-grid">
    <div class="feature-card">
        <i class="fas fa-mug-hot"></i>
        <h3>Cioccolata Artigianale</h3>
        <p>Degusta la nostra cioccolata calda preparata secondo ricette tradizionali</p>
    </div>
    <div class="feature-card">
        <i class="fas fa-bed"></i>
        <h3>Camere di Lusso</h3>
        <p>Rilassati nelle nostre suite arredate con eleganza e comfort</p>
    </div>
    <div class="feature-card">
        <i class="fas fa-spa"></i>
        <h3>Area Benessere</h3>
        <p>Goditi i nostri trattamenti al cioccolato nel centro benessere</p>
    </div>
</div>