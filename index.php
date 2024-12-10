<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coccole e Croissant B&B </title>
</head>
<body>
    <?php require_once 'templates/header.php'; ?>

    <div class="main-content">
        <section class="welcome-section">
            <h1>Benvenuti a Coccole e Croissant</h1>
            <p>Dove il lusso incontra il comfort  </p>
            
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
                    <label for="check_in">Data Check-in</label>
                    <input type="text" id="check_in" name="check_in" required class="datepicker">
                </div>
                <div class="booking-form-group">
                    <label for="check_out">Data Check-out</label>
                    <input type="text" id="check_out" name="check_out" required class="datepicker">
                </div>
                <div class="booking-form-group">
                    <label for="guests">Numero di ospiti</label>
                    <select id="guests" name="guests" required>
                        <?php for($i = 1; $i <= 10; $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?> <?php echo $i == 1 ? 'ospite' : 'ospiti'; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <button type="submit" class="submit-btn">Prenota ora</button>
            </form>
        </section>

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
    </div>

    <?php require_once 'templates/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateConfig = {
                dateFormat: "Y-m-d",
                minDate: "today",
                locale: "it"
            };
            
            const checkIn = flatpickr("#check_in", {
                ...dateConfig,
                onChange: function(selectedDates) {
                    checkOut.set('minDate', selectedDates[0]);
                }
            });
            
            const checkOut = flatpickr("#check_out", {
                ...dateConfig,
                minDate: "today"
            });
        });
    </script>
</body>
</html>