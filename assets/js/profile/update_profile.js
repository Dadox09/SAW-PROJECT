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
        
        if (lengthCheck && letterCheck && numberCheck) {
            lengthCheck.classList.toggle('valid', password.length >= 8);
            letterCheck.classList.toggle('valid', /[A-Za-z]/.test(password));
            numberCheck.classList.toggle('valid', /\d/.test(password));
        }
    }

    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', function() {
            updatePasswordRequirements(this.value);
        });
    }

    // Gestione fetch per il form password
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const newPass = newPasswordInput.value;
            const confirmPass = confirmPasswordInput.value;
            const messageContainer = document.getElementById('password-message');
            
            if (newPass !== confirmPass) {
                if (messageContainer) {
                    messageContainer.innerHTML = '<div class="error-message">Le password non coincidono!</div>';
                }
                return;
            }
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (messageContainer) {
                    messageContainer.innerHTML = `<div class="${data.status === 'success' ? 'success' : 'error'}-message">${data.message}</div>`;
                }
                
                if (data.status === 'success') {
                    // Reset form
                    this.reset();
                    updatePasswordRequirements('');
                }
            })
            .catch(error => {
                if (messageContainer) {
                    messageContainer.innerHTML = '<div class="error-message">Si è verificato un errore durante l\'aggiornamento della password.</div>';
                }
                console.error('Error:', error);
            });
        });
    }

    // Gestione fetch per il form di aggiornamento profilo
    const profileForm = document.getElementById('profile-form');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageContainer = document.getElementById('profile-message');
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (messageContainer) {
                    messageContainer.innerHTML = `<div class="${data.status === 'success' ? 'success' : 'error'}-message">${data.message}</div>`;
                }
                
                if (data.status === 'success') {
                    document.querySelector('.profile-header h1').textContent = `Benvenuto, ${data.data.firstname}!`;
                }
            })
            .catch(error => {
                if (messageContainer) {
                    messageContainer.innerHTML = '<div class="error-message">Si è verificato un errore durante l\'aggiornamento del profilo.</div>';
                }
                console.error('Error:', error);
            });
        });
    }
});