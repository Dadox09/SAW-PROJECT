<?php
    include '../templates/header.php';

    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . BASE_URL);
        exit;
    }
?>

<div class="profile-container">
    <div class="profile-content">
        <div class="profile-card">
            <div class="profile-header">Benvenuto <?php echo $_SESSION['first_name']; ?></div>
            <div class="profile-info">
                <p><strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>
                <p><strong>Nome:</strong> <?php echo $_SESSION['first_name']; ?> <?php echo $_SESSION['last_name']; ?></p>
            </div>
        </div>
    </div>
</div>