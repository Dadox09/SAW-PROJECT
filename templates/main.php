<div class="booking-container" id="main-content">
<h2 class="booking-title">Prenota il Tuo Soggiorno di Lusso</h2>
    <div class="booking-content">
        
        <div class="booking-info">
            
            <p>Lasciati avvolgere dal calore del cioccolato e dalla magia del relax</p>
            <div class="experience-highlights">
                <div class="highlight">
                    <i class="fas fa-hot-tub"></i>
                    <span>Spa al Cioccolato</span>
                </div>
                <div class="highlight">
                    <i class="fas fa-bed"></i>
                    <span>Suite di Lusso</span>
                </div>
                <div class="highlight">
                    <i class="fas fa-utensils"></i>
                    <span>Degustazioni</span>
                </div>
            </div>
        </div>
        
        <div class="booking-form-container">
            <form id="booking-form" class="booking-form" action="<?php echo BASE_URL; ?>/functions/process_booking.php" method="POST">
                <div class="booking-message"></div>
                
                <div class="form-group">
                    <label for="check-in">Data di Check-in</label>
                    <div class="input-with-icon">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" id="check-in" name="check_in" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="check-out">Data di Check-out</label>
                    <div class="input-with-icon">
                        <i class="fas fa-calendar-alt"></i>
                        <input type="date" id="check-out" name="check_out" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="guests">Numero di Ospiti</label>
                    <div class="input-with-icon">
                        <i class="fas fa-users"></i>
                        <select id="guests" name="guests" required>
                            <option value="" disabled selected>Seleziona numero ospiti:</option>
                            <?php for($i = 1; $i <= 10; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?> <?php echo $i == 1 ? 'ospite' : 'ospiti'; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="submit-btn">
                    <span>Prenota Ora</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</div>