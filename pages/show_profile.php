<?php
    include '../templates/header.php';

    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL);
        exit;
    }
?>

<link rel="stylesheet" href="../assets/css/pages/show_profile.css">

<div class="profile-container">
    <div class="profile-content">
        <i class="fas fa-user-circle"></i>
        <div class="profile-header"><h1>Benvenuto <?php echo $_SESSION['first_name']; ?>!</h1></div>
        <div class="profile-card">
            <div class="tab">
                <button class="tablink active" onclick="openTab(event, 'modify-profile')"><i class="fas fa-user"></i></button>
                <button class="tablink" onclick="openTab(event, 'modify-password')"><i class="fas fa-cog"></i></button>
            </div>

            <div id="modify-profile" class="tabcontent" style="display: block;">
                <h1>Modifica il tuo profilo</h1>
                <form action="../auth/update_profile.php" method="post">
                    <div class="form-group">
                        <label for="first_name">Nome:</label>
                        <input type="text" name="first_name" id="first_name" value="<?php echo $_SESSION['first_name']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="last_name">Cognome:</label>
                        <input type="text" name="last_name" id="last_name" value="<?php echo $_SESSION['last_name']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" value="<?php echo $_SESSION['email']; ?>">
                    </div>
                    <button type="submit" name="submit" class="profile-button">Salva</button>
                </form>
            </div>

            <div id="modify-password" class="tabcontent" style="display: none;">
                <h1>Modifica la password</h1>
                <form action="../auth/update_password.php" method="post">
                    <div class="form-group">
                        <label for="password">Nuova password:</label>
                        <input type="password" name="password" id="password">
                    </div>
                    <button type="submit" name="submit" class="profile-button">Salva</button>
                </form>
            </div>

        </div>
    </div>
</div>
<script src="../assets/js/profile/show_profile.js"></script>
<?php 
echo "<script>console.log(" . json_encode($_SESSION) . ");</script>";
include '../templates/footer.php'; ?>