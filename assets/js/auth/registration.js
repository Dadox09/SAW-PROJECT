document.addEventListener('DOMContentLoaded', function() {
    const registrationForm = document.getElementById('registration-form');
    if (registrationForm) {
        registrationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const messageContainer = document.getElementById('registration-message');
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    if (messageContainer) {
                        messageContainer.innerHTML = `<div class="success-message">${data.message}</div>`;
                    }
                    
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 700);
                } else {
                    if (messageContainer) {
                        messageContainer.innerHTML = `<div class="error-message">${data.message}</div>`;
                    }
                }
            })
            .catch(error => {
                if (messageContainer) {
                    messageContainer.innerHTML = `<div class="error-message">Si è verificato un errore durante la registrazione. Riprova più tardi.</div>`;
                }
                console.error('Error:', error);
            });
        });
    }
});
