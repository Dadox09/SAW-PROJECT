<div class="main-content">
        <section class="welcome-section">
            <h1>Coccole e Croissant</h1>
            <p>Scopri il lusso del comfort nel cuore della città. Prenota ora il tuo soggiorno indimenticabile.</p>
            
            <?php
            if (isset($_SESSION['error'])) {
                echo '<div class="error-message">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo '<div class="success-message">' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']);
            }
            ?>
            
            <form action="auth/process_booking.php" method="POST" class="booking-form">
                <div class="booking-form-group">
                    <label for="check_in">Check-in</label>
                    <input type="text" id="check_in" name="check_in" required class="datepicker" placeholder="Seleziona data">
                </div>
                <div class="booking-form-group">
                    <label for="check_out">Check-out</label>
                    <input type="text" id="check_out" name="check_out" required class="datepicker" placeholder="Seleziona data">
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
                <button type="submit" class="submit-btn">Verifica disponibilità</button>
            </form>
        </section>
</div>