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
        <div class="profile-header">
            <i class="fas fa-user-circle profile-icon"></i>
            <h1>Benvenuto, <?php echo htmlspecialchars($_SESSION['firstname']); ?>!</h1>
            <p class="profile-subtitle">Gestisci il tuo profilo e le impostazioni dell'account</p>
        </div>
        
        <div class="profile-card">
            <div class="tab">
                <button class="tablink active" onclick="openTab(event, 'modify-profile')">
                    <i class="fas fa-user"></i>
                    <span>Profilo</span>
                </button>
                <button class="tablink" onclick="openTab(event, 'modify-password')">
                    <i class="fas fa-lock"></i>
                    <span>Password</span>
                </button>
            </div>

            <div id="modify-profile" class="tabcontent" style="display: block;">
                <h1>Modifica il tuo profilo</h1>
                <form action="../auth/update_profile.php" method="post" class="profile-form" id="profile-form">
                    <div class="form-group">
                        <label for="first_name">
                            <i class="fas fa-user-edit"></i> Nome:
                        </label>
                        <input type="text" 
                               name="first_name" 
                               id="first_name" 
                               value="<?php echo htmlspecialchars($_SESSION['firstname']); ?>"
                               required
                               pattern="[A-Za-z\s]+"
                               title="Il nome deve contenere solo lettere e spazi"
                               placeholder="Inserisci il tuo nome">
                    </div>
                    <div class="form-group">
                        <label for="last_name">
                            <i class="fas fa-user-edit"></i> Cognome:
                        </label>
                        <input type="text" 
                               name="last_name" 
                               id="last_name" 
                               value="<?php echo htmlspecialchars($_SESSION['lastname']); ?>"
                               required
                               pattern="[A-Za-z\s]+"
                               title="Il cognome deve contenere solo lettere e spazi"
                               placeholder="Inserisci il tuo cognome">
                    </div>
                    <div class="form-group">
                        <label for="email">
                            <i class="fas fa-envelope"></i> Email:
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="<?php echo htmlspecialchars($_SESSION['email']); ?>"
                               required
                               placeholder="La tua email">
                    </div>
                    <button type="submit" name="submit" class="profile-button">
                        <i class="fas fa-save"></i>
                        <span>Salva Modifiche</span>
                    </button>
                </form>
            </div>

            <div id="modify-password" class="tabcontent" style="display: none;">
                <h1>Modifica la password</h1>
                <form action="../auth/update_password.php" method="post" class="profile-form" id="password-form">
                    <div class="form-group">
                        <label for="current_password">
                            <i class="fas fa-key"></i> Password Attuale:
                        </label>
                        <input type="password" 
                               name="current_password" 
                               id="current_password"
                               required
                               minlength="8"
                               placeholder="Inserisci la password attuale">
                    </div>
                    <div class="form-group">
                        <label for="new_password">
                            <i class="fas fa-lock"></i> Nuova Password:
                        </label>
                        <input type="password" 
                               name="new_password" 
                               id="new_password"
                               required
                               minlength="8"
                               pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$"
                               title="La password deve contenere almeno 8 caratteri, inclusi lettere e numeri"
                               placeholder="Inserisci la nuova password">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">
                            <i class="fas fa-lock"></i> Conferma Password:
                        </label>
                        <input type="password" 
                               name="confirm_password" 
                               id="confirm_password"
                               required
                               minlength="8"
                               placeholder="Conferma la nuova password">
                    </div>
                    <div class="password-requirements">
                        <p><i class="fas fa-info-circle"></i> La password deve:</p>
                        <ul>
                            <li id="length-check"><i class="fas fa-check"></i> Contenere almeno 8 caratteri</li>
                            <li id="letter-check"><i class="fas fa-check"></i> Contenere almeno una lettera</li>
                            <li id="number-check"><i class="fas fa-check"></i> Contenere almeno un numero</li>
                        </ul>
                    </div>
                    <button type="submit" name="submit" class="profile-button">
                        <i class="fas fa-key"></i>
                        <span>Aggiorna Password</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestione dei tab
    window.openTab = function(evt, tabName) {
        var i, tabcontent, tablinks;
        
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        
        tablinks = document.getElementsByClassName("tablink");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    // Validazione form password
    const passwordForm = document.getElementById('password-form');
    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    
    function updatePasswordRequirements(password) {
        const lengthCheck = document.getElementById('length-check');
        const letterCheck = document.getElementById('letter-check');
        const numberCheck = document.getElementById('number-check');
        
        lengthCheck.classList.toggle('valid', password.length >= 8);
        letterCheck.classList.toggle('valid', /[A-Za-z]/.test(password));
        numberCheck.classList.toggle('valid', /\d/.test(password));
    }

    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', function() {
            updatePasswordRequirements(this.value);
        });
    }

    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            const newPass = newPasswordInput.value;
            const confirmPass = confirmPasswordInput.value;
            
            if (newPass !== confirmPass) {
                e.preventDefault();
                alert('Le password non coincidono!');
            }
        });
    }
});
</script>

<?php include '../templates/footer.php'; ?>