<?php
if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL);
    exit;
}

include '../templates/header.php';
?>

<link rel="stylesheet" href="../assets/css/auth/registration.css">
<link rel="stylesheet" href="../assets/css/errors/errors.css">

<div class="registration-container">
    <div class="registration-card">
        <div class="registration-header">
            <i class="fas fa-user-circle"></i>
            <h1>Benvenuto</h1>
            <p>Accedi al tuo account</p>
        </div>

        <?php
            if (isset($_SESSION['error'])) {
                echo '<div class="error-message"><i class="fas fa-exclamation-circle"></i>' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }            
            if (isset($_SESSION['success'])) {
                echo '<div class="success-message"><i class="fas fa-exclamation-circle"></i>' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']);
            }
        ?>

        <form action="<?php echo BASE_URL; ?>/functions/registration.php" method="post" class="registration-form">
            <div class="form-group">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="firstname" id="first_name" placeholder="Il tuo nome" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="lastname" id="last_name" placeholder="Il tuo cognome" required>
                </div>
            </div>


            <div class="form-group">
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" id="email" placeholder="La tua email" required>
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="pass" id="password" placeholder="La tua password" required>
                    <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="confirm" id="confirm_password" placeholder="Conferma la tua password" required>
                    <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                </div>
            </div>

            <button type="submit" name="submit" class="registration-button">
                <span>Registrati</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        <div class="registration-footer">
            <p>Hai già un account? <a href="formLogin.php">Accedi</a></p>
        </div>
    </div>
</div>

<script src="../assets/js/auth/auth.js"></script>
<?php include '../templates/footer.php'; ?>