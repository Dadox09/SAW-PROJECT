<?php

include '../templates/header.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL);
    exit;
}


?>

<link rel="stylesheet" href="../assets/css/auth/login.css">
<link rel="stylesheet" href="../assets/css/errors/errors.css">

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <i class="fas fa-user-circle"></i>
            <h1>Benvenuto</h1>
            <p>Accedi al tuo account</p>
        </div>
        
        <div id="login-message"></div>

        <form action="<?php echo BASE_URL; ?>/functions/login.php" method="post" class="login-form" id="login-form">
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

            <button type="submit" name="submit" class="login-button">
                <span>Accedi</span>
                <i class="fas fa-arrow-right"></i>
            </button>
        </form>

        <div class="login-footer">
            <p>Non hai un account? <a href="formRegistration.php">Registrati</a></p>
        </div>
    </div>
</div>

<script src="../assets/js/auth/auth.js"></script>
<script src="../assets/js/auth/login.js"></script>
<?php include '../templates/footer.php'; ?>